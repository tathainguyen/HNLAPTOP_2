<?php

// Kiểm tra trạng thái phiên trước khi khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu không có session hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php"); // Chuyển hướng về trang đăng nhập
    exit();
}

require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Thống kê đơn hàng theo trạng thái
$stats = [];
$statusQuery = "SELECT trang_thai, COUNT(*) AS so_luong, SUM(tong_tien) AS tong_doanh_thu 
                FROM donhang 
                GROUP BY trang_thai";
$statsResult = $conn->query($statusQuery);
while ($stat = $statsResult->fetch_assoc()) {
    $stats[$stat['trang_thai']] = [
        'so_luong' => $stat['so_luong'],
        'doanh_thu' => $stat['tong_doanh_thu'] ?: 0
    ];
}

// Tính tổng số đơn hàng và doanh thu
$totalOrders = 0;
$totalRevenue = 0;
$successOrders = 0;
$successRevenue = 0;

foreach ($stats as $status => $data) {
    $totalOrders += $data['so_luong'];
    $totalRevenue += $data['doanh_thu'];

    if ($status === 'Giao hàng thành công') {
        $successOrders = $data['so_luong'];
        $successRevenue = $data['doanh_thu'];
    }
}

// Xử lý lọc đơn hàng
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Xử lý phân trang
$recordsPerPage = 10;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Câu truy vấn đếm tổng số đơn hàng phù hợp với điều kiện lọc
$countSql = "
    SELECT COUNT(*) as total
    FROM donhang
    LEFT JOIN users ON donhang.id_kh = users.id
    WHERE 1=1
";

if (!empty($statusFilter)) {
    $countSql .= " AND donhang.trang_thai = '" . $conn->real_escape_string($statusFilter) . "'";
}

if (!empty($searchTerm)) {
    $countSql .= " AND (
        donhang.code LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR
        donhang.ten_khach_hang LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR
        donhang.so_dien_thoai LIKE '%" . $conn->real_escape_string($searchTerm) . "%'
    )";
}

$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Câu truy vấn lấy dữ liệu có phân trang
$sql = "
    SELECT donhang.*, users.id AS id_kh
    FROM donhang
    LEFT JOIN users ON donhang.id_kh = users.id
    WHERE 1=1
";

if (!empty($statusFilter)) {
    $sql .= " AND donhang.trang_thai = '" . $conn->real_escape_string($statusFilter) . "'";
}

if (!empty($searchTerm)) {
    $sql .= " AND (
        donhang.code LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR
        donhang.ten_khach_hang LIKE '%" . $conn->real_escape_string($searchTerm) . "%' OR
        donhang.so_dien_thoai LIKE '%" . $conn->real_escape_string($searchTerm) . "%'
    )";
}

$sql .= " ORDER BY donhang.id DESC LIMIT $offset, $recordsPerPage";

$result = $conn->query($sql);
?>

