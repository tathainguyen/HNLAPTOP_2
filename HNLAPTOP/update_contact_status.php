<?php
// Kết nối database
require_once 'mod/config.php';

// Kiểm tra request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    
    if ($id && in_array($status, ['Chưa xem', 'Đã xem', 'Đã phản hồi'])) {
        // Cập nhật trạng thái
        $stmt = $pdo->prepare("UPDATE contacts SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        
        // Quay lại trang trước
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// Nếu có lỗi, chuyển hướng về trang trước
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;