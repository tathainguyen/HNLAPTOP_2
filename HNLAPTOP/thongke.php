<?php
require_once __DIR__ . '/mod/config.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Kiểm tra trạng thái phiên trước khi khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu không có session hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

// Lấy năm được chọn từ request, mặc định là năm hiện tại
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Truy vấn tổng số lượng
$sqlCounts = [
    'hanghoa' => "SELECT COUNT(*) AS total FROM hanghoa",
    'loaihang' => "SELECT COUNT(*) AS total FROM loaihang",
    'users' => "SELECT COUNT(*) AS total FROM users",
    'donhang' => "SELECT COUNT(*) AS total FROM donhang",
];

$counts = [];
foreach ($sqlCounts as $key => $sql) {
    $result = $conn->query($sql);
    $counts[$key] = $result ? $result->fetch_assoc()['total'] : 0;
}

// Truy vấn để lấy danh sách các năm có dữ liệu
$sqlYears = "SELECT DISTINCT YEAR(ngay_tao) as year FROM donhang WHERE trang_thai = 'Giao hàng thành công' ORDER BY year DESC";
$resultYears = $conn->query($sqlYears);
$availableYears = [];
while ($row = $resultYears->fetch_assoc()) {
    $availableYears[] = (int)$row['year'];
}

// Nếu không có năm nào trong DB, thêm năm hiện tại
if (empty($availableYears)) {
    $availableYears[] = date('Y');
}

// Truy vấn doanh thu theo tháng cho năm được chọn
$sqlDoanhThu = "SELECT MONTH(ngay_tao) AS thang, SUM(tong_tien) AS doanh_thu 
                FROM donhang 
                WHERE trang_thai = 'Giao hàng thành công' AND YEAR(ngay_tao) = ?
                GROUP BY thang ORDER BY thang ASC";
$stmt = $conn->prepare($sqlDoanhThu);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

// Tạo mảng mặc định (12 tháng, doanh thu = 0)
$doanhThuThang = array_fill(1, 12, 0);
while ($row = $result->fetch_assoc()) {
    $doanhThuThang[(int)$row['thang']] = $row['doanh_thu'];
}

// Tính toán các số liệu thống kê
$tongDoanhThu = array_sum($doanhThuThang);
$thangCaoNhat = array_keys($doanhThuThang, max($doanhThuThang))[0];
$filtered = array_filter($doanhThuThang);
if (!empty($filtered)) {
    $thangThapNhat = array_keys($doanhThuThang, min($filtered))[0];
} else {
    $thangThapNhat = 0;
}
$doanhThuCaoNhat = max($doanhThuThang);
$doanhThuThapNhat = !empty(array_filter($doanhThuThang)) ? min(array_filter($doanhThuThang)) : 0;
$doanhThuTrungBinh = $tongDoanhThu / 12;

// Tính số liệu tăng trưởng so với năm trước
$previousYear = $selectedYear - 1;
$sqlPreviousYearRevenue = "SELECT SUM(tong_tien) as total FROM donhang WHERE trang_thai = 'Giao hàng thành công' AND YEAR(ngay_tao) = ?";
$stmtPrev = $conn->prepare($sqlPreviousYearRevenue);
$stmtPrev->bind_param("i", $previousYear);
$stmtPrev->execute();
$resultPrev = $stmtPrev->get_result();
$doanhThuNamTruoc = $resultPrev->fetch_assoc()['total'] ?? 0;

$tangTruong = $doanhThuNamTruoc > 0 ? 
    (($tongDoanhThu - $doanhThuNamTruoc) / $doanhThuNamTruoc) * 100 : 0;

$conn->close();
?>

