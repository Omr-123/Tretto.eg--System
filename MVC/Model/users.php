<?php
require_once(__DIR__ . '/person.php');
require_once(__DIR__ . '/../../db.php');

class users extends Person{
    public $shippingaddress;
    public $phoneNumber;
    public function __construct($data) {
        parent::__construct($data);
        $this->shippingaddress = $data['shippingaddress'] ?? null;
        $this->phoneNumber = $data['phoneNumber'] ?? null;
    }
    
}