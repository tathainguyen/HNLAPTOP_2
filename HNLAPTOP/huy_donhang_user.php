<?php
session_start();
require_once __DIR__ . '/mod/config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Không tìm thấy đơn hàng.";
    header("Location: donhang_damua.php");
    exit();
}

$id_donhang = intval($_GET['id']);
$id_kh = $_SESSION['user_id'];

// Kết nối CSDL
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Kiểm tra đơn hàng có thuộc khách hàng này không và có thể hủy không
$sql = "SELECT trang_thai FROM donhang WHERE id = ? AND id_kh = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_donhang, $id_kh);
$stmt->execute();
$result = $stmt->get_result();
$donhang = $result->fetch_assoc();

if (!$donhang) {
    $_SESSION['error'] = "Đơn hàng không tồn tại.";
} elseif ($donhang['trang_thai'] !== 'Đang chờ duyệt' && $donhang['trang_thai'] !== 'Đã duyệt') {
    $_SESSION['error'] = "Bạn không thể hủy đơn hàng này.";
} else {
    // Cập nhật trạng thái đơn hàng thành 'Đã hủy'
    $update_sql = "UPDATE donhang SET trang_thai = 'Đã hủy' WHERE id = ? AND id_kh = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $id_donhang, $id_kh);
    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Đơn hàng đã được hủy thành công.";
    } else {
        $_SESSION['error'] = "Lỗi khi hủy đơn hàng.";
    }
}

$stmt->close();
$conn->close();
header("Location: myaccount.php");
exit();
