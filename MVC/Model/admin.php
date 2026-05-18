<?php
require_once __DIR__ . '/../../db.php';

class Admin
{
    private PDO $conn;
    private int $ID = 0;
    private string $name = '';
    private string $email = '';
    private string $role = 'admin';
    private string $permissions = 'Full Access';

    public function __construct()
    {
        $db = new Databases();
        $this->conn = $db->getConnection();
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getID(): int { return $this->ID; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }
    public function getPermissions(): string { return $this->permissions; }

    private function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private function fetchOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    private function execute(string $sql, array $params = []): bool
    {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Throwable $e) {
            return false;
        }
    }

    private function normalizeCategory(string $category): string
    {
        $c = strtolower(trim($category));
        return match ($c) {
            'bag', 'bags' => 'Bag',
            'clog', 'clogs' => 'Clog',
            'slipper', 'slippers' => 'Slipper',
            default => trim($category),
        };
    }

    public function login(string $email, string $password): bool
    {
        $row = $this->fetchOne(
            "SELECT userID, name, email, password, role FROM users WHERE email = ? AND LOWER(role) = 'admin' LIMIT 1",
            [$email]
        );

        if (!$row) {
            return false;
        }

        $storedPassword = (string)($row['password'] ?? '');
        $ok = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);
        if (!$ok) {
            return false;
        }

