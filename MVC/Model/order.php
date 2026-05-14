<?php
require_once('../../db.php');

class Order
{
    public $order_ID;
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
        $sql = "SELECT o.*, u.name AS userName 
                FROM orders o 
                JOIN User u ON o.userID = u.userID 
                WHERE o.order_ID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $order = new Order();
            foreach ($row as $key => $value) {
                if (property_exists($order, $key)) {
                    $order->$key = $value;
                }
            }
            $order->userName = $row['userName'];
            return $order;
        }
        return null;
    }

    public static function getItems($conn, $orderID)
    {
        $stmt = $conn->prepare("SELECT product_name, quantity, price FROM order_items WHERE order_ID = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
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
