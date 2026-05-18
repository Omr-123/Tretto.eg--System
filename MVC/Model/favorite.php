<?php
require_once __DIR__ . '/../../db.php';
class Favorite
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getFavorites($userID)
    {
        $userID = intval($userID);
        $result = $this->conn->query("
            SELECT
                f.favoriteID,
                f.addedDate,
                p.PID,
                p.name,
                p.price,
                pi.images AS image
            FROM favorites f
            JOIN product p ON f.PID = p.PID
            LEFT JOIN product_variants pv ON pv.PID = p.PID
            LEFT JOIN product_images pi ON pi.pvid = pv.pvid
            WHERE f.userID = $userID
            GROUP BY f.favoriteID
            ORDER BY f.addedDate DESC
        ");
        if (!$result)
            return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function favoriteExists($userID, $PID)
    {
        $userID = intval($userID);
        $PID = intval($PID);
        $result = $this->conn->query(
            "SELECT favoriteID FROM favorites WHERE userID = $userID AND PID = $PID LIMIT 1"
        );
        return $result && $result->num_rows > 0;
    }

    public function addFavorite($userID, $PID)
    {
        if ($this->favoriteExists($userID, $PID))
            return false;
        $userID = intval($userID);
        $PID = intval($PID);
        return $this->conn->query(
            "INSERT INTO favorites (userID, PID) VALUES ($userID, $PID)"
        );
    }

    public function removeFavorite($favoriteID)
    {
        $this->conn->query(
            "DELETE FROM favorites WHERE favoriteID = $favoriteID"
        );
    }
}
?>