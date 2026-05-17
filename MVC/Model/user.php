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
            "SELECT userID, name, email, phone, password, role,
                    address, city, country, registrationDate
             FROM users
             WHERE email = '$email'
             LIMIT 1"
        );

        if (!$result || $result->num_rows === 0)
            return false;

        $row = $result->fetch_assoc();

        $user = new User();
        $user->userID = (int) $row['userID'];
        $user->name = $row['name'];
        $user->email = $row['email'];
        $user->phone = $row['phone'];
        $user->password = $row['password'];
        $user->user_type = $row['role'];
        $user->address = $row['address'];
        $user->city = $row['city'];
        $user->country = $row['country'];
        $user->registrationDate = $row['registrationDate'];
        $user->created_at = $row['registrationDate'];

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