<?php
require_once (__DIR__ . '/../../db.php');

class Product {
    public $pid;
    public $name;
    public $price;
    public $stockquantity;
    public $description;
    public $category;
    public $storeID;
    public $color;
    public $image;
    public $size;
    public $number_of_selling;

    public function __construct($pid, $name,$description,$price, $stock, $category, $image, $storeID) {
        $this->pid = $pid;
        $this->name = $name;
        $this->price = $price;
        $this->stockquantity = $stock;
        $this->description = $description;
        $this->image = $image;
        $this->category = $category;
        $this->storeID = $storeID;
    }
}