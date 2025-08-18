<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

require_once __DIR__ . '/mod/config.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID liên hệ không hợp lệ']);
    exit;
}

try {
    $contact_id = (int) $_POST['id'];
    
    // Lấy thông tin liên hệ
    $contact_query = $pdo->prepare("SELECT * FROM contacts WHERE id = ?");
    $contact_query->execute([$contact_id]);
    $contact = $contact_query->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy liên hệ']);
        exit;
    }

    // Đảm bảo encoding UTF-8
    foreach ($contact as $key => $value) {
        if (is_string($value)) {
            $contact[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
        }
    }

    // Trả về dữ liệu JSON
    echo json_encode([
        'success' => true,
        'data' => $contact
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?>