<?php
class Database
{
    private $host = "localhost";
    private $db_name = "auth-jwt";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        } catch (PDOException $exception) {
            echo "Database connection error:" . $exception->getMessage();
        }

        return $this->conn;
    }
}