<?php
require_once('../../db.php');
require_once('product.php');

class Bag extends Product {
    public $capacityliters;
    public $numpackets;
}