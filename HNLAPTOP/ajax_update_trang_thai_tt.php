<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'msg' => 'Không có quyền']);
    exit;
}
require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Lỗi kết nối']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$trang_thai_tt = $_POST['trang_thai_tt'] ?? '';
if (!$id || $trang_thai_tt === '') {
    echo json_encode(['success' => false, 'msg' => 'Thiếu dữ liệu']);
    exit;
}

$stmt = $conn->prepare("UPDATE donhang SET trang_thai_thanh_toan=? WHERE id=?");
$stmt->bind_param("ii", $trang_thai_tt, $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'msg' => 'Cập nhật thành công', 'trang_thai_tt' => $trang_thai_tt]);
} else {
    echo json_encode(['success' => false, 'msg' => 'Cập nhật thất bại']);
}
$stmt->close();
$conn->close();