<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thanh công cụ -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 mb-6 text-white overflow-hidden relative">
        <div class="absolute right-0 top-0 opacity-10">
            <i class="fas fa-shopping-cart text-9xl transform translate-x-6 -translate-y-6"></i>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="z-10">
                <h2 class="text-2xl font-bold font-heading mb-1">Quản lý đơn hàng</h2>
                <p class="text-indigo-100">Theo dõi và quản lý tất cả đơn hàng từ khách hàng</p>
            </div>
            <div class="mt-4 md:mt-0 z-10 flex space-x-3">
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Tổng đơn hàng</div>
                        <div class="font-bold"><?= $totalOrders ?></div>
                    </div>
                </div>
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-coins mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Doanh thu</div>
                        <div class="font-bold"><?= number_format($successRevenue, 0, ',', '.') ?>đ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê nhanh -->
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
        <!-- Tổng số đơn hàng -->
        <div class="bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-sm border border-indigo-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $totalOrders ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Tổng đơn hàng</p>
                </div>
            </div>
        </div>

        <!-- Đơn hàng thành công -->
        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-xl shadow-sm border border-emerald-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $successOrders ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Thành công</p>
                </div>
            </div>
        </div>

        <!-- Đơn đang chờ duyệt -->
        <div class="bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-sm border border-amber-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $stats['Đang chờ duyệt']['so_luong'] ?? 0 ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Chờ duyệt</p>
                </div>
            </div>
        </div>

        <!-- Đang xử lý -->
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm border border-blue-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-truck"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= ($stats['Đã duyệt']['so_luong'] ?? 0) + ($stats['Đang giao']['so_luong'] ?? 0) ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Đang xử lý</p>
                </div>
            </div>
        </div>

        <!-- Đơn hàng hủy -->
        <div class="bg-gradient-to-br from-white to-rose-50 rounded-xl shadow-sm border border-rose-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-rose-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-ban"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $stats['Đã hủy']['so_luong'] ?? 0 ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Đã hủy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Tìm kiếm -->
            <div class="md:flex-1">
                <form action="" method="GET" class="flex">
                    <input type="hidden" name="page" value="quanly_donhang.php">
                    <?php if (!empty($statusFilter)): ?>
                        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
                    <?php endif; ?>
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" class="form-control w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pl-10 pr-4 py-2 shadow-sm" placeholder="Tìm theo mã đơn, tên KH hoặc SĐT">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <button type="submit" class="ml-2 bg-indigo-600 text-white rounded-lg px-4 py-2 shadow-sm hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-search mr-1"></i> Tìm
                    </button>
                </form>
            </div>

            <!-- Lọc theo trạng thái -->
            <div class="md:w-1/4">
                <form action="" method="GET" id="statusFilterForm" class="flex">
                    <input type="hidden" name="page" value="quanly_donhang.php">
                    <?php if (!empty($searchTerm)): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                    <?php endif; ?>
                    <select name="status" class="form-control w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Đang chờ duyệt" <?= $statusFilter == 'Đang chờ duyệt' ? 'selected' : '' ?>>Đang chờ duyệt</option>
                        <option value="Đã duyệt" <?= $statusFilter == 'Đã duyệt' ? 'selected' : '' ?>>Đã duyệt</option>
                        <option value="Đang giao" <?= $statusFilter == 'Đang giao' ? 'selected' : '' ?>>Đang giao</option>
                        <option value="Giao hàng thành công" <?= $statusFilter == 'Giao hàng thành công' ? 'selected' : '' ?>>Giao hàng thành công</option>
                        <option value="Đã hủy" <?= $statusFilter == 'Đã hủy' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </form>
            </div>

            <!-- Xóa bộ lọc -->
            <?php if (!empty($searchTerm) || !empty($statusFilter)): ?>
                <div class="md:flex-none">
                    <a href="?page=quanly_donhang.php" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i> Xóa bộ lọc
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Hiển thị bộ lọc đang áp dụng -->
        <?php if (!empty($searchTerm) || !empty($statusFilter)): ?>
            <div class="mt-4 pt-3 border-t border-slate-200 flex flex-wrap items-center">
                <span class="text-slate-600 mr-2 text-sm">Đang lọc:</span>
                <?php if (!empty($searchTerm)): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2 mb-2 animate__animated animate__fadeIn">
                        <i class="fas fa-search mr-1.5"></i> <?= htmlspecialchars($searchTerm) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($statusFilter)): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-2 mb-2 animate__animated animate__fadeIn">
                        <i class="fas fa-filter mr-1.5"></i> <?= htmlspecialchars($statusFilter) ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bảng đơn hàng -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-indigo-600 mr-2"></i> Danh sách đơn hàng
                </h3>
                <div class="text-sm text-slate-500">
                    Hiển thị <span class="font-medium"><?= min($recordsPerPage, $result->num_rows) ?></span> / <?= $totalRecords ?> đơn hàng
                </div>
            </div>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <div class="py-12 text-center text-slate-500">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-slate-100 rounded-full p-3 mb-3">
                        <i class="fas fa-shopping-cart text-3xl text-slate-400"></i>
                    </div>
                    <p class="font-medium">Không tìm thấy đơn hàng nào</p>
                    <p class="text-sm mt-1">Hãy thử tìm kiếm hoặc lọc khác</p>
                </div>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 text-slate-700 text-sm font-medium">
                        <tr>
                            <th class="py-3 px-3 text-left">Mã đơn</th>
                            <th class="py-3 px-3 text-left">Khách hàng</th>
                            <th class="py-3 px-3 text-right">Tổng tiền</th>
                            <th class="py-3 px-3 text-center">Thanh toán</th>
                            <th class="py-3 px-3 text-center">Trạng thái</th>
                            <th class="py-3 px-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition-colors animate__animated animate__fadeIn animate__faster">
                                <td class="py-3 px-3">
                                    <div class="flex items-center">
                                        <?php
                                        // Icon và màu dựa vào trạng thái
                                        $statusIcons = [
                                            'Đang chờ duyệt' => '<i class="fas fa-hourglass-half text-amber-500"></i>',
                                            'Đã duyệt' => '<i class="fas fa-check text-blue-500"></i>',
                                            'Đang giao' => '<i class="fas fa-truck text-indigo-500"></i>',
                                            'Giao hàng thành công' => '<i class="fas fa-check-circle text-emerald-500"></i>',
                                            'Đã hủy' => '<i class="fas fa-ban text-rose-500"></i>'
                                        ];
                                        $icon = $statusIcons[$row['trang_thai']] ?? '<i class="fas fa-circle text-slate-400"></i>';
                                        ?>
                                        <div class="mr-2.5">
                                            <?= $icon ?>
                                        </div>
                                        <div class="font-medium text-indigo-600">#<?= $row['code'] ?></div>
                                    </div>
                                </td>

                                <td class="py-3 px-3">
                                    <div class="font-medium text-slate-700 truncate max-w-[150px]" title="<?= htmlspecialchars($row['ten_khach_hang']) ?>"><?= htmlspecialchars($row['ten_khach_hang']) ?></div>
                                    <div class="text-xs text-slate-500"><?= $row['so_dien_thoai'] ?></div>
                                </td>

                                <td class="py-3 px-3 text-right">
                                    <div class="font-semibold text-slate-800"><?= number_format($row['tong_tien'], 0, ',', '.') ?>đ</div>
                                    <div class="text-xs text-slate-500"><?= $row['phuong_thuc_thanh_toan'] ?></div>
                                </td>

                                <td class="py-3 px-3 text-center">
                                    <?php
                                    $trangThaiTT = $row['trang_thai_thanh_toan'];
                                    $statusTTClasses = [
                                        '0' => 'bg-amber-100 text-amber-800',
                                        '1' => 'bg-emerald-100 text-emerald-800',
                                        '2' => 'bg-blue-100 text-blue-800',
                                        '3' => 'bg-rose-100 text-rose-800',
                                    ];
                                    $statusTTLabels = [
                                        '0' => 'Chưa TT',
                                        '1' => 'Đã TT',
                                        '2' => 'Hoàn tiền',
                                        '3' => 'TT thất bại',
                                    ];
                                    $statusTTClass = $statusTTClasses[$trangThaiTT] ?? 'bg-slate-100 text-slate-800';
                                    $statusTTLabel = $statusTTLabels[$trangThaiTT] ?? $trangThaiTT;
                                    ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $statusTTClass ?>">
                                        <?= $statusTTLabel ?>
                                    </span>
                                </td>

                                <td class="py-3 px-3 text-center">
                                    <?php
                                    $trangThai = $row['trang_thai'];

                                    $statusColors = [
                                        'Đang chờ duyệt' => 'bg-amber-100 text-amber-800',
                                        'Đã duyệt' => 'bg-blue-100 text-blue-800',
                                        'Đang giao' => 'bg-indigo-100 text-indigo-800',
                                        'Giao hàng thành công' => 'bg-emerald-100 text-emerald-800',
                                        'Đã hủy' => 'bg-rose-100 text-rose-800'
                                    ];

                                    $statusClass = $statusColors[$trangThai] ?? 'bg-slate-100 text-slate-800';
                                    ?>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= $trangThai ?>
                                    </span>
                                </td>

                                <td class="py-3 px-3">
                                    <div class="flex justify-center space-x-1">
                                        <button onclick="viewOrderDetail(<?= $row['id'] ?>)"
                                            class="bg-indigo-50 text-indigo-700 p-1.5 rounded hover:bg-indigo-100 transition-colors"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if (
                                            $row['trang_thai'] === 'Đang chờ duyệt'
                                            && (
                                                $row['phuong_thuc_thanh_toan'] !== 'QR Code'
                                                || $row['trang_thai_thanh_toan'] != 0
                                            )
                                        ): ?>
                                            
                                            <button type="button"
                                                class="btn-update-status bg-emerald-50 text-emerald-700 p-1.5 rounded hover:bg-emerald-100 transition-colors"
                                                data-id="<?= $row['id'] ?>"
                                                data-status="Đã duyệt"
                                                title="Duyệt đơn hàng">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn-update-status bg-rose-50 text-rose-700 p-1.5 rounded hover:bg-rose-100 transition-colors"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-status="Đã hủy"
                                                    title="Hủy đơn hàng">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php elseif ($row['trang_thai'] === 'Đã duyệt'): ?>
                                                <button type="button"
                                                    class="btn-update-status bg-indigo-50 text-indigo-700 p-1.5 rounded hover:bg-indigo-100 transition-colors"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-status="Đang giao"
                                                    title="Bắt đầu giao hàng">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </button>
                                            <?php elseif ($row['trang_thai'] === 'Đang giao'): ?>
                                                <button type="button"
                                                    class="btn-update-status bg-emerald-50 text-emerald-700 p-1.5 rounded hover:bg-emerald-100 transition-colors"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-status="Giao hàng thành công"
                                                    title="Xác nhận giao hàng thành công">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                        <?php endif; ?>

                                        <!-- Nút QR -->
                                        <?php if (
                                            $row['phuong_thuc_thanh_toan'] === 'QR Code'
                                            && $row['trang_thai_thanh_toan'] == 0
                                        ): ?>
                                            <div class="dropdown-container relative inline-block" x-data="{ open: false }">
                                                <button @click="open = !open" class="bg-blue-50 text-blue-700 p-1.5 rounded hover:bg-blue-100 transition-colors"
                                                    title="Thanh toán QR">
                                                    <i class="fas fa-qrcode"></i>
                                                </button>
                                                <div x-show="open" @click.away="open = false"
                                                    class="absolute bottom-full right-0 mb-1 w-40 bg-white rounded-lg shadow-lg py-1 border border-slate-200"
                                                    style="z-index: 999; transform-origin: bottom right;"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95">
                                                    <!-- Mũi tên nhỏ chỉ vào nút -->
                                                    <div class="absolute top-full right-1.5 w-3 h-3 transform rotate-45 bg-white border-r border-b border-slate-200"></div>

                                                
                                                    <button type="button"
                                                        class="btn-update-tt block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                        data-id="<?= $row['id'] ?>"
                                                        data-tt="1">
                                                        <i class="fas fa-check-circle text-emerald-500 mr-1.5"></i>
                                                        Đã thanh toán
                                                    </button>
                                                    <button type="button"
                                                        class="btn-update-tt block w-full text-left px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                        data-id="<?= $row['id'] ?>"
                                                        data-tt="3">
                                                        <i class="fas fa-times-circle text-rose-500 mr-1.5"></i>
                                                        TT thất bại
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (
                                            $row['phuong_thuc_thanh_toan'] === 'QR Code'
                                            && $row['trang_thai'] === 'Đã hủy'
                                            && $row['trang_thai_thanh_toan'] == 1
                                        ): ?>
                                            <button type="button"
                                                class="btn-update-tt bg-blue-50 text-blue-700 p-1.5 rounded hover:bg-blue-100 transition-colors"
                                                data-id="<?= $row['id'] ?>"
                                                data-tt="2"
                                                title="Đã hoàn tiền">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <div class="p-4 border-t border-slate-200">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="text-sm text-slate-500">
                        Trang <?= $page ?> / <?= $totalPages ?>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        <?php
                        // Xây dựng URL cơ bản với các tham số hiện tại
                        $baseUrl = '?page=quanly_donhang.php';
                        if (!empty($searchTerm)) {
                            $baseUrl .= '&search=' . urlencode($searchTerm);
                        }
                        if (!empty($statusFilter)) {
                            $baseUrl .= '&status=' . urlencode($statusFilter);
                        }
                        ?>

                        <!-- Nút trang đầu và trang trước -->
                        <?php if ($page > 1): ?>
                            <a href="<?= $baseUrl ?>&p=1" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-double-left text-xs"></i>
                            </a>

                            <a href="<?= $baseUrl ?>&p=<?= $page - 1 ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-left text-xs"></i>
                            </a>
                        <?php endif; ?>

                        <!-- Trang hiện tại và các trang xung quanh -->
                        <?php
                        $startPage = max($page - 1, 1);
                        $endPage = min($page + 1, $totalPages);

                        // Luôn hiển thị ít nhất 3 trang nếu có thể
                        if ($endPage - $startPage + 1 < 3) {
                            if ($startPage == 1) {
                                $endPage = min(3, $totalPages);
                            } elseif ($endPage == $totalPages) {
                                $startPage = max(1, $totalPages - 2);
                            }
                        }

                        for ($i = $startPage; $i <= $endPage; $i++):
                            $isActive = $i == $page;
                        ?>
                            <?php if ($isActive): ?>
                                <span class="inline-flex items-center justify-center h-8 w-8 border border-indigo-600 rounded-md text-sm font-medium text-white bg-indigo-600">
                                    <?= $i ?>
                                </span>
                            <?php else: ?>
                                <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                    <?= $i ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Nút trang sau và trang cuối -->
                        <?php if ($page < $totalPages): ?>
                            <a href="<?= $baseUrl ?>&p=<?= $page + 1 ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-right text-xs"></i>
                            </a>

                            <a href="<?= $baseUrl ?>&p=<?= $totalPages ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-double-right text-xs"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hướng dẫn nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i> Trạng thái đơn hàng
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="flex items-center p-2 rounded-lg bg-amber-50 border border-amber-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    <span class="text-xs text-amber-800">Đang chờ duyệt</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-blue-50 border border-blue-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    <span class="text-xs text-blue-800">Đã duyệt</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-indigo-50 border border-indigo-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                    <span class="text-xs text-indigo-800">Đang giao</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-emerald-50 border border-emerald-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    <span class="text-xs text-emerald-800">Giao thành công</span>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center">
                <i class="fas fa-wallet text-indigo-600 mr-2"></i> Trạng thái thanh toán
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div class="flex items-center p-2 rounded-lg bg-amber-50 border border-amber-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                    <span class="text-xs text-amber-800">Chưa thanh toán</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-emerald-50 border border-emerald-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    <span class="text-xs text-emerald-800">Đã thanh toán</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-blue-50 border border-blue-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                    <span class="text-xs text-blue-800">Đã hoàn tiền</span>
                </div>

                <div class="flex items-center p-2 rounded-lg bg-rose-50 border border-rose-200">
                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                    <span class="text-xs text-rose-800">TT thất bại</span>
                </div>
            </div>
        </div>
        <!-- Modal chi tiết đơn hàng -->
        <div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                        <h3 class="text-xl font-bold flex items-center">
                            <i class="fas fa-receipt mr-2"></i>
                            Chi tiết đơn hàng
                        </h3>
                        <button onclick="closeOrderDetailModal()" class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div id="orderDetailContent" class="p-6 overflow-y-auto max-h-[80vh]">
                        <!-- Content sẽ được load bằng JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {

    
    document.querySelectorAll('.btn-update-status').forEach(function(btn) {
        btn.addEventListener('click', handleUpdateStatusClick);
    });

    document.querySelectorAll('.btn-update-tt').forEach(function(btn) {
        btn.addEventListener('click', handleUpdateTTClick);
        
    });
    
});

