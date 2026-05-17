<?php
class ReviewModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllReviews()
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

        $reviews = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }
        return $reviews;
    }

    public function addReview($prod_ID, $userID, $rating, $comment)
    {
        $comment = $this->conn->real_escape_string($comment);

        $result = $this->conn->query("
            INSERT INTO review (prod_ID, userID, rating, comment)
            VALUES ($prod_ID, $userID, $rating, '$comment')
        ");

        return $result;
    }

    public function getAllProducts($userID)
    {
        $result = $this->conn->query("
            SELECT DISTINCT p.PID AS prod_ID, p.name
            FROM product p
            INNER JOIN order_items oi ON oi.PID = p.PID
            INNER JOIN orders o ON o.orderID = oi.orderID
            WHERE o.userID = $userID
            AND o.status = 'Delivered'
            GROUP BY p.PID, p.name
            ORDER BY p.name ASC
        ");

        $products = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        return $products;
    }
}
?>