<?php
require_once 'mod/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn không có quyền thực hiện thao tác này'
    ]);
    exit();
}

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ID liên hệ không hợp lệ'
    ]);
    exit();
}

$contact_id = (int)$_GET['id'];

try {
    // Kiểm tra liên hệ tồn tại
    $stmt = $pdo->prepare("SELECT id FROM contacts WHERE id = ?");
    $stmt->execute([$contact_id]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Liên hệ không tồn tại'
        ]);
        exit();
    }

    // Thực hiện xóa
    $delete_stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $delete_stmt->execute([$contact_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Xóa liên hệ thành công'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi khi xóa liên hệ: ' . $e->getMessage()
    ]);
}