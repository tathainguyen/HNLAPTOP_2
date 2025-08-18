<?php
class Database {
    private static $instance = null;
    private $connect;

    private function __construct() {
        require_once __DIR__ . '/config.php'; // Load cấu hình DB
        try {
            $this->connect = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", 
                                      DB_USER, DB_PASS);
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connect;
    }
}
?>
