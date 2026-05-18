<?php
class ReviewModel
{
    private mysqli $conn;
    private string $lastError = '';

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    public function getAllReviews(): array
    {
        $result = $this->conn->query("
            SELECT 
                r.review_ID,
                r.rating,
                r.comment,
                r.reviewDate,
                r.helpful_count,
                u.name AS user_name,
                p.name AS product_name
            FROM review r
            LEFT JOIN users u ON r.userID = u.userID
            LEFT JOIN product p ON r.prod_ID = p.PID
            ORDER BY r.reviewDate DESC
        ");

        if (!$result) {
            $this->lastError = $this->conn->error;
            return [];
        }

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    public function addReview(int $prod_ID, int $userID, float $rating, string $comment): bool
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO review (prod_ID, userID, rating, comment) VALUES (?, ?, ?, ?)'
        );

        if (!$stmt) {
            $this->lastError = $this->conn->error;
            return false;
        }

        $stmt->bind_param('iids', $prod_ID, $userID, $rating, $comment);
        $ok = $stmt->execute();

        if (!$ok) {
            $this->lastError = $stmt->error;
        }

        $stmt->close();
        return $ok;
    }

    public function getAllProducts(int $userID): array
    {
        if ($userID <= 0) {
            return [];
        }

        $stmt = $this->conn->prepare("
            SELECT DISTINCT p.PID AS prod_ID, p.name
            FROM product p
            INNER JOIN order_items oi ON oi.PID = p.PID
            INNER JOIN orders o ON o.orderID = oi.orderID
            WHERE o.userID = ?
            AND o.status = 'Delivered'
            ORDER BY p.name ASC
        ");

        if (!$stmt) {
            $this->lastError = $this->conn->error;
            return [];
        }

        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        $stmt->close();
        return $products;
    }
}
