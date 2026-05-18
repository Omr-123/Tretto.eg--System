<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Controller/products_controller.php';

class Favorite
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getFavorites($userID)
    {
        $userID = intval($userID);
        $result = $this->conn->query("
            SELECT * FROM favorites WHERE userID=$userID
        ");
        if (!$result)
            return [];
        $r=$result->fetch_all(MYSQLI_ASSOC);
        $p=new ProductsController();
        $prod=[];
        foreach($r as $rr){
            $prod[]=$p->getProductbyID($rr['PID']);
        }
        return $prod;
    }
    public function getFav($userID){
        $userID = intval($userID);
        $result = $this->conn->query("
            SELECT * FROM favorites WHERE userID=$userID
        ");
        if (!$result)
            return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function favoriteExists($userID, $PID)
    {
        $userID = intval($userID);
        $PID = intval($PID);
        $result = $this->conn->query(
            "SELECT favoriteID FROM favorites WHERE userID = $userID AND PID = $PID LIMIT 1"
        );
        return $result && $result->num_rows > 0;
    }

    public function addFavorite($userID, $PID)
    {
        if ($this->favoriteExists($userID, $PID))
            return false;
        $userID = intval($userID);
        $PID = intval($PID);
        return $this->conn->query(
            "INSERT INTO favorites (userID, PID) VALUES ($userID, $PID)"
        );
    }

    public function removeFavorite($favoriteID)
    {
        $this->conn->query(
            "DELETE FROM favorites WHERE favoriteID = $favoriteID"
        );
    }
}
?>