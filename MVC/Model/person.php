<?php
require_once(__DIR__ . '/../../db.php');


class Person{
    public $ID;
    public $name;
    public $email;
    public $password;
    
    public function __construct($data) {
        $this->ID = $data['ID'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }
}