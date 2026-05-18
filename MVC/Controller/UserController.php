<?php
require_once(__DIR__ . '/../../db.php');

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
        $query = "
            SELECT SUM(ci.quantity) as total
            FROM cart c
            JOIN cart_items ci ON c.cartID = ci.cartID
            WHERE c.userID = :ID
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['total'] ?? 0;
    }
}