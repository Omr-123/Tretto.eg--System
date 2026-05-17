<?php

require_once (__DIR__ . '/../Model/cart.php');
require_once (__DIR__ . '/../Model/product.php');
require_once (__DIR__ . '/../../db.php');
require_once('products_controller.php');


class Cart_Controller{
    private $cart_model;

    public function __construct(){
        $database = new Database();
        $this->cart_model=$database->getConnection();

    }

    public function get_cart_items($user_id){
        $query = "SELECT * FROM cart WHERE ID = :user_id";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cart=$stmt->fetchAll();
        
        $query = "SELECT * FROM cart_items WHERE cartID = :cartID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cart[0]['cartID']);
        $stmt->execute();
        
        $cart = $stmt->fetchAll();
        $products=[];
        $prod=new ProductsController();
        foreach($cart as $cartt){
            
            $products[]=$prod->getProductbyID($cartt['prod_ID']); 
        }
        return $products;
    }
    public function Subtotal($cartID){
        $query="SELECT * FROM cart_items WHERE cartID=:cartID";
        $stmt=$this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->execute();
        $cart=$stmt->fetchAll();
        $prod=new ProductsController();
        $subtotal=0;
        foreach($cart as $cartt){
            $product=$prod->getProductbyID($cartt['prod_ID']);
            $subtotal+=($product->price+ $product->variants[$cartt['pvid']]->add_price )* $cartt['quantity'];
        }
        return $subtotal;
    }
    public function add_to_cart($user_id, $prod_ID, $quantity){
        $query = "INSERT INTO cart (userID, prod_ID, quantity) VALUES (:user_id, :prod_ID, :quantity)";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }
     public function remove_from_cart($user_id, $prod_ID){
        $query = "DELETE FROM cart WHERE userID = :user_id AND prod_ID = :prod_ID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':prod_ID', $prod_ID);
        return $stmt->execute();
     }
     public function viewAllCart(){
        $query = "SELECT * FROM cart";
        $stmt = $this->cart_model->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
     }
}