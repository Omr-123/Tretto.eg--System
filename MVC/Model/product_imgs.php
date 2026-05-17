<?php
require_once (__DIR__ . '/../../db.php');
require_once(__DIR__ . '/product.php');

class product_imgs {
    public $pvid;
    public $imgID;
    public $img_url;
     public function __construct($data) {
        $this->imgID = $data['imgID'] ?? null;
        $this->pvid = $data['pvid'] ?? null;
        $this->img_url = $data['img_url'] ?? null;
        
    }
 
   
}