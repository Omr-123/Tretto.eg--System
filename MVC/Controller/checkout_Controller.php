<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../Model/checkout.php');

$userID = 1; 

$cartData  = Checkout::getCartItems($conn, $userID);
$cartItems = $cartData['items'];
$total     = $cartData['total'];

include(__DIR__ . '/../../View/GUI/checkout.php');

