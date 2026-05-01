<?php
require_once('../../db.php');
require_once('product.php');

class Bag extends Product {
    public $bag_type;
    public $material;
    public $capacity;
    public $capacityliters;
    public $numpackets;
}