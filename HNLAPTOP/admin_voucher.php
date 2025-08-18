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
require_once 'mod/database.php';

// Thêm voucher
if (isset($_POST['add_voucher'])) {
    $code = $_POST['code'] ?: strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
    $type = $_POST['type'];
    $value = floatval($_POST['value']);
    $expired_at = $_POST['expired_at'] ?: null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $total_uses = intval($_POST['total_uses']);

    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("INSERT INTO voucher (code, type, value, expired_at, is_active, total_uses, used_count) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->execute([$code, $type, $value, $expired_at, $is_active, $total_uses]);
    $message = "Thêm voucher thành công!";
}

// Xóa voucher
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo = Database::getInstance();
    $pdo->prepare("DELETE FROM voucher WHERE id=?")->execute([$id]);
    $message = "Đã xóa voucher!";
}

// Sửa voucher
if (isset($_POST['edit_voucher'])) {
    $id = $_POST['id'];
    $code = $_POST['code'];
    $type = $_POST['type'];
    $value = floatval($_POST['value']);
    $expired_at = $_POST['expired_at'] ?: null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $total_uses = intval($_POST['total_uses']);

    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE voucher SET code=?, type=?, value=?, expired_at=?, is_active=?, total_uses=? WHERE id=?");
    $stmt->execute([$code, $type, $value, $expired_at, $is_active, $total_uses, $id]);
    $message = "Cập nhật voucher thành công!";
}

// Lấy danh sách voucher
$pdo = Database::getInstance();
$vouchers = $pdo->query("SELECT * FROM voucher ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Nếu sửa, lấy thông tin voucher
$edit_voucher = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_voucher = $pdo->prepare("SELECT * FROM voucher WHERE id=?");
    $edit_voucher->execute([$id]);
    $edit_voucher = $edit_voucher->fetch(PDO::FETCH_ASSOC);
}

// Thống kê voucher
$totalVouchers = count($vouchers);
$activeVouchers = 0;
$expiredVouchers = 0;
$percentVouchers = 0;
$cashVouchers = 0;

foreach ($vouchers as $v) {
    if ($v['is_active']) {
        $activeVouchers++;
    }

    if ($v['expired_at'] && strtotime($v['expired_at']) < time()) {
        $expiredVouchers++;
    }

    if ($v['type'] == 'percent') {
        $percentVouchers++;
    } else {
        $cashVouchers++;
    }
}
?>

<link rel="stylesheet" href="style.css?<?php echo time(); ?>">

