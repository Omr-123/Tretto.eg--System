<?php
require_once (__DIR__ . '/../../db.php');



class UserController{
    private $conn;
    public function __construct(){
        $db=new Database();
        $this->conn=$db->getConnection();
    }
    public function Fav_Num($ID){
        // Get cart ID
    $query="SELECT COUNT(*) as total FROM favorties WHERE userID=:id";
    $stmt=$this->conn->prepare($query);
    $stmt->bindParam(':id',$ID);
    return $result['total'] ?? 0;
    }
   public function cart_Number($ID) {

    // Get cart ID
    $query = "SELECT cartID FROM cart WHERE ID = :ID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':ID', $ID);
    $stmt->execute();

    $result = $stmt->fetch();

    if (!$result) {
        return 0;
    }

    $cartID = $result['cartID'];

    // Get total quantity directly from DB
    $query = "SELECT SUM(quantity) as total FROM cart_items WHERE cartID = :cartID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':cartID', $cartID);
    $stmt->execute();

    $result = $stmt->fetch();

    return $result['total'] ?? 0;
}
}