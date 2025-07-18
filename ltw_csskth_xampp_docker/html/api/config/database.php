<?php
class Database {
    private $host = "db";
    private $db_name = "uth_health";
    private $username = "uth_user";
    private $password = "uth_pass";

    // private $host = "sql100.infinityfree.com";
    // private $db_name = "if0_39385541_uth_health";
    // private $username = "if0_39385541";
    // private $password = "Q0t6Gpp9c9O9";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>