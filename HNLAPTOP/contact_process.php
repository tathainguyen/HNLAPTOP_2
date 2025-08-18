<?php
// Kết nối database
require_once 'mod/config.php'; // file chứa thông tin kết nối CSDL
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Lấy username từ form (nếu có) hoặc từ session (nếu user đã đăng nhập)
    $username = '';
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } elseif (isset($_POST['username'])) {
        $username = trim($_POST['username']);
    }

    // Validate dữ liệu (bạn có thể bổ sung thêm)
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO contacts (username, name, email, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $name, $email, $subject, $message])) {
            header("Location: contact_success.php");
            exit();
        } else {
            $errorInfo = $stmt->errorInfo();
            echo "Lỗi khi gửi liên hệ: " . $errorInfo[2];
        }
    } else {
        echo "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

