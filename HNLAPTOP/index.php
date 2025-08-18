<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
$is_logged_in = isset($_SESSION['username']);

$giohang = isset($_SESSION['giohang']) ? $_SESSION['giohang'] : [];
$tongsoluong = 0;

foreach ($giohang as $soluong) {
    $tongsoluong += $soluong;
}

require 'mod/hanghoa.php';
require 'mod/loaihang.php';
require 'mod/config.php';

// Khởi tạo đối tượng PDO để kết nối cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

$hh = new HangHoa(); // Instance của lớp HangHoa (được giữ lại cho các hàm khác nếu cần)
$lh = new LoaiHang(); // Instance của lớp LoaiHang

$list_loai = $lh->getAll(); // Lấy tất cả loại hàng cho dropdown

// Lấy các tham số từ URL
$idloaihang = isset($_GET['idloaihang']) ? $_GET['idloaihang'] : '';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : ''; // Lấy giá trị bộ lọc giá

// Định nghĩa các khoảng giá dựa trên GIÁ SAU KHI GIẢM GIÁ (cần tính ngược lại giá gốc)
// Giá gốc = Giá đã giảm / 0.79
$price_ranges_data = [
    '10-20' => ['min_original' => 10000000 / 0.79, 'max_original' => 20000000 / 0.79],
    '20-30' => ['min_original' => 20000000 / 0.79, 'max_original' => 30000000 / 0.79],
    '30-up' => ['min_original' => 30000000 / 0.79, 'max_original' => 999999999999 / 0.79] // Một số rất lớn cho "trên 30tr"
];

// --- Xử lý Tìm kiếm riêng biệt (như cấu trúc hiện tại của bạn) ---
$hanghoa_search_results = []; // Đổi tên biến để tránh nhầm lẫn với $list
if (!empty($search_query)) {
    $sql_search = "SELECT * FROM hanghoa WHERE tenhanghoa LIKE :search";
    $stmt_search = $pdo->prepare($sql_search);
    $stmt_search->execute(['search' => "%$search_query%"]);
    $hanghoa_search_results = $stmt_search->fetchAll(PDO::FETCH_ASSOC);
}

