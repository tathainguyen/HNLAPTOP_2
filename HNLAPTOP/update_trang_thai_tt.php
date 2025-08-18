<?php
require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = intval($_GET['id']);
$trang_thai_thanh_toan = intval($_GET['trang_thai_thanh_toan']);

if ($id > 0 && in_array($trang_thai_thanh_toan, [0, 1, 2, 3])) {
    $conn->query("UPDATE donhang SET trang_thai_thanh_toan = $trang_thai_thanh_toan WHERE id = $id");
}

header("Location: quanly.php?page=quanly_donhang.php");
exit;
?>