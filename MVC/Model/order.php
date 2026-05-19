<?php
require_once(__DIR__ . '/../../db.php');

class Order
{
    public $orderID;
    public $userID;
    public $orderDate;
    public $totalAmount;
    public $status;
    public $shippingAddress;
    public $paymentMethod;
    public $deliveryDate;
    public $userName;

    public static function getById($conn, $orderID)
    {
        $orderID = (int) $orderID;
        $stmt = $conn->prepare(
            "SELECT o.*, u.name AS userName
             FROM orders o
             JOIN users u ON o.userID = u.userID
             WHERE o.orderID = ?"
        );
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row)
            return null;

        $order = new Order();
        $order->orderID = $row['orderID'];
        $order->userID = $row['userID'];
        $order->orderDate = $row['orderDate'];
        $order->totalAmount = $row['totalAmount'];
        $order->status = $row['status'];
        $order->shippingAddress = $row['shippingAddress'];
        $order->paymentMethod = $row['paymentMethod'];
        $order->deliveryDate = $row['deliveryDate'];
        $order->userName = $row['userName'];
        return $order;
    }

    public static function getItems($conn, $orderID)
    {
        $orderID = (int) $orderID;
        $stmt = $conn->prepare(
            "SELECT p.name AS product_name, oi.quantity, oi.price,
                    (SELECT images FROM product_images WHERE PID = p.PID LIMIT 1) AS images
             FROM order_items oi
             JOIN product p ON oi.PID = p.PID
             WHERE oi.orderID = ?"
        );
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
}