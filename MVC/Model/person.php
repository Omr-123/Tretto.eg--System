<?php
require_once('../../db.php');
require_once('user.php');

class Person extends User {
    public $dateOfBirth;
    public $gender;
    public $address;

}