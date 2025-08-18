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

// Kiểm tra xem có ID và trạng thái được gửi hay không
if (isset($_GET['id']) && isset($_GET['trang_thai'])) {
    $id = intval($_GET['id']);
    $trang_thai = $_GET['trang_thai'];

    // Cập nhật trạng thái đơn hàng
    $sql = "UPDATE donhang SET trang_thai = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $trang_thai, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật trạng thái thành công!";
    } else {
        $_SESSION['message'] = "Lỗi khi cập nhật trạng thái!";
    }

    $stmt->close();
}

// Quay lại trang quản lý đơn hàng
header("Location: quanly.php?page=quanly_donhang.php");
exit();
?>
