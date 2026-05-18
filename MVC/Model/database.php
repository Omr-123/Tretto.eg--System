<?php

class Database
{
    private string $server;
    private string $username;
    private string $password;
    private string $dbname;
    private int $port;

    public function __construct(
        string $server = 'localhost',
        string $username = 'root',
        string $password = '',
        string $dbname = 'Tretto',
        int $port = 3306
    ) {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
    }

    public function connectToDB(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_OFF);

        $conn = new mysqli(
            $this->server,
            $this->username,
            $this->password,
            $this->dbname,
            $this->port
        );

        if ($conn->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $conn->connect_error);
        }

        $conn->set_charset('utf8mb4');
        return $conn;
    }
}