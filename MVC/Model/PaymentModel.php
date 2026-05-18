<?php

require_once __DIR__ . '/../../db.php';

class PaymentModel
{
    private mysqli $conn;

    public const SHIPPING_FEE = 70.0;
    public const FREE_SHIPPING_MIN = 2000.0;

    public function __construct(?mysqli $conn = null)
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getCartIdByUser(int $userId): ?int
    {
        $stmt = $this->conn->prepare('SELECT cartID FROM cart WHERE userID = ? LIMIT 1');
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? (int) $row['cartID'] : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getCartLineItems(int $userId): array
    {
        $sql = "
            SELECT
                ci.PID,
                ci.pvid,
                ci.quantity,
                ci.price AS line_unit_price,
                p.name AS product_name,
                p.price AS base_price,
                pv.color,
                pv.sizes AS size_value,
                pv.add_price,
                (
                    SELECT pi.images
                    FROM product_images pi
                    WHERE pi.pvid = ci.pvid
                    ORDER BY pi.piid ASC
                    LIMIT 1
                ) AS image_path
            FROM cart c
            INNER JOIN cart_items ci ON ci.cartID = c.cartID
            INNER JOIN product p ON p.PID = ci.PID
            LEFT JOIN product_variants pv ON pv.pvid = ci.pvid
            WHERE c.userID = ?
            ORDER BY ci.PID ASC
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $qty = (int) $row['quantity'];
            $unit = (float) $row['line_unit_price'];
            if ($unit <= 0) {
                $unit = (float) $row['base_price'] + (float) ($row['add_price'] ?? 0);
            }

            $items[] = [
                'pid' => (int) $row['PID'],
                'pvid' => (int) $row['pvid'],
                'name' => $row['product_name'],
                'image' => $this->resolveImageUrl($row['image_path'] ?? ''),
                'size' => $row['size_value'] !== null ? (string) $row['size_value'] : 'N/A',
                'color' => $row['color'] ?? 'N/A',
                'quantity' => $qty,
                'unit_price' => $unit,
                'subtotal' => $unit * $qty,
            ];
        }

        $stmt->close();
        return $items;
    }

    /**
     * @param list<array<string, mixed>> $items
     * @return array{subtotal: float, shipping: float, total: float}
     */
    public function calculateTotals(array $items): array
    {
        $subtotal = 0.0;
        foreach ($items as $item) {
            $subtotal += (float) $item['subtotal'];
        }

        $shipping = $subtotal >= self::FREE_SHIPPING_MIN || $subtotal <= 0
            ? 0.0
            : self::SHIPPING_FEE;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    public function getCheckoutById(int $checkoutId, int $userId): ?array
    {
        $stmt = $this->conn->prepare(
            'SELECT checkoutID, shippingAddress, city, governorate, building
             FROM checkout
             WHERE checkoutID = ? AND userID = ?
             LIMIT 1'
        );
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('ii', $checkoutId, $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    /**
     * @param list<array<string, mixed>> $items
     */
    public function placeOrder(int $userId, int $checkoutId, string $paymentMethod, array $items, float $totalAmount): array
    {
        $checkout = $this->getCheckoutById($checkoutId, $userId);
        if (!$checkout) {
            return ['success' => false, 'error' => 'Checkout session expired. Please start again.'];
        }

        if (empty($items)) {
            return ['success' => false, 'error' => 'Your cart is empty'];
        }

        $shippingAddress = trim(
            $checkout['shippingAddress'] . ', ' .
            $checkout['building'] . ', ' .
            $checkout['city'] . ', ' .
            $checkout['governorate']
        );

        $methodLabel = $paymentMethod === 'visa' ? 'Visa' : 'Cash on Delivery';
        $cartId = $this->getCartIdByUser($userId);

        $this->conn->begin_transaction();

        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO orders (userID, totalAmount, status, shippingAddress, paymentMethod)
                 VALUES (?, ?, ?, ?, ?)'
            );
            if (!$stmt) {
                throw new RuntimeException($this->conn->error);
            }

            $status = 'Pending';
            $stmt->bind_param('idsss', $userId, $totalAmount, $status, $shippingAddress, $methodLabel);
            if (!$stmt->execute()) {
                throw new RuntimeException($stmt->error);
            }
            $orderId = (int) $stmt->insert_id;
            $stmt->close();

            $itemStmt = $this->conn->prepare(
                'INSERT INTO order_items (orderID, PID, quantity, price) VALUES (?, ?, ?, ?)'
            );
            if (!$itemStmt) {
                throw new RuntimeException($this->conn->error);
            }

            foreach ($items as $item) {
                $pid = (int) $item['pid'];
                $qty = (int) $item['quantity'];
                $price = (float) $item['unit_price'];
                $itemStmt->bind_param('iiid', $orderId, $pid, $qty, $price);
                if (!$itemStmt->execute()) {
                    throw new RuntimeException($itemStmt->error);
                }
            }
            $itemStmt->close();

            if ($cartId) {
                $clear = $this->conn->prepare('DELETE FROM cart_items WHERE cartID = ?');
                if ($clear) {
                    $clear->bind_param('i', $cartId);
                    $clear->execute();
                    $clear->close();
                }
            }

            $this->conn->commit();

            return ['success' => true, 'orderID' => $orderId];
        } catch (Throwable $e) {
            $this->conn->rollback();
            return ['success' => false, 'error' => 'Could not place order. Please try again.'];
        }
    }

    private function resolveImageUrl(string $path): string
    {
        if ($path === '') {
            return '/Tretto.eg--System/MVC/View/GUI/assets/images/placeholder.png';
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }
        return '/Tretto.eg--System/MVC/View/GUI/' . ltrim($path, '/');
    }
}
