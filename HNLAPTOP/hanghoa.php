<?php
// Kiểm tra trạng thái phiên trước khi khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu không có session hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php"); // Chuyển hướng về trang chủ nếu không phải admin
    exit();
}
require 'mod/hanghoa.php';
require 'mod/loaihang.php';

$hh = new HangHoa();
$lh = new LoaiHang();

$list = $hh->getAll();
$list_loai = $lh->getAll();

// Đếm số lượng sản phẩm theo loại
$countByCategory = [];
foreach ($list_loai as $l) {
    $countByCategory[$l->idloaihang] = 0;
}

foreach ($list as $h) {
    if (isset($countByCategory[$h->idloaihang])) {
        $countByCategory[$h->idloaihang]++;
    }
}
?>

<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê nhanh -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Quản lý hàng hoá</h2>
            <p class="text-slate-500">Quản lý tất cả sản phẩm trong hệ thống</p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center bg-indigo-50 text-indigo-700 rounded-lg px-4 py-2">
            <i class="fas fa-laptop-code mr-2"></i>
            <span class="font-medium">Tổng số: <?= count($list) ?> sản phẩm</span>
        </div>
    </div>

    <!-- Thống kê số lượng sản phẩm theo loại -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <?php foreach ($list_loai as $l): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium text-indigo-600"><?= htmlspecialchars($l->tenloaihang) ?></span>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><?= $countByCategory[$l->idloaihang] ?></span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5">
                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: <?= min(100, ($countByCategory[$l->idloaihang] / max(1, count($list)) * 100)) ?>%"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Thêm sản phẩm mới -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6 mb-6">
        <h3 class="text-lg font-medium text-slate-800 mb-4 flex items-center">
            <i class="fas fa-plus-circle text-indigo-600 mr-2"></i> Thêm sản phẩm mới
        </h3>
        
        <form method="post" action="actions/hanghoa_act.php" enctype="multipart/form-data">
            <input type="hidden" name="add" value="1">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tên sản phẩm <span class="text-rose-500">*</span></label>
                    <input type="text" name="tenhanghoa" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Mô tả <span class="text-rose-500">*</span></label>
                    <input type="text" name="mota" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Giá <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="giathamkhao" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-12" required min="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">VNĐ</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Loại <span class="text-rose-500">*</span></label>
                    <select name="idloaihang" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        <?php foreach ($list_loai as $l): ?>
                        <option value="<?= $l->idloaihang ?>">
                            <?= htmlspecialchars($l->tenloaihang) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hình ảnh</label>
                    <input type="file" name="hinhanh" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
            </div>
            <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
                <i class="fas fa-save mr-2"></i> Thêm sản phẩm
            </button>
        </form>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <h3 class="text-lg font-medium text-slate-800 mb-2 sm:mb-0">Danh sách sản phẩm</h3>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." class="block w-full pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400"></i>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full" id="productsTable">
                <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Hình ảnh</th>
                        <th class="py-3 px-4 text-left">Tên sản phẩm</th>
                        <th class="py-3 px-4 text-left">Mô tả</th>
                        <th class="py-3 px-4 text-left">Chi tiết</th>
                        <th class="py-3 px-4 text-left">Giá</th>
                        <th class="py-3 px-4 text-left">Loại</th>
                        <th class="py-3 px-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if (empty($list)): ?>
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-box-open text-4xl text-slate-300 mb-3"></i>
                                    <p>Chưa có sản phẩm nào</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($list as $h): ?>
                            <!-- Dòng hiển thị bình thường -->
                            <tr id="row_<?= $h->idhanghoa ?>" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-medium"><?= $h->idhanghoa ?></td>
                                <td class="py-3 px-4">
                                    <?php if (!empty($h->hinhanh)): ?>
                                        <img src="uploads/<?= htmlspecialchars($h->hinhanh) ?>" class="w-12 h-12 object-cover rounded-lg shadow-sm" alt="<?= htmlspecialchars($h->tenhanghoa) ?>">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 font-medium"><?= htmlspecialchars($h->tenhanghoa) ?></td>
                                <td class="py-3 px-4 text-slate-600 max-w-xs truncate"><?= htmlspecialchars($h->mota) ?></td>
                                <!-- Cột chi tiết -->
                                <td class="py-3 px-4 text-center">
                                    <a href="suachitiet.php?id=<?= $h->idhanghoa ?>"
                                        class="inline-block bg-blue-100 text-blue-700 p-2 rounded-lg hover:bg-blue-200 transition-colors"
                                        title="Sửa chi tiết">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-slate-700 font-medium"><?= number_format($h->giathamkhao, 0, ',', '.') ?> VNĐ</td>
                                <td class="py-3 px-4">
                                    <?php foreach ($list_loai as $l): ?>
                                        <?php if ($l->idloaihang == $h->idloaihang): ?>
                                            <span class="inline-block px-2.5 py-0.5 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium">
                                                <?= htmlspecialchars($l->tenloaihang) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" onclick="enableEdit(<?= $h->idhanghoa ?>)" 
                                            class="bg-emerald-100 text-emerald-700 p-2 rounded-lg hover:bg-emerald-200 transition-colors" 
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="actions/hanghoa_act.php?delete=<?= $h->idhanghoa ?>" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"
                                            class="bg-rose-100 text-rose-700 p-2 rounded-lg hover:bg-rose-200 transition-colors" 
                                            title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Dòng hiển thị khi chỉnh sửa -->
                            <tr id="editRow_<?= $h->idhanghoa ?>" style="display: none;" class="bg-slate-50">
                                <form method="post" action="actions/hanghoa_act.php" enctype="multipart/form-data" class="w-full">
                                    <input type="hidden" name="update" value="<?= $h->idhanghoa ?>">
                                    <td class="py-3 px-4 font-medium"><?= $h->idhanghoa ?></td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col space-y-2">
                                            <?php if (!empty($h->hinhanh)): ?>
                                                <img src="uploads/<?= htmlspecialchars($h->hinhanh) ?>" class="w-12 h-12 object-cover rounded-lg shadow-sm" alt="<?= htmlspecialchars($h->tenhanghoa) ?>">
                                            <?php endif; ?>
                                            <input type="file" name="hinhanh" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="tenhanghoa" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= htmlspecialchars($h->tenhanghoa) ?>" required>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="mota" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= htmlspecialchars($h->mota) ?>" required>
                                    </td>
                                    <td class="py-3 px-4">
                                        <input type="text" name="mota" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= htmlspecialchars($h->mota) ?>" required>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="relative">
                                            <input type="number" name="giathamkhao" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-12" value="<?= $h->giathamkhao ?>" required min="0">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">VNĐ</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <select name="idloaihang" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                            <?php foreach ($list_loai as $l): ?>
                                                <option value="<?= $l->idloaihang ?>" <?= ($l->idloaihang == $h->idloaihang) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($l->tenloaihang) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button type="submit" class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition-colors" title="Lưu">
                                                <i class="fas fa-save"></i>
                                            </button>
                                            <button type="button" onclick="cancelEdit(<?= $h->idhanghoa ?>)" class="bg-slate-300 text-slate-700 p-2 rounded-lg hover:bg-slate-400 transition-colors" title="Hủy">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="script.js"></script>