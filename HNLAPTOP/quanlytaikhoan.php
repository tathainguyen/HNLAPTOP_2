<?php
// Kiểm tra trạng thái phiên trước khi khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'mod/config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

// Kết nối database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, 'hn_laptop');
if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

// Xử lý cập nhật tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $status = intval($_POST['status']);
    $dia_chi_chi_tiet = trim($_POST['dia_chi_chi_tiet']) ?: null;
    $quan_huyen = trim($_POST['quan_huyen']) ?: null;
    $tinh_thanh_pho = trim($_POST['tinh_thanh_pho']) ?: null;
    $password = !empty($_POST['password']) ? trim($_POST['password']) : null;

    if (!empty($username)) {
        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET username=?, phone=?, gender=?, role=?, status=?, dia_chi_chi_tiet=?, quan_huyen=?, tinh_thanh_pho=?, password=? WHERE id=?");
            $stmt->bind_param("ssssissssi", $username, $phone, $gender, $role, $status, $dia_chi_chi_tiet, $quan_huyen, $tinh_thanh_pho, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, phone=?, gender=?, role=?, status=?, dia_chi_chi_tiet=?, quan_huyen=?, tinh_thanh_pho=? WHERE id=?");
            $stmt->bind_param("ssssisssi", $username, $phone, $gender, $role, $status, $dia_chi_chi_tiet, $quan_huyen, $tinh_thanh_pho, $id);
        }

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Lỗi khi cập nhật: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Tên tài khoản không được để trống!";
    }
    exit();
}

// Xử lý xóa tài khoản
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // 1. Lấy danh sách ID đơn hàng của user này
    $donhang_ids = [];
    $stmt = $conn->prepare("SELECT id FROM donhang WHERE id_kh = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $donhang_ids[] = $row['id'];
    }
    $stmt->close();

    // 2. Xóa chi tiết đơn hàng (chitietdonhang)
    if (!empty($donhang_ids)) {
        $in_clause = implode(',', array_fill(0, count($donhang_ids), '?'));
        $types = str_repeat('i', count($donhang_ids));
        $stmt = $conn->prepare("DELETE FROM chitietdonhang WHERE id_donhang IN ($in_clause)");
        $stmt->bind_param($types, ...$donhang_ids);
        $stmt->execute();
        $stmt->close();
    }

    // 3. Xóa đơn hàng (donhang)
    $stmt = $conn->prepare("DELETE FROM donhang WHERE id_kh = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // 4. Xóa user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Lỗi khi xóa: " . $stmt->error;
    }
    $stmt->close();
    exit();
}

// Lấy danh sách tài khoản
$result = $conn->query("SELECT * FROM users");

// Thống kê
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalAdmins = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'admin'")->fetch_assoc()['total'];
$totalActiveUsers = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 1")->fetch_assoc()['total'];
$totalInactiveUsers = $conn->query("SELECT COUNT(*) as total FROM users WHERE status = 0")->fetch_assoc()['total'];
?>

