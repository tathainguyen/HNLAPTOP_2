<?php
// Kiểm tra trạng thái phiên trước khi khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu không có session hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php"); // Chuyển hướng về trang đăng nhập nếu không phải admin
    exit();
}
require 'mod/loaihang.php';
require 'mod/hanghoa.php';

// Xử lý cập nhật AJAX
$lh = new LoaiHang();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_id"])) {
    $id = intval($_POST["edit_id"]);
    $tenloaihang = trim($_POST["tenloaihang"]);
    $mota = trim($_POST["mota"]);

    if (!empty($tenloaihang) && !empty($mota)) {
        $result = $lh->update($id, $tenloaihang, $mota);
        echo $result ? "success" : "Lỗi khi cập nhật!";
    } else {
        echo "Tên loại hàng và mô tả không được để trống!";
    }
    exit();
}

// Lấy danh sách loại hàng
$list = $lh->getAll();

// Đếm số sản phẩm trong mỗi loại
$hh = new HangHoa();
$allProducts = $hh->getAll();
$countByCategory = [];

foreach ($list as $l) {
    $countByCategory[$l->idloaihang] = 0;
}

foreach ($allProducts as $product) {
    if (isset($countByCategory[$product->idloaihang])) {
        $countByCategory[$product->idloaihang]++;
    }
}

// Tính tổng sản phẩm
$totalProducts = count($allProducts);

// Chuẩn bị dữ liệu cho biểu đồ
$chartLabels = [];
$chartData = [];
$chartColors = [];

// Các màu đẹp cho biểu đồ
$colorPalette = [
    'rgba(99, 102, 241, 0.7)',   // indigo
    'rgba(16, 185, 129, 0.7)',   // emerald
    'rgba(245, 158, 11, 0.7)',   // amber
    'rgba(236, 72, 153, 0.7)',   // pink
    'rgba(14, 165, 233, 0.7)',   // sky
    'rgba(168, 85, 247, 0.7)',   // purple
    'rgba(239, 68, 68, 0.7)',    // red
    'rgba(34, 197, 94, 0.7)',    // green
    'rgba(59, 130, 246, 0.7)',   // blue
    'rgba(249, 115, 22, 0.7)',   // orange
];

$colorIndex = 0;
foreach ($list as $l) {
    $chartLabels[] = $l->tenloaihang;
    $chartData[] = $countByCategory[$l->idloaihang];
    $chartColors[] = $colorPalette[$colorIndex % count($colorPalette)];
    $colorIndex++;
}
?>

