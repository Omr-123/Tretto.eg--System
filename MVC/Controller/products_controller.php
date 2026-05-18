<?php
require_once __DIR__ . '/../Model/product.php';
require_once __DIR__ . '/../Model/ProductFactory.php';
require_once __DIR__ . '/../Model/cart.php';
require_once __DIR__ . '/../Model/ProductFactory.php';
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/cart_Controller.php';

class ProductsController{
private $conn;
    public function __construct() {
        $database = new Databases();
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
   
    public function addToCart($prod_ID, $pvid, $price, $user_id){
 
        $c=new Cart_Controller();
        $cartID=$c->getUserCart($user_id);
        $query="SELECT * FROM cart_items WHERE cartID = :cartID AND PID = :prod_ID AND pvid = :pvid";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->bindParam(':prod_ID', $prod_ID);
        $stmt->bindParam(':pvid', $pvid);
        $stmt->execute();
        $existingItem = $stmt->fetch();
        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + 1;
            $updateQuery = "UPDATE cart_items SET quantity = :quantity WHERE cartID = :cartID AND PID = :prod_ID AND pvid = :pvid";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':quantity', $newQuantity);
            $updateStmt->bindParam(':cartID', $cartID);
            $updateStmt->bindParam(':prod_ID', $prod_ID);
            $updateStmt->bindParam(':pvid', $pvid);
            $updateStmt->execute();
            header("Location:cart.php");
            return;
        }



        $query = "INSERT INTO cart_items (cartID, PID, pvid, quantity, price) VALUES (:cartID, :PID, :pvid, :quantity, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cartID', $cartID);
        $stmt->bindParam(':PID', $prod_ID);
        $stmt->bindParam(':pvid', $pvid);
        $stmt->bindValue(':quantity', 1);
        $stmt->bindValue(':price', $price);
        $stmt->execute();

        header("Location:cart.php");
        }

       public function addToFav($prod_id, $user_id){
    $query = "INSERT INTO favorites (userID, PID, addedDate) 
              VALUES (:user_id, :prodID, :time)";
    $stmt = $this->conn->prepare($query);
    $time = time(); // or date("Y-m-d H:i:s") if column is DATETIME
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':prodID', $prod_id);
    $stmt->bindParam(':time', $time);
    $stmt->execute();
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
    public function getProductbyID_cart($cartt){
        $query="SELECT * FROM product WHERE PID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $cartt['PID']);
        $stmt->execute();
        $row=$stmt->fetch();
        $productFactory = new ProductFactory();
    
        return $productFactory->create($row['category'], $row) ?? null;
    }
    public function getProductbyID_Fav($fav){
        $query="SELECT * FROM favorite_items WHERE PID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $fav['PID']);
        $stmt->execute();
        $row=$stmt->fetch();
        $productFactory = new ProductFactory();
    
        return $productFactory->create($row['category'], $row) ?? null;
    }
    public function getProductbyID($id){
        $query="SELECT * FROM product WHERE PID=:i";
        $stmt=$this->conn->prepare($query);
        $stmt->bindParam("i", $id);
        $stmt->execute();
        $row=$stmt->fetch();
        $productFactory = new ProductFactory();
        return $productFactory->create($row['category'], $row) ?? null;
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

