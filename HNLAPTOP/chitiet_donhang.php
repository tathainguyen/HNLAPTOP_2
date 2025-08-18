<?php
session_start();

// Không kiểm tra admin ở đây vì cho user thường xem

require_once __DIR__ . '/mod/config.php';

// Kết nối database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Lấy ID đơn hàng từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID đơn hàng không hợp lệ.");
}

$donhang_id = (int) $_GET['id'];

// Truy vấn thông tin đơn hàng
$order_query = $conn->prepare("SELECT * FROM donhang WHERE id = ?");
$order_query->bind_param("i", $donhang_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Không tìm thấy đơn hàng.");
}

// Truy vấn chi tiết đơn hàng
$order_details_query = $conn->prepare("
    SELECT h.tenhanghoa, h.hinhanh, ct.so_luong, ct.gia 
    FROM chitietdonhang ct
    JOIN hanghoa h ON ct.id_sanpham = h.idhanghoa
    WHERE ct.id_donhang = ?");
$order_details_query->bind_param("i", $donhang_id);
$order_details_query->execute();
$order_details_result = $order_details_query->get_result();

// TRUY VẤN LỊCH SỬ TRẠNG THÁI ĐƠN HÀNG
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
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header bg-dark text-white">
                <h3 class="mb-0">Chi tiết đơn hàng #
                    <?= $donhang_id ?>
                </h3>
            </div>
            <div class="card-body">
                <h5 class="mb-3">Thông tin khách hàng</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Tên khách hàng</th>
                        <td>
                            <?= htmlspecialchars($order['ten_khach_hang']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>
                            <?= htmlspecialchars($order['so_dien_thoai']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>
                            <?= htmlspecialchars($order['dia_chi']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>ID khách hàng</th>
                        <td>
                            <?= htmlspecialchars($order['id_kh']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Phương thức thanh toán</th>
                        <td>
                            <?= htmlspecialchars($order['phuong_thuc_thanh_toan']) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Voucher đã áp dụng</th>
                        <td>
                            <?= htmlspecialchars($order['voucher_code']) ?>
                            <?php if (!empty($order['voucher_discount'])): ?>
                                (Giảm <?= number_format($order['voucher_discount'], 0, ',', '.') ?> VND)
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            <?= date("d/m/Y H:i:s", strtotime($order['ngay_tao'])) ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tổng tiền</th>
                        <td><strong class="text-success fw-bold">
                                <?= number_format($order['tong_tien'], 0, ',', '.') ?> VND
                            </strong></td>
                    </tr>
                </table>

                <h5 class="mt-4">Danh sách sản phẩm</h5>
                <table class="table table-hover table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                            <th>Trạng thái đơn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $order_details_result->fetch_assoc()) : ?>
                        <tr>
                            <td>
                                <img src="uploads/<?= htmlspecialchars($row['hinhanh']) ?>" alt="Hình ảnh sản phẩm"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </td>
                            <td class="align-middle fw-bold">
                                <?= htmlspecialchars($row['tenhanghoa']) ?>
                            </td>
                            <td class="align-middle fw-bold">
                                <?= $row['so_luong'] ?>
                            </td>
                            <td class="align-middle text-success fw-bold">
                                <?= number_format($row['gia'], 0, ',', '.') ?> VND
                            </td>
                            <td class="align-middle text-success fw-bold">
                                <?= number_format($row['so_luong'] * $row['gia'], 0, ',', '.') ?> VND
                            </td>
                            <td class="align-middle <?php 
                                    if ($order['trang_thai'] === 'Đã duyệt') echo 'text-success fw-bold'; 
                                    elseif ($order['trang_thai'] === 'Đang chờ duyệt') echo 'text-warning fw-bold'; 
                                    elseif ($order['trang_thai'] === 'Đã hủy') echo 'text-danger fw-bold';
                                    elseif ($order['trang_thai'] === 'Đang giao') echo 'text-primary fw-bold';
                                    elseif ($order['trang_thai'] === 'Giao hàng thành công') echo 'text-info fw-bold';
                                ?>">
                                <?= $order['trang_thai'] ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- PHẦN LỊCH SỬ TRẠNG THÁI -->
                <h5 class="mt-4">Lịch sử vận đơn</h5>
                <?php if (count($history)): ?>
                <ul class="list-group mb-4">
                    <?php foreach ($history as $his): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-secondary me-2"><?= date("d/m/Y H:i:s", strtotime($his['thoi_gian_thay_doi'])) ?></span>
                            <span>
                                <b><?= htmlspecialchars($his['trang_thai_cu']) ?></b>
                                <span class="mx-1">&rarr;</span>
                                <b><?= htmlspecialchars($his['trang_thai_moi']) ?></b>
                            </span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <div class="alert alert-info mb-4">Chưa có lịch sử trạng thái nào cho đơn hàng này.</div>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn btn-danger">Quay lại</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>