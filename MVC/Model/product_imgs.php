<?php
require_once (__DIR__ . '/../../db.php');
require_once(__DIR__ . '/product.php');

class product_imgs {
    public $PID;
    public $img_url;
     public function __construct($data) {
        $this->PID= $data['PID'] ?? null;
        $this->img_url = $data['img_url'] ?? null;
        
    }
 
   
}