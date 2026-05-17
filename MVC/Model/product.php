<?php
require_once (__DIR__ . '/../../db.php');
interface ProductInterface {
    public function getSpecifications(); 
}


abstract class Product implements ProductInterface {
    public $pid;
    public $name;
    public $price;
    public $descriptions;
    
    public $category;
    public $Number_of_sales;
    public $BranchID;
    public $variants = []; // This holds your "Source of Truth"

    public $conn;

    public function __construct($data) {
        $conn= new Database();
        $this->conn = $conn->getConnection(); 

        $this->pid      = $data['PID'] ?? null;
        $this->name     = $data['name'] ?? 'Unknown';
        $this->price    = $data['price'] ?? 0;
        $this->category = $data['category'] ?? '';
        $this->descriptions = $data['descriptions'] ?? '';
        $this->Number_of_sales = $data['Number_Of_Sells'] ?? 0;
        $this->BranchID = $data['BranchID'] ?? null;

        // Linking the variants here
        $query= "SELECT * FROM product_variants WHERE PID = :pid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pid', $this->pid);
        $stmt->execute();
        $variantsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            foreach ($variantsData as $variantData) {
                $this->variants[] = new product_variants($variantData);
            }
    
    }
    public function getThumbnail() {
        // Return the first image of the first variant as thumbnail
        return $this->variants[0]->img_url[0] ?? 'default_thumbnail.jpg';
    }
}