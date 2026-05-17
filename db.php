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
<<<<<<< HEAD

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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tretto";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?>
=======

  // Check connection
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
>>>>>>> 074824b3b14c5980ac71650c409b1de4defe8662
