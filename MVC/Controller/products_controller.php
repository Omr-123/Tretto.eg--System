<?php
require_once __DIR__ . '/../Model/product.php';
require_once __DIR__ . '/../Model/cart.php';
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
   
    public function addToCart($prod_ID, $user_id){
        $query = "INSERT INTO cart (`userID`, `prod_ID`, `quantity`, `addedDate`) VALUES (:user_id, :prod_ID, 2, CURRENT_TIMESTAMP())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->execute();
        include __DIR__ . '/../View/GUI/cart.php';
    }
    public function addToFav($prod_id,$user_id){

    }
    public function getFilter($sortOrder){
        $products = $this->getAllProducts();
        // 2. Check if a sort was requested
        $sortOrder = $_GET['sort'] ?? 'newest';
        
        switch ($sortOrder) {
            case 'price-asc':
                usort($products, fn($a, $b) => $a->price <=> $b->price);
                break;

            case 'price-desc':
                usort($products, fn($a, $b) => $b->price <=> $a->price);
                break;

            case 'popular':
                usort($products, fn($a, $b) => $b->sales_count <=> $a->sales_count);
                break;
                
            case 'default':
            usort($products, fn($a, $b) => $a->id <=> $b->id);
            break;

            case 'newest':
            default:
                // Assuming your object has an 'id' or 'created_at'
                usort($products, fn($a, $b) => $b->id <=> $a->id);
                break;

        }
        return $products;
    }
// 3. Now send $products to your view to be displayed in a foreach loop
    
    public function getProductbyID($id){
        $query="SELECT * FROM product WHERE prod_ID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $id);
        $stmt->execute();
        $row=$stmt->fetch();
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

