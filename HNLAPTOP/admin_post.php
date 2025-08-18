<?php

include 'db.php';

$thongbao = '';
$edit_mode = false;
$edit_id = null;
$edit_data = [
  'title' => '',
  'image' => '',
  'content' => '',
  'status' => 'active'
];

// Hàm lấy ID user từ username
function getUserId($conn, $username) {
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user ? $user['id'] : null;
}

// Lấy thông tin user hiện tại
$current_username = $_SESSION['username'] ?? 'huymoba04'; // Fallback nếu không có session
$current_user_id = getUserId($conn, $current_username);

if (!$current_user_id) {
    $thongbao = '<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg"><div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-circle"></i></div><div class="ml-3"><p class="text-sm">Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.</p></div></div></div>';
}

// Xử lý xóa bài viết
if (isset($_GET['delete']) && $current_user_id) {
  $id = intval($_GET['delete']);
  // Xóa file ảnh nếu có
  $res_img = $conn->query("SELECT image FROM posts WHERE id=$id");
  if ($row_img = $res_img->fetch_assoc()) {
    if ($row_img['image'] && file_exists($row_img['image'])) unlink($row_img['image']);
  }
  $conn->query("DELETE FROM posts WHERE id=$id");
  header("Location: ?page=admin_post.php&deleted=1");
  exit;
}

// Xử lý lấy dữ liệu để sửa bài viết
if (isset($_GET['edit'])) {
  $edit_mode = true;
  $edit_id = intval($_GET['edit']);
  $res_edit = $conn->query("SELECT * FROM posts WHERE id=$edit_id");
  if ($edit_row = $res_edit->fetch_assoc()) {
    $edit_data = $edit_row;
  } else {
    $thongbao = '<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg"><div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-circle"></i></div><div class="ml-3"><p class="text-sm">Không tìm thấy bài viết để sửa.</p></div></div></div>';
    $edit_mode = false;
  }
}

// Xử lý lưu bài viết mới hoặc cập nhật bài đã sửa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_user_id) {
  $title = $_POST['title'];
  $content = $_POST['content'];
  $status = $_POST['status'];
  $image_url = isset($_POST['old_image']) ? $_POST['old_image'] : '';

  // Xử lý upload ảnh nếu có chọn file mới
  if (isset($_FILES['imagefile']) && $_FILES['imagefile']['error'] == UPLOAD_ERR_OK) {
    $allow_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['imagefile']['type'], $allow_types)) {
      $thongbao = '<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg"><div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-circle"></i></div><div class="ml-3"><p class="text-sm">Chỉ cho phép upload ảnh JPG, PNG, GIF, WEBP.</p></div></div></div>';
    } else {
      $target_dir = "uploads/";
      if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
      $filename = time() . "_" . basename($_FILES["imagefile"]["name"]);
      $target_file = $target_dir . $filename;
      if (move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file)) {
        // Nếu sửa và có ảnh cũ, xóa ảnh cũ trên server
        if (isset($_POST['old_image']) && $_POST['old_image'] && file_exists($_POST['old_image'])) {
          unlink($_POST['old_image']);
        }
        $image_url = $target_file;
      }
    }
  }

  // Nếu đang sửa bài viết
  if (isset($_POST['edit_id']) && $_POST['edit_id']) {
    $id = intval($_POST['edit_id']);
    if (empty($thongbao)) {
      // Cập nhật bài viết (không thay đổi author_id khi sửa)
      $sql = "UPDATE posts SET title=?, image=?, content=?, status=? WHERE id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssi", $title, $image_url, $content, $status, $id);
      if ($stmt->execute()) {
        header("Location: ?page=admin_post.php&updated=1");
        exit;
      } else {
        $thongbao = '<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg"><div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-circle"></i></div><div class="ml-3"><p class="text-sm">Lỗi khi cập nhật: ' . $conn->error . '</p></div></div></div>';
      }
      $stmt->close();
    }
  } else {
    // Đang thêm bài mới - THÊM author_id
    if (empty($thongbao)) {
      $sql = "INSERT INTO posts (title, image, content, status, author_id) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssssi", $title, $image_url, $content, $status, $current_user_id);
      if ($stmt->execute()) {
        header("Location: ?page=admin_post.php&success=1");
        exit;
      } else {
        $thongbao = '<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg"><div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-circle"></i></div><div class="ml-3"><p class="text-sm">Lỗi khi lưu: ' . $conn->error . '</p></div></div></div>';
      }
      $stmt->close();
    }
  }
}

