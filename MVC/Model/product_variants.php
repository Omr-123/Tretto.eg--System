<?php

require_once (__DIR__ . '/../../db.php');
require_once(__DIR__ . '/product_imgs.php');
require_once('product.php');


class product_variants {
    public $pvid;
    public $pid;
    public $color;
    public $stock;
    public $add_price;
    public $size;
    public $img_url=[];
    public $conn;
    
    public function __construct($data) {
        $db = new Databases();
        $this->conn = $db->getConnection();
        $this->pvid = $data['pvid'] ?? null;

        $this->pid = $data['PID'] ?? null;
        $this->color = $data['color'] ?? null;
        $this->stock = $data['stock'] ?? null;
        $this->add_price = $data['add_price'] ?? null;
        $this->size = $data['sizes'] ?? null;
        $this->getImages(); // Fetch images when the variant is created
    }
    public function getImages(){
        $stmt = $this->conn->prepare("SELECT * FROM product_images WHERE pvid = :pvid");
        $stmt->bindParam(':pvid', $this->pvid);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $row) {
            $this->img_url[] = $row['images'];
        }
    }

    public function getSize($size,$color){
        $stmt = $this->conn->prepare("SELECT * FROM product_variants WHERE pvid = :pvid AND sizes = :sizes AND color = :color");
        $stmt->bindParam(':pvid', $this->pvid);
        $stmt->bindParam(':sizes', $size);
        $stmt->bindParam(':color', $color);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0] ?? null;
    }
 
}