// --- Xử lý Danh sách sản phẩm chính (có phân trang và bộ lọc) ---
$list_main_products = [];
$total_items = 0;
$total_pages = 1;
$items_per_page = 16;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Chỉ thực hiện truy vấn danh sách sản phẩm chính nếu không có từ khóa tìm kiếm
if (empty($search_query)) {
    $sql_main_products = "SELECT * FROM hanghoa WHERE 1";
    $sql_count = "SELECT COUNT(*) FROM hanghoa WHERE 1";
    $params_main = [];

    // Lọc theo loại hàng
    if (!empty($idloaihang)) {
        $sql_main_products .= " AND idloaihang = :idloaihang";
        $sql_count .= " AND idloaihang = :idloaihang";
        $params_main[':idloaihang'] = $idloaihang;
    }

    // Lọc theo khoảng giá (dựa trên giá gốc sau khi tính ngược từ giá giảm)
    if (!empty($price_range) && isset($price_ranges_data[$price_range])) {
        $min_original_price = $price_ranges_data[$price_range]['min_original'];
        $max_original_price = $price_ranges_data[$price_range]['max_original'];
        $sql_main_products .= " AND giathamkhao >= :min_original_price AND giathamkhao <= :max_original_price";
        $sql_count .= " AND giathamkhao >= :min_original_price AND giathamkhao <= :max_original_price";
        $params_main[':min_original_price'] = $min_original_price;
        $params_main[':max_original_price'] = $max_original_price;
    }

    // Lấy tổng số sản phẩm cho phân trang
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($params_main);
    $total_items = $stmt_count->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Thêm LIMIT và OFFSET cho truy vấn chính
    $sql_main_products .= " LIMIT :limit OFFSET :offset";
    $params_main[':limit'] = $items_per_page;
    $params_main[':offset'] = $offset;

    $stmt_main = $pdo->prepare($sql_main_products);
    // Bind các tham số số nguyên một cách rõ ràng
    foreach ($params_main as $key => &$val) {
        if (strpos($key, 'limit') !== false || strpos($key, 'offset') !== false || strpos($key, 'idloaihang') !== false || strpos($key, 'price') !== false) {
            $stmt_main->bindParam($key, $val, PDO::PARAM_INT);
        } else {
            $stmt_main->bindParam($key, $val);
        }
    }
    $stmt_main->execute();
    $list_main_products = $stmt_main->fetchAll(PDO::FETCH_OBJ);
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H-N LAPTOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" xintegrity="sha512-1ycn6IcaQQ40JuKhBTEbuhfPJlRwRWvhnBPtTIDwA/3IDYvL/IQFeVp+zpnX9Ld1qjU7jL+1Qj2tU2/T0N+tQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css?<?php echo time(); ?>">
    <style>
        /* Tùy chỉnh nhỏ để icon và chữ cách nhau một chút trong menu chính */
        .navbar-nav .nav-link i {
            margin-right: 8px;
            /* Khoảng cách giữa icon và chữ */
        }

        /* Style cho số lượng sản phẩm trong giỏ hàng */
        .soluong {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 1px 5px;
            /* Giảm padding */
            font-size: 0.6em;
            /* Giảm kích thước font */
            top: -8px;
            /* Dịch lên trên */
            right: -8px;
            /* Dịch sang phải */
            min-width: 18px;
            /* Đảm bảo hình tròn không bị méo khi số lượng nhỏ */
            height: 18px;
            /* Đảm bảo chiều cao để tạo hình tròn */
            display: flex;
            /* Sử dụng flexbox để căn giữa nội dung */
            align-items: center;
            justify-content: center;
        }

        /* Đảm bảo hình ảnh logo không bị biến dạng */
        .navbar-brand img {
            height: auto;
            width: 180px
        }

        /* Điều chỉnh kích thước và khoảng cách cho các nút Tài Khoản và Giỏ Hàng */
        .header-action-btn {
            /* Đã bỏ padding và margin-bottom để trở về kích thước cũ nhưng vẫn giữ hover */
            border-radius: 0.5rem;
            /* Bo tròn nhẹ các góc */
            transition: background-color 0.3s ease;
            /* Hiệu ứng hover */
        }

        .header-action-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            /* Nền nhẹ khi hover */
        }
    </style>
</head>


