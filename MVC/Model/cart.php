<?php
require_once(__DIR__ . '/../../db.php');

require_once(__DIR__ . '/users.php');

class cart {
    public $cartID;
    public $ID;
    public $total;
     public function __construct($data) {
        $this->cartID = $data['cartID'] ?? null;
        $this->ID = $data['userID'] ?? null;
        $this->total = $data['total_amount'] ?? null;
        
    }
 
   
}
