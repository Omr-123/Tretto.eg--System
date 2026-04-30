<?php
require_once('../../db.php');

class Order {
    public $order_ID;
    public $userID;
    public $orderDate;
    public $totalAmount;
    public $status;
    public $shippingAddress;
    public $paymentMethod;
    public $deliveryDate;

}