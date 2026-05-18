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

        $sql = "SELECT o.*, u.name AS userName
                FROM orders o
                JOIN users u ON o.userID = u.userID
                WHERE o.orderID = $orderID";

        $result = $conn->query($sql);
        if (!$result)
            die("Query failed: " . $conn->error);

        if ($row = $result->fetch_assoc()) {
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
        return null;
    }

    public static function getItems($conn, $orderID)
    {
        $orderID = (int) $orderID;

        $sql = "SELECT p.name AS product_name, oi.quantity, oi.price,
                   pi.images
            FROM order_items oi
            JOIN product p ON oi.PID = p.PID
            LEFT JOIN product_variants pv ON pv.PID = p.PID
            LEFT JOIN product_images pi ON pi.pvid = pv.pvid
            WHERE oi.orderID = $orderID
            GROUP BY oi.itemID";

        $result = $conn->query($sql);
        if (!$result)
            die("Query failed: " . $conn->error);

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
}