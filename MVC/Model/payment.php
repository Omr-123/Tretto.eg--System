<?php
require_once('../../db.php');

class Payment {
    public $payment_ID;
    public $order_ID;
    public $amount;
    public $paymentDate;
    public $method;
    public $status;

}