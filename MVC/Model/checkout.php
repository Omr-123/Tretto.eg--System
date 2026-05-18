<?php
require_once __DIR__ . '/../../db.php';

class Checkout
{
    public static function getCartItems($userID)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("
            SELECT ci.PID, ci.quantity, ci.price, p.name, p.category
            FROM cart ca
            JOIN cart_items ci ON ca.cartID = ci.cartID
            JOIN product p     ON ci.PID    = p.PID
            WHERE ca.userID = ?
        ");
        $stmt->execute([$userID]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($items as $row) {
            $total += $row['price'] * $row['quantity'];
        }

        return ['items' => $items, 'total' => $total];
    }

    public static function saveCheckout($userID, $shippingAddress, $city, $governorate, $building)
    {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT cartID FROM cart WHERE userID = ? LIMIT 1");
        $stmt->execute([$userID]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cart)
            return ['success' => false, 'error' => 'Cart not found.'];

        $cartID = $cart['cartID'];

        $stmt = $conn->prepare("SELECT COUNT(*) FROM cart_items WHERE cartID = ?");
        $stmt->execute([$cartID]);
        if ($stmt->fetchColumn() == 0)
            return ['success' => false, 'error' => 'Cart is empty.'];

        $stmt = $conn->prepare("
            INSERT INTO checkout (userID, cartID, shippingAddress, city, governorate, building)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userID, $cartID, $shippingAddress, $city, $governorate, $building]);
        $checkoutID = $conn->lastInsertId();

        return ['success' => true, 'checkoutID' => $checkoutID];
    }
}