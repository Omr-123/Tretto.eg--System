<?php
require_once('../../db.php');
require_once('payment.php');

class Cash extends Payment {
    public $receivedAmount;
    public $changeAmount;
    public $paymentLocation;

}