<?php
require_once __DIR__ . '/../../db.php';

class ExchangeModel
{
    public mysqli $db;
    private string $lastError = '';

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
    public function getDeliveredOrdersByUser(int $userID): array
    {
        if ($userID <= 0) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT orderID, userID, orderDate, totalAmount,
                   status, shippingAddress, paymentMethod, deliveryDate
            FROM orders
            WHERE userID = ?
              AND status = 'Delivered'
            ORDER BY COALESCE(deliveryDate, orderDate) DESC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }
    public function getOrderItems(int $orderID): array
    {
        if ($orderID <= 0) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT
                oi.itemID,
                oi.orderID,
                oi.PID AS product_id,
                oi.quantity,
                oi.price,
                p.name AS product_name,
                COALESCE(MAX(pi.images), '') AS product_image,
                p.category,
                p.descriptions AS description,
                o.deliveryDate AS delivery_date
            FROM order_items oi
            INNER JOIN product p ON p.PID = oi.PID
            INNER JOIN orders o ON o.orderID = oi.orderID
            LEFT JOIN product_variants pv ON pv.PID = p.PID
            LEFT JOIN product_images pi ON pi.pvid = pv.pvid
            WHERE oi.orderID = ?
            GROUP BY oi.itemID, oi.orderID, oi.PID, oi.quantity, oi.price,
                     p.name, p.category, p.descriptions, o.deliveryDate
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }
    /**
     * @return array{type: string, id: int}|false
     */
    public function saveRequest(array $d): array|false
    {
        $type = (string) ($d['request_type'] ?? '');

        if ($type === 'refund') {
            $id = $this->createRefund($d);
            return $id ? ['type' => 'refund', 'id' => $id] : false;
        }

        if ($type === 'exchange') {
            $id = $this->createExchangeRecord($d);
            return $id ? ['type' => 'exchange', 'id' => $id] : false;
        }

        $this->lastError = 'Invalid request type.';
        return false;
    }
    public function createRefund(array $d): int|false
    {
        $orderId = (int) $d['order_id'];
        $userId = (int) $d['user_id'];
        $productId = (int) $d['old_product_id'];
        $amount = $this->getLineItemAmount($orderId, $productId);
        $reason = $this->buildReasonText($d);

        $stmt = $this->db->prepare("
            INSERT INTO refund (orderID, userID, refundAmount, status, reason)
            VALUES (?, ?, ?, 'Pending', ?)
        ");

        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param('iids', $orderId, $userId, $amount, $reason);

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error;
            $stmt->close();
            return false;
        }

        $id = (int) $stmt->insert_id;
        $stmt->close();
        return $id > 0 ? $id : false;
    }

    public function createExchangeRecord(array $d): int|false
    {
        $orderId = (int) $d['order_id'];
        $userId = (int) $d['user_id'];
        $oldProductId = (int) $d['old_product_id'];
        $newProductId = !empty($d['new_product_id']) ? (int) $d['new_product_id'] : $oldProductId;
        $paymentId = $this->getPaymentIdForOrder($orderId);

        if ($paymentId === null) {
            $this->lastError = 'No payment record found for this order.';
            return false;
        }

        $reason = $this->buildReasonText($d);

        $stmt = $this->db->prepare("
            INSERT INTO exchange (orderID, userID, paymentID, oldProductID, newProductID, reason, status)
            VALUES (?, ?, ?, ?, ?, ?, 'Pending')
        ");

        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param('iiiiss', $orderId, $userId, $paymentId, $oldProductId, $newProductId, $reason);

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error;
            $stmt->close();
            return false;
        }

        $id = (int) $stmt->insert_id;
        $stmt->close();
        return $id > 0 ? $id : false;
    }

    public function getSavedRequest(string $type, int $id, int $productId = 0): array|false
    {
        if ($type === 'refund') {
            return $this->getRefundByID($id, $productId);
        }
        if ($type === 'exchange') {
            return $this->getExchangeByID($id);
        }
        return false;
    }

