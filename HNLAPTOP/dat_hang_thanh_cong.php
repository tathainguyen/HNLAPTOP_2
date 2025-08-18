<?php
$ma_donhang = $_GET['ma_donhang'] ?? 'Không xác định';
$amount = $_GET['amount'] ?? null;
$qr_url = isset($_GET['qr_url']) ? htmlspecialchars(urldecode($_GET['qr_url'])) : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .success-icon {
            font-size: 3.5rem;
            color: #28a745;
        }
        .qr-image {
            max-width: 100%;
            border-radius: 8px;
            border: 1px dashed #ccc;
            padding: 8px;
            background-color: #fff;
        }
        .qr-box {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="max-width: 700px; width: 100%;">
        <div class="text-center mb-3">
            <i class="fas fa-check-circle success-icon"></i>
            <h3 class="text-success fw-bold mt-2">Đặt hàng thành công!</h3>
        </div>

        <div class="row align-items-center">
            <!-- Bên trái: Thông tin -->
            <div class="col-md-7">
                <p><strong>Mã đơn hàng:</strong> <span class="text-primary"><?= htmlspecialchars($ma_donhang) ?></span></p>

                <?php if ($qr_url && $amount): ?>
                    <p><strong>Phương thức:</strong> Thanh toán bằng QR Code</p>
                    <p><strong>Số tiền:</strong> <?= number_format($amount, 0, ',', '.') ?> VND</p>
                    <p><strong>Nội dung chuyển khoản:</strong><br>
                        <code><?= htmlspecialchars($ma_donhang) ?></code>
                    </p>
                    <p class="text-muted small">(Vui lòng chuyển khoản đúng nội dung để được xác nhận đơn nhanh chóng)</p>
                <?php else: ?>
                    <p><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</p>
                    <p class="text-muted">Chúng tôi sẽ liên hệ với bạn sớm để xác nhận đơn.</p>
                <?php endif; ?>

                <a href="index.php" class="btn btn-primary mt-3 w-100">
                    <i class="fas fa-shopping-cart"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- Bên phải: QR Code -->
            <div class="col-md-5 text-center mt-4 mt-md-0">
                <?php if ($qr_url && $amount): ?>
                    <div class="qr-box">
                        <h6 class="text-muted">Quét mã để thanh toán</h6>
                        <img src="<?= $qr_url ?>" alt="QR thanh toán VietQR" class="qr-image">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
