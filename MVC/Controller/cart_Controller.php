<?php

require_once (__DIR__ . '/../Model/cart.php');
require_once (__DIR__ . '/../../db.php');


class Cart_Controller{
    private $cart_model;

    public function __construct(){
        $database = new Database();
        $this->cart_model=$database->getConnection();

    }

    public function get_cart_items($user_id){
        $query = "SELECT * FROM cart WHERE userID = :user_id";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cart=$stmt->fetchAll();
        $cart_items = [];
        foreach($cart as $cartt){
            $cart_items[] = new Cart(
                $cartt['cart_ID'],
                $cartt['userID'],
                $cartt['prod_ID'],
                $cartt['quantity']
            );
        }
        return $cart_items;
    }
     
}