<?php
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $host = "localhost";
        $dbname = "banque";
        $username = "root";
        $password = "";
        
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}
?>