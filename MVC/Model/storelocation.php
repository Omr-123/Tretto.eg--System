<?php
class Location
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
    }

    public function getAllLocations()
    {
        $result = $this->db->query("SELECT * FROM StoreLocation");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>