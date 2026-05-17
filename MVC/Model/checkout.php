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
        $items = $stmt->fetchAll();

        $total = 0;
        foreach ($items as $row) {
            $total += $row['price'] * $row['quantity'];
        }

        return ['items' => $items, 'total' => $total];
    }

    public static function placeOrder($userID, $shippingAddress, $city, $governorate, $building, $deliveryDate, $paymentMethod)
    {
        $db = new Database();
        $conn = $db->getConnection();

        // cart
        $stmt = $conn->prepare("SELECT cartID FROM cart WHERE userID = ? LIMIT 1");
        $stmt->execute([$userID]);
        $cart = $stmt->fetch();
        if (!$cart)
            return ['success' => false, 'error' => 'Cart not found.'];
        $cartID = $cart['cartID'];

        // items
        $stmt = $conn->prepare("SELECT PID, quantity, price FROM cart_items WHERE cartID = ?");
        $stmt->execute([$cartID]);
        $items = $stmt->fetchAll();
        if (!$items)
            return ['success' => false, 'error' => 'Cart is empty.'];

        $total = 0;
        foreach ($items as $row) {
            $total += $row['price'] * $row['quantity'];
        }

        // save checkout
        $stmt = $conn->prepare("
        INSERT INTO checkout (userID, cartID, shippingAddress, city, governorate, building, deliveryDate, paymentMethod)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->execute([$userID, $cartID, $shippingAddress, $city, $governorate, $building, $deliveryDate, $paymentMethod]);
        $checkoutID = $conn->lastInsertId();

        // save order linked to checkout
        $stmt = $conn->prepare("
        INSERT INTO orders (userID, totalAmount, status, shippingAddress, deliveryDate, paymentMethod, checkoutID)
        VALUES (?, ?, 'Pending', ?, ?, ?, ?)
    ");
        $stmt->execute([$userID, $total, $shippingAddress, $deliveryDate, $paymentMethod, $checkoutID]);
        $orderID = $conn->lastInsertId();

        // order items
        $stmt = $conn->prepare("INSERT INTO order_items (orderID, PID, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$orderID, $item['PID'], $item['quantity'], $item['price']]);
        }

        // clear cart
        $conn->prepare("DELETE FROM cart_items WHERE cartID = ?")->execute([$cartID]);
        $conn->prepare("UPDATE cart SET total = 0 WHERE cartID = ?")->execute([$cartID]);

        return ['success' => true, 'orderID' => $orderID, 'checkoutID' => $checkoutID];
    }

}

