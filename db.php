
<?php
// Database.php
class Database {
    private $host = "localhost";
    private $db_name = "tretto";
    private $username = "root";
    private $password = "";
    public $conn;
    public $state;
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            // This line tells PHP to show errors if the SQL fails
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            $this->state = "Connection failed: " . $exception->getMessage();
        }
        $this->state = "Connection successful";
        return $this->conn;
    }
}

?>
