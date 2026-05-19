<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../Model/checkout.php';
require_once __DIR__ . '/../Model/PaymentModel.php';

if (empty($_SESSION['userID'])) {
    header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
    exit;
}

$userID = (int) $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once __DIR__ . '/cart_Controller.php';
    $cartCtrl = new Cart_Controller();
    $cartItems = $cartCtrl->get_cart_items($userID);
    $cart_info = $cartCtrl->get_cart_info($userID);

    if (empty($cartItems)) {
        header('Location: /Tretto.eg--System/MVC/View/GUI/cart.php');
        exit;
    }

    $subtotal = (float) $cartCtrl->Subtotal($userID);
    $shipping = $subtotal >= PaymentModel::FREE_SHIPPING_MIN ? 0.0 : PaymentModel::SHIPPING_FEE;
    $total = $subtotal + $shipping;

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