        $this->ID = (int)$row['userID'];
        $this->name = (string)$row['name'];
        $this->email = (string)$row['email'];
        $this->role = (string)($row['role'] ?? 'admin');
        return true;
    }

    public function addProduct(array $data): int
    {
        $category = $this->normalizeCategory($data['category'] ?? '');
        $branchID = !empty($data['BranchID']) ? (int)$data['BranchID'] : null;

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare(
                'INSERT INTO product (name, price, descriptions, category, Number_Of_Sells, BranchID)
                 VALUES (?, ?, ?, ?, 0, ?)'
            );
            $stmt->execute([
                trim($data['name'] ?? ''),
                (float)($data['price'] ?? 0),
                trim($data['description'] ?? ''),
                $category,
                $branchID,
            ]);

            $pid = (int)$this->conn->lastInsertId();

            $color = trim($data['color'] ?? 'Default');
            if ($color === '') $color = 'Default';
            $stock = (int)($data['stock'] ?? 0);
            $size = (int)($data['size'] ?? $data['sizes'] ?? 0);
            $addPrice = (float)($data['add_price'] ?? 0);

            $stmt = $this->conn->prepare(
                'INSERT INTO product_variants (PID, color, stock, add_price, sizes) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$pid, $color, $stock, $addPrice, $size]);
            $pvid = (int)$this->conn->lastInsertId();

            $image = trim($data['image'] ?? '');
            if ($image !== '') {
                $stmt = $this->conn->prepare('INSERT INTO product_images (PID, images) VALUES (?, ?)');
                $stmt->execute([$pid, $image]);
            }

            // Optional subtype tables. If columns differ, ignore instead of breaking product creation.
            if ($category === 'Bag') {
                $this->execute('INSERT INTO Bag (PID, capacityLiters, numpackets) VALUES (?, ?, ?)', [
                    $pid, (int)($data['capacityLiters'] ?? $data['capacity'] ?? 0), (int)($data['numpackets'] ?? 0)
                ]);
            } elseif ($category === 'Clog') {
                $this->execute('INSERT INTO Clogs (PID, heelHeight, strapType) VALUES (?, ?, ?)', [
                    $pid, (float)($data['heelHeight'] ?? 0), trim($data['strapType'] ?? '')
                ]);
            } elseif ($category === 'Slipper') {
                $this->execute('INSERT INTO Slipper (PID, materialsoftness) VALUES (?, ?)', [
                    $pid, trim($data['materialsoftness'] ?? '')
                ]);
            }

            $this->conn->commit();
            return $pid;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return 0;
        }
    }

    public function modifyProduct(array $data): bool
    {
        $pid = (int)($data['PID'] ?? $data['prod_ID'] ?? 0);
        if ($pid <= 0) return false;

        $category = $this->normalizeCategory($data['category'] ?? '');
        $ok = $this->execute(
            'UPDATE product SET name = ?, price = ?, descriptions = ?, category = ?, BranchID = ? WHERE PID = ?',
            [
                trim($data['name'] ?? ''),
                (float)($data['price'] ?? 0),
                trim($data['description'] ?? ''),
                $category,
                !empty($data['BranchID']) ? (int)$data['BranchID'] : null,
                $pid,
            ]
        );

        if ($category === 'Bag') {
            $this->execute('DELETE FROM Bag WHERE PID = ?', [$pid]);
            $this->execute('INSERT INTO Bag (PID, capacityLiters, numpackets) VALUES (?, ?, ?)', [
                $pid, (int)($data['capacityLiters'] ?? 0), (int)($data['numpackets'] ?? 0)
            ]);
        } elseif ($category === 'Clog') {
            $this->execute('DELETE FROM Clogs WHERE PID = ?', [$pid]);
            $this->execute('INSERT INTO Clogs (PID, heelHeight, strapType) VALUES (?, ?, ?)', [
                $pid, (float)($data['heelHeight'] ?? 0), trim($data['strapType'] ?? '')
            ]);
        } elseif ($category === 'Slipper') {
            $this->execute('DELETE FROM Slipper WHERE PID = ?', [$pid]);
            $this->execute('INSERT INTO Slipper (PID, materialsoftness) VALUES (?, ?)', [
                $pid, trim($data['materialsoftness'] ?? '')
            ]);
        }

        return $ok;
    }

    public function deleteProduct(int $PID): bool
    {
        if ($PID <= 0) return false;
        try {
            $this->conn->beginTransaction();
            $variants = $this->fetchAll('SELECT pvid FROM product_variants WHERE PID = ?', [$PID]);
            foreach ($variants as $v) {
                $this->execute('DELETE FROM product_images WHERE pvid = ?', [(int)$v['pvid']]);
            }
            $this->execute('DELETE FROM product_variants WHERE PID = ?', [$PID]);
            $this->execute('DELETE FROM Bag WHERE PID = ?', [$PID]);
            $this->execute('DELETE FROM Clogs WHERE PID = ?', [$PID]);
            $this->execute('DELETE FROM Slipper WHERE PID = ?', [$PID]);
            $ok = $this->execute('DELETE FROM product WHERE PID = ?', [$PID]);
            $this->conn->commit();
            return $ok;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return false;
        }
    }

    public function updateStock(int $PID, int $quantity): bool
    {
        $variant = $this->fetchOne('SELECT pvid FROM product_variants WHERE PID = ? ORDER BY pvid ASC LIMIT 1', [$PID]);
        if ($variant) {
            return $this->execute('UPDATE product_variants SET stock = ? WHERE pvid = ?', [$quantity, (int)$variant['pvid']]);
        }
        return $this->execute('INSERT INTO product_variants (PID, color, stock, add_price, sizes) VALUES (?, ?, ?, ?, ?)', [$PID, 'Default', $quantity, 0, 0]);
    }

    public function viewProducts(): array
    {
        return $this->fetchAll(
            "SELECT p.PID, p.name, p.price, p.descriptions, p.category, p.Number_Of_Sells, p.BranchID,
                    COALESCE(SUM(pv.stock), 0) AS stock,
                    MIN(pv.color) AS color,
                    MIN(pv.sizes) AS size,
                    MIN(pi.images) AS image,
                    MAX(b.capacityLiters) AS capacityLiters,
                    MAX(b.numpackets) AS numpackets,
                    MAX(c.heelHeight) AS heelHeight,
                    MAX(c.strapType) AS strapType,
                    MAX(s.materialsoftness) AS materialsoftness
             FROM product p
             LEFT JOIN product_variants pv ON pv.PID = p.PID
             LEFT JOIN product_images pi ON pi.PID = p.PID
             LEFT JOIN Bag b ON b.PID = p.PID
             LEFT JOIN Clogs c ON c.PID = p.PID
             LEFT JOIN Slipper s ON s.PID = p.PID
             GROUP BY p.PID, p.name, p.price, p.descriptions, p.category, p.Number_Of_Sells, p.BranchID
             ORDER BY p.PID DESC"
        );
    }

    public function getProductByID(int $PID): ?array
    {
        return $this->fetchOne(
            "SELECT p.*,
                    b.capacityLiters, b.numpackets,
                    c.heelHeight, c.strapType,
                    s.materialsoftness
             FROM product p
             LEFT JOIN Bag b ON b.PID = p.PID
             LEFT JOIN Clogs c ON c.PID = p.PID
             LEFT JOIN Slipper s ON s.PID = p.PID
             WHERE p.PID = ?
             LIMIT 1",
            [$PID]
        );
    }

    public function getProductVariants(int $PID): array
    {
        return $this->fetchAll('SELECT * FROM product_variants WHERE PID = ?', [$PID]);
    }

    public function getVariantImages(int $PID): array
    {
        return $this->fetchAll('SELECT * FROM product_images WHERE PID = ?', [$PID]);
    }

    public function getProductImages(int $PID): array
    {
        return $this->fetchAll(
            'SELECT pi.* FROM product_images pi WHERE PID=?',
            [$PID]
        );
    }

    public function addProductVariant(array $data): bool
    {
        $PID = (int)($data['PID'] ?? 0);
        if ($PID <= 0) return false;
        $ok = $this->execute(
            'INSERT INTO product_variants (PID, color, stock, add_price, sizes) VALUES (?, ?, ?, ?, ?)',
            [$PID, trim($data['color'] ?? 'Default'), (int)($data['stock'] ?? 0), (float)($data['add_price'] ?? 0), (int)($data['size'] ?? $data['sizes'] ?? 0)]
        );
        return $ok;
    }

    public function updateProductVariant(array $data): bool
    {
        $pvid = (int)($data['pvid'] ?? 0);
        if ($pvid <= 0) return false;
        return $this->execute(
            'UPDATE product_variants SET color = ?, stock = ?, add_price = ?, sizes = ? WHERE pvid = ?',
            [trim($data['color'] ?? 'Default'), (int)($data['stock'] ?? 0), (float)($data['add_price'] ?? 0), (int)($data['size'] ?? $data['sizes'] ?? 0), $pvid]
        );
    }

    public function deleteProductVariant(int $pvid): bool
    {
        if ($pvid <= 0) return false;
        try {
            $this->conn->beginTransaction();
            $this->execute('DELETE FROM product_variants WHERE pvid = ?', [$pvid]);
            $this->conn->commit();
            return $ok;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return false;
        }
    }

    public function addVariantImage(int $PID, string $image): bool
    {
        if ($PID <= 0 || trim($image) === '') return false;
        return $this->execute('INSERT INTO product_images (PID, images) VALUES (?, ?)', [$PID, $image]);
    }

    public function deleteProductImage(int $piid): bool
    {
        if ($piid <= 0) return false;
        return $this->execute('DELETE FROM product_images WHERE piid = ?', [$piid]);
    }

    public function viewOrders(): array
    {
        return $this->fetchAll(
            "SELECT o.*, u.name AS userName, u.email AS userEmail
             FROM orders o
             LEFT JOIN users u ON o.userID = u.userID
             ORDER BY o.orderDate DESC"
        );
    }

    public function modifyOrder(int $orderID, string $status): bool
    {
        $allowed = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($status, $allowed, true)) return false;
        return $this->execute('UPDATE orders SET status = ? WHERE orderID = ?', [$status, $orderID]);
    }

    public function deleteOrder(int $orderID): bool
    {
        return $this->execute('DELETE FROM orders WHERE orderID = ?', [$orderID]);
    }

    public function viewRefunds(): array
    {
        return $this->fetchAll(
            "SELECT r.*, u.name AS userName
             FROM refund r
             LEFT JOIN users u ON u.userID = r.userID
             ORDER BY r.refundDate DESC"
        );
    }

    public function applyRefund(int $refundID): bool
    {
        return $this->execute("UPDATE refund SET status = 'Approved' WHERE refundID = ?", [$refundID]);
    }

    public function denyRefund(int $refundID): bool
    {
        return $this->execute("UPDATE refund SET status = 'Rejected' WHERE refundID = ?", [$refundID]);
    }

    public function viewExchanges(): array
    {
        return $this->fetchAll(
            "SELECT e.*, u.name AS userName
             FROM exchange e
             LEFT JOIN users u ON u.userID = e.userID
             ORDER BY e.exchangeID DESC"
        );
    }

    public function applyExchange(int $exchangeID): bool
    {
        return $this->execute("UPDATE exchange SET status = 'Approved' WHERE exchangeID = ?", [$exchangeID]);
    }

    public function denyExchange(int $exchangeID): bool
    {
        return $this->execute("UPDATE exchange SET status = 'Rejected' WHERE exchangeID = ?", [$exchangeID]);
    }

    public function viewReviews(): array
    {
        return $this->fetchAll(
            "SELECT
                r.review_ID AS reviewID,
                r.review_ID,
                r.prod_ID AS PID,
                r.prod_ID,
                r.userID,
                r.rating,
                r.comment,
                r.reviewDate,
                r.helpful_count,
                p.name AS productName,
                u.name AS userName
             FROM Review r
             LEFT JOIN product p ON p.PID = r.prod_ID
             LEFT JOIN users u ON u.userID = r.userID
             ORDER BY r.review_ID DESC"
        );
    }
    
    public function deleteReview(int $reviewID): bool
    {
        if ($reviewID <= 0) {
            return false;
        }
    
        return $this->execute(
            "DELETE FROM Review WHERE review_ID = ?",
            [$reviewID]
        );
    }
    
    public function viewStoreLocations(): array
    {
        return $this->fetchAll(
            "SELECT
                storeID,
                storeID AS BranchID,
                name,
                address,
                address AS Address,
                phone,
                email,
                city,
                city AS City,
                country,
                created_at
             FROM StoreLocation
             ORDER BY storeID DESC"
        );
    }
    
    public function addStoreLocation(array $data): bool
    {
        $name = trim($data['name'] ?? '');
        $city = trim($data['city'] ?? $data['City'] ?? '');
        $address = trim($data['address'] ?? $data['Address'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $email = trim($data['email'] ?? '');
        $country = trim($data['country'] ?? 'Egypt');
    
        if ($city === '' || $address === '') {
            return false;
        }
    
        if ($name === '') {
            $name = $city . ' Branch';
        }
    
        return $this->execute(
            "INSERT INTO StoreLocation (name, address, phone, email, city, country)
             VALUES (?, ?, ?, ?, ?, ?)",
            [$name, $address, $phone, $email, $city, $country]
        );
    }
    
    public function editStoreLocation(int $storeID, array $data): bool
    {
        if ($storeID <= 0) {
            return false;
        }
    
        $name = trim($data['name'] ?? '');
        $city = trim($data['city'] ?? $data['City'] ?? '');
        $address = trim($data['address'] ?? $data['Address'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $email = trim($data['email'] ?? '');
        $country = trim($data['country'] ?? 'Egypt');
    
        if ($city === '' || $address === '') {
            return false;
        }
    
        if ($name === '') {
            $name = $city . ' Branch';
        }
    
        return $this->execute(
            "UPDATE StoreLocation
             SET name = ?, address = ?, phone = ?, email = ?, city = ?, country = ?
             WHERE storeID = ?",
            [$name, $address, $phone, $email, $city, $country, $storeID]
        );
    }
    
    public function deleteStoreLocation(int $storeID): bool
    {
        if ($storeID <= 0) {
            return false;
        }
    
        return $this->execute(
            "DELETE FROM StoreLocation WHERE storeID = ?",
            [$storeID]
        );
    }
    
    public function viewSupport(): array
    {
        return $this->fetchAll(
            "SELECT
                s.supportID,
                s.supportID AS support_ID,
                s.userID,
                s.issue,
                s.issue AS message,
                s.status,
                s.createdDate,
                u.name AS userName
             FROM support s
             LEFT JOIN users u ON u.userID = s.userID
             ORDER BY s.supportID DESC"
        );
    }
    
    public function addSupport(array $data): bool
    {
        $userID = (int)($data['userID'] ?? 0);
        $issue = trim($data['issue'] ?? $data['message'] ?? '');
        $status = trim($data['status'] ?? 'Open');
    
        if ($userID <= 0 || $issue === '') {
            return false;
        }
    
        $allowed = ['Open', 'In Progress', 'Resolved', 'Closed'];
    
        if (!in_array($status, $allowed, true)) {
            $status = 'Open';
        }
    
        return $this->execute(
            "INSERT INTO support (userID, issue, status)
             VALUES (?, ?, ?)",
            [$userID, $issue, $status]
        );
    }
    
    public function modifySupport(int $supportID, array $data): bool
    {
        if ($supportID <= 0) {
            return false;
        }
    
        $issue = trim($data['issue'] ?? $data['message'] ?? '');
        $status = trim($data['status'] ?? 'Open');
    
        if ($issue === '') {
            return false;
        }
    
        $allowed = ['Open', 'In Progress', 'Resolved', 'Closed'];
    
        if (!in_array($status, $allowed, true)) {
            $status = 'Open';
        }
    
        return $this->execute(
            "UPDATE support
             SET issue = ?, status = ?
             WHERE supportID = ?",
            [$issue, $status, $supportID]
        );
    }
    
    public function deleteSupport(int $supportID): bool
    {
        if ($supportID <= 0) {
            return false;
        }
    
        return $this->execute(
            "DELETE FROM support WHERE supportID = ?",
            [$supportID]
        );
    }
    public function getTopSellingProducts(int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));
        return $this->fetchAll("SELECT * FROM product ORDER BY Number_Of_Sells DESC LIMIT $limit");
    }

    public function calculateMonthlyRevenue(): float
    {
        $row = $this->fetchOne(
            "SELECT SUM(totalAmount) AS revenue
             FROM orders
             WHERE MONTH(orderDate) = MONTH(NOW())
             AND YEAR(orderDate) = YEAR(NOW())
             AND status != 'Cancelled'"
        );
        return (float)($row['revenue'] ?? 0);
    }

    public function getLowStockAlerts(int $threshold = 10): array
    {
        return $this->fetchAll(
            'SELECT p.PID, p.name, p.category, p.price, pv.stock, pv.color, pv.sizes AS size
             FROM product_variants pv
             JOIN product p ON p.PID = pv.PID
             WHERE pv.stock < ?
             ORDER BY pv.stock ASC',
            [$threshold]
        );
    }
}