    public function getRefundByID(int $id, int $productId = 0): array|false
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT r.*, o.orderID AS order_ref, r.refundDate AS created_at
            FROM refund r
            JOIN orders o ON o.orderID = r.orderID
            WHERE r.refundID = ?
            LIMIT 1
        ");

        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return false;
        }

        $row['request_type'] = 'refund';
        $row['old_product_name'] = $this->getProductName($productId);

        return $row;
    }

    public function getExchangeByID(int $id): array|false
    {
        if ($id <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT e.*, p.name AS old_product_name, o.orderID AS order_ref, e.exchangeDate AS created_at
            FROM exchange e
            JOIN product p ON p.PID = e.oldProductID
            JOIN orders o ON o.orderID = e.orderID
            WHERE e.exchangeID = ?
            LIMIT 1
        ");

        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) {
            return false;
        }

        $row['request_type'] = 'exchange';

        return $row;
    }

    private function buildReasonText(array $d): string
    {
        $lines = [
            'Reason: ' . ($d['reason'] ?? ''),
            'Details: ' . ($d['details'] ?? ''),
            'Contact: ' . ($d['contact_method'] ?? ''),
            'Product ID: ' . ($d['old_product_id'] ?? ''),
        ];

        if (!empty($d['preferred_size'])) {
            $lines[] = 'Preferred size: ' . $d['preferred_size'];
        }
        if (!empty($d['preferred_color'])) {
            $lines[] = 'Preferred color: ' . $d['preferred_color'];
        }
        if (!empty($d['new_product_id'])) {
            $lines[] = 'New product ID: ' . $d['new_product_id'];
        }

        return implode("\n", $lines);
    }

    private function getLineItemAmount(int $orderID, int $productID): float
    {
        $stmt = $this->db->prepare("
            SELECT price, quantity FROM order_items
            WHERE orderID = ? AND PID = ?
            LIMIT 1
        ");

        if ($stmt) {
            $stmt->bind_param('ii', $orderID, $productID);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($row) {
                return round((float) $row['price'] * (int) $row['quantity'], 2);
            }
        }

        $stmt = $this->db->prepare("SELECT totalAmount FROM orders WHERE orderID = ? LIMIT 1");
        if (!$stmt) {
            return 0.0;
        }
        $stmt->bind_param('i', $orderID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return round((float) ($row['totalAmount'] ?? 0), 2);
    }

    private function getPaymentIdForOrder(int $orderID): ?int
    {
        $stmt = $this->db->prepare("
            SELECT paymentID FROM payment
            WHERE orderID = ?
            ORDER BY paymentID DESC
            LIMIT 1
        ");

        if ($stmt) {
            $stmt->bind_param('i', $orderID);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if ($row) {
                return (int) $row['paymentID'];
            }
        }

        $stmt = $this->db->prepare("
            SELECT totalAmount, paymentMethod FROM orders WHERE orderID = ? LIMIT 1
        ");
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $orderID);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$order) {
            return null;
        }

        $amount = (float) $order['totalAmount'];
        $method = (string) ($order['paymentMethod'] ?? 'Cash');

        $insert = $this->db->prepare("
            INSERT INTO payment (orderID, amount, method, status)
            VALUES (?, ?, ?, 'Completed')
        ");
        if (!$insert) {
            $this->lastError = $this->db->error;
            return null;
        }

        $insert->bind_param('ids', $orderID, $amount, $method);
        if (!$insert->execute()) {
            $this->lastError = $insert->error;
            $insert->close();
            return null;
        }

        $paymentId = (int) $insert->insert_id;
        $insert->close();

        return $paymentId > 0 ? $paymentId : null;
    }

    private function getProductName(int $productID): string
    {
        if ($productID <= 0) {
            return '';
        }

        $stmt = $this->db->prepare("SELECT name FROM product WHERE PID = ? LIMIT 1");
        if (!$stmt) {
            return '';
        }
        $stmt->bind_param('i', $productID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (string) ($row['name'] ?? '');
    }
    public function isOrderOwnedByUser(int $orderID, int $userID): bool
    {
        if ($orderID <= 0 || $userID <= 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS cnt
            FROM orders
            WHERE orderID = ?
              AND userID  = ?
              AND status  = 'Delivered'
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('ii', $orderID, $userID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) ($row['cnt'] ?? 0) > 0;
    }
    public function getAvailableProducts(): array
    {
        $result = $this->db->query("
            SELECT
                p.PID AS product_id,
                p.name,
                p.price,
                p.category
            FROM product p
            WHERE EXISTS (
                SELECT 1 FROM product_variants pv
                WHERE pv.PID = p.PID AND pv.stock > 0
            )
            ORDER BY p.name ASC
        ");

        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getVariantsByProduct(): array
    {
        $result = $this->db->query("
            SELECT PID, sizes, color, stock
            FROM product_variants
            WHERE stock > 0
            ORDER BY PID, sizes, color
        ");

        if (!$result) {
            return [];
        }

        $map = [];

        while ($row = $result->fetch_assoc()) {
            $pid = (int) $row['PID'];
            if (!isset($map[$pid])) {
                $map[$pid] = ['sizes' => [], 'colors' => []];
            }

            if ($row['sizes'] !== null && $row['sizes'] !== '') {
                $size = (string) (int) $row['sizes'];
                if (!in_array($size, $map[$pid]['sizes'], true)) {
                    $map[$pid]['sizes'][] = $size;
                }
            }

            $color = trim((string) ($row['color'] ?? ''));
            if ($color !== '') {
                $exists = false;
                foreach ($map[$pid]['colors'] as $c) {
                    if ($c['value'] === $color) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $map[$pid]['colors'][] = [
                        'value' => $color,
                        'label' => $this->formatColorLabel($color),
                    ];
                }
            }
        }

        foreach ($map as &$entry) {
            sort($entry['sizes'], SORT_NUMERIC);
        }
        unset($entry);

        return $map;
    }

    public function getGlobalSizes(): array
    {
        $result = $this->db->query("
            SELECT DISTINCT sizes
            FROM product_variants
            WHERE stock > 0 AND sizes IS NOT NULL
            ORDER BY sizes ASC
        ");

        if (!$result) {
            return [];
        }

        $sizes = [];
        while ($row = $result->fetch_assoc()) {
            $sizes[] = (string) (int) $row['sizes'];
        }
        return $sizes;
    }

    public function getGlobalColors(): array
    {
        $result = $this->db->query("
            SELECT DISTINCT color
            FROM product_variants
            WHERE stock > 0 AND color IS NOT NULL AND color != ''
            ORDER BY color ASC
        ");

        if (!$result) {
            return [];
        }

        $colors = [];
        while ($row = $result->fetch_assoc()) {
            $value = trim((string) $row['color']);
            if ($value === '') {
                continue;
            }
            $colors[] = [
                'value' => $value,
                'label' => $this->formatColorLabel($value),
            ];
        }
        return $colors;
    }

    private function formatColorLabel(string $color): string
    {
        $key = strtoupper(trim($color));
        $names = [
            '#7BA7BC' => 'Denim Blue',
            '#000000' => 'Black',
            '#050505' => 'Black',
            '#FFFFFF' => 'White',
            '#F5F5DC' => 'Beige',
        ];

        return $names[$key] ?? $color;
    }
}
