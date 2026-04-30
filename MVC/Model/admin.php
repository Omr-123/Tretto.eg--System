<?php
require_once('../../db.php');
require_once('user.php');

class Admin extends User {
    public $adminID;
    public $role;
    public $permissions;
    public $dateOfHire;

}