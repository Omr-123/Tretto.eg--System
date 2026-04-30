<?php
require_once('../../db.php');

class Refund {
    public $refund_ID;
    public $order_ID;
    public $refundAmount;
    public $refundDate;
    public $reason;
    public $status;

}