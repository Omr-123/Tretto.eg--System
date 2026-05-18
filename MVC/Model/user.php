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
        $stmt = $this->conn->prepare(
            "SELECT userID, name, email, phone, password, role,
                    address, city, country, registrationDate
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result || $result->num_rows === 0) {
            $stmt->close();
            return false;
        }

        $row = $result->fetch_assoc();
        $stmt->close();

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
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, phone, password, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['phone'] ?? '';
        $password = $data['password'];
        $role = $data['user_type'] ?? 'user';

        $stmt->bind_param('sssss', $name, $email, $phone, $password, $role);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS cnt FROM users WHERE email = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (int) ($row['cnt'] ?? 0) > 0;
    }
}
