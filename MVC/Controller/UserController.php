<?php
require_once(__DIR__ . '/../../db.php');
require_once __DIR__ . '/cart_Controller.php';


class UserController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function Fav_Num($ID){
    $query="SELECT COUNT(*) as total FROM favorites WHERE userID=:id";
    $stmt=$this->conn->prepare($query);
    $stmt->bindParam(':id',$ID);
    $stmt->execute();
    return $stmt->fetchColumn();
    }

    // Cart total quantity
    public function cart_Number($ID)
    {
        $cart=new Cart_Controller();
        $cartID=$cart->getUserCart($ID);
        $query="SELECT SUM(quantity) as total FROM cart_items WHERE cartID=:ID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ID', $cartID);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['total'] ?? 0;
    }
}