<div class="animate__animated animate__fadeIn">
    <!-- Tiêu đề và thống kê nhanh -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 mb-1">Quản lý tài khoản</h2>
            <p class="text-slate-500">Quản lý người dùng và phân quyền trong hệ thống</p>
        </div>
        <div class="mt-3 md:mt-0 flex items-center bg-indigo-50 text-indigo-700 rounded-lg px-4 py-2">
            <i class="fas fa-users mr-2"></i>
            <span class="font-medium">Tổng số: <?= $totalUsers ?> tài khoản</span>
        </div>
    </div>

    <!-- Thẻ thống kê nhanh -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Tổng số tài khoản -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-friends text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Tổng tài khoản</p>
                    <h3 class="text-slate-800 text-2xl font-bold"><?= $totalUsers ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-xs text-slate-600">
                    <span class="text-indigo-600 font-semibold"><?= round($totalActiveUsers / max(1, $totalUsers) * 100) ?>%</span> đang hoạt động
                </div>
            </div>
        </div>
        
        <!-- Tài khoản admin -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-shield text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Tài khoản Admin</p>
                    <h3 class="text-slate-800 text-2xl font-bold"><?= $totalAdmins ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-xs text-slate-600">
                    <span class="text-red-600 font-semibold"><?= round($totalAdmins / max(1, $totalUsers) * 100) ?>%</span> tổng số tài khoản
                </div>
            </div>
        </div>
        
        <!-- Tài khoản active -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-check text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Đang hoạt động</p>
                    <h3 class="text-slate-800 text-2xl font-bold"><?= $totalActiveUsers ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-xs text-slate-600">
                    <i class="fas fa-info-circle mr-1 text-emerald-500"></i>
                    Tài khoản có thể đăng nhập
                </div>
            </div>
        </div>
        
        <!-- Tài khoản inactive -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-3">
                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-user-lock text-slate-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Đã vô hiệu hóa</p>
                    <h3 class="text-slate-800 text-2xl font-bold"><?= $totalInactiveUsers ?></h3>
                </div>
            </div>
            <div class="mt-2">
                <div class="text-xs text-slate-600">
                    <i class="fas fa-info-circle mr-1 text-slate-500"></i>
                    Tài khoản bị hạn chế quyền
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-grow relative">
                <input type="text" id="searchAccount" placeholder="Tìm kiếm tài khoản..." class="block w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
            </div>
            <div class="flex space-x-2">
                <select id="filterRole" class="bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block py-2.5 px-4">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
                <select id="filterStatus" class="bg-slate-50 border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block py-2.5 px-4">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Hoạt động</option>
                    <option value="0">Vô hiệu hóa</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách tài khoản -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full" id="accountTable">
                <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
                    <tr>
                        <th class="py-3 px-4 text-left">ID</th>
                        <th class="py-3 px-4 text-left">Tên tài khoản</th>
                        <th class="py-3 px-4 text-left">Số điện thoại</th>
                        <th class="py-3 px-4 text-left">Giới tính</th>
                        <th class="py-3 px-4 text-left">Quyền</th>
                        <th class="py-3 px-4 text-left">Mật khẩu</th>
                        <th class="py-3 px-4 text-left">Trạng thái</th>
                        <th class="py-3 px-4 text-left">Địa chỉ</th>
                        <th class="py-3 px-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if ($result->num_rows === 0): ?>
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-4xl text-slate-300 mb-3"></i>
                                    <p>Chưa có tài khoản nào</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr data-id="<?= $row['id'] ?>" data-role="<?= $row['role'] ?>" data-status="<?= $row['status'] ?>" class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-4 font-medium"><?= $row['id'] ?></td>
                                <td class="py-3 px-4">
                                    <span class="view-mode"><?= htmlspecialchars($row['username']) ?></span>
                                    <input type="text" class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 username" value="<?= htmlspecialchars($row['username']) ?>">
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode"><?= htmlspecialchars($row['phone']) ?></span>
                                    <input type="text" class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 phone" value="<?= htmlspecialchars($row['phone']) ?>">
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode"><?= htmlspecialchars($row['gender']) ?></span>
                                    <select class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 gender">
                                        <option value="Nam" <?= $row['gender'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Nữ" <?= $row['gender'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                        <option value="Khác" <?= $row['gender'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode">
                                        <?php if ($row['role'] == 'admin'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                User
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                    <select class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 role">
                                        <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode password-hidden text-slate-400"><i class="fas fa-lock mr-1"></i> ******</span>
                                    <input type="password" class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 password" placeholder="Nhập mật khẩu mới">
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode">
                                        <?php if ($row['status'] == 1): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                                Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                                                Vô hiệu hóa
                                            </span>
                                        <?php endif; ?>
                                    </span>
                                    <select class="edit-mode hidden block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 status">
                                        <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="0" <?= $row['status'] == 0 ? 'selected' : '' ?>>Vô hiệu hóa</option>
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="view-mode text-xs text-slate-600">
                                        <?php
                                        $address_parts = [];
                                        if (!empty($row['dia_chi_chi_tiet'])) $address_parts[] = htmlspecialchars($row['dia_chi_chi_tiet']);
                                        if (!empty($row['quan_huyen'])) $address_parts[] = htmlspecialchars($row['quan_huyen']);
                                        if (!empty($row['tinh_thanh_pho'])) $address_parts[] = htmlspecialchars($row['tinh_thanh_pho']);
                                        echo !empty($address_parts) ? implode(", ", $address_parts) : '<span class="text-slate-400">Chưa cập nhật</span>';
                                        ?>
                                    </span>
                                    <div class="edit-mode hidden space-y-2">
                                        <input type="text" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dia_chi_chi_tiet" value="<?= htmlspecialchars($row['dia_chi_chi_tiet'] ?? '') ?>" placeholder="Địa chỉ chi tiết">
                                        <input type="text" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 quan_huyen" value="<?= htmlspecialchars($row['quan_huyen'] ?? '') ?>" placeholder="Quận/Huyện">
                                        <input type="text" class="block w-full p-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 tinh_thanh_pho" value="<?= htmlspecialchars($row['tinh_thanh_pho'] ?? '') ?>" placeholder="Tỉnh/Thành phố">
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="donhang_user.php?id_kh=<?= $row['id'] ?>" class="bg-blue-100 text-blue-700 p-2 rounded-lg hover:bg-blue-200 transition-colors" title="Xem đơn hàng">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                        <button type="button" class="edit-btn bg-emerald-100 text-emerald-700 p-2 rounded-lg hover:bg-emerald-200 transition-colors" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="save-btn hidden bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition-colors" title="Lưu">
                                            <i class="fas fa-save"></i>
                                        </button>
                                        <button type="button" class="delete-btn bg-rose-100 text-rose-700 p-2 rounded-lg hover:bg-rose-200 transition-colors" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chức năng tìm kiếm và lọc
    const searchAccount = document.getElementById('searchAccount');
    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatus');
    const accountTable = document.getElementById('accountTable');
    const rows = accountTable.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchAccount.value.toLowerCase();
        const roleFilter = filterRole.value;
        const statusFilter = filterStatus.value;
        
        rows.forEach(function(row) {
            // Bỏ qua hàng thông báo "không có tài khoản"
            if (!row.getAttribute('data-id')) return;
            
            const rowRole = row.getAttribute('data-role');
            const rowStatus = row.getAttribute('data-status');
            const text = row.textContent.toLowerCase();
            
            const matchSearch = text.includes(searchTerm);
            const matchRole = roleFilter === '' || rowRole === roleFilter;
            const matchStatus = statusFilter === '' || rowStatus === statusFilter;
            
            row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
        });
    }

    searchAccount.addEventListener('keyup', filterTable);
    filterRole.addEventListener('change', filterTable);
    filterStatus.addEventListener('change', filterTable);

    // Chức năng sửa và lưu
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let row = this.closest("tr");
            let id = row.getAttribute("data-id");

            // Hiển thị form chỉnh sửa
            row.querySelectorAll(".view-mode").forEach(el => {
                el.classList.add("hidden");
            });
            row.querySelectorAll(".edit-mode").forEach(el => {
                el.classList.remove("hidden");
            });

            // Ẩn nút chỉnh sửa, hiển thị nút lưu
            this.classList.add("hidden");
            row.querySelector(".save-btn").classList.remove("hidden");
        });
    });

    document.querySelectorAll(".save-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let row = this.closest("tr");
            let id = row.getAttribute("data-id");
            
            let data = new URLSearchParams();
            data.append("edit_id", id);
            data.append("username", row.querySelector(".username").value);
            data.append("phone", row.querySelector(".phone").value);
            data.append("gender", row.querySelector(".gender").value);
            data.append("role", row.querySelector(".role").value);
            data.append("status", row.querySelector(".status").value);
            data.append("dia_chi_chi_tiet", row.querySelector(".dia_chi_chi_tiet").value);
            data.append("quan_huyen", row.querySelector(".quan_huyen").value);
            data.append("tinh_thanh_pho", row.querySelector(".tinh_thanh_pho").value);
            
            let password = row.querySelector(".password").value;
            if (password) data.append("password", password);

            fetch("quanlytaikhoan.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: data.toString()
            }).then(response => response.text())
            .then(result => {
                if (result === "success") {
                    // Hiển thị thông báo
                    showNotification("Cập nhật tài khoản thành công!", "success");
                    
                    // Cập nhật dữ liệu hiển thị trên trang
                    setTimeout(() => {
                        location.reload(); // Tải lại trang để hiển thị dữ liệu mới nhất
                    }, 1000);
                } else {
                    alert("Lỗi: " + result);
                }
            }).catch(error => {
                console.error("Lỗi fetch:", error);
            });
        });
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            if (!confirm("Bạn có chắc chắn muốn xóa tài khoản này? Mọi đơn hàng liên quan cũng sẽ bị xóa!")) return;

            let row = this.closest("tr");
            let id = row.getAttribute("data-id");

            fetch("quanlytaikhoan.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "delete_id=" + id
            })
            .then(response => response.text())
            .then(result => {
                if (result === "success") {
                    row.remove(); // Xóa hàng khỏi bảng ngay lập tức
                    showNotification("Đã xóa tài khoản thành công!", "success");
                } else {
                    alert("Lỗi khi xóa: " + result);
                }
            }).catch(error => {
                console.error("Lỗi fetch:", error);
            });
        });
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