<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê nhanh -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 mb-6 text-white overflow-hidden relative">
        <div class="absolute right-0 top-0 opacity-10">
            <i class="fas fa-ticket-alt text-9xl transform translate-x-6 -translate-y-6"></i>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="z-10">
                <h2 class="text-2xl font-bold font-heading mb-1">Quản lý Voucher</h2>
                <p class="text-indigo-100">Quản lý tất cả mã giảm giá trong hệ thống</p>
            </div>
            <div class="mt-4 md:mt-0 z-10 flex space-x-3">
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-ticket-alt mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Tổng số voucher</div>
                        <div class="font-bold"><?= $totalVouchers ?></div>
                    </div>
                </div>
                <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-calendar-check mr-2"></i>
                    <div>
                        <div class="text-xs text-indigo-200">Đang hoạt động</div>
                        <div class="font-bold"><?= $activeVouchers ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê voucher -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-sm border border-green-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $activeVouchers ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Voucher đang hoạt động</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-white to-orange-50 rounded-xl shadow-sm border border-orange-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-hourglass-end"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $expiredVouchers ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Voucher hết hạn</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm border border-blue-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-percentage"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $percentVouchers ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Voucher theo %</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-sm border border-purple-100/60 p-4 hover:shadow transition-all duration-300">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $cashVouchers ?></h3>
                    <p class="text-xs text-slate-500 font-medium">Voucher tiền mặt</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    <?php if (isset($message)): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm"><?= htmlspecialchars($message) ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Thêm voucher mới -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6 mb-6">
        <h3 class="text-lg font-medium text-slate-800 mb-4 flex items-center">
            <i class="fas fa-<?= $edit_voucher ? 'edit' : 'plus-circle' ?> text-indigo-600 mr-2"></i> <?= $edit_voucher ? "Sửa Voucher" : "Thêm Voucher mới" ?>
        </h3>

        <form method="post">
            <?php if ($edit_voucher): ?>
                <input type="hidden" name="id" value="<?= $edit_voucher['id'] ?>">
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mã voucher</label>
                    <input type="text" name="code" value="<?= isset($edit_voucher['code']) ? htmlspecialchars($edit_voucher['code']) : '' ?>"
                        class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Để trống để tạo tự động">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Loại <span class="text-rose-500">*</span></label>
                    <select name="type" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="percent" <?= (isset($edit_voucher['type']) && $edit_voucher['type'] == 'percent') ? 'selected' : '' ?>>Phần trăm (%)</option>
                        <option value="cash" <?= (isset($edit_voucher['type']) && $edit_voucher['type'] == 'cash') ? 'selected' : '' ?>>Tiền mặt</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Giá trị <span class="text-rose-500">*</span></label>
                    <input type="number" name="value" value="<?= isset($edit_voucher['value']) ? $edit_voucher['value'] : '' ?>"
                        class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required min="1">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Ngày hết hạn</label>
                    <input type="datetime-local" name="expired_at"
                        value="<?= isset($edit_voucher['expired_at']) && $edit_voucher['expired_at'] ? date('Y-m-d\TH:i', strtotime($edit_voucher['expired_at'])) : '' ?>"
                        class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Giới hạn lượt sử dụng <span class="text-rose-500">*</span></label>
                    <input type="number" name="total_uses" value="<?= isset($edit_voucher['total_uses']) ? $edit_voucher['total_uses'] : 1 ?>"
                        class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required min="1">
                </div>
                <div class="flex items-center">
                    <div class="flex items-center h-5 mt-5">
                        <input type="checkbox" name="is_active" id="is_active"
                            <?= (!isset($edit_voucher) || $edit_voucher['is_active']) ? 'checked' : '' ?>
                            class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 text-sm font-medium text-slate-700">Kích hoạt</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <?php if ($edit_voucher): ?>
                    <button type="submit" name="edit_voucher" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
                        <i class="fas fa-save mr-2"></i> Cập nhật voucher
                    </button>
                    <a href="?page=admin_voucher.php" class="ml-2 inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
                        <i class="fas fa-times mr-2"></i> Huỷ sửa
                    </a>
                <?php else: ?>
                    <button type="submit" name="add_voucher" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Thêm voucher
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Danh sách voucher -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h3 class="font-bold text-slate-800 flex items-center">
                <i class="fas fa-list text-indigo-600 mr-2"></i> Danh sách voucher
            </h3>
            <div class="mt-3 sm:mt-0 relative">
                <input type="text" id="searchInput" placeholder="Tìm kiếm voucher..." class="block w-full pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="vouchersTable">
                <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Mã</th>
                        <th class="py-3 px-4 text-left">Loại</th>
                        <th class="py-3 px-4 text-left">Giá trị</th>
                        <th class="py-3 px-4 text-left">Ngày hết hạn</th>
                        <th class="py-3 px-4 text-left">Trạng thái</th>
                        <th class="py-3 px-4 text-left">Đã dùng</th>
                        <th class="py-3 px-4 text-left">Giới hạn</th>
                        <th class="py-3 px-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($vouchers)): ?>
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-ticket-alt text-4xl text-slate-300 mb-3"></i>
                                    <p>Chưa có voucher nào</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vouchers as $index => $v): ?>
                            <tr class="hover:bg-slate-50 transition-colors animate__animated animate__fadeIn animate__faster"
                                style="animation-delay: <?= $index * 0.05 ?>s">
                                <td class="py-3 px-4 font-medium"><?= $v['id'] ?></td>
                                <td class="py-3 px-4">
                                    <span class="font-mono font-medium text-indigo-700"><?= htmlspecialchars($v['code']) ?></span>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($v['type'] == 'percent'): ?>
                                        <span class="inline-block px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-percentage mr-1"></i> Phần trăm
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-2.5 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                            <i class="fas fa-money-bill-wave mr-1"></i> Tiền mặt
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 font-medium">
                                    <?= $v['type'] == 'percent' ? $v['value'] . '%' : number_format($v['value'], 0, ',', '.') . ' VNĐ' ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($v['expired_at']): ?>
                                        <?php
                                        $expired = strtotime($v['expired_at']) < time();
                                        $expireClass = $expired ? 'text-rose-600' : 'text-slate-700';
                                        ?>
                                        <span class="<?= $expireClass ?>">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($v['expired_at'])) ?>
                                            <?= $expired ? ' (Hết hạn)' : '' ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-slate-500">Không giới hạn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <?php if ($v['is_active']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Hoạt động
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-800 rounded-full text-xs font-medium">
                                            <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                            Vô hiệu
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4"><?= $v['used_count'] ?? 0 ?></td>
                                <td class="py-3 px-4"><?= $v['total_uses'] ?? 0 ?></td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="?page=admin_voucher.php&edit=<?= $v['id'] ?>"
                                            class="bg-emerald-100 text-emerald-700 p-2 rounded-lg hover:bg-emerald-200 transition-colors"
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?page=admin_voucher.php&delete=<?= $v['id'] ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?');"
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

<script>
    // Script tìm kiếm voucher
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('vouchersTable');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();

            rows.forEach(row => {
                let found = false;
                const cells = row.querySelectorAll('td');

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().indexOf(query) > -1) {
                        found = true;
                    }
                });

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>