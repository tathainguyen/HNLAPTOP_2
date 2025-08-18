<?php

require_once 'mod/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

// Xác định trang hiện tại
$current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($current_page < 1) $current_page = 1;

// Số lượng liên hệ mỗi trang
$per_page = 10;

// Xử lý tìm kiếm và lọc
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';

try {
    // Đếm tổng số liên hệ (với các điều kiện lọc)
    $countSql = "SELECT COUNT(*) AS total FROM contacts WHERE 1=1";
    $params = [];

    if (!empty($statusFilter)) {
        $countSql .= " AND status = :status";
        $params[':status'] = $statusFilter;
    }

    if (!empty($searchTerm)) {
        $countSql .= " AND (name LIKE :search OR email LIKE :search OR subject LIKE :search OR message LIKE :search)";
        $params[':search'] = '%' . $searchTerm . '%';
    }

    $countStmt = $pdo->prepare($countSql);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalContacts = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Đếm số liên hệ từng trạng thái
    $statusStats = [
        'Chưa xem' => 0,
        'Đã xem' => 0,
        'Đã phản hồi' => 0
    ];

    $statsStmt = $pdo->query("SELECT status, COUNT(*) as count FROM contacts GROUP BY status");
    while ($row = $statsStmt->fetch(PDO::FETCH_ASSOC)) {
        if (isset($statusStats[$row['status']])) {
            $statusStats[$row['status']] = $row['count'];
        }
    }

    // Tính tổng số trang
    $total_pages = ceil($totalContacts / $per_page);
    if ($total_pages < 1) $total_pages = 1;

    // Đảm bảo trang hiện tại không vượt quá tổng số trang
    if ($current_page > $total_pages) $current_page = $total_pages;

    // Tính offset
    $offset = ($current_page - 1) * $per_page;

    // Lấy dữ liệu phân trang với điều kiện tìm kiếm và lọc
    $sql = "SELECT * FROM contacts WHERE 1=1";

    if (!empty($statusFilter)) {
        $sql .= " AND status = :status";
    }

    if (!empty($searchTerm)) {
        $sql .= " AND (name LIKE :search OR email LIKE :search OR subject LIKE :search OR message LIKE :search)";
    }

    $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    if (!empty($statusFilter)) {
        $stmt->bindValue(':status', $statusFilter);
    }

    if (!empty($searchTerm)) {
        $stmt->bindValue(':search', '%' . $searchTerm . '%');
    }

    $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
}
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<link rel="stylesheet" href="style.css?<?php echo time(); ?>">
<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 mb-6 text-white overflow-hidden relative">
        <div class="absolute right-0 top-0 opacity-10">
            <i class="fas fa-envelope-open-text text-9xl transform translate-x-6 -translate-y-6"></i>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="z-10">
                <h2 class="text-2xl font-bold font-heading mb-1">Quản lý liên hệ</h2>
                <p class="text-indigo-100">Quản lý và phản hồi các yêu cầu liên hệ từ khách hàng</p>
            </div>
            <div class="mt-4 md:mt-0 z-10 flex space-x-3">
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Tổng liên hệ</div>
                        <div class="font-bold"><?= $totalContacts ?></div>
                    </div>
                </div>
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-eye-slash mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Chưa xem</div>
                        <div class="font-bold"><?= $statusStats['Chưa xem'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Liên hệ chưa xem -->
        <div class="bg-gradient-to-br from-white to-amber-50 rounded-xl shadow-sm border border-amber-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $statusStats['Chưa xem'] ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Liên hệ chưa xem</p>
                </div>
            </div>
        </div>

        <!-- Liên hệ đã xem -->
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm border border-blue-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $statusStats['Đã xem'] ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Liên hệ đã xem</p>
                </div>
            </div>
        </div>

        <!-- Liên hệ đã phản hồi -->
        <div class="bg-gradient-to-br from-white to-emerald-50 rounded-xl shadow-sm border border-emerald-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-reply"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $statusStats['Đã phản hồi'] ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Liên hệ đã phản hồi</p>
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
                    <input type="hidden" name="page" value="admin_contact.php">
                    <?php if (!empty($_GET['status'])): ?>
                        <input type="hidden" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
                    <?php endif; ?>
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" class="form-control w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pl-10 pr-4 py-2 shadow-sm" placeholder="Tìm kiếm liên hệ...">
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
                    <input type="hidden" name="page" value="admin_contact.php">
                    <?php if (!empty($searchTerm)): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                    <?php endif; ?>
                    <select name="status" class="form-select w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 py-2.5 shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Chưa xem" <?= $statusFilter === 'Chưa xem' ? 'selected' : '' ?>>Chưa xem</option>
                        <option value="Đã xem" <?= $statusFilter === 'Đã xem' ? 'selected' : '' ?>>Đã xem</option>
                        <option value="Đã phản hồi" <?= $statusFilter === 'Đã phản hồi' ? 'selected' : '' ?>>Đã phản hồi</option>
                    </select>
                </form>
            </div>
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
                
                <!-- Nút xóa bộ lọc -->
                <a href="?page=admin_contact.php" class="inline-flex items-center justify-center px-3 py-1 text-xs font-medium text-slate-600 bg-slate-100 rounded-full hover:bg-slate-200 transition-colors">
                    <i class="fas fa-times-circle mr-1"></i> Xóa bộ lọc
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bảng liên hệ -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h3 class="font-bold text-slate-800 flex items-center">
                <i class="fas fa-inbox text-indigo-600 mr-2"></i> Danh sách liên hệ
            </h3>
            <div class="text-sm text-slate-500 mt-2 sm:mt-0">
                Hiển thị <?= min($per_page, count($contacts)) ?> / <?= $totalContacts ?> liên hệ
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Tên khách hàng</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-left">Chủ đề</th>
                        <th class="py-3 px-4 text-left">Thời gian</th>
                        <th class="py-3 px-4 text-center">Trạng thái</th>
                        <th class="py-3 px-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($contacts)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-envelope-open text-slate-400 text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Không tìm thấy liên hệ nào</p>
                                    <p class="text-sm mt-1">Chưa có yêu cầu liên hệ nào được gửi đến</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contacts as $index => $contact): ?>
                            <tr class="hover:bg-slate-50 transition-colors animate__animated animate__fadeIn animate__faster contact-row"
                                data-id="<?= $contact['id'] ?>"
                                data-status="<?= $contact['status'] ?>"
                                style="animation-delay: <?= $index * 0.05 ?>s">
                                <td class="py-3 px-4 font-medium"><?= $contact['id'] ?></td>
                                <td class="py-3 px-4 font-medium text-slate-700">
                                    <?= htmlspecialchars($contact['name']) ?>
                                    <?php if (!empty($contact['username'])): ?>
                                        <div class="text-xs text-slate-500">
                                            <i class="fas fa-user mr-1"></i> <?= htmlspecialchars($contact['username']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                        <?= htmlspecialchars($contact['email']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4 max-w-[200px] truncate" title="<?= htmlspecialchars($contact['subject']) ?>">
                                    <?= htmlspecialchars($contact['subject']) ?>
                                </td>
                                <td class="py-3 px-4 text-sm text-slate-500">
                                    <div class="flex items-center">
                                        <i class="far fa-clock mr-1.5 text-slate-400"></i>
                                        <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php
                                    $statusClass = [
                                        'Chưa xem' => 'bg-amber-100 text-amber-800',
                                        'Đã xem' => 'bg-blue-100 text-blue-800',
                                        'Đã phản hồi' => 'bg-emerald-100 text-emerald-800'
                                    ];
                                    $statusIcon = [
                                        'Chưa xem' => 'fa-envelope',
                                        'Đã xem' => 'fa-eye',
                                        'Đã phản hồi' => 'fa-reply'
                                    ];
                                    $class = $statusClass[$contact['status']] ?? 'bg-gray-100 text-gray-800';
                                    $icon = $statusIcon[$contact['status']] ?? 'fa-question';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $class ?>">
                                        <i class="fas <?= $icon ?> mr-1"></i>
                                        <?= htmlspecialchars($contact['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <button onclick="viewContactDetail(<?= $contact['id'] ?>)" class="bg-indigo-50 text-indigo-700 p-2 rounded-lg hover:bg-indigo-100 transition-colors flex items-center justify-center" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if ($contact['status'] === 'Chưa xem'): ?>
                                            <form action="update_contact_status.php" method="post" class="inline">
                                                <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                <input type="hidden" name="status" value="Đã xem">
                                                <button type="submit" class="bg-blue-50 text-blue-700 p-2 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center" title="Đánh dấu đã xem">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($contact['status'] === 'Đã xem'): ?>
                                            <form action="update_contact_status.php" method="post" class="inline">
                                                <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                <input type="hidden" name="status" value="Đã phản hồi">
                                                <button type="submit" class="bg-emerald-50 text-emerald-700 p-2 rounded-lg hover:bg-emerald-100 transition-colors flex items-center justify-center" title="Đánh dấu đã phản hồi">
                                                    <i class="fas fa-reply"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <button onclick="confirmDelete(<?= $contact['id'] ?>)"
                                            class="bg-rose-50 text-rose-700 p-2 rounded-lg hover:bg-rose-100 transition-colors flex items-center justify-center"
                                            title="Xóa liên hệ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <?php if ($total_pages > 1): ?>
            <div class="p-4 border-t border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <div class="text-sm text-slate-500 mb-3 sm:mb-0">
                        Trang <?= $current_page ?> / <?= $total_pages ?>
                    </div>
                    <div class="flex flex-wrap gap-1">
                        <?php
                        // Xây dựng URL cơ bản với các tham số hiện tại
                        $baseUrl = '?page=admin_contact.php';
                        if (!empty($searchTerm)) {
                            $baseUrl .= '&search=' . urlencode($searchTerm);
                        }
                        if (!empty($statusFilter)) {
                            $baseUrl .= '&status=' . urlencode($statusFilter);
                        }
                        ?>
                        
                        <!-- Nút trang đầu và trang trước -->
                        <?php if ($current_page > 1): ?>
                            <a href="<?= $baseUrl ?>&p=1" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-double-left text-xs"></i>
                            </a>

                            <a href="<?= $baseUrl ?>&p=<?= $current_page - 1 ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-left text-xs"></i>
                            </a>
                        <?php endif; ?>

                        <!-- Các trang -->
                        <?php
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);

                        for ($i = $start_page; $i <= $end_page; $i++):
                        ?>
                            <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="inline-flex items-center justify-center h-8 w-8 border <?= $i == $current_page ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-slate-700 hover:bg-slate-50' ?> rounded-md text-sm font-medium">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Nút trang sau và trang cuối -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="<?= $baseUrl ?>&p=<?= $current_page + 1 ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
                                <i class="fas fa-angle-right text-xs"></i>
                            </a>

                            <a href="<?= $baseUrl ?>&p=<?= $total_pages ?>" class="inline-flex items-center justify-center h-8 w-8 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
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
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i> Hướng dẫn quản lý liên hệ
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center p-3 rounded-lg bg-amber-50 border border-amber-200">
                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-envelope text-amber-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Chưa xem</p>
                    <p class="text-xs text-slate-500">Liên hệ mới chưa được xem</p>
                </div>
            </div>

            <div class="flex items-center p-3 rounded-lg bg-blue-50 border border-blue-200">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-eye text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Đã xem</p>
                    <p class="text-xs text-slate-500">Liên hệ đã được xem nhưng chưa phản hồi</p>
                </div>
            </div>

            <div class="flex items-center p-3 rounded-lg bg-emerald-50 border border-emerald-200">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-reply text-emerald-600"></i>
                </div>
                <div>
                    <p class="font-medium text-sm text-slate-700">Đã phản hồi</p>
                    <p class="text-xs text-slate-500">Liên hệ đã được xem và phản hồi</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal chi tiết liên hệ -->
    <div id="contactDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 text-white flex justify-between items-center">
                    <h3 class="text-xl font-bold flex items-center">
                        <i class="fas fa-envelope-open-text mr-2"></i>
                        Chi tiết liên hệ
                    </h3>
                    <button onclick="closeContactDetailModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <!-- Content -->
                <div id="contactDetailContent" class="p-6 overflow-y-auto max-h-[80vh]">
                    <!-- Content sẽ được load bằng JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Xem chi tiết liên hệ
function viewContactDetail(contactId) {
    console.log('Requesting contact detail for ID:', contactId);
    
    const modal = document.getElementById('contactDetailModal');
    const content = document.getElementById('contactDetailContent');
    
    content.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <span class="ml-2 text-slate-600">Đang tải...</span>
        </div>
    `;
    
    modal.classList.remove('hidden');

    fetch('ajax_get_contact_detail.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${contactId}`
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
            const contact = data.data;
            
            // Format trạng thái
            const getStatusClass = (status) => {
                const statusClasses = {
                    'Chưa xem': 'bg-amber-100 text-amber-800',
                    'Đã xem': 'bg-blue-100 text-blue-800',
                    'Đã phản hồi': 'bg-emerald-100 text-emerald-800'
                };
                return statusClasses[status] || 'bg-slate-100 text-slate-800';
            };

            const getStatusIcon = (status) => {
                const statusIcons = {
                    'Chưa xem': 'fa-envelope',
                    'Đã xem': 'fa-eye',
                    'Đã phản hồi': 'fa-reply'
                };
                return statusIcons[status] || 'fa-question';
            };

            // Escape HTML trong message
            const safeMessage = contact.message ? 
                contact.message.replace(/&/g, '&amp;')
                             .replace(/</g, '&lt;')
                             .replace(/>/g, '&gt;')
                             .replace(/"/g, '&quot;')
                             .replace(/'/g, '&#39;')
                             .replace(/\n/g, '<br>') : 'Không có nội dung';

            content.innerHTML = `
                <div class="space-y-6">
                    <!-- Thông tin liên hệ -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                            Thông tin liên hệ
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-slate-500">ID liên hệ:</span>
                                <p class="font-bold text-indigo-700">#${contact.id}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Thời gian gửi:</span>
                                <p class="font-medium text-slate-800">${new Date(contact.created_at).toLocaleString('vi-VN')}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Chủ đề:</span>
                                <p class="font-medium text-slate-800">${contact.subject}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Trạng thái:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusClass(contact.status)}">
                                    <i class="fas ${getStatusIcon(contact.status)} mr-1"></i>
                                    ${contact.status}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin người gửi -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            Thông tin người gửi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-slate-500">Họ và tên:</span>
                                <p class="font-medium text-slate-800">${contact.name}</p>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Email:</span>
                                <p class="font-medium text-blue-600">
                                    <a href="mailto:${contact.email}" class="hover:underline">${contact.email}</a>
                                </p>
                            </div>
                            ${contact.username ? `
                            <div class="col-span-2">
                                <span class="text-sm text-slate-500">Username:</span>
                                <p class="font-medium text-slate-800">${contact.username}</p>
                            </div>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Nội dung liên hệ -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-4 border border-yellow-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-comment-alt text-yellow-600 mr-2"></i>
                            Nội dung liên hệ
                        </h4>
                        
                        <div class="bg-white rounded-lg border border-yellow-300 shadow-sm p-4">
                            <div class="text-slate-700 leading-relaxed whitespace-pre-wrap">${safeMessage}</div>
                        </div>
                    </div>

                    <!-- Thao tác nhanh -->
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-lg p-4 border border-slate-200">
                        <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                            <i class="fas fa-tools text-slate-600 mr-2"></i>
                            Thao tác nhanh
                        </h4>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="mailto:${contact.email}?subject=Re: ${encodeURIComponent(contact.subject)}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-reply mr-2"></i>
                                Phản hồi qua Email
                            </a>
                            
                            ${contact.status === 'Chưa xem' ? `
                            <button onclick="updateContactStatus(${contact.id}, 'Đã xem')" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Đánh dấu đã xem
                            </button>
                            ` : ''}
                            
                            ${contact.status === 'Đã xem' ? `
                            <button onclick="updateContactStatus(${contact.id}, 'Đã phản hồi')" 
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-reply mr-2"></i>
                                Đánh dấu đã phản hồi
                            </button>
                            ` : ''}
                            
                            <button onclick="copyToClipboard('${contact.email}')" 
                                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-copy mr-2"></i>
                                Copy Email
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="text-center py-8 text-slate-500">
                    <i class="fas fa-exclamation-triangle text-4xl mb-3 text-red-400"></i>
                    <p class="font-medium">Không thể tải thông tin chi tiết liên hệ</p>
                    <p class="text-sm mt-1">${data.message || 'Vui lòng thử lại sau'}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        content.innerHTML = `
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-exclamation-triangle text-4xl mb-3 text-red-400"></i>
                <p class="font-medium">Có lỗi xảy ra khi tải dữ liệu</p>
                <p class="text-sm mt-1">${error.message}</p>
                <button onclick="viewContactDetail(${contactId})" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Thử lại
                </button>
            </div>
        `;
    });
}

// Đóng modal chi tiết liên hệ
function closeContactDetailModal() {
    document.getElementById('contactDetailModal').classList.add('hidden');
}

// Đóng modal khi click outside
document.getElementById('contactDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeContactDetailModal();
    }
});

// Cập nhật trạng thái liên hệ từ modal
function updateContactStatus(contactId, status) {
    const formData = new FormData();
    formData.append('id', contactId);
    formData.append('status', status);

    fetch('update_contact_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        // Đóng modal và reload trang để cập nhật
        closeContactDetailModal();
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}

// Copy email vào clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Hiển thị thông báo thành công
        showNotification('Đã copy email vào clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        alert('Không thể copy email');
    });
}

// Hiển thị thông báo
function showNotification(message, type = 'success') {
    // Có thể sử dụng toast notification library hoặc alert đơn giản
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
// Thêm vào cuối trang admin_contact.php, trước thẻ </body>

// Xác nhận xóa liên hệ
function confirmDelete(contactId) {
    if (confirm('Bạn có chắc chắn muốn xóa liên hệ này không?')) {
        // Thực hiện xóa
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_contact.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = contactId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<!-- Thêm SweetAlert2 từ CDN nếu cần -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Thẻ link đến file script.js -->
<script src="script.js"></script>

