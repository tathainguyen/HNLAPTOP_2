<?php
// Định nghĩa hằng số kết nối
define('DB_HOST', 'localhost');
define('DB_NAME', 'hn_laptop');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    // Sử dụng hằng số thay vì biến chưa khai báo
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>
