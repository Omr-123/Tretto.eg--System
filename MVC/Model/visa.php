<?php
require_once('../../db.php');
require_once('payment.php');

class Visa extends Payment {
    public $cardNumber;
    public $cardHolderName;
    public $expiryDate;
    public $cvv;

}