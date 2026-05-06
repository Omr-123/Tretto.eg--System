<?php


class Cart {
    public $cartID;
    public $userID;
    public $quantity;
    public $total_amount;
    public $product_ID;
    public $itemlist = [];
     public function __construct($cartID, $userID,  $product_ID,$quantity) {
        $this->cartID = $cartID;
        $this->userID = $userID;
        $this->product_ID = $product_ID;
        $this->quantity = $quantity;
    }
 
   
}
