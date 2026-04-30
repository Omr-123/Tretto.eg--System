<?php
require_once('../../db.php');
require_once('payment.php');

class Exchange extends Payment {
    public $oldProductID;
    public $newProductID;
    public $exchangeDate;
    public $reason;

}