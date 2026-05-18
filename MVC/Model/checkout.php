<?php
require_once __DIR__ . '/../../db.php';

class Checkout
{
    private static function conn(): mysqli
    {
        global $conn;
        return $conn;
    }

    public static function getCartItems(int $userID): array
    {
        if ($userID <= 0) {
            return ['items' => [], 'total' => 0];
        }

        $conn = self::conn();
        $stmt = $conn->prepare("
            SELECT ci.PID, ci.quantity, ci.price, p.name, p.category
            FROM cart ca
            INNER JOIN cart_items ci ON ca.cartID = ci.cartID
            INNER JOIN product p ON ci.PID = p.PID
            WHERE ca.userID = ?
        ");

        if (!$stmt) {
            return ['items' => [], 'total' => 0];
        }

        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        $total = 0.0;
        foreach ($items as $row) {
            $total += (float) $row['price'] * (int) $row['quantity'];
        }

        return ['items' => $items, 'total' => round($total, 2)];
    }

    public static function saveCheckout(
        int $userID,
        string $shippingAddress,
        string $city,
        string $governorate,
        string $building
    ): array {
        if ($userID <= 0) {
            return ['success' => false, 'error' => 'Please log in first.'];
        }

        $conn = self::conn();

        $stmt = $conn->prepare('SELECT cartID FROM cart WHERE userID = ? LIMIT 1');
        if (!$stmt) {
            return ['success' => false, 'error' => 'Database error.'];
        }

        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $cart = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$cart) {
            return ['success' => false, 'error' => 'Cart not found.'];
        }

        $cartID = (int) $cart['cartID'];

        $stmt = $conn->prepare('SELECT COUNT(*) AS cnt FROM cart_items WHERE cartID = ?');
        if (!$stmt) {
            return ['success' => false, 'error' => 'Database error.'];
        }

        $stmt->bind_param('i', $cartID);
        $stmt->execute();
        $count = (int) ($stmt->get_result()->fetch_assoc()['cnt'] ?? 0);
        $stmt->close();

        if ($count === 0) {
            return ['success' => false, 'error' => 'Cart is empty.'];
        }

        $stmt = $conn->prepare("
            INSERT INTO checkout (userID, cartID, shippingAddress, city, governorate, building)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            return ['success' => false, 'error' => 'Database error.'];
        }

        $stmt->bind_param('iissss', $userID, $cartID, $shippingAddress, $city, $governorate, $building);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'error' => 'Could not save checkout.'];
        }

        $checkoutID = (int) $stmt->insert_id;
        $stmt->close();

        return ['success' => true, 'checkoutID' => $checkoutID];
    }
}
