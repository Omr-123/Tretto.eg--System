<?php
require_once(__DIR__ . '/product.php');
require_once(__DIR__ . '/../db.php');

class Clogs extends Product {
    public $heel_height;
    public $type;
    public $Strap_type;
    public function __construct($data) {
        parent::__construct($data['id'], $data['name'], $data['description'], $data['price'], $data['stockquantity'], $data['category'], $data['image'], $data['storeID']);
        $this->heel_height = $data['heel_height'] ?? null;
        $this->type = "clogs";
        $this->Strap_type = $data['Strap_type'] ?? null;
    }
}