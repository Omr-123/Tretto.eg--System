<?php
require_once __DIR__ . '/../../db.php';

class User
{
    public $userID;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $user_type;
    public $address;
    public $city;
    public $country;
    public $created_at;
    public $registrationDate;
}

class UserModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function findByEmail($email)
    {
        $email = $this->conn->real_escape_string($email);
        $result = $this->conn->query(
            "SELECT *
             FROM person
             WHERE email = '$email'
             LIMIT 1"
        );
        
        if (!$result || $result->num_rows === 0)
            return false;
        $row = $result->fetch_assoc();
        $user = new User();
        $user->userID = (int) $row['ID'];
        $user->name = $row['name'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        
        $result = $this->conn->query(
            "SELECT *
             FROM users
             WHERE ID = '$user->userID'
             LIMIT 1"
        );
        
        $row = $result->fetch_assoc();
        $user->phone = $row['phoneNumber'];
        $user->address = $row['shippingaddress'];
        return $user;
    }

    public function createUser($data)
    {
        $name = $this->conn->real_escape_string($data['name']);
        $email = $this->conn->real_escape_string($data['email']);
        $phone = $this->conn->real_escape_string($data['phone'] ?? '');
        $password = $this->conn->real_escape_string($data['password']);
        $role = $this->conn->real_escape_string($data['user_type'] ?? 'user');

        return $this->conn->query(
            "INSERT INTO users (name, email, phone, password, role)
             VALUES ('$name', '$email', '$phone', '$password', '$role')"
        );
    }

    public function emailExists($email)
    {
        $email = $this->conn->real_escape_string($email);
        $result = $this->conn->query(
            "SELECT COUNT(*) AS cnt FROM users WHERE email = '$email'"
        );
        $row = $result->fetch_assoc();
        return (int) $row['cnt'] > 0;
    }
}