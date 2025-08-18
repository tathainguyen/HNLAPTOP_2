<?php
require '../mod/loaihang.php';

$lh = new LoaiHang();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_id"])) {
    $id = intval($_POST["edit_id"]);
    $tenloaihang = trim($_POST["tenloaihang"]);
    $mota = trim($_POST["mota"]);

    if (!empty($tenloaihang) && !empty($mota)) {
        $result = $lh->update($id, $tenloaihang, $mota);
        echo $result ? "success" : "Lỗi khi cập nhật!";
    } else {
        echo "Tên loại hàng và mô tả không được để trống!";
    }
    exit();
}


// Xử lý thêm loại hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = filter_input(INPUT_POST, 'tenloaihang', FILTER_SANITIZE_STRING);
    $mota = filter_input(INPUT_POST, 'mota', FILTER_SANITIZE_STRING);

    if ($ten && $mota) {
        $lh->add($ten, $mota);
    }

    header('Location: ../quanly.php?page=loaihang.php');
    exit();
}

// Xử lý xóa loại hàng
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    
    if ($id && $lh->getById($id)) {
        $lh->delete($id);
    }

    header('Location: ../quanly.php?page=loaihang.php');
    exit();
}
?>
