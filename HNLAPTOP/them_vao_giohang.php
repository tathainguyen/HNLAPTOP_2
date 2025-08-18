<?php
session_start();

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    // Kiểm tra ID hợp lệ
    if ($id <= 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ']);
        exit;
    }
    
    switch ($action) {
        case 'add':
            $soluong = isset($_POST['soluong']) ? intval($_POST['soluong']) : 1;
            if (isset($_SESSION['giohang'][$id])) {
                $_SESSION['giohang'][$id] += $soluong;
            } else {
                $_SESSION['giohang'][$id] = $soluong;
            }
            break;
            
        case 'update':
            $soluong = isset($_POST['soluong']) ? intval($_POST['soluong']) : 1;
            if ($soluong > 0) {
                $_SESSION['giohang'][$id] = $soluong;
            } else {
                unset($_SESSION['giohang'][$id]);
            }
            break;
            
        case 'delete':
            unset($_SESSION['giohang'][$id]);
            break;
    }
    
    // Tính tổng số lượng và tổng tiền
    $tongsoluong = 0;
    $tongtien = 0;
    
    if (!empty($_SESSION['giohang'])) {
        foreach ($_SESSION['giohang'] as $product_id => $quantity) {
            $tongsoluong += $quantity;
            
            // Tính tổng tiền
            try {
                require_once 'mod/hanghoa.php';
                $hh = new HangHoa();
                $sanpham = $hh->getById($product_id);
                if ($sanpham) {
                    $tongtien += ($sanpham->giathamkhao * 0.79 * $quantity);
                }
            } catch (Exception $e) {
                // Nếu có lỗi khi lấy thông tin sản phẩm, bỏ qua
                error_log("Lỗi lấy thông tin sản phẩm ID $product_id: " . $e->getMessage());
            }
        }
    }
    
    // Đảm bảo trả về JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'tongsoluong' => $tongsoluong,
        'tongtien' => number_format($tongtien, 0, ',', '.') . ' VNĐ',
        'tongtien_raw' => $tongtien,
        'message' => 'Cập nhật giỏ hàng thành công'
    ]);
    exit;
}

// Nếu không phải POST request, redirect về trang chủ
header('Location: index.php');
exit;
?>