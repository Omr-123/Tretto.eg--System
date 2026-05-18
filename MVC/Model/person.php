<?php
require_once __DIR__ . '/database.php';

class Person
{
    protected int $ID;
    protected string $name;
    protected string $email;
    protected string $password;
    protected mysqli $conn;
    protected Database $db;

    public function __construct(int $ID = 0, string $name = '', string $email = '', string $password = '')
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->db = new Database();
        $this->conn = $this->db->connectToDB();
    }

    public function getID(): int { return $this->ID; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }

    public function setName(string $name): void { $this->name = $name; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function login(string $email, string $password): bool
    {
        $stmt = $this->conn->prepare('SELECT userID, name, email, password FROM users WHERE email = ? LIMIT 1');
        if (!$stmt) return false;

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows !== 1) return false;

        $row = $result->fetch_assoc();
        $storedPassword = (string)$row['password'];
        $passwordOk = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);
        if (!$passwordOk) return false;

        $this->ID = (int)$row['userID'];
        $this->name = (string)$row['name'];
        $this->email = (string)$row['email'];
        $this->password = $storedPassword;
        return true;
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    public function viewProducts(): array
    {
        $sql = "SELECT p.*, COALESCE(SUM(pv.stock), 0) AS total_stock, MIN(pi.images) AS image
                FROM product p
                LEFT JOIN product_variants pv ON pv.PID = p.PID
                LEFT JOIN product_images pi ON pi.pvid = pv.pvid
                GROUP BY p.PID
                ORDER BY p.PID DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function searchProducts(string $keyword): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM product WHERE name LIKE ? OR descriptions LIKE ?');
        if (!$stmt) return [];
        $like = "%$keyword%";
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function filterProducts(string $criteria): array
    {
        $stmt = $this->conn->prepare('SELECT * FROM product WHERE category = ?');
        if (!$stmt) return [];
        $stmt->bind_param('s', $criteria);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function sortProducts(string $order = 'ASC'): array
    {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $result = $this->conn->query("SELECT * FROM product ORDER BY price $order");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function viewStoreLocation(): array
    {
        $result = $this->conn->query('SELECT * FROM StoreLocation');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function browseCollection(): array
    {
        // Original SQL currently has no Collection table, so return empty safely.
        $result = $this->conn->query("SHOW TABLES LIKE 'Collection'");
        if (!$result || $result->num_rows === 0) return [];
        $result = $this->conn->query('SELECT * FROM Collection');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function view_popular(): array
    {
        $result = $this->conn->query('SELECT * FROM product ORDER BY Number_Of_Sells DESC LIMIT 10');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
