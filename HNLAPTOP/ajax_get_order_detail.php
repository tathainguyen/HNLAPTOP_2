<?php
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
    exit;
}

require_once __DIR__ . '/mod/config.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID đơn hàng không hợp lệ']);
    exit;
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối database']);
    exit;
}

$donhang_id = (int) $_POST['id'];

try {
    // Lấy thông tin đơn hàng đơn giản
    $order_query = $conn->prepare("SELECT * FROM donhang WHERE id = ?");
    $order_query->bind_param("i", $donhang_id);
    $order_query->execute();
    $order_result = $order_query->get_result();
    $order = $order_result->fetch_assoc();

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng']);
        exit;
    }

    // Lấy chi tiết sản phẩm trong đơn hàng
    $details_query = $conn->prepare("
        SELECT h.tenhanghoa, h.hinhanh, ct.so_luong, ct.gia 
        FROM chitietdonhang ct
        JOIN hanghoa h ON ct.id_sanpham = h.idhanghoa
        WHERE ct.id_donhang = ?
    ");
    $details_query->bind_param("i", $donhang_id);
    $details_query->execute();
    $details_result = $details_query->get_result();
    
    $products = [];
    while ($row = $details_result->fetch_assoc()) {
        $products[] = $row;
    }

    // Lấy lịch sử thay đổi trạng thái đơn hàng
    $history = [];
    $history_query = $conn->prepare("
        SELECT trang_thai_cu, trang_thai_moi, nguoi_thay_doi, thoi_gian_thay_doi, ghi_chu
        FROM lich_su_trang_thai_donhang
        WHERE donhang_id = ?
        ORDER BY thoi_gian_thay_doi ASC
    ");
    $history_query->bind_param("i", $donhang_id);
    $history_query->execute();
    $history_result = $history_query->get_result();
    while ($row = $history_result->fetch_assoc()) {
        $history[] = $row;
    }

    // Trả về dữ liệu JSON (thêm field history)
    echo json_encode([
        'success' => true,
        'data' => [
            'order' => $order,
            'products' => $products,
            'history' => $history
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}

$conn->close();
?>