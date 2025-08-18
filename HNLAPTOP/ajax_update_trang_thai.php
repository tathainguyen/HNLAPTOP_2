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

// LẤY DỮ LIỆU ĐẦU VÀO
$id = intval($_POST['id'] ?? 0);
$trang_thai = trim($_POST['trang_thai'] ?? '');

if (!$id || !$trang_thai) {
    echo json_encode(['success' => false, 'msg' => 'Thiếu dữ liệu']);
    exit;
}

// Lấy trạng thái cũ của đơn hàng
$oldStatus = '';
$getOldStatus = $conn->prepare("SELECT trang_thai FROM donhang WHERE id=?");
$getOldStatus->bind_param("i", $id);
$getOldStatus->execute();
$getOldStatus->bind_result($oldStatus);
$getOldStatus->fetch();
$getOldStatus->close();

// Cập nhật trạng thái mới
$stmt = $conn->prepare("UPDATE donhang SET trang_thai=? WHERE id=?");
$stmt->bind_param("si", $trang_thai, $id);
if ($stmt->execute()) {
    // Thêm vào bảng lịch sử
    $nguoi_thay_doi = $_SESSION['username'] ?? 'admin'; // hoặc lấy id user
    $note = ''; // bạn có thể thêm ghi chú
    $insertHis = $conn->prepare("INSERT INTO lich_su_trang_thai_donhang (donhang_id, trang_thai_cu, trang_thai_moi, nguoi_thay_doi, thoi_gian_thay_doi, ghi_chu)
        VALUES (?, ?, ?, ?, NOW(), ?)");
    $insertHis->bind_param("issss", $id, $oldStatus, $trang_thai, $nguoi_thay_doi, $note);
    $insertHis->execute();
    $insertHis->close();

    echo json_encode(['success' => true, 'msg' => 'Cập nhật thành công', 'trang_thai' => $trang_thai]);
} else {
    echo json_encode(['success' => false, 'msg' => 'Cập nhật thất bại']);
}
$stmt->close();
$conn->close();