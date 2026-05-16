<?php
require_once __DIR__ . '/../Model/product.php';
require_once __DIR__ . '/../Model/ProductFactory.php';
require_once __DIR__ . '/../Model/product.php';
require_once __DIR__ . '/../Model/cart.php';
require_once __DIR__ . '/../Model/ProductFactory.php';
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
        $Prod = $stmt->fetchAll();
        $productFactory = new ProductFactory();
        foreach ($Prod as $row) {
            $products[] = $productFactory->create($row['category'], $row);
        }
        return $products;
    }
   
    public function addToCart($prod_ID, $user_id){
        $query = "INSERT INTO cart_items (cartID, PID, pvid, quantity, price) VALUES (:cartID, :PID, :pvid, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->execute();
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
        $query="SELECT * FROM product WHERE PID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $id);
        $stmt->execute();
        $row=$stmt->fetch();
        $productFactory = new ProductFactory();
        return $productFactory->create($row['category'], $row);
    }
    public function createProduct($type,$data){
        $query = "INSERT INTO product (name, description, price, category, BranchID,created_at) VALUES (:name, :description, :price, :category, :storeID, :created_at)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':image', $data['image']);
        $stmt->bindParam(':storeID', $data['storeID']);

    }
}

