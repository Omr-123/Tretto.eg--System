<?php
require_once(__DIR__ . '/../../db.php');

class Checkout {
    public $userId;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $governorate;
    public $city;
    public $streetAddress;
    public $buildingApartment;

    public static function getCartItems($conn, $userID) {
        $sql = "SELECT c.cart_ID, c.userID, c.prod_ID, c.quantity, c.addedDate,
                       p.name, p.image, p.category, p.price
                FROM cart c
                JOIN product p ON c.prod_ID = p.prod_ID
                WHERE c.userID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $res = $stmt->get_result();

        $items = [];
        $total = 0;
        while ($row = $res->fetch_assoc()) {
            $items[] = $row;
            $total += $row['price'] * $row['quantity'];
        }

        return ['items' => $items, 'total' => $total];
    }

    public function processCheckout($conn) {
        if (!$this->email || !$this->userId) {
            return "❌ Missing required fields";
        }

        $sqlCart = "SELECT c.prod_ID, c.quantity, p.price 
                    FROM cart c 
                    JOIN product p ON c.prod_ID = p.prod_ID 
                    WHERE c.userID = ?";
        $stmtCart = $conn->prepare($sqlCart);
        $stmtCart->bind_param("i", $this->userId);
        $stmtCart->execute();
        $resCart = $stmtCart->get_result();

        $total = 0;
        $items = [];
        while ($row = $resCart->fetch_assoc()) {
            $items[] = $row;
            $total += $row['price'] * $row['quantity'];
        }

        if (empty($items)) {
            return "❌ Cart is empty";
        }

        $sqlOrder = "INSERT INTO orders 
            (userID, first_name, last_name, email, phone, governorate, city, street_address, building_apartment, totalAmount, status, orderDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param(
            "isssssssid",
            $this->userId,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->governorate,
            $this->city,
            $this->streetAddress,
            $this->buildingApartment,
            $total
        );
        $stmtOrder->execute();
        $orderID = $stmtOrder->insert_id;

        foreach ($items as $item) {
            $sqlItem = "INSERT INTO order_items (order_ID, productID, quantity, price) VALUES (?, ?, ?, ?)";
            $stmtItem = $conn->prepare($sqlItem);
            $stmtItem->bind_param("iiid", $orderID, $item['prod_ID'], $item['quantity'], $item['price']);
            $stmtItem->execute();
        }

        $sqlClear = "DELETE FROM cart WHERE userID = ?";
        $stmtClear = $conn->prepare($sqlClear);
        $stmtClear->bind_param("i", $this->userId);
        $stmtClear->execute();

        return "✅ Order saved successfully. Order ID: " . $orderID;
    }
}
