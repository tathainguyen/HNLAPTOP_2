<?php
// Đầu file - đảm bảo không có whitespace trước <?php

require_once 'mod/config.php';
require_once 'mod/hanghoa.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check quyền admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit;
}

// Xử lý AJAX lấy chi tiết đánh giá
// Xử lý AJAX lấy chi tiết đánh giá - SIMPLE VERSION
if (isset($_POST['action']) && $_POST['action'] == 'get_detail' && isset($_POST['id'])) {
    if (ob_get_level()) {
        ob_clean();
    }
    
    header('Content-Type: application/json; charset=utf-8');
    
    $id = intval($_POST['id']);
    
    try {
        // Query đơn giản chỉ lấy tenhanghoa và ho_va_ten
        $stmt = $pdo->prepare("
            SELECT 
                d.*,
                h.tenhanghoa,
                u.ho_va_ten as full_name
            FROM danhgia d 
            LEFT JOIN hanghoa h ON d.idhanghoa = h.idhanghoa 
            LEFT JOIN users u ON d.id_kh = u.id 
            WHERE d.id = ?
        ");
        $stmt->execute([$id]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($review) {
            // Đảm bảo encoding UTF-8
            foreach ($review as $key => $value) {
                if (is_string($value)) {
                    $review[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
                }
            }
            
            $response = [
                'success' => true,
                'data' => $review
            ];
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đánh giá với ID: ' . $id]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $e->getMessage()]);
    }
    
    exit;
}
// Xử lý AJAX XÓA đánh giá
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['id'])) {
    if (ob_get_level()) {
        ob_clean();
    }
    error_reporting(0);
    header('Content-Type: application/json');
    
    $id = intval($_POST['id']);
    try {
        $stmt = $pdo->prepare("SELECT image FROM danhgia WHERE id=?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['image'] && file_exists($row['image'])) {
            unlink($row['image']);
        }
        $pdo->prepare("DELETE FROM danhgia WHERE id=?")->execute([$id]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Phân trang
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 10; // Số mục trên mỗi trang
$offset = ($page - 1) * $limit;

// Tìm kiếm và lọc
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$ratingFilter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;

// Xây dựng điều kiện lọc
$whereConditions = [];
$params = [];

if (!empty($searchTerm)) {
    $whereConditions[] = "(h.tenhanghoa LIKE ? OR d.username LIKE ? OR d.comment LIKE ?)";
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
}

if ($ratingFilter > 0 && $ratingFilter <= 5) {
    $whereConditions[] = "d.rating = ?";
    $params[] = $ratingFilter;
}

// Tạo mệnh đề WHERE
$whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";

// Đếm tổng số đánh giá cho từng rating
$ratingStats = [];
for ($i = 1; $i <= 5; $i++) {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM danhgia WHERE rating = ?");
    $countStmt->execute([$i]);
    $ratingStats[$i] = $countStmt->fetchColumn();
}

// Đếm tổng số đánh giá
$countSql = "SELECT COUNT(*) FROM danhgia d LEFT JOIN hanghoa h ON d.idhanghoa = h.idhanghoa $whereClause";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $limit);

// Lấy danh sách đánh giá (join tên sản phẩm)
$sql = "SELECT d.id, d.idhanghoa, h.tenhanghoa, d.username, d.rating, d.comment, d.image, d.created_at 
        FROM danhgia d 
        LEFT JOIN hanghoa h ON d.idhanghoa = h.idhanghoa
        $whereClause
        ORDER BY d.created_at DESC
        LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính điểm đánh giá trung bình
$avgStmt = $pdo->query("SELECT AVG(rating) FROM danhgia");
$avgRating = round($avgStmt->fetchColumn(), 1);

// Tổng số đánh giá
$totalStmt = $pdo->query("SELECT COUNT(*) FROM danhgia");
$totalReviews = $totalStmt->fetchColumn();
?>

<link rel="stylesheet" href="style.css?<?php echo time(); ?>">
<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 mb-6 text-white overflow-hidden relative">
        <div class="absolute right-0 top-0 opacity-10">
            <i class="fas fa-star text-9xl transform translate-x-6 -translate-y-6"></i>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="z-10">
                <h2 class="text-2xl font-bold font-heading mb-1">Quản lý đánh giá sản phẩm</h2>
                <p class="text-indigo-100">Quản lý tất cả đánh giá từ khách hàng về sản phẩm</p>
            </div>
            <div class="mt-4 md:mt-0 z-10 flex space-x-3">
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-star mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Đánh giá TB</div>
                        <div class="font-bold"><?= $avgRating ?>/5.0</div>
                    </div>
                </div>
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-comment-dots mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Tổng đánh giá</div>
                        <div class="font-bold"><?= $totalReviews ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê sao -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <?php for ($i = 5; $i >= 1; $i--): ?>
            <div class="bg-gradient-to-br from-white to-indigo-50 rounded-xl shadow-sm border border-indigo-100/60 p-4 hover:shadow transition-all duration-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-800"><?= isset($ratingStats[$i]) ? $ratingStats[$i] : 0 ?></h3>
                        <div class="flex text-indigo-500">
                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                <i class="fas fa-star <?= $j <= $i ? 'text-indigo-400' : 'text-slate-300' ?> text-xs"></i>
                            <?php endfor; ?>
                            <span class="text-xs text-slate-500 ml-1 font-medium"><?= $i ?> sao</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Tìm kiếm -->
            <div class="md:flex-1">
                <form action="" method="GET" class="flex">
                    <input type="hidden" name="page" value="admin_danhgia.php">
                    <?php if ($ratingFilter): ?>
                        <input type="hidden" name="rating" value="<?= $ratingFilter ?>">
                    <?php endif; ?>
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>"
                            class="form-control w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pl-10 pr-4 py-2 shadow-sm"
                            placeholder="Tìm kiếm đánh giá...">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <button type="submit" class="ml-2 bg-indigo-600 text-white rounded-lg px-4 py-2 shadow-sm hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-search mr-1"></i> Tìm
                    </button>
                </form>
            </div>

            <!-- Lọc theo số sao -->
            <div class="md:w-1/4">
                <form action="" method="GET" id="ratingFilterForm" class="flex">
                    <input type="hidden" name="page" value="admin_danhgia.php">
                    <?php if (!empty($searchTerm)): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                    <?php endif; ?>
                    <select name="rating" class="form-select w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2.5 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Lọc theo số sao --</option>
                        <option value="5" <?= $ratingFilter === 5 ? 'selected' : '' ?>>5 sao</option>
                        <option value="4" <?= $ratingFilter === 4 ? 'selected' : '' ?>>4 sao</option>
                        <option value="3" <?= $ratingFilter === 3 ? 'selected' : '' ?>>3 sao</option>
                        <option value="2" <?= $ratingFilter === 2 ? 'selected' : '' ?>>2 sao</option>
                        <option value="1" <?= $ratingFilter === 1 ? 'selected' : '' ?>>1 sao</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Hiển thị bộ lọc đang áp dụng -->
        <?php if (!empty($searchTerm) || $ratingFilter): ?>
            <div class="mt-4 pt-3 border-t border-slate-200 flex flex-wrap items-center">
                <span class="text-slate-600 mr-2 text-sm">Đang lọc:</span>
                <?php if (!empty($searchTerm)): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2 mb-2 animate__animated animate__fadeIn">
                        <i class="fas fa-search mr-1.5"></i> <?= htmlspecialchars($searchTerm) ?>
                    </span>
                <?php endif; ?>

                <?php if ($ratingFilter): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-2 mb-2 animate__animated animate__fadeIn">
                        <i class="fas fa-star mr-1.5"></i> <?= $ratingFilter ?> sao
                    </span>
                <?php endif; ?>

                <!-- Nút xóa bộ lọc -->
                <a href="?page=admin_danhgia.php" class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium text-slate-600 bg-slate-100 rounded-full hover:bg-slate-200 transition-colors">
                    <i class="fas fa-times-circle mr-1"></i> Xóa bộ lọc
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bảng đánh giá -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h3 class="font-bold text-slate-800 flex items-center">
                <i class="fas fa-star text-indigo-600 mr-2"></i> Danh sách đánh giá
            </h3>
            <div class="text-sm text-slate-500 mt-2 sm:mt-0">
                Hiển thị <?= min($limit, count($reviews)) ?> / <?= $totalItems ?> đánh giá
            </div>
        </div>

        <div class="overflow-x-auto">
            <?php if (count($reviews) > 0): ?>
                <table class="w-full">
                    <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                        <tr>
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Sản phẩm</th>
                            <th class="py-3 px-4 text-left">Người dùng</th>
                            <th class="py-3 px-4 text-center">Đánh giá</th>
                            <th class="py-3 px-4 text-left">Bình luận</th>
                            <th class="py-3 px-4 text-center">Hình ảnh</th>
                            <th class="py-3 px-4 text-left">Thời gian</th>
                            <th class="py-3 px-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php foreach ($reviews as $index => $review): ?>
                            <tr id="row-<?= $review['id'] ?>" class="hover:bg-slate-50 transition-colors animate__animated animate__fadeIn animate__faster"
                                style="animation-delay: <?= $index * 0.05 ?>s">
                                <td class="py-3 px-4 font-medium"><?= $review['id'] ?></td>
                                <td class="py-3 px-4 font-medium text-slate-700 max-w-[200px] truncate">
                                    <a href="chitietsanpham.php?id=<?= $review['idhanghoa'] ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline" target="_blank" title="<?= htmlspecialchars($review['tenhanghoa'] ?? 'Sản phẩm đã xóa') ?>">
                                        <?= htmlspecialchars($review['tenhanghoa'] ?? 'Sản phẩm đã xóa') ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-700">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold mr-2">
                                            <?= strtoupper(substr($review['username'], 0, 1)) ?>
                                        </div>
                                        <span><?= htmlspecialchars($review['username']) ?></span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star text-<?= $i <= $review['rating'] ? 'indigo' : 'slate' ?>-400"></i>
                                        <?php endfor; ?>
                                        <span class="ml-2 bg-indigo-100 text-indigo-800 text-xs rounded-full px-2 py-0.5"><?= $review['rating'] ?>/5</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 max-w-[250px] text-slate-600">
                                    <div class="line-clamp-2" title="<?= htmlspecialchars($review['comment']) ?>">
                                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php if ($review['image']): ?>
                                        <div class="flex justify-center">
                                            <img src="<?= htmlspecialchars($review['image']) ?>" alt="Hình ảnh đánh giá"
                                                class="h-16 w-16 object-cover rounded-lg shadow-sm">
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-400 italic text-xs">Không có hình ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    <div class="flex items-center">
                                        <i class="far fa-clock mr-1.5 text-slate-400"></i>
                                        <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="viewDetailReview(<?= $review['id'] ?>)"
                                            class="bg-blue-50 text-blue-700 p-2 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="confirmDelete(<?= $review['id'] ?>)"
                                            class="bg-rose-50 text-rose-700 p-2 rounded-lg hover:bg-rose-100 transition-colors flex items-center justify-center"
                                            title="Xóa đánh giá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="py-8 text-center text-slate-500">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-star text-slate-400 text-2xl"></i>
                        </div>
                        <p class="font-medium">Không tìm thấy đánh giá nào</p>
                        <p class="text-sm mt-1">
                            <?= !empty($searchTerm) || $ratingFilter ? 'Không có kết quả nào phù hợp với tìm kiếm của bạn.' : 'Chưa có đánh giá nào được gửi từ khách hàng.' ?>
                        </p>
                        <?php if (!empty($searchTerm) || $ratingFilter): ?>
                            <div class="mt-4">
                                <a href="?page=admin_danhgia.php" class="btn bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-lg px-4 py-2 inline-flex items-center gap-2">
                                    <i class="fas fa-arrow-left"></i>
                                    Quay lại tất cả đánh giá
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <div class="p-4 border-t border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="text-sm text-slate-500 mb-3 sm:mb-0">
                        Trang <?= $page ?> / <?= $totalPages ?>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <?php
                        // Xây dựng URL cơ bản với các tham số hiện tại
                        $baseUrl = '?page=admin_danhgia.php';
                        if (!empty($searchTerm)) {
                            $baseUrl .= '&search=' . urlencode($searchTerm);
                        }
                        if ($ratingFilter) {
                            $baseUrl .= '&rating=' . $ratingFilter;
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

                        <!-- Các trang -->
                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($totalPages, $page + 2);

                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="inline-flex items-center justify-center h-8 w-8 border <?= $i == $page ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-700 hover:bg-slate-50' ?> rounded-md text-sm font-medium">
                                <?= $i ?>
                            </a>
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
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4 mb-6">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center">
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i> Hướng dẫn quản lý đánh giá
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="flex items-center p-3 rounded-lg bg-blue-50 border border-blue-200">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Xem chi tiết</p>
                    <p class="text-xs text-slate-500">Xem thông tin đầy đủ của đánh giá</p>
                </div>
            </div>

            <div class="flex items-center p-3 rounded-lg bg-rose-50 border border-rose-200">
                <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-trash text-rose-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Xóa đánh giá</p>
                    <p class="text-xs text-slate-500">Xóa vĩnh viễn đánh giá không phù hợp</p>
                </div>
            </div>

            <div class="flex items-center p-3 rounded-lg bg-indigo-50 border border-indigo-200">
                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-star text-indigo-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Đánh giá sao</p>
                    <p class="text-xs text-slate-500">Xem đánh giá của khách hàng về sản phẩm</p>
                </div>
            </div>

            <div class="flex items-center p-3 rounded-lg bg-green-50 border border-green-200">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-filter text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Lọc đánh giá</p>
                    <p class="text-xs text-slate-500">Lọc theo số sao hoặc từ khóa</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chi tiết đánh giá -->
<div id="reviewDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-eye text-indigo-600 mr-2"></i>
                    Chi tiết đánh giá
                </h3>
                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="reviewDetailContent">
                <!-- Nội dung sẽ được load bằng JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 từ CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewDetailReview(id) {
    console.log('Requesting detail for review ID:', id);
    
    const modal = document.getElementById('reviewDetailModal');
    const content = document.getElementById('reviewDetailContent');
    
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <span class="ml-2 text-slate-600">Đang tải...</span>
        </div>
    `;
    
    modal.classList.remove('hidden');

    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `action=get_detail&id=${id}`
    })
    .then(response => response.text())
    .then(text => {
        console.log('Raw response:', text);
        
        text = text.trim();
        let data;
        
        try {
            data = JSON.parse(text);
        } catch (e) {
            const start = text.indexOf('{');
            const end = text.lastIndexOf('}') + 1;
            
            if (start >= 0 && end > start) {
                text = text.substring(start, end);
                data = JSON.parse(text);
            } else {
                throw new Error('Invalid JSON response');
            }
        }
        
        console.log('Parsed data:', data);
        
        if (data.success && data.data) {
            const review = data.data;
            
            // Escape HTML trong comment
            const safeComment = review.comment ? 
                review.comment.replace(/&/g, '&amp;')
                             .replace(/</g, '&lt;')
                             .replace(/>/g, '&gt;')
                             .replace(/"/g, '&quot;')
                             .replace(/'/g, '&#39;')
                             .replace(/\n/g, '<br>') : 'Không có bình luận';
            
            content.innerHTML = `
                <div class="space-y-6" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <!-- Thông tin sản phẩm -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-box text-indigo-600 mr-2"></i>
                            Thông tin sản phẩm
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-slate-500">Mã sản phẩm:</span>
                                <p class="font-bold text-indigo-700">#${review.idhanghoa}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Tên sản phẩm:</span>
                                <p class="font-medium ${review.tenhanghoa ? 'text-slate-800' : 'text-slate-400 italic'}">${review.tenhanghoa || 'Sản phẩm đã bị xóa'}</p>
                            </div>
                        </div>
                        ${review.tenhanghoa ? `
                        <div class="mt-3 pt-3 border-t border-blue-200">
                            <a href="chitietsanpham.php?id=${review.idhanghoa}" target="_blank" 
                               class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Xem chi tiết sản phẩm
                            </a>
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
                                <span class="text-sm text-slate-500">Username:</span>
                                <p class="font-medium text-slate-800">${review.username}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">ID khách hàng:</span>
                                <p class="font-medium text-green-700">#${review.id_kh || 'Chưa liên kết'}</p>
                            </div>
                            ${review.full_name ? `
                            <div class="col-span-2">
                                <span class="text-sm text-slate-500">Họ và tên:</span>
                                <p class="font-medium text-slate-800">${review.full_name}</p>
                            </div>
                            ` : `
                            <div class="col-span-2">
                                <span class="text-sm text-slate-500">Họ và tên:</span>
                                <p class="font-medium text-slate-400 italic">Chưa cập nhật</p>
                            </div>
                            `}
                        </div>
                    </div>

                    <!-- Nội dung đánh giá -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-star text-yellow-600 mr-2"></i>
                            Nội dung đánh giá
                        </h4>
                        
                        <div class="mb-4">
                            <span class="text-sm text-slate-500">Đánh giá:</span>
                            <div class="flex items-center mt-1">
                                ${[1,2,3,4,5].map(i => 
                                    `<i class="fas fa-star text-${i <= parseInt(review.rating) ? 'yellow' : 'gray'}-400 mr-1"></i>`
                                ).join('')}
                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-sm rounded-full px-3 py-1 font-medium">
                                    ${review.rating}/5 sao
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="text-sm text-slate-500">Bình luận:</span>
                            <div class="mt-2 p-3 bg-white rounded-lg border border-yellow-300 shadow-sm">
                                <p class="text-slate-700 leading-relaxed">${safeComment}</p>
                            </div>
                        </div>

                        <div>
                            <span class="text-sm text-slate-500">Hình ảnh đính kèm:</span>
                            ${review.image && review.image.trim() !== '' && review.image !== 'NULL' ? `
                            <div class="mt-2">
                                <img src="${review.image}" alt="Hình ảnh đánh giá" 
                                     class="max-w-full h-auto rounded-lg shadow-sm border border-yellow-300 cursor-pointer hover:shadow-md transition-shadow"
                                     onclick="window.open('${review.image}', '_blank')"
                                     onerror="this.parentElement.innerHTML='<span class=\\'text-slate-400 italic\\'>Không thể tải hình ảnh</span>'">
                            </div>
                            ` : `
                            <p class="text-slate-400 italic mt-1">Không có hình ảnh</p>
                            `}
                        </div>
                    </div>

                    <!-- Thông tin thời gian -->
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-lg p-4 border border-slate-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-clock text-slate-600 mr-2"></i>
                            Thông tin thời gian
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-slate-500">Thời gian đánh giá:</span>
                                <p class="font-medium text-slate-800">${new Date(review.created_at).toLocaleString('vi-VN')}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">ID đánh giá:</span>
                                <p class="font-medium text-slate-600">#${review.id}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="text-center py-8 text-slate-500" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <i class="fas fa-exclamation-triangle text-4xl mb-3 text-red-400"></i>
                    <p class="font-medium">Không thể tải thông tin chi tiết</p>
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
                <p class="font-medium">Có lỗi xảy ra</p>
                <p class="text-sm mt-1">${error.message}</p>
                <button onclick="viewDetailReview(${id})" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Thử lại
                </button>
            </div>
        `;
    });
}

// Đóng modal chi tiết
function closeDetailModal() {
    document.getElementById('reviewDetailModal').classList.add('hidden');
}

// Đóng modal khi click outside
document.getElementById('reviewDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});


    // Xóa đánh giá
    function confirmDelete(id) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: 'Bạn có chắc chắn muốn xóa đánh giá này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                deleteReview(id);
            }
        });
    }

    function deleteReview(id) {
        // Hiển thị trạng thái đang xóa
        Swal.fire({
            title: 'Đang xử lý...',
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false
        });

        // Gọi AJAX để xóa đánh giá
        fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `action=delete&id=${id}`
            })
            .then(response => {
                // Kiểm tra nếu response là OK, nếu không báo lỗi
                if (!response.ok) {
                    throw new Error('Lỗi kết nối với server');
                }
                return response.text(); // Đọc phản hồi dưới dạng text trước
            })
            .then(text => {
                // Thử chuyển đổi text thành JSON
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.log('Phản hồi không phải JSON: ', text);
                    // Nếu không phải JSON hợp lệ nhưng vẫn xóa thành công (hiển thị bằng F5)
                    // Vẫn coi là thành công
                    return {
                        success: true
                    };
                }
                return data;
            })
            .then(data => {
                // Xử lý kết quả
                if (data && data.success) {
                    // Xóa hàng từ bảng
                    const row = document.getElementById(`row-${id}`);
                    if (row) {
                        row.remove();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Đã xóa!',
                        text: 'Đánh giá đã được xóa thành công.',
                        confirmButtonColor: '#4f46e5',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    }).then(() => {
                        // Nếu không còn đánh giá nào, làm mới trang
                        const tableRows = document.querySelectorAll('tbody tr');
                        if (tableRows.length === 0) {
                            window.location.reload();
                        }
                    });
                } else {
                    // Nếu server trả về lỗi hoặc không thành công
                    console.error('Lỗi từ server:', data);
                    Swal.fire({
                        icon: 'info',
                        title: 'Đang làm mới trang...',
                        text: 'Dữ liệu có thể đã được xóa nhưng cần làm mới trang để cập nhật.',
                        confirmButtonColor: '#4f46e5',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    }).then(() => {
                        window.location.reload(); // Làm mới trang để cập nhật
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hiện thông báo lỗi nhưng vẫn làm mới trang sau đó
                Swal.fire({
                    icon: 'info',
                    title: 'Có thể đã xóa thành công',
                    text: 'Đang làm mới trang để kiểm tra cập nhật...',
                    confirmButtonColor: '#4f46e5',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    }
                }).then(() => {
                    window.location.reload(); // Làm mới trang để kiểm tra
                });
            });
    }
</script>