<?php
session_start();
session_unset(); // Xóa tất cả session
session_destroy(); // Hủy phiên đăng nhập

// Sau khi đăng xuất, chuyển hướng về trang đăng nhập
header("Location: trangchu.php");
exit();
?>
