<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "UPDATE donhang SET trang_thai='Đã hủy' WHERE id=$id AND trang_thai='Đang chờ duyệt'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Đơn hàng đã được hủy.";
    } else {
        $_SESSION['error'] = "Lỗi khi hủy đơn hàng.";
    }
}

$conn->close();
header("Location: quanly_donhang.php");
exit();
?>