<!-- Thêm thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="animate__animated animate__fadeIn">
    <!-- Thống kê tổng quan -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card Hàng hóa -->
            <a href="quanly.php?page=hanghoa.php" class="block">
                <div class="p-6 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-laptop text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Hàng hóa</p>
                            <h3 class="text-white text-3xl font-bold"><?= $counts['hanghoa'] ?></h3>
                        </div>
                    </div>
                    <div class="flex items-center mt-4 text-indigo-100">
                        <i class="fas fa-box mr-2"></i>
                        <span class="text-sm">Sản phẩm đang kinh doanh</span>
                    </div>
                </div>
            </a>

            <!-- Card Loại hàng -->
            <a href="quanly.php?page=loaihang.php" class="block">
                <div class="p-6 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-tags text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Loại hàng</p>
                            <h3 class="text-white text-3xl font-bold"><?= $counts['loaihang'] ?></h3>
                        </div>
                    </div>
                    <div class="flex items-center mt-4 text-emerald-100">
                        <i class="fas fa-layer-group mr-2"></i>
                        <span class="text-sm">Danh mục sản phẩm</span>
                    </div>
                </div>
            </a>

            <!-- Card Tài khoản -->
            <a href="quanly.php?page=quanlytaikhoan.php" class="block">
                <div class="p-6 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Tài khoản</p>
                            <h3 class="text-white text-3xl font-bold"><?= $counts['users'] ?></h3>
                        </div>
                    </div>
                    <div class="flex items-center mt-4 text-amber-100">
                        <i class="fas fa-user-friends mr-2"></i>
                        <span class="text-sm">Khách hàng đã đăng ký</span>
                    </div>
                </div>
            </a>

            <!-- Card Đơn hàng -->
            <a href="quanly.php?page=quanly_donhang.php" class="block">
                <div class="p-6 bg-gradient-to-r from-rose-500 to-pink-600 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-cart text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-rose-100 text-sm font-medium">Đơn hàng</p>
                            <h3 class="text-white text-3xl font-bold"><?= $counts['donhang'] ?></h3>
                        </div>
                    </div>
                    <div class="flex items-center mt-4 text-rose-100">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        <span class="text-sm">Đơn hàng đã nhận</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Tóm tắt doanh thu -->
    <div class="mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Card Tổng doanh thu -->
            <div class="bg-white rounded-xl shadow-md border border-slate-200/60 p-6 animate__animated animate__fadeInLeft">
                <div class="flex items-center">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-dollar-sign text-indigo-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Tổng doanh thu năm <?= $selectedYear ?></p>
                        <h3 class="text-slate-800 text-3xl font-bold font-heading"><?= number_format($tongDoanhThu, 0, ',', '.') ?> VND</h3>
                    </div>
                </div>
                <div class="mt-4 flex justify-between">
                    <div class="text-sm">
                        <span class="text-slate-500">Doanh thu TB/tháng:</span>
                        <span class="font-medium text-slate-800"><?= number_format($doanhThuTrungBinh, 0, ',', '.') ?> VND</span>
                    </div>
                    <div class="text-sm flex items-center <?= $tangTruong >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <i class="fas fa-<?= $tangTruong >= 0 ? 'arrow-up' : 'arrow-down' ?> mr-1"></i>
                        <span><?= number_format(abs($tangTruong), 1) ?>% so với năm <?= $previousYear ?></span>
                    </div>
                </div>
            </div>

            <!-- Card Tháng có doanh thu cao nhất -->
            <div class="bg-white rounded-xl shadow-md border border-slate-200/60 p-6 animate__animated animate__fadeInUp">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Tháng doanh thu cao nhất</p>
                        <h3 class="text-slate-800 text-3xl font-bold font-heading">Tháng <?= $thangCaoNhat ?>/<?= $selectedYear ?></h3>
                    </div>
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-emerald-600 text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="bg-slate-100 rounded-lg p-3 flex justify-between items-center">
                        <span class="text-slate-600">Doanh thu:</span>
                        <span class="font-bold text-emerald-600"><?= number_format($doanhThuCaoNhat, 0, ',', '.') ?> VND</span>
                    </div>
                </div>
            </div>

            <!-- Card Tháng có doanh thu thấp nhất -->
            <div class="bg-white rounded-xl shadow-md border border-slate-200/60 p-6 animate__animated animate__fadeInRight">
                <div class="flex justify-between mb-4">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Tháng doanh thu thấp nhất</p>
                        <h3 class="text-slate-800 text-3xl font-bold font-heading">Tháng <?= $thangThapNhat ?: 'N/A' ?>/<?= $selectedYear ?></h3>
                    </div>
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-area text-amber-600 text-2xl"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="bg-slate-100 rounded-lg p-3 flex justify-between items-center">
                        <span class="text-slate-600">Doanh thu:</span>
                        <span class="font-bold text-amber-600"><?= number_format($doanhThuThapNhat, 0, ',', '.') ?> VND</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="mb-6 animate__animated animate__fadeIn">
        <div class="bg-white rounded-xl shadow-md border border-slate-200/60 p-6">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-slate-800 text-xl font-bold font-heading">Biểu đồ doanh thu theo tháng - Năm <?= $selectedYear ?></h4>
                <div class="flex gap-3">
                    <!-- Dropdown chọn năm -->
                    <select id="yearSelect" class="form-select py-2 px-3 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                        <?php foreach ($availableYears as $year): ?>
                            <option value="<?= $year ?>" <?= $year == $selectedYear ? 'selected' : '' ?>>
                                Năm <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <!-- Dropdown chọn loại biểu đồ -->
                    <select id="chartType" class="form-select py-2 px-3 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm">
                        <option value="bar">Biểu đồ cột</option>
                        <option value="line">Biểu đồ đường</option>
                    </select>
                </div>
            </div>
            <div>
                <canvas id="doanhThuChart" class="w-full h-80"></canvas>
            </div>
        </div>
    </div>

    <!-- Doanh thu từng tháng -->
    <div class="animate__animated animate__fadeIn">
        <div class="bg-white rounded-xl shadow-md border border-slate-200/60 p-6">
            <h4 class="text-slate-800 text-xl font-bold font-heading mb-6">Doanh thu chi tiết từng tháng - Năm <?= $selectedYear ?></h4>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 transition-all hover:shadow-md hover:border-indigo-300">
                    <div class="text-center">
                        <div class="w-12 h-12 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-2">
                            <span class="text-indigo-600 font-bold"><?= $i ?></span>
                        </div>
                        <p class="text-slate-600 text-sm font-medium">Tháng <?= $i ?></p>
                        <h5 class="text-slate-800 font-bold mt-1"><?= number_format($doanhThuThang[$i], 0, ',', '.') ?></h5>
                        <p class="text-xs text-slate-500">VND</p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPalette = {
            primary: '#6366f1', // indigo-500
            primaryLight: 'rgba(99, 102, 241, 0.2)',
            secondary: '#8b5cf6', // violet-500
            accent: '#ec4899', // pink-500
            success: '#10b981', // emerald-500
            warning: '#f59e0b', // amber-500
            danger: '#ef4444', // red-500
        };
        
        // Dữ liệu doanh thu các tháng
        const doanhThuData = <?= json_encode(array_values($doanhThuThang)) ?>;
        const labels = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        
        // Cấu hình biểu đồ
        const ctx = document.getElementById('doanhThuChart').getContext('2d');
        let chartConfig = {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: doanhThuData,
                    backgroundColor: colorPalette.primaryLight,
                    borderColor: colorPalette.primary,
                    borderWidth: 2,
                    borderRadius: 6,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1e293b',
                        bodyColor: '#1e293b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return `Doanh thu: ${value.toLocaleString('vi-VN')} VND`;
                            }
                        }
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false } 
                    },
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VND';
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        };
        
        // Tạo biểu đồ
        let doanhThuChart = new Chart(ctx, chartConfig);
        
        // Xử lý khi thay đổi năm
        document.getElementById('yearSelect').addEventListener('change', function() {
            const selectedYear = this.value;
            // Reload trang với năm được chọn
            const url = new URL(window.location);
            url.searchParams.set('year', selectedYear);
            window.location.href = url.toString();
        });
        
        // Xử lý khi thay đổi loại biểu đồ
        document.getElementById('chartType').addEventListener('change', function() {
            const chartType = this.value;
            doanhThuChart.destroy();
            
            chartConfig.type = chartType;
            
            // Điều chỉnh cấu hình dựa trên loại biểu đồ
            if (chartType === 'line') {
                chartConfig.data.datasets[0].backgroundColor = colorPalette.primaryLight;
                chartConfig.data.datasets[0].fill = true;
                chartConfig.data.datasets[0].pointBackgroundColor = colorPalette.primary;
                chartConfig.data.datasets[0].pointRadius = 4;
                chartConfig.data.datasets[0].pointHoverRadius = 6;
            } else {
                chartConfig.data.datasets[0].backgroundColor = colorPalette.primaryLight;
                chartConfig.data.datasets[0].borderRadius = 6;
            }
            
            doanhThuChart = new Chart(ctx, chartConfig);
        });
    });
</script>