function handleUpdateStatusClick(event) {
    const btn = event.currentTarget;
    if (btn.dataset.status === 'Đã hủy' && !confirm('Bạn có chắc muốn hủy đơn hàng này?')) return;
    fetch('ajax_update_trang_thai.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(btn.dataset.id) + '&trang_thai=' + encodeURIComponent(btn.dataset.status)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = btn.closest('tr');
            const statusCell = row.querySelector('td:nth-child(5) span');
            statusCell.textContent = data.trang_thai;
            statusCell.className = 'inline-flex px-2 py-0.5 rounded-full text-xs font-medium';
            if (data.trang_thai === 'Giao hàng thành công') {
                statusCell.classList.add('bg-emerald-100', 'text-emerald-800');
            } else if (data.trang_thai === 'Đã duyệt') {
                statusCell.classList.add('bg-blue-100', 'text-blue-800');
            } else if (data.trang_thai === 'Đang giao') {
                statusCell.classList.add('bg-indigo-100', 'text-indigo-800');
            } else if (data.trang_thai === 'Đang chờ duyệt') {
                statusCell.classList.add('bg-amber-100', 'text-amber-800');
            } else if (data.trang_thai === 'Đã hủy') {
                statusCell.classList.add('bg-rose-100', 'text-rose-800');
            }

            // Cập nhật lại các nút thao tác
            const actionCell = row.querySelector('td:last-child .flex');
            if (actionCell) {
                actionCell.querySelectorAll('.btn-update-status').forEach(btn => btn.remove());
                if (data.trang_thai === 'Đang chờ duyệt') {
                    actionCell.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            class="btn-update-status bg-emerald-50 text-emerald-700 p-1.5 rounded hover:bg-emerald-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Đã duyệt"
                            title="Duyệt đơn hàng">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button"
                            class="btn-update-status bg-rose-50 text-rose-700 p-1.5 rounded hover:bg-rose-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Đã hủy"
                            title="Hủy đơn hàng">
                            <i class="fas fa-times"></i>
                        </button>
                    `);
                } else if (data.trang_thai === 'Đã duyệt') {
                    actionCell.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            class="btn-update-status bg-indigo-50 text-indigo-700 p-1.5 rounded hover:bg-indigo-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Đang giao"
                            title="Bắt đầu giao hàng">
                            <i class="fas fa-shipping-fast"></i>
                        </button>
                    `);
                } else if (data.trang_thai === 'Đang giao') {
                    actionCell.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            class="btn-update-status bg-emerald-50 text-emerald-700 p-1.5 rounded hover:bg-emerald-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Giao hàng thành công"
                            title="Xác nhận giao hàng thành công">
                            <i class="fas fa-check-circle"></i>
                        </button>
                    `);
                }
                // Gắn lại sự kiện cho các nút mới
                actionCell.querySelectorAll('.btn-update-status').forEach(function(newBtn) {
                    newBtn.addEventListener('click', handleUpdateStatusClick);
                });
            }

            showNotification('Cập nhật thành công!', 'success');
        } else {
            alert(data.msg || 'Có lỗi xảy ra!');
        }
    })
    .catch(() => alert('Lỗi kết nối!'));
}

function handleUpdateTTClick(event) {
    const btn = event.currentTarget;
    fetch('ajax_update_trang_thai_tt.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(btn.dataset.id) + '&trang_thai_tt=' + encodeURIComponent(btn.dataset.tt)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = btn.closest('tr');
            const ttCell = row.querySelector('td:nth-child(4) span');
            let label = 'Chưa TT', cls = 'bg-amber-100 text-amber-800';
            if (data.trang_thai_tt == 1) { label = 'Đã TT'; cls = 'bg-emerald-100 text-emerald-800'; }
            else if (data.trang_thai_tt == 2) { label = 'Hoàn tiền'; cls = 'bg-blue-100 text-blue-800'; }
            else if (data.trang_thai_tt == 3) { label = 'TT thất bại'; cls = 'bg-rose-100 text-rose-800'; }
            ttCell.textContent = label;
            ttCell.className = 'inline-flex px-2 py-0.5 rounded-full text-xs font-medium ' + cls;

            // XÓA DROPDOWN QR nếu có
            const qrDropdown = row.querySelector('.dropdown-container');
            if (qrDropdown) {
                qrDropdown.remove();
            }

            // Cập nhật lại các nút thao tác liên quan đến thanh toán
            const actionCell = row.querySelector('td:last-child .flex');
            if (actionCell) {
                actionCell.querySelectorAll('.btn-update-tt').forEach(btn => btn.remove());
                // ...phần thêm lại nút hoàn tiền nếu cần...
                const trangThai = row.querySelector('td:nth-child(5) span').textContent.trim();
                if (trangThai === 'Đã hủy' && data.trang_thai_tt == 1) {
                    actionCell.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            class="btn-update-tt bg-blue-50 text-blue-700 p-1.5 rounded hover:bg-blue-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-tt="2"
                            title="Đã hoàn tiền">
                            <i class="fas fa-undo"></i>
                        </button>
                    `);
                }
                actionCell.querySelectorAll('.btn-update-tt').forEach(function(newBtn) {
                    newBtn.addEventListener('click', handleUpdateTTClick);
                });
            }

            // Cập nhật lại nút duyệt/hủy nếu cần (nếu trạng thái đơn là Đang chờ duyệt và đã TT)
            const trangThai = row.querySelector('td:nth-child(5) span').textContent.trim();
            if (actionCell) {
                actionCell.querySelectorAll('.btn-update-status').forEach(btn => btn.remove());
                if (trangThai === 'Đang chờ duyệt' && (data.trang_thai_tt == 1 || data.trang_thai_tt == 3)) {
                    actionCell.insertAdjacentHTML('beforeend', `
                        <button type="button"
                            class="btn-update-status bg-emerald-50 text-emerald-700 p-1.5 rounded hover:bg-emerald-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Đã duyệt"
                            title="Duyệt đơn hàng">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button"
                            class="btn-update-status bg-rose-50 text-rose-700 p-1.5 rounded hover:bg-rose-100 transition-colors"
                            data-id="${btn.dataset.id}"
                            data-status="Đã hủy"
                            title="Hủy đơn hàng">
                            <i class="fas fa-times"></i>
                        </button>
                    `);
                }
                actionCell.querySelectorAll('.btn-update-status').forEach(function(newBtn) {
                    newBtn.addEventListener('click', handleUpdateStatusClick);
                });
            }

            showNotification('Cập nhật trạng thái thanh toán thành công!', 'success');
        } else {
            alert(data.msg || 'Có lỗi xảy ra!');
        }
    })
    .catch(() => alert('Lỗi kết nối!'));
}

