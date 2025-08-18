<?php
session_start();
require_once __DIR__ . '/mod/config.php';
require_once __DIR__ . '/mod/hanghoa.php';

// Kiểm tra giỏ hàng
if (!isset($_SESSION['giohang']) || empty($_SESSION['giohang'])) {
    header('Location: giohang.php');
    exit();
}

// Kiểm tra đăng nhập
$id_kh = $_SESSION['user_id'] ?? null;
if (!$id_kh) {
    echo "<script>alert('Vui lòng đăng nhập để tiếp tục đặt hàng.'); window.location.href='dangnhap.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'] ?? '';
    $sdt = $_POST['sdt'] ?? '';
    $tinh_tp = $_POST['tinh_tp'] ?? '';
    $quan_huyen = $_POST['quan_huyen'] ?? '';
    $dia_chi_chi_tiet = $_POST['dia_chi_chi_tiet'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'COD';

    if (empty($ten) || empty($sdt) || empty($tinh_tp) || empty($quan_huyen) || empty($dia_chi_chi_tiet)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin.'); window.history.back();</script>";
        exit();
    }

    $dia_chi = "$dia_chi_chi_tiet, $quan_huyen, $tinh_tp";
    $tong_tien = 0;

    // Tính tổng tiền
    $hh = new HangHoa();
    foreach ($_SESSION['giohang'] as $id => $soluong) {
        $sanpham = $hh->getById($id);
        if ($sanpham) {
            $tong_tien += $sanpham->giathamkhao * 0.79 * $soluong;
        }
    }

    // Kết nối database (đặt trước khi kiểm tra voucher)
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Lỗi kết nối database: " . $conn->connect_error);
    }

    // Áp dụng voucher (nếu có)
    $voucher_code = $_POST['voucher_code'] ?? ($_SESSION['voucher_code'] ?? '');
    $voucher_discount = 0;
    if ($voucher_code) {
        require_once 'mod/voucher.php';
        $voucher = Voucher::getByCode($voucher_code);
        if ($voucher && $voucher->is_active) {
            // Kiểm tra tổng số lượt sử dụng
            if ($voucher->used_count >= $voucher->total_uses) {
                echo "<script>alert('Voucher đã hết lượt sử dụng!'); window.history.back();</script>";
                exit();
            }
            // Kiểm tra user đã dùng voucher này chưa
            $stmt = $conn->prepare("SELECT * FROM voucher_user WHERE id_kh = ? AND voucher_id = ?");
            $stmt->bind_param("ii", $id_kh, $voucher->id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->fetch_assoc()) {
                echo "<script>alert('Bạn đã sử dụng voucher này rồi!'); window.history.back();</script>";
                exit();
            }
            // Áp dụng giảm giá
            if ($voucher->type === 'percent') {
                $voucher_discount = $tong_tien * ($voucher->value / 100);
            } elseif ($voucher->type === 'cash') {
                $voucher_discount = $voucher->value;
            }
            $voucher_discount = min($voucher_discount, $tong_tien);
            $tong_tien -= $voucher_discount;
        }
    }

    // Tạo mã đơn hàng
    $code = substr(md5(uniqid(rand(), true)), 0, 10);

    // Chèn đơn hàng
    $stmt = $conn->prepare("INSERT INTO donhang (code, ten_khach_hang, so_dien_thoai, dia_chi, tong_tien, id_kh, phuong_thuc_thanh_toan, trang_thai_thanh_toan, voucher_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $trang_thai_thanh_toan = 0; // Mặc định là chưa thanh toán
    $stmt->bind_param("ssssdisis", $code, $ten, $sdt, $dia_chi, $tong_tien, $id_kh, $payment_method, $trang_thai_thanh_toan, $voucher_code);

    if ($stmt->execute()) {
        $donhang_id = $stmt->insert_id;

        // Lưu chi tiết đơn hàng
        $stmt_chitiet = $conn->prepare("INSERT INTO chitietdonhang (id_donhang, id_sanpham, so_luong, gia, thanh_tien) VALUES (?, ?, ?, ?, ?)");
        foreach ($_SESSION['giohang'] as $id => $soluong) {
            $sanpham = $hh->getById($id);
            if ($sanpham) {
                $gia = $sanpham->giathamkhao * 0.79;
                $thanh_tien = $soluong * $gia;
                $stmt_chitiet->bind_param("iiidd", $donhang_id, $id, $soluong, $gia, $thanh_tien);
                $stmt_chitiet->execute();
            }
        }
        if ($voucher_code && isset($voucher) && $voucher->is_active) {
            // Thêm record vào bảng voucher_user
            $stmt_voucher_user = $conn->prepare("INSERT INTO voucher_user (id_kh, voucher_id, used_at) VALUES (?, ?, NOW())");
            $stmt_voucher_user->bind_param("ii", $id_kh, $voucher->id);
            $stmt_voucher_user->execute();
            $stmt_voucher_user->close();

            // Tăng used_count trong bảng voucher
            $stmt_update_voucher = $conn->prepare("UPDATE voucher SET used_count = used_count + 1 WHERE id = ?");
            $stmt_update_voucher->bind_param("i", $voucher->id);
            $stmt_update_voucher->execute();
            $stmt_update_voucher->close();
        }


        // Xóa giỏ hàng & voucher khỏi session
        unset($_SESSION['giohang']);
        unset($_SESSION['voucher']);
        unset($_SESSION['voucher_code']);

        // Chuyển hướng sang trang đặt hàng thành công
        if ($payment_method === 'QR Code') {
            $encoded_name = urlencode("LU HO GIA HUY");
            $encoded_info = urlencode($code);
            $amount = (int)$tong_tien;
            $qr_url = "https://img.vietqr.io/image/970415-109876820048-compact.png?amount=$amount&addInfo=$encoded_info&accountName=$encoded_name";
            header("Location: dat_hang_thanh_cong.php?ma_donhang=$code&amount=$amount&qr_url=" . urlencode($qr_url));
        } else {
            header("Location: dat_hang_thanh_cong.php?ma_donhang=$code");
        }
        exit();
    } else {
        echo "<script>alert('Lỗi khi đặt hàng. Vui lòng thử lại!'); window.history.back();</script>";
    }

    // Đóng kết nối
    $stmt->close();
    if (isset($stmt_chitiet)) $stmt_chitiet->close();
    $conn->close();
}
?>