// Lấy danh sách bài viết với thông tin tác giả
$res = $conn->query("
    SELECT p.*, u.username as author_username, u.ho_va_ten as author_name 
    FROM posts p 
    LEFT JOIN users u ON p.author_id = u.id 
    ORDER BY p.created_at DESC
");

// Thống kê bài viết
$total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts")->fetch_assoc()['total'];
$active_posts = $conn->query("SELECT COUNT(*) AS active FROM posts WHERE status='active'")->fetch_assoc()['active'];
$inactive_posts = $conn->query("SELECT COUNT(*) AS inactive FROM posts WHERE status='inactive'")->fetch_assoc()['inactive'];
$has_image_posts = $conn->query("SELECT COUNT(*) AS has_image FROM posts WHERE image != '' AND image IS NOT NULL")->fetch_assoc()['has_image'];
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<link rel="stylesheet" href="style.css?<?php echo time(); ?>">

<div class="animate__animated animate__fadeIn">
  <!-- Tiêu đề và thống kê nhanh -->
  <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-md p-5 mb-6 text-white overflow-hidden relative">
    <div class="absolute right-0 top-0 opacity-10">
      <i class="fas fa-newspaper text-9xl transform translate-x-6 -translate-y-6"></i>
    </div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
      <div class="z-10">
        <h2 class="text-2xl font-bold font-heading mb-1">Quản lý bài viết</h2>
        <p class="text-indigo-100">Quản lý tất cả bài viết trong hệ thống</p>
        <?php if ($current_user_id): ?>
          <p class="text-indigo-200 text-sm mt-1">
            <i class="fas fa-user mr-1"></i>Đang đăng nhập: <?= htmlspecialchars($current_username) ?>
          </p>
        <?php endif; ?>
      </div>
      <div class="mt-4 md:mt-0 z-10 flex space-x-3">
        <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
          <i class="fas fa-newspaper mr-2"></i>
          <div>
            <div class="text-xs text-indigo-200">Tổng số bài viết</div>
            <div class="font-bold"><?= $total_posts ?></div>
          </div>
        </div>
        <div class="glass-effect px-4 py-2 rounded-lg flex items-center">
          <i class="fas fa-eye mr-2"></i>
          <div>
            <div class="text-xs text-indigo-200">Đang hiển thị</div>
            <div class="font-bold"><?= $active_posts ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Thống kê bài viết -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-sm border border-green-100/60 p-4 hover:shadow transition-all duration-300">
      <div class="flex items-center">
        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
          <i class="fas fa-eye"></i>
        </div>
        <div>
          <h3 class="text-xl font-bold text-slate-800"><?= $active_posts ?></h3>
          <p class="text-xs text-slate-500 font-medium">Bài viết đang hiển thị</p>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-white to-orange-50 rounded-xl shadow-sm border border-orange-100/60 p-4 hover:shadow transition-all duration-300">
      <div class="flex items-center">
        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
          <i class="fas fa-eye-slash"></i>
        </div>
        <div>
          <h3 class="text-xl font-bold text-slate-800"><?= $inactive_posts ?></h3>
          <p class="text-xs text-slate-500 font-medium">Bài viết ẩn</p>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm border border-blue-100/60 p-4 hover:shadow transition-all duration-300">
      <div class="flex items-center">
        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white shadow-lg mr-3">
          <i class="fas fa-image"></i>
        </div>
        <div>
          <h3 class="text-xl font-bold text-slate-800"><?= $has_image_posts ?></h3>
          <p class="text-xs text-slate-500 font-medium">Bài viết có ảnh</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Thông báo -->
  <?php if (isset($_GET['success'])): ?>
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm">Đã lưu bài viết thành công!</p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['updated'])): ?>
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm">Đã cập nhật bài viết thành công!</p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm">Đã xóa bài viết thành công!</p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?= $thongbao ?>

  <!-- Form thêm/sửa bài viết -->
  <?php if ($current_user_id): ?>
  <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6 mb-6" id="addPostForm" <?php if (!$edit_mode) echo 'style="display:none;"'; ?>>
    <h3 class="text-lg font-medium text-slate-800 mb-4 flex items-center">
      <i class="fas fa-<?= $edit_mode ? 'edit' : 'plus-circle' ?> text-indigo-600 mr-2"></i> <?= $edit_mode ? "Sửa bài viết" : "Thêm bài viết mới" ?>
    </h3>

    <form method="post" enctype="multipart/form-data">
      <?php if ($edit_mode): ?>
        <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
      <?php endif; ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Tiêu đề <span class="text-rose-500">*</span></label>
          <input type="text" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" name="title" required value="<?= htmlspecialchars($edit_data['title']) ?>">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1">Trạng thái</label>
          <select class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" name="status">
            <option value="active" <?= $edit_data['status'] == 'active' ? 'selected' : '' ?>>Đang hiển thị</option>
            <option value="inactive" <?= $edit_data['status'] == 'inactive' ? 'selected' : '' ?>>Ẩn</option>
          </select>
        </div>
      </div>

      <div class="mt-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Ảnh đại diện</label>
        <div class="flex items-center space-x-4">
          <input type="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" name="imagefile" accept="image/*">
          <?php if ($edit_mode && $edit_data['image']): ?>
            <div class="flex items-center">
              <img src="<?= htmlspecialchars($edit_data['image']) ?>" class="h-10 w-auto object-cover rounded-lg" alt="Preview">
              <input type="hidden" name="old_image" value="<?= htmlspecialchars($edit_data['image']) ?>">
            </div>
          <?php elseif (!$edit_mode): ?>
            <input type="hidden" name="old_image" value="">
          <?php endif; ?>
        </div>
      </div>

      <div class="mt-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Nội dung bài viết <span class="text-rose-500">*</span></label>
        <textarea name="content" id="content" class="block w-full p-2.5 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="8"><?= htmlspecialchars($edit_data['content']) ?></textarea>
      </div>

      <div class="mt-4 flex">
        <?php if ($edit_mode): ?>
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
            <i class="fas fa-save mr-2"></i> Cập nhật bài viết
          </button>
          <a href="?page=admin_post.php" class="ml-2 inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
            <i class="fas fa-times mr-2"></i> Huỷ sửa
          </a>
        <?php else: ?>
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
            <i class="fas fa-plus mr-2"></i> Thêm bài viết
          </button>
        <?php endif; ?>
      </div>
    </form>
  </div>
  <?php if (!$edit_mode): ?>
    <div class="mb-4">
      <button id="showAddPostFormBtn" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
        <i class="fas fa-plus mr-2"></i> Thêm bài viết mới
      </button>
    </div>
  <?php endif; ?>
  <?php else: ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm">Bạn cần đăng nhập để có thể đăng bài viết.</p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Danh sách bài viết -->
  <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center">
      <h3 class="font-bold text-slate-800 flex items-center">
        <i class="fas fa-list text-indigo-600 mr-2"></i> Danh sách bài viết
      </h3>
      <div class="mt-3 sm:mt-0 relative">
        <input type="text" id="searchInput" placeholder="Tìm kiếm bài viết..." class="block w-full pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400"></i>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full" id="postsTable">
        <thead class="bg-slate-100 text-slate-700 text-sm font-medium">
          <tr>
            <th class="py-3 px-4 text-left">ID</th>
            <th class="py-3 px-4 text-left">Tiêu đề</th>
            <th class="py-3 px-4 text-left">Tác giả</th>
            <th class="py-3 px-4 text-left">Ảnh đại diện</th>
            <th class="py-3 px-4 text-left">Trạng thái</th>
            <th class="py-3 px-4 text-left">Ngày đăng</th>
            <th class="py-3 px-4 text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <?php if ($res->num_rows === 0): ?>
            <tr>
              <td colspan="7" class="py-6 text-center text-slate-500">
                <div class="flex flex-col items-center justify-center">
                  <i class="fas fa-newspaper text-4xl text-slate-300 mb-3"></i>
                  <p>Chưa có bài viết nào</p>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php $index = 0;
            while ($row = $res->fetch_assoc()): ?>
              <tr class="hover:bg-slate-50 transition-colors animate__animated animate__fadeIn animate__faster"
                style="animation-delay: <?= $index * 0.05 ?>s">
                <td class="py-3 px-4 font-medium"><?= $row['id'] ?></td>
                <td class="py-3 px-4 font-medium max-w-sm truncate"><?= htmlspecialchars($row['title']) ?></td>
                <td class="py-3 px-4">
                  <?php if ($row['author_name']): ?>
                    <div>
                      <div class="font-medium text-slate-800"><?= htmlspecialchars($row['author_name']) ?></div>
                      <div class="text-xs text-slate-500">@<?= htmlspecialchars($row['author_username']) ?></div>
                    </div>
                  <?php elseif ($row['author_username']): ?>
                    <div class="text-slate-600">@<?= htmlspecialchars($row['author_username']) ?></div>
                  <?php else: ?>
                    <span class="text-slate-400 italic">Không xác định</span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-4">
                  <?php if ($row['image']): ?>
                    <img src="<?= htmlspecialchars($row['image']) ?>" class="w-16 h-12 object-cover rounded-lg shadow-sm" alt="<?= htmlspecialchars($row['title']) ?>">
                  <?php else: ?>
                    <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                      <i class="fas fa-image text-slate-400"></i>
                    </div>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-4">
                  <?php if ($row['status'] == 'active'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                      <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                      Đang hiển thị
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 bg-slate-100 text-slate-800 rounded-full text-xs font-medium">
                      <span class="w-1.5 h-1.5 bg-slate-500 rounded-full mr-1.5"></span>
                      Ẩn
                    </span>
                  <?php endif; ?>
                </td>
                <td class="py-3 px-4 text-slate-600">
                  <span class="whitespace-nowrap">
                    <i class="far fa-calendar-alt mr-1"></i>
                    <?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                  </span>
                </td>
                <td class="py-3 px-4">
                  <div class="flex items-center justify-center space-x-2">
                    <a href="?page=admin_post.php&edit=<?= $row['id'] ?>"
                      class="bg-emerald-100 text-emerald-700 p-2 rounded-lg hover:bg-emerald-200 transition-colors"
                      title="Chỉnh sửa">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?page=admin_post.php&delete=<?= $row['id'] ?>"
                      onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');"
                      class="bg-rose-100 text-rose-700 p-2 rounded-lg hover:bg-rose-200 transition-colors"
                      title="Xóa">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php $index++;
            endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
  $(document).ready(function() {
    // Initialize Summernote
    $('#content').summernote({
      height: 300,
      placeholder: 'Nhập nội dung bài viết...',
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });

    // Xử lý tìm kiếm
    $('#searchInput').on('keyup', function() {
      const value = $(this).val().toLowerCase();
      $('#postsTable tbody tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('showAddPostFormBtn');
    var form = document.getElementById('addPostForm');
    if (btn && form) {
      btn.onclick = function() {
        form.style.display = 'block';
        btn.style.display = 'none';
        // Focus vào ô tiêu đề
        var titleInput = form.querySelector('input[name="title"]');
        if (titleInput) titleInput.focus();
      };
    }
  });
</script>

<?php $conn->close(); ?>