<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê nhanh -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Quản lý loại hàng</h2>
            <p class="text-slate-500">Quản lý các danh mục sản phẩm trong hệ thống</p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center bg-indigo-50 text-indigo-700 rounded-lg px-4 py-2">
            <i class="fas fa-tags mr-2"></i>
            <span class="font-medium">Tổng số: <?= count($list) ?> loại hàng</span>
        </div>
    </div>

    <!-- Thẻ thống kê nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Tổng loại hàng -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-tags text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Tổng loại hàng</p>
                    <h3 class="text-slate-800 text-3xl font-bold"><?= count($list) ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-slate-600">
                    <i class="fas fa-info-circle mr-1 text-indigo-500"></i>
                    Các danh mục đang hiển thị trong cửa hàng
                </div>
            </div>
        </div>

        <!-- Tổng sản phẩm -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-laptop text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Tổng sản phẩm</p>
                    <h3 class="text-slate-800 text-3xl font-bold"><?= $totalProducts ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-slate-600">
                    <i class="fas fa-info-circle mr-1 text-emerald-500"></i>
                    Số lượng sản phẩm đang kinh doanh
                </div>
            </div>
        </div>

        <!-- Trung bình sản phẩm/loại -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">TB sản phẩm/loại</p>
                    <h3 class="text-slate-800 text-3xl font-bold"><?= count($list) ? round($totalProducts / count($list), 1) : 0 ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-sm text-slate-600">
                    <i class="fas fa-info-circle mr-1 text-amber-500"></i>
                    Số sản phẩm trung bình mỗi danh mục
                </div>
            </div>
        </div>
    </div>

    <!-- Grid layout cho form thêm mới và bảng dữ liệu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Form thêm loại hàng mới -->
        <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6">
            <h3 class="text-lg font-medium text-slate-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-indigo-600 mr-2"></i> Thêm loại hàng mới
            </h3>
            
            <form method="post" action="actions/loaihang_act.php" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tên loại hàng <span class="text-rose-500">*</span></label>
                    <input type="text" name="tenloaihang" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả <span class="text-rose-500">*</span></label>
                    <textarea name="mota" rows="3" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
                    <i class="fas fa-save mr-2"></i> Thêm loại hàng
                </button>
            </form>

            <!-- Hướng dẫn -->
            <div class="mt-6 bg-blue-50 text-blue-800 p-3 rounded-lg text-sm">
                <div class="flex items-start">
                    <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                    <div>
                        <p class="font-medium">Lưu ý khi thêm loại hàng:</p>
                        <ul class="list-disc pl-4 mt-1 space-y-1">
                            <li>Tên loại hàng nên ngắn gọn, dễ nhớ</li>
                            <li>Mô tả chi tiết giúp khách hàng hiểu rõ hơn về loại sản phẩm</li>
                            <li>Không nên tạo quá nhiều loại hàng tương đồng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách loại hàng -->
        <div class="lg:col-span-2 bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-slate-800">Danh sách loại hàng</h3>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Tìm kiếm..." class="block w-full pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400"></i>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full" id="categoryTable">
                    <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Tên loại hàng</th>
                            <th class="py-3 px-4 text-left">Mô tả</th>
                            <th class="py-3 px-4 text-left">Sản phẩm</th>
                            <th class="py-3 px-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if (empty($list)): ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                        <p>Chưa có loại hàng nào</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($list as $l): ?>
                                <!-- Dòng hiển thị bình thường -->
                                <tr data-id="<?= $l->idloaihang ?>" class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 font-medium"><?= $l->idloaihang ?></td>
                                    <td class="py-3 px-4">
                                        <span class="view-mode font-medium text-indigo-700"><?= htmlspecialchars($l->tenloaihang) ?></span>
                                        <input type="text" class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 tenloaihang" value="<?= htmlspecialchars($l->tenloaihang) ?>">
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="view-mode text-slate-600"><?= htmlspecialchars($l->mota) ?></span>
                                        <textarea class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 mota"><?= htmlspecialchars($l->mota) ?></textarea>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <?= $countByCategory[$l->idloaihang] ?> sản phẩm
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button type="button" class="edit-btn bg-emerald-100 text-emerald-700 p-2 rounded-lg hover:bg-emerald-200 transition-colors" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="save-btn hidden bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition-colors" title="Lưu">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="button" class="cancel-btn hidden bg-slate-300 text-slate-700 p-2 rounded-lg hover:bg-slate-400 transition-colors" title="Hủy">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <a href="actions/loaihang_act.php?delete=<?= $l->idloaihang ?>" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa loại hàng này? Việc này có thể ảnh hưởng đến các sản phẩm thuộc loại này.');"
                                               class="bg-rose-100 text-rose-700 p-2 rounded-lg hover:bg-rose-200 transition-colors" 
                                               title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Biểu đồ phân bố sản phẩm -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6 mb-6">
        <h3 class="text-lg font-medium text-slate-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-indigo-600 mr-2"></i> Phân bố sản phẩm theo loại hàng
        </h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="h-64">
                <canvas id="categoryPieChart"></canvas>
            </div>
            <div class="h-64">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Bảng thống kê chi tiết -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg font-medium text-slate-800">Chi tiết số lượng sản phẩm theo loại</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($list as $l): ?>
                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200/80">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-medium text-indigo-700"><?= htmlspecialchars($l->tenloaihang) ?></span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <?= $countByCategory[$l->idloaihang] ?> sản phẩm
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mb-2">
                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: <?= $totalProducts > 0 ? ($countByCategory[$l->idloaihang] / $totalProducts) * 100 : 0 ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>0%</span>
                            <span><?= $totalProducts > 0 ? round(($countByCategory[$l->idloaihang] / $totalProducts) * 100, 1) : 0 ?>%</span>
                            <span>100%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Thêm thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chức năng tìm kiếm
    const searchInput = document.getElementById('searchInput');
    const categoryTable = document.getElementById('categoryTable');
    const rows = categoryTable.querySelectorAll('tbody tr');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = searchInput.value.toLowerCase();
        
        rows.forEach(function(row) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Chức năng sửa và lưu
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let row = btn.closest("tr");
            let id = row.getAttribute("data-id");

            // Hiển thị form chỉnh sửa
            row.querySelectorAll(".view-mode").forEach(el => el.classList.add("hidden"));
            row.querySelectorAll(".edit-mode").forEach(el => el.classList.remove("hidden"));

            // Ẩn nút chỉnh sửa, hiển thị nút lưu và hủy
            row.querySelector(".edit-btn").classList.add("hidden");
            row.querySelector(".save-btn").classList.remove("hidden");
            row.querySelector(".cancel-btn").classList.remove("hidden");
        });
    });

    document.querySelectorAll(".save-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let row = btn.closest("tr");
            let id = row.getAttribute("data-id");
            let tenloaihang = row.querySelector(".tenloaihang").value;
            let mota = row.querySelector(".mota").value;

            // Kiểm tra dữ liệu nhập
            if (!tenloaihang || !mota) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            // Gửi dữ liệu qua AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "loaihang.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.responseText === "success") {
                        // Cập nhật giao diện
                        row.querySelector("td:nth-child(2) .view-mode").textContent = tenloaihang;
                        row.querySelector("td:nth-child(3) .view-mode").textContent = mota;
                        
                        // Ẩn form chỉnh sửa
                        row.querySelectorAll(".edit-mode").forEach(el => el.classList.add("hidden"));
                        row.querySelectorAll(".view-mode").forEach(el => el.classList.remove("hidden"));
                        
                        // Ẩn nút lưu và hủy, hiển thị lại nút chỉnh sửa
                        row.querySelector(".save-btn").classList.add("hidden");
                        row.querySelector(".cancel-btn").classList.add("hidden");
                        row.querySelector(".edit-btn").classList.remove("hidden");
                        
                        // Hiển thị thông báo thành công
                        showNotification("Cập nhật loại hàng thành công!", "success");
                    } else {
                        alert("Lỗi: " + this.responseText);
                    }
                }
            };
            xhr.send(`edit_id=${id}&tenloaihang=${encodeURIComponent(tenloaihang)}&mota=${encodeURIComponent(mota)}`);
        });
    });
    
    document.querySelectorAll(".cancel-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let row = btn.closest("tr");
            
            // Khôi phục giá trị ban đầu
            let tenloaihangOriginal = row.querySelector("td:nth-child(2) .view-mode").textContent.trim();
            let motaOriginal = row.querySelector("td:nth-child(3) .view-mode").textContent.trim();
            
            row.querySelector(".tenloaihang").value = tenloaihangOriginal;
            row.querySelector(".mota").value = motaOriginal;
            
            // Ẩn form chỉnh sửa
            row.querySelectorAll(".edit-mode").forEach(el => el.classList.add("hidden"));
            row.querySelectorAll(".view-mode").forEach(el => el.classList.remove("hidden"));
            
            // Ẩn nút lưu và hủy, hiển thị lại nút chỉnh sửa
            row.querySelector(".save-btn").classList.add("hidden");
            row.querySelector(".cancel-btn").classList.add("hidden");
            row.querySelector(".edit-btn").classList.remove("hidden");
        });
    });

    // Biểu đồ tròn thống kê loại hàng
    const ctxPie = document.getElementById('categoryPieChart').getContext('2d');
    const categoryPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartData) ?>,
                backgroundColor: <?= json_encode($chartColors) ?>,
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    titleColor: '#1e293b',
                    bodyColor: '#1e293b',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.formattedValue;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((context.raw / total) * 100);
                            return `${label}: ${value} sản phẩm (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true
            }
        }
    });
    
    // Biểu đồ cột thống kê loại hàng
    const ctxBar = document.getElementById('categoryBarChart').getContext('2d');
    const categoryBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Số lượng sản phẩm',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: <?= json_encode($chartColors) ?>,
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1,
                borderRadius: 4
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
                    callbacks: {
                        label: function(context) {
                            let value = context.parsed.y;
                            return `${value} sản phẩm`;
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
                        stepSize: 1
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });
    
    // Hàm hiển thị thông báo
    function showNotification(message, type = "info") {
        // Tạo phần tử thông báo
        const notification = document.createElement("div");
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-opacity duration-300 animate__animated animate__fadeInRight ${type === 'success' ? 'bg-emerald-100 text-emerald-800 border-l-4 border-emerald-500' : 'bg-blue-100 text-blue-800 border-l-4 border-blue-500'}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium">${message}</p>
                </div>
            </div>
        `;
        document.body.appendChild(notification);
        
        // Tự động ẩn thông báo sau 3 giây
        setTimeout(() => {
            notification.classList.add("animate__fadeOutRight");
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 500);
        }, 3000);
    }
});
</script>