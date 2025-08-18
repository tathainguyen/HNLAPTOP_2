<?php
session_start();
require_once 'mod/config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, 'hn_laptop');
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Xử lý cập nhật tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username=?, phone=?, gender=?, role=?, password=?, status=? WHERE id=?");
        $stmt->bind_param("ssssssi", $username, $phone, $gender, $role, $password, $status, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, phone=?, gender=?, role=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $username, $phone, $gender, $role, $status, $id);
    }
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    $stmt->close();
    exit();
}

// Xử lý xóa tài khoản
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "";
    $stmt->close();
    exit();
}

// Xử lý vô hiệu hóa tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disable_id'])) {
    $id = intval($_POST['disable_id']);
    $stmt = $conn->prepare("UPDATE users SET status = 'disabled' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    $stmt->close();
    exit();
}

$conn->close();

$result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">QUẢN LÝ TÀI KHOẢN</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên tài khoản</th>
                <th>Số điện thoại</th>
                <th>Giới tính</th>
                <th>Quyền</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?= $row['id'] ?>">
                    <td><?= $row['id'] ?></td>
                    <td><input type="text" class="form-control username" value="<?= $row['username'] ?>"></td>
                    <td><input type="text" class="form-control phone" value="<?= $row['phone'] ?>"></td>
                    <td>
                        <select class="form-select gender">
                            <option value="male" <?= $row['gender'] == 'male' ? 'selected' : '' ?>>Nam</option>
                            <option value="female" <?= $row['gender'] == 'female' ? 'selected' : '' ?>>Nữ</option>
                            <option value="other" <?= $row['gender'] == 'other' ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select role">
                            <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm save-btn">Lưu</button>
                        <button class="btn btn-danger btn-sm delete-btn">Xóa</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.querySelectorAll(".save-btn").forEach(btn => {
    btn.addEventListener("click", function() {
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let data = new URLSearchParams();
        data.append("edit_id", id);
        data.append("username", row.querySelector(".username").value);
        data.append("phone", row.querySelector(".phone").value);
        data.append("gender", row.querySelector(".gender").value);
        data.append("role", row.querySelector(".role").value);

        fetch("quanlytaikhoan.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: data.toString()
        }).then(response => response.text())
        .then(result => {
            if (result === "success") {
                alert("Cập nhật thành công!");
            } else {
                alert("Lỗi: " + result);
            }
        }).catch(error => console.error("Lỗi:", error));
    });
});

document.querySelectorAll(".delete-btn").forEach(btn => {
    btn.addEventListener("click", function() {
        if (!confirm("Bạn có chắc chắn muốn xóa tài khoản này?")) return;
        let row = this.closest("tr");
        let id = row.getAttribute("data-id");
        let data = new URLSearchParams();
        data.append("delete_id", id);

        fetch("quanlytaikhoan.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: data.toString()
        }).then(response => response.text())
        .then(result => {
            if (result === "success") {
                alert("Xóa thành công!");
                row.remove();
            } else {
                alert("Lỗi khi xóa!");
            }
        }).catch(error => console.error("Lỗi:", error));
    });
});
</script>
</body>
</html>