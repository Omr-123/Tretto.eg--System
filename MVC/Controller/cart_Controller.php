<?php

require_once (__DIR__ . '/../Model/cart.php');
require_once (__DIR__ . '/../Model/product.php');
require_once (__DIR__ . '/../Model/ProductFactory.php');
require_once (__DIR__ . '/products_controller.php');
require_once (__DIR__ . '/../../db.php');
require_once('products_controller.php');


class Cart_Controller{
    private $cart_model;

    public function __construct(){
        $database = new Database();
        $this->cart_model=$database->getConnection();

    }
    public function getUserCart($user_id){
        $query = "SELECT cartID FROM cart WHERE ID = :user_id";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch()['cartID'] ?? null;
    }
    public function get_cart_items($user_id){
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return [];
        }
        
        $query = "SELECT * FROM cart_items WHERE cartID = :cartID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->execute();
        
        $cart = $stmt->fetchAll();
        $products=[];
        $prod=new ProductsController();
        foreach($cart as $cartt){
            
            $products[]=$prod->getProductbyID_cart($cartt); 
        }
        return $products;
    }
    public function update_cart_item($user_id, $prod_ID, $quantity,$pvid){
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return false;
        }
        if($quantity == 0){
            return $this->remove_from_cart($user_id, $prod_ID,$pvid);
        }
        $query = "UPDATE cart_items SET quantity = :quantity WHERE cartID = :cartID AND PID = :prod_ID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->execute();
    }
    public function get_cart_info($user_id){
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return null;
        }
        
        $query = "SELECT * FROM cart_items WHERE cartID = :cartID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    public function Subtotal($user_id){

        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return 0;
        }

        $query="SELECT * FROM cart_items WHERE cartID=:cartID";
        $stmt=$this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->execute();
        $cart=$stmt->fetchAll();
        $prod=new ProductsController();
        $subtotal=0;
        foreach($cart as $cartt){
            $product=$prod->getProductbyID($cartt['PID']);
            $add_price = $product->variants[$cartt['pvid']]->add_price ?? 0;

            $subtotal += ($product->price + $add_price) * $cartt['quantity'];
        }
        return $subtotal;
    }
    public function add_to_cart($user_id, $prod_ID, $quantity){
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return false;
        }
        $query = "INSERT INTO cart_items (cartID, PID, quantity) VALUES (:cartID, :prod_ID, :quantity)";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->bindParam(':quantity', $quantity);
        return $stmt->execute();
    }
     public function remove_from_cart($user_id, $prod_ID, $pvid){
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return false;
        }

        $query = "DELETE FROM cart_items WHERE cartID = :cartID AND PID = :prod_ID AND pvid = :pvid";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->bindParam(':pvid', $pvid);
        return $stmt->execute();
     }
     public function viewAllCart(){
        $query = "SELECT * FROM cart";
        $stmt = $this->cart_model->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
     }
     public function handleCartAction($user_id, $action, $prod_ID, $quantity, $pvid) {
        switch ($action) {
            case 'increase':
                $this->update_cart_item($user_id, $prod_ID, $quantity + 1, $pvid);
                break;
            case 'decrease':
                $this->update_cart_item($user_id, $prod_ID, $quantity - 1, $pvid);
                break;
            case 'remove':
                if ($pvid !== null) {
                    $this->remove_from_cart($user_id, $prod_ID, $pvid);
                }
                break;
            default:
                // Invalid action
                break;
        }
    }
    public function clear_cart($user_id) {
        $cartID = $this->getUserCart($user_id);
        if (!$cartID) {
            return false;
        }

        $query = "DELETE FROM cart_items WHERE cartID = :cartID";
        $stmt = $this->cart_model->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        return $stmt->execute();
    }
    public function getPrice($price, $add_price, $quantity){        
        return ($price + $add_price) * $quantity;
    }
}