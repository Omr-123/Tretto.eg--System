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

    if (empty($cartItems)) {
        header('Location: /Tretto.eg--System/MVC/View/GUI/cart.php');
        exit;
    }

    $user = [
        'firstName' => $_SESSION['firstName'] ?? '',
        'lastName' => $_SESSION['lastName'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'phone' => $_SESSION['phone'] ?? '',
    ];

    unset($_SESSION['checkout_error']);
    include __DIR__ . '/../View/GUI/checkout.php';
    exit;
}

$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$shippingAddress = trim($_POST['street'] ?? '');
$city = trim($_POST['city'] ?? '');
$governorate = trim($_POST['governorate'] ?? '');
$building = trim($_POST['building'] ?? '');

if (
    empty($firstName) || empty($lastName) || empty($email) || empty($phone) ||
    empty($shippingAddress) || empty($city) || empty($governorate) || empty($building)
) {
    $_SESSION['checkout_error'] = 'Please fill in all fields.';
    header('Location: checkout_Controller.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['checkout_error'] = 'Please enter a valid email address.';
    header('Location: checkout_Controller.php');
    exit;
}

$result = Checkout::saveCheckout($userID, $shippingAddress, $city, $governorate, $building);


if (!$result['success']) {
    $_SESSION['checkout_error'] = $result['error'];
    header('Location: checkout_Controller.php');
    exit;
}
$_SESSION['checkoutID'] = $result['checkoutID'];
unset($_SESSION['checkout_error']);

header('Location: /Tretto.eg--System/MVC/View/GUI/payment.php');
exit;