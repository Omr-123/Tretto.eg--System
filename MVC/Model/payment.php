<?php
require_once __DIR__ . '/database.php';

interface PaymentInterface
{
    public function authorizePayment(): bool;
}

class Payment implements PaymentInterface
{
    protected int $paymentID = 0;
    protected int $orderID;
    protected float $amount;
    protected string $method;
    protected string $status;
    protected mysqli $conn;
    protected Database $db;

    public function __construct(int $orderID = 0, float $amount = 0.0, string $method = 'Cash')
    {
        $this->orderID = $orderID;
        $this->amount = $amount;
        $this->method = $method;
        $this->status = 'Pending';
        $this->db = new Database();
        $this->conn = $this->db->connectToDB();
    }

    public function getPaymentID(): int { return $this->paymentID; }
    public function getOrderID(): int { return $this->orderID; }
    public function getAmount(): float { return $this->amount; }
    public function getStatus(): string { return $this->status; }
    public function getMethod(): string { return $this->method; }

    public function authorizePayment(): bool
    {
        return $this->orderID > 0 && $this->amount > 0;
    }

    public function savePayment(string $status = 'Completed'): int
    {
        $stmt = $this->conn->prepare('INSERT INTO payment (orderID, amount, method, status) VALUES (?, ?, ?, ?)');
        if (!$stmt) return 0;
        $stmt->bind_param('idss', $this->orderID, $this->amount, $this->method, $status);
        if (!$stmt->execute()) return 0;
        $this->paymentID = (int)$this->conn->insert_id;
        $this->status = $status;
        return $this->paymentID;
    }

    public function getPaymentByOrder(int $orderID): ?array
    {
        $stmt = $this->conn->prepare('SELECT * FROM payment WHERE orderID = ? ORDER BY paymentDate DESC LIMIT 1');
        if (!$stmt) return null;
        $stmt->bind_param('i', $orderID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateStatus(int $paymentID, string $status): bool
    {
        $allowed = ['Pending', 'Completed', 'Failed'];
        if (!in_array($status, $allowed, true)) return false;
        $stmt = $this->conn->prepare('UPDATE payment SET status = ? WHERE paymentID = ?');
        if (!$stmt) return false;
        $stmt->bind_param('si', $status, $paymentID);
        $this->status = $status;
        return $stmt->execute();
    }

    public function getAllPayments(): array
    {
        $sql = "SELECT p.*, o.totalAmount, o.status AS orderStatus, u.name AS customerName
                FROM payment p
                LEFT JOIN orders o ON o.orderID = p.orderID
                LEFT JOIN users u ON u.userID = o.userID
                ORDER BY p.paymentDate DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
