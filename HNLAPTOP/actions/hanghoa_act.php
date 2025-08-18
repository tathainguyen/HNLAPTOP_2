<?php
require '../mod/hanghoa.php';
$hh = new HangHoa();

// 📌 Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {  // Nếu có yêu cầu cập nhật
        $id = $_POST['update'];
        $ten = $_POST['tenhanghoa'];
        $mota = $_POST['mota'];
        
        $gia = $_POST['giathamkhao'];
        $loai = $_POST['idloaihang'];

        // Xử lý hình ảnh nếu có upload
        $hinhanh = null;
        if (!empty($_FILES['hinhanh']['name'])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES['hinhanh']['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file)) {
                    $hinhanh = basename($_FILES['hinhanh']['name']);
                }
            }
        }

        // Gọi hàm update
        $hh->update($id, $ten, $mota, $gia, $loai, $hinhanh);
        header('Location: ../quanly.php?page=hanghoa.php');
        exit();
    }
}

// 📌 Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $ten = $_POST['tenhanghoa'];
    $mota = $_POST['mota'];
    $gia = $_POST['giathamkhao'];
    $loai = $_POST['idloaihang'];

    // Xử lý hình ảnh
    $hinhanh = null;
    if (!empty($_FILES['hinhanh']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES['hinhanh']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowTypes)) {
            if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file)) {
                $hinhanh = basename($_FILES['hinhanh']['name']);
            }
        }
    }

    // Gọi hàm thêm sản phẩm
    $hh->add($ten, $mota, $gia, $loai, $hinhanh);
    header('Location: ../quanly.php?page=hanghoa.php');
    exit();
}

// 📌 Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $hh->delete($_GET['delete']);
    header('Location: ../quanly.php?page=hanghoa.php');
    exit();
}
?>
