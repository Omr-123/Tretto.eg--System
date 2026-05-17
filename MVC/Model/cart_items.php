<?php
require_once (__DIR__ . '/../../db.php');
require_once('product.php');
require_once('cart.php');

class cart_items{
    public $cartID;
    public $PID;
    public $pvid;
    public $quantity;
    public $price;
    private $conn;
    public function __construct($data) {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->cartID = $data['cartID'];
        $this->PID = $data['prod_ID'];
        $this->pvid = $data['pvid'];
        $this->quantity = $data['quantity'];
        $this->price = $data['price'];
        
    }
    public function getCartItems($user_id){
        $query = "SELECT * FROM cart WHERE userID = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cartItems = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cartItems[] = new cart_items($row);
        }
        return $cartItems;
    }
}