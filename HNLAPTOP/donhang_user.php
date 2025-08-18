<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$id_kh = isset($_GET['id_kh']) ? intval($_GET['id_kh']) : 0;
$sql = "SELECT * FROM donhang WHERE id_kh = $id_kh ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của khách hàng #<?= $id_kh ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        
        <h3>Đơn hàng của khách hàng ID: <?= $id_kh ?></h3>
        <table class="table table-hover table-bordered text-center mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Mã Đơn Hàng</th>
                    <th>Tên Khách</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['code'] ?></td>
                    <td><?= htmlspecialchars($row['ten_khach_hang']) ?></td>
                    <td><?= $row['so_dien_thoai'] ?></td>
                    <td><?= htmlspecialchars($row['dia_chi']) ?></td>
                    <td class="text-success fw-bold"><?= number_format($row['tong_tien'], 0, ',', '.') ?> VND</td>
                    <td>
                        <span class="<?php
                            switch ($row['trang_thai']) {
                                case 'Đang chờ duyệt': echo 'text-warning fw-bold'; break;
                                case 'Đã duyệt': echo 'text-success fw-bold'; break;
                                case 'Đang giao': echo 'text-primary fw-bold'; break;
                                case 'Giao hàng thành công': echo 'text-info fw-bold'; break;
                                case 'Đã hủy': echo 'text-danger fw-bold'; break;
                                default: echo 'text-secondary'; break;
                            }
                        ?>">
                            <?= $row['trang_thai'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="chitiet_donhang.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Chi tiết</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8">Không có đơn hàng nào.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
