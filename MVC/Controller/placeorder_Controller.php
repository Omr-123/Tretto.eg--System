<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../Model/order.php');

$orderID = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($orderID <= 0) {
    header("Location: /Tretto.eg--System/MVC/View/GUI/index.php");
    exit();
}

$order = Order::getById($conn, $orderID);
if (!$order) {
    header("Location: /Tretto.eg--System/MVC/View/GUI/index.php");
    exit();
}

$orderItems = Order::getItems($conn, $orderID);

include(__DIR__ . '/../View/GUI/placeorder.php');