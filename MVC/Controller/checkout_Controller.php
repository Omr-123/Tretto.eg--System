<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../Model/Checkout.php';

if (empty($_SESSION['logged_in'])) {
    header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
    exit;
}

$userID = (int) $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cartData = Checkout::getCartItems($userID);
    $cartItems = $cartData['items'];
    $total = $cartData['total'];
    include(__DIR__ . '/../View/GUI/checkout.php');
    exit;
}

$shippingAddress = trim($_POST['street'] ?? '');
$city = trim($_POST['city'] ?? '');
$governorate = trim($_POST['governorate'] ?? '');
$building = trim($_POST['building'] ?? '');
$deliveryDate = trim($_POST['deliveryDate'] ?? '');
$paymentMethod = trim($_POST['paymentMethod'] ?? '');

if (empty($shippingAddress) || empty($city) || empty($governorate) || empty($building) || empty($deliveryDate) || empty($paymentMethod)) {
    $_SESSION['checkout_error'] = 'Please fill in all fields.';
    $_SESSION['old_checkout'] = $_POST;
    header('Location: checkout_Controller.php');
    exit;
}

$result = Checkout::placeOrder($userID, $shippingAddress, $city, $governorate, $building, $deliveryDate, $paymentMethod);

if (!$result['success']) {
    $_SESSION['checkout_error'] = $result['error'];
    header('Location: checkout_Controller.php');
    exit;
}

header('Location: placeorder_controller.php?id=' . $result['orderID']);
exit;
