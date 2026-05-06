<?php
require_once __DIR__ . '/../Model/product.php';
require_once __DIR__ . '/../../db.php';

class ProductsController{
private $conn;
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function getAllProducts(){
        
        $query = "SELECT * FROM product";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $products = [];
        $Prod=$stmt->fetchAll();
        foreach($Prod as $row){
            $products[] = new Product(
                $row['prod_ID'],
                $row['name'],
                $row['description'],
                $row['price'],
                $row['stock'],
                $row['category'],
                $row['image'],
                $row['storeID'],
            );

        }
        return $products;
    }
    public function getProductbyID($id){
        $query="SELECT * FROM product WHERE prod_ID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $id);
        $stmt->execute();
        $row=$stmt->fetchAll();
        return new Product(
                $row['prod_ID'],
                $row['name'],
                $row['description'],
                $row['price'],
                $row['stock'],
                $row['category'],
                $row['image'],
                $row['storeID'],
            );
    }
}