<body class="bg-light">

    <!-- navbar -->
    <div>
        <div class="bg-light border-bottom">
            <div class="container py-3">
                <div class="row align-items-center">

                    <div class="col-12 col-md-3 text-center text-md-start mb-2 mb-md-0">
                        <a class="navbar-brand" href="trangchu.php">
                            <img src="./img/logo_2.png" alt="H-N LAPTOP" class="img-fluid">
                        </a>
                    </div>

                    <div class="col-12 col-md-6 mb-2 mb-md-0 justify-content-center">
                        <form action="index.php" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Bạn muốn tìm sản phẩm gì?" name="search">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512">
                                        <path fill="#ffffff"
                                            d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-12 col-md-3 d-flex justify-content-center justify-content-lg-end align-items-center mb-2 mb-md-0 ">
                        <div class="d-flex gap-5 py-2">
                            <!-- Nút Tài Khoản -->
                            <?php if (isset($_SESSION['username'])): ?>
                                <a href="myaccount.php" class="header-action-btn d-flex flex-column justify-content-center align-items-center text-decoration-none text-dark">
                                    <i class="fas fa-user-circle fa-2x"></i>
                                    <span class="mt-1">Tài Khoản</span>
                                </a>
                            <?php else: ?>
                                <a href="dangnhap.php" class="header-action-btn d-flex flex-column justify-content-center align-items-center text-decoration-none text-dark">
                                    <i class="fas fa-user-circle fa-2x"></i>
                                    <span class="mt-1">Tài Khoản</span>
                                </a>
                            <?php endif; ?>

                            <!-- Nút Giỏ Hàng -->
                            <a href="./giohang.php" class="header-action-btn d-flex flex-column justify-content-center align-items-center text-decoration-none position-relative text-dark">
                                <div class="position-relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="cart-icon" width="24" height="24" viewBox="0 0 512 512">
                                        <path fill="#000000"
                                            d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c-2.2 11.3-12.1 19.5-23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM252 160c0 11 9 20 20 20l44 0 0 44c0 11 9 20 20 20s20-9 20-20l0-44 44 0c11 0 20-9 20-20s-9-20-20-20l-44 0 0-44c0-11-9-20-20-20s-20 9-20 20l0 44-44 0c-11 0-20 9-20 20z" />
                                    </svg>
                                    <?php if ($tongsoluong > 0): ?>
                                        <span class="soluong position-absolute"><?= $tongsoluong ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="mt-1">Giỏ Hàng</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand d-lg-none" href="#">Menu</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="mainNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./trangchu.php">
                                <i class="fas fa-home"></i> Trang Chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./index.php">
                                <i class="fas fa-laptop"></i> Sản Phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./gioithieu.php">
                                <i class="fas fa-info-circle"></i> Giới Thiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./hoidap.php">
                                <i class="fas fa-question-circle"></i> Hỏi Đáp
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./lienhe.php">
                                <i class="fas fa-envelope"></i> Liên Hệ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="./tintuc.php">
                                <i class="fas fa-newspaper"></i> Tin Tức
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>



    <!-- break crumb -->
    <section class="mt-5">
        <div class="container">
            <div class="row align-items-center gx-md-5">
                <div class="col-md-12">
                    <div class="breadcrumb-holder">
                        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                            aria-label="breadcrumb">
                            <ol
                                class="breadcrumb border border-secondary rounded-pill d-inline-flex align-items-center px-3 py-1 ">
                                <li class="breadcrumb-item">
                                    <a class="text-decoration-none small text-black" href="trangchu.php">Trang Chủ</a>
                                </li>
                                <li class="breadcrumb-item"><a class="text-decoration-none small text-black"
                                        href="index.php">Sản Phẩm</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
    </section>

    <!-- tìm kiếm -->
    <section class="container">
        <?php if (isset($_GET['search'])): ?>
            <?php if (!empty($search_query)): ?>
                <h2 class="mb-3 py-4">Kết quả tìm kiếm</h2>

                <?php if (!empty($hanghoa_search_results)): ?>
                    <div class="row row-cols-1 row-cols-md-4 g-2 mb-4">
                        <?php foreach ($hanghoa_search_results as $h): // Sửa từ $hanghoa thành $hanghoa_search_results
                            $giaGiam = $h['giathamkhao'] * 0.79; // Giảm giá 21%
                        ?>
                            <div class="col-md-3">
                                <a href="chitietsanpham.php?id=<?= $h['idhanghoa'] ?>" class="text-decoration-none">
                                    <div class="card product-card bg-light">
                                        <div class="badge-discount">-21%</div>
                                        <img src="uploads/<?= htmlspecialchars($h['hinhanh']) ?>" class="card-img-top"
                                            alt="<?= htmlspecialchars($h['tenhanghoa']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?= htmlspecialchars($h['tenhanghoa']) ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= htmlspecialchars(explode("<br>", $h['mota'])[0]) ?>
                                            </p>
                                            <div class="price d-flex justify-content-between align-items-end">
                                                <span class="current-price fw-bold text-danger fs-5 fst-italic ms-1">
                                                    <?= number_format($giaGiam, 0, ',', '.') ?> VNĐ
                                                </span>
                                                <span class="text-muted text-decoration-line-through fs-6 me-1">
                                                    <?= number_format($h['giathamkhao'], 0, ',', '.') ?> VNĐ
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-danger">Không tìm thấy sản phẩm nào phù hợp.</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-center text-muted">Vui lòng nhập từ khóa để tìm kiếm sản phẩm.</p>
            <?php endif; ?>
        <?php endif; ?>
    </section>



    <!-- danh sach san pham -->
    <?php if (empty($search_query)): ?> <section class="container py-4">
            <h2 class="mb-4">Danh sách sản phẩm</h2>
            <form method="GET" class="mb-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="idloaihang" class="form-label visually-hidden">Loại hàng:</label>
                        <select name="idloaihang" id="idloaihang" class="form-select" onchange="this.form.submit()">
                            <option value="">Tất cả loại hàng</option>
                            <?php foreach ($list_loai as $l): ?>
                                <option value="<?= htmlspecialchars($l->idloaihang) ?>" <?= $idloaihang == $l->idloaihang ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l->tenloaihang) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="price_range" class="form-label visually-hidden">Khoảng giá:</label>
                        <select name="price_range" id="price_range" class="form-select" onchange="this.form.submit()">
                            <option value="">Tất cả giá</option>
                            <option value="10-20" <?= $price_range == '10-20' ? 'selected' : '' ?>>10 triệu - 20 triệu</option>
                            <option value="20-30" <?= $price_range == '20-30' ? 'selected' : '' ?>>20 triệu - 30 triệu</option>
                            <option value="30-up" <?= $price_range == '30-up' ? 'selected' : '' ?>>Trên 30 triệu</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="page" value="<?= htmlspecialchars($current_page) ?>">
            </form>
            <div class="row row-cols-1 row-cols-md-4 g-2">
                <?php if (!empty($list_main_products)): ?>
                    <?php foreach ($list_main_products as $h):
                        $giaGiam = $h->giathamkhao * 0.79; // Giảm giá 21%
                    ?>
                        <div class="col-md-3">
                            <a href="chitietsanpham.php?id=<?= $h->idhanghoa ?>" class="text-decoration-none">
                                <div class="card product-card bg-white">
                                    <div class="badge-discount">-21%</div>
                                    <img src="uploads/<?= htmlspecialchars($h->hinhanh) ?>" class="card-img-top"
                                        alt="<?= htmlspecialchars($h->tenhanghoa) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?= htmlspecialchars($h->tenhanghoa) ?>
                                        </h5>
                                        <p class="card-text">
                                            <?= htmlspecialchars(explode("<br>", $h->mota)[0]) ?>
                                        </p>
                                        <div class="price d-flex justify-content-between align-items-end">
                                            <span class="current-price fw-bold text-danger fs-5 fst-italic ms-1">
                                                <?= number_format($giaGiam, 0, ',', '.') ?> VNĐ
                                            </span>
                                            <span class="text-muted text-decoration-line-through fs-6 me-1">
                                                <?= number_format($h->giathamkhao, 0, ',', '.') ?> VNĐ
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center text-muted">Không tìm thấy sản phẩm nào phù hợp với bộ lọc của bạn.</p>
                    </div>
                <?php endif; ?>
            </div>


            <!-- Phân trang -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page - 1 ?><?= $idloaihang ? '&idloaihang=' . $idloaihang : '' ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo; Trước</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $current_page == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= $idloaihang ? '&idloaihang=' . $idloaihang : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $current_page + 1 ?><?= $idloaihang ? '&idloaihang=' . $idloaihang : '' ?>" aria-label="Next">
                                    <span aria-hidden="true">Tiếp &raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </section>
    <?php endif; ?> <!-- Kết thúc kiểm tra -->

    <!-- support -->
    <section class="support py-4 border-bottom">
        <div class="container">
            <div class="row g-3">
                <div class="col-lg-3">
                    <div class="p-4 d-flex align-items-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60" height="60"
                                fill="currentColor" class="me-1 bi bi-envelope-check-fill">
                                <path
                                    d="M48 0C21.5 0 0 21.5 0 48L0 368c0 26.5 21.5 48 48 48l16 0c0 53 43 96 96 96s96-43 96-96l128 0c0 53 43 96 96 96s96-43 96-96l32 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l0-64 0-32 0-18.7c0-17-6.7-33.3-18.7-45.3L512 114.7c-12-12-28.3-18.7-45.3-18.7L416 96l0-48c0-26.5-21.5-48-48-48L48 0zM416 160l50.7 0L544 237.3l0 18.7-128 0 0-96zM112 416a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm368-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h3 class="mb-1 mt-3 small">
                                CHÍNH SÁCH GIAO HÀNG
                            </h3>
                            <p class="small">
                                Nhận hàng và thanh toán tại nhà
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-4 d-flex align-items-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60" height="60"
                                fill="currentColor" class="me-1 bi bi-envelope-check-fill">
                                <path
                                    d="M320 488c0 9.5-5.6 18.1-14.2 21.9s-18.8 2.3-25.8-4.1l-80-72c-5.1-4.6-7.9-11-7.9-17.8s2.9-13.3 7.9-17.8l80-72c7-6.3 17.2-7.9 25.8-4.1s14.2 12.4 14.2 21.9l0 40 16 0c35.3 0 64-28.7 64-64l0-166.7C371.7 141 352 112.8 352 80c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3L464 320c0 70.7-57.3 128-128 128l-16 0 0 40zM456 80a24 24 0 1 0 -48 0 24 24 0 1 0 48 0zM192 24c0-9.5 5.6-18.1 14.2-21.9s18.8-2.3 25.8 4.1l80 72c5.1 4.6 7.9 11 7.9 17.8s-2.9 13.3-7.9 17.8l-80 72c-7 6.3-17.2 7.9-25.8 4.1s-14.2-12.4-14.2-21.9l0-40-16 0c-35.3 0-64 28.7-64 64l0 166.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3L48 192c0-70.7 57.3-128 128-128l16 0 0-40zM56 432a24 24 0 1 0 48 0 24 24 0 1 0 -48 0z" />
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h3 class="mb-1 mt-3 small">
                                ĐỔI MỚI 15 NGÀY ĐẦU
                            </h3>
                            <p class="small">
                                Áp dụng với sản phẩm Laptop
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-4 d-flex align-items-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60" height="60"
                                fill="currentColor" class="me-1 bi bi-envelope-check-fill">
                                <path
                                    d="M64 32C28.7 32 0 60.7 0 96l0 32 576 0 0-32c0-35.3-28.7-64-64-64L64 32zM576 224L0 224 0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-192zM112 352l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm112 16c0-8.8 7.2-16 16-16l128 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-128 0c-8.8 0-16-7.2-16-16z" />
                            </svg>
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h3 class="mb-1 mt-3 small">
                                THANH TOÁN TIỆN LỢI
                            </h3>
                            <p class="small">
                                Trả tiền mặt, CK, trả góp 0%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="p-4 d-flex align-items-center">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="60" height="60"
                                fill="currentColor" class="me-1 bi bi-envelope-check-fill">
                                <path
                                    d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3 14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5 17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5 4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0 0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7 39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z" />
                            </svg>
                            </svg>
                        </div>
                        <div class="ms-2">
                            <h3 class="mb-1 mt-3 small">
                                HỖ TRỢ NHIỆT TÌNH
                            </h3>
                            <p class="small">
                                Tư vấn, giải đáp mọi thắc mắc
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- subscribe -->
    <section class="subscribe py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="mb-3 mb-md-0 text-center">
                        <h5 class="text-white fs-4 fw-light text-md-end">Subscribe for new products</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- form sub -->
                    <form class="d-flex" role="search">
                        <div class="input-group">
                            <input class="form-control form-control-sm me-0" type="email" placeholder="Enter your email"
                                aria-label="Search">
                            <button class="btn btn-primary btn-sm d-inline-flex align-items-center" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="me-2 bi bi-envelope-check-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 4.697v4.974A4.491 4.491 0 0 0 12.5 8a4.49 4.49 0 0 0-1.965.45l-.338-.207L16 4.697Z">
                                    </path>
                                    <path
                                        d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z">
                                    </path>
                                </svg>
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <footer class="footer py-5 text-white" style="background-color:rgb(21, 22, 29);">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="brand-icon mb-2">
                        <img src="./img/2snapedit_1742732103514.png" alt="logo" style="width: 30%;" />
                    </div>
                    <address class="small text-secondary">
                        Le Binh, Cai Rang, <br>
                        Can Thơ, VietNam.<br>
                        0913322428
                    </address>
                    <p class="small text-secondary">
                        contact@HNlaptop.com
                    </p>
                </div>
                <div class="col-md-3">
                    <h6>Main Menu</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">
                            <a class="text-decoration-none text-secondary" href="trangchu.php">Trang Chủ</a>
                        </li>
                        <li class="mb-2">
                            <a class="text-decoration-none text-secondary" href="gioithieu.php">Giới Thiệu Về Chúng
                                Tôi</a>
                        </li>
                        <li class="mb-2">
                            <a class="text-decoration-none text-secondary" href="hoidap.php">Tư Vấn Khách Hàng</a>
                        </li>
                        <li class="mb-2">
                            <a class="text-decoration-none text-secondary" href="lienhe.php">Liên Hệ Với Chúng Tôi</a>
                        </li>

                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Chứng Nhận</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-1">
                            <img alt="DMCA" loading="lazy" width="52" height="32" decoding="async" data-nimg="1"
                                style="color: transparent;"
                                srcset="https://cdn2.fptshop.com.vn/svg/dmca_icon_8fc6622bd5.svg?w=64&amp;q=100 1x, https://cdn2.fptshop.com.vn/svg/dmca_icon_8fc6622bd5.svg?w=128&amp;q=100 2x"
                                src="https://cdn2.fptshop.com.vn/svg/dmca_icon_8fc6622bd5.svg?w=128&amp;q=100">
                        </li>
                        <li class="mb-1">
                            <img alt="Thương hiệu mạnh Việt Nam 2013" loading="lazy" width="52" height="32"
                                decoding="async" data-nimg="1" style="color: transparent;"
                                srcset="https://cdn2.fptshop.com.vn/svg/thuong_hieu_manh_2013_icon_b56f772475.svg?w=64&amp;q=100 1x, https://cdn2.fptshop.com.vn/svg/thuong_hieu_manh_2013_icon_b56f772475.svg?w=128&amp;q=100 2x"
                                src="https://cdn2.fptshop.com.vn/svg/thuong_hieu_manh_2013_icon_b56f772475.svg?w=128&amp;q=100">
                        </li>
                        <li class="mb-1">
                            <img alt="Sản phẩm - Dịch vụ hàng đầu Việt Nam 2014" loading="lazy" width="52" height="32"
                                decoding="async" data-nimg="1" style="color: transparent;"
                                srcset="https://cdn2.fptshop.com.vn/svg/san_pham_dich_vu_hang_dau_viet_nam_icon_282a9ba4f7.svg?w=64&amp;q=100 1x, https://cdn2.fptshop.com.vn/svg/san_pham_dich_vu_hang_dau_viet_nam_icon_282a9ba4f7.svg?w=128&amp;q=100 2x"
                                src="https://cdn2.fptshop.com.vn/svg/san_pham_dich_vu_hang_dau_viet_nam_icon_282a9ba4f7.svg?w=128&amp;q=100">
                        </li class="mb-1">
                        <li>
                            <a rel="nofollow" target="_blank" href="http://online.gov.vn/Home/WebDetails/21883"><img
                                    alt="Đã thông báo Bộ Công Thương" loading="lazy" width="86" height="32"
                                    decoding="async" data-nimg="1" style="color: transparent;"
                                    srcset="https://cdn2.fptshop.com.vn/svg/da_thong_bao_bo_cong_thuong_icon_64785fb3f7.svg?w=96&amp;q=100 1x, https://cdn2.fptshop.com.vn/svg/da_thong_bao_bo_cong_thuong_icon_64785fb3f7.svg?w=180&amp;q=100 2x"
                                    src="https://cdn2.fptshop.com.vn/svg/da_thong_bao_bo_cong_thuong_icon_64785fb3f7.svg?w=180&amp;q=100"></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Follow us</h6>
                    <div>
                        <a href="#Facebook"
                            class="btn btn-sm px-3 btn-primary rounded-pill d-inline-flex align-items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-facebook me-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z">
                                </path>
                            </svg>
                            <span class="d-none d-md-block">
                                Facebook
                            </span>
                        </a>
                        <a href="#Twitter"
                            class="btn btn-sm px-3 btn-info rounded-pill d-inline-flex align-items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-twitter me-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z">
                                </path>
                            </svg>
                            <span class="d-none d-md-block">
                                Twitter
                            </span>
                        </a>
                        <a href="#Youtube"
                            class="btn btn-sm px-3 mt-lg-0 btn-danger rounded-pill d-inline-flex align-items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-youtube me-md-2" viewBox="0 0 16 16">
                                <path
                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z">
                                </path>
                            </svg>
                            <span class="d-none d-md-block">
                                Youtube
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- copyright -->
    <div class="copyright py-4 small">
        <div class="container">
            <div class="row text-center justify-content-center text-white small">
                <p class="mb-0">Copyright © 2025. H-N Laptop</p>
            </div>
        </div>
    </div>

    <!-- nhúng js bt -->
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="script.js"></script>
</body>

</html>