// Xem chi tiết đơn hàng - VIETNAMESE FONT SUPPORT
function viewOrderDetail(orderId) {
    console.log('Requesting order detail for ID:', orderId);
    
    const modal = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');
    
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <span class="ml-2 text-slate-600">Đang tải...</span>
        </div>
    `;
    
    modal.classList.remove('hidden');

    fetch('ajax_get_order_detail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${orderId}`
    })
    .then(response => response.text())
    .then(text => {
        console.log('Raw response:', text);
        
        let data;
        try {
            data = JSON.parse(text.trim());
        } catch (e) {
            throw new Error('Invalid JSON response');
        }
        
        if (data.success && data.data) {
    const order = data.data.order;
    const products = data.data.products;
    const history = data.data.history;
    let historyHtml = '';
    if (history && history.length > 0) {
        historyHtml = `
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-lg p-4 border border-slate-200 mt-6">
                <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-history text-orange-600 mr-2"></i>
                    Lịch sử trạng thái đơn hàng
                </h4>
                <ul class="space-y-3">
                    ${history.map(item => `
                        <li class="flex items-center">
                            <span class="inline-block w-40 text-sm text-slate-500">
                                ${new Date(item.thoi_gian_thay_doi).toLocaleString('vi-VN')}
                            </span>
                            <span class="mx-2 text-xs text-slate-700">
                                <b>${item.trang_thai_cu}</b> &rarr; <b>${item.trang_thai_moi}</b>
                            </span>
                            <span class="ml-2 text-xs text-slate-600 italic">
                                (${item.nguoi_thay_doi})
                            </span>
                            ${item.ghi_chu ? `<span class="ml-2 text-xs text-purple-600">[${item.ghi_chu}]</span>` : ''}
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
    }
    
    // CHỈNH Ở ĐÂY: Chèn thêm historyHtml vào cuối content.innerHTML
    content.innerHTML = `
        <div class="space-y-6" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <!-- Thông tin đơn hàng -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                    Thông tin đơn hàng
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-slate-500">Mã đơn hàng:</span>
                        <p class="font-bold text-indigo-700">#${order.code}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Ngày tạo:</span>
                        <p class="font-medium text-slate-800">${new Date(order.ngay_tao).toLocaleString('vi-VN')}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Phương thức thanh toán:</span>
                        <p class="font-medium text-slate-800">${order.phuong_thuc_thanh_toan}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Tổng tiền:</span>
                        <p class="font-bold text-emerald-600 text-lg">${new Intl.NumberFormat('vi-VN').format(order.tong_tien)} VNĐ</p>
                    </div>
                </div>
                ${order.voucher_code ? `
                <div class="mt-3 pt-3 border-t border-blue-200">
                    <div class="flex items-center">
                        <i class="fas fa-ticket-alt text-purple-600 mr-2"></i>
                        <span class="text-sm text-slate-500">Voucher áp dụng:</span>
                        <span class="ml-2 bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm font-medium">
                            ${order.voucher_code}
                        </span>
                        ${order.voucher_discount ? `
                        <span class="ml-2 text-sm text-emerald-600 font-medium">
                            (-${new Intl.NumberFormat('vi-VN').format(order.voucher_discount)} VNĐ)
                        </span>
                        ` : ''}
                    </div>
                </div>
                ` : ''}
            </div>

            <!-- Thông tin khách hàng -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    Thông tin khách hàng
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-slate-500">Tên khách hàng:</span>
                        <p class="font-medium text-slate-800">${order.ten_khach_hang}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Số điện thoại:</span>
                        <p class="font-medium text-slate-800">${order.so_dien_thoai}</p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-sm text-slate-500">Địa chỉ giao hàng:</span>
                        <p class="font-medium text-slate-800">${order.dia_chi}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">ID khách hàng:</span>
                        <p class="font-medium text-green-700">#${order.id_kh || 'Khách vãng lai'}</p>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-200">
                <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                    <i class="fas fa-shopping-bag text-yellow-600 mr-2"></i>
                    Danh sách sản phẩm (${products.length} sản phẩm)
                </h4>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-white/70 text-slate-700 text-sm">
                            <tr>
                                <th class="py-2 px-3 text-left rounded-l-lg">Hình ảnh</th>
                                <th class="py-2 px-3 text-left">Tên sản phẩm</th>
                                <th class="py-2 px-3 text-center">Số lượng</th>
                                <th class="py-2 px-3 text-right">Đơn giá</th>
                                <th class="py-2 px-3 text-right rounded-r-lg">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-yellow-200">
                            ${products.map(product => `
                            <tr class="hover:bg-white/50 transition-colors">
                                <td class="py-3 px-3">
                                    <img src="uploads/${product.hinhanh}" alt="${product.tenhanghoa}" 
                                         class="w-12 h-12 object-cover rounded-lg border border-yellow-300"
                                         onerror="this.src='https://via.placeholder.com/48x48?text=N/A'">
                                </td>
                                <td class="py-3 px-3">
                                    <p class="font-medium text-slate-800 line-clamp-2">${product.tenhanghoa}</p>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="bg-white px-2 py-1 rounded-full text-sm font-medium">${product.so_luong}</span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <span class="font-medium text-slate-800">${new Intl.NumberFormat('vi-VN').format(product.gia)} VNĐ</span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <span class="font-bold text-emerald-600">${new Intl.NumberFormat('vi-VN').format(product.so_luong * product.gia)} VNĐ</span>
                                </td>
                            </tr>
                            `).join('')}
                        </tbody>
                        <tfoot class="bg-white/70">
                            <tr>
                                <td colspan="4" class="py-3 px-3 text-right font-bold text-slate-800">Tổng cộng:</td>
                                <td class="py-3 px-3 text-right font-bold text-emerald-600 text-lg">
                                    ${new Intl.NumberFormat('vi-VN').format(order.tong_tien)} VNĐ
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Chèn lịch sử trạng thái tại đây -->
            ${historyHtml}
        </div>
    `;
} else {
    content.innerHTML = `
        <div class="text-center py-8 text-slate-500" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <i class="fas fa-exclamation-triangle text-4xl mb-3 text-red-400"></i>
            <p class="font-medium">Không thể tải thông tin chi tiết đơn hàng</p>
            <p class="text-sm mt-1">${data.message || 'Vui lòng thử lại sau'}</p>
        </div>
    `;
}
        
    })
    .catch(error => {
        console.error('Fetch error:', error);
        content.innerHTML = `
            <div class="text-center py-8 text-slate-500" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <i class="fas fa-exclamation-triangle text-4xl mb-3 text-red-400"></i>
                <p class="font-medium">Có lỗi xảy ra khi tải dữ liệu</p>
                <p class="text-sm mt-1">${error.message}</p>
                <button onclick="viewOrderDetail(${orderId})" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Thử lại
                </button>
            </div>
            ${historyHtml}
        `;
    });
}

// Đóng modal chi tiết đơn hàng
function closeOrderDetailModal() {
    document.getElementById('orderDetailModal').classList.add('hidden');
}

// Đóng modal khi click outside
document.getElementById('orderDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOrderDetailModal();
    }
});
</script>
<script src="script.js"></script>
