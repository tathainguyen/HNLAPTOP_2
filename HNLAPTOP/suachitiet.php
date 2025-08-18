<?php
require 'mod/hanghoa.php';
if (!isset($_GET['id'])) {
  header('Location: quanly.php?page=hanghoa.php');
  exit();
}
$id = $_GET['id'];
$hh = new HangHoa();
$sp = $hh->getById($id);

if (!$sp) {
  echo "Không tìm thấy sản phẩm!";
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $chitiet = $_POST['chitiet'];
  $hh->updateChiTiet($id, $chitiet);
  header("Location: quanly.php?page=hanghoa.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sửa chi tiết sản phẩm - HN Laptop</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- jQuery (bắt buộc cho Summernote) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Summernote CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#5A67D8',
            'primary-dark': '#4C51BF',
            'primary-light': '#7F9CF5',
            secondary: '#8B5CF6',
            accent: '#F472B6',
            darkbg: '#252F3F',
            lightbg: '#F8FAFC',
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
            heading: ['Montserrat', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #F1F5F9;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    .font-heading {
      font-family: 'Montserrat', sans-serif;
    }

    .pattern-bg {
      background-color: #5A67D8;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 20.83l2.83-2.83 1.41 1.41L1.41 22H0v-1.17zM0 3.07l2.83-2.83 1.41 1.41L1.41 4.24H0V3.07zm28.24 35.17l1.41-1.41 2.83 2.83V40h-1.41l-2.83-2.83zm-8.48 0l1.41-1.41 2.83 2.83V40h-1.41l-2.83-2.83zm-8.48 0l1.41-1.41 2.83 2.83V40h-1.41l-2.83-2.83zm-8.48 0l1.41-1.41 2.83 2.83V40h-1.41l-2.83-2.83zm25.45-35.17l1.41 1.41L26.17 8.24h1.41V7.07L30.42 4.24h-1.42L26.17 7.07zm-8.47 0l1.41 1.41L17.7 8.24h1.41V7.07L21.95 4.24h-1.42L17.7 7.07zm-8.48 0l1.41 1.41L9.22 8.24h1.41V7.07L13.47 4.24h-1.42L9.22 7.07zm-8.48 0l1.41 1.41L.74 8.24h1.41V7.07L4.99 4.24H3.57L.74 7.07z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #c7d2fe;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #5A67D8;
    }

    /* Note editor customizations */
    .note-editor {
      border-radius: 0.5rem !important;
      border-color: #e2e8f0 !important;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    }

    .note-toolbar {
      background-color: #f8fafc !important;
      border-bottom: 1px solid #e2e8f0 !important;
      border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .note-btn {
      border-color: #cbd5e1 !important;
      background-color: white !important;
    }

    .note-btn:hover {
      background-color: #f1f5f9 !important;
    }

    .note-editor.note-frame .note-editing-area .note-editable {
      background-color: white !important;
      color: #334155 !important;
      padding: 1rem !important;
    }
  </style>
</head>

<body>
  <!-- Top Navigation with Pattern Background -->
  <nav class="pattern-bg text-white shadow-lg">
    <div class="container mx-auto">
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <div class="flex items-center space-x-3 hover-lift cursor-pointer">
          <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg animate__animated animate__fadeIn">
            <i class="fas fa-laptop text-indigo-600 text-xl"></i>
          </div>
          <div>
            <span class="font-heading text-2xl font-bold tracking-wider">HN LAPTOP</span>
            <p class="text-xs text-indigo-200 -mt-1">Quản lý hệ thống</p>
          </div>
        </div>

        <!-- Back button -->
        <div>
          <a href="quanly.php?page=hanghoa.php" class="flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition-all duration-300">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Quay lại trang quản lý</span>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mx-auto px-4 py-8 animate__animated animate__fadeIn">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center animate__animated animate__slideInDown">
      <div>
        <h1 class="text-3xl font-heading font-bold text-slate-800 mb-1">Chỉnh sửa chi tiết sản phẩm</h1>
        <p class="text-slate-500">
          Cập nhật thông tin chi tiết cho sản phẩm
        </p>
      </div>

      <nav class="flex mt-4 md:mt-0" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white px-4 py-2 rounded-lg shadow-sm">
          <li class="inline-flex items-center">
            <a href="quanly.php" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
              <i class="fas fa-home mr-2"></i>
              Trang chủ
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-slate-400 mx-2 text-sm"></i>
              <a href="quanly.php?page=hanghoa.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Hàng hóa
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <i class="fas fa-chevron-right text-slate-400 mx-2 text-sm"></i>
              <span class="text-sm font-medium text-slate-500">Sửa chi tiết</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>

    <!-- Product Information Card -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6 items-start">
        <!-- Product Image -->
        <div class="w-full md:w-1/4 lg:w-1/6">
          <?php if (!empty($sp->hinhanh)): ?>
            <div class="rounded-xl overflow-hidden shadow-sm border border-slate-200 aspect-square">
              <img src="uploads/<?= htmlspecialchars($sp->hinhanh) ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($sp->tenhanghoa) ?>">
            </div>
          <?php else: ?>
            <div class="w-full aspect-square bg-slate-100 rounded-xl flex items-center justify-center">
              <i class="fas fa-laptop text-slate-400 text-4xl"></i>
            </div>
          <?php endif; ?>
        </div>

        <!-- Product Details -->
        <div class="w-full md:w-3/4 lg:w-5/6">
          <h3 class="text-xl font-medium text-slate-800 mb-2"><?= htmlspecialchars($sp->tenhanghoa) ?></h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="bg-slate-50 p-3 rounded-lg">
              <span class="text-sm font-medium text-slate-600">Mô tả:</span>
              <p class="text-slate-700"><?= htmlspecialchars($sp->mota) ?></p>
            </div>

            <div class="bg-slate-50 p-3 rounded-lg">
              <span class="text-sm font-medium text-slate-600">Giá tham khảo:</span>
              <p class="text-indigo-600 font-medium"><?= number_format($sp->giathamkhao, 0, ',', '.') ?> VNĐ</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Editor Card -->
    <div class="bg-white shadow-sm rounded-xl border border-slate-200/60 overflow-hidden mb-6">
      <div class="border-b border-slate-200 bg-slate-50 p-4 flex justify-between items-center">
        <h3 class="text-lg font-medium text-slate-800 flex items-center">
          <i class="fas fa-edit text-indigo-600 mr-2"></i>
          Chi tiết sản phẩm
        </h3>
        <div>
          <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-1 rounded-full">
            ID: <?= $sp->idhanghoa ?>
          </span>
        </div>
      </div>

      <div class="p-6">
        <form method="post" id="editForm">
          <div class="form-group mb-4">
            <label class="block text-sm font-medium text-slate-700 mb-2">Chi tiết sản phẩm</label>
            <textarea name="chitiet" id="chitiet" rows="10"><?= htmlspecialchars($sp->chitiet) ?></textarea>
          </div>

          <div class="flex flex-wrap gap-2 mt-6">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-300 shadow-sm">
              <i class="fas fa-save mr-2"></i> Lưu thay đổi
            </button>
            <a href="quanly.php?page=hanghoa.php" class="inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-medium rounded-lg transition-colors duration-300">
              <i class="fas fa-times mr-2"></i> Hủy
            </a>
          </div>
        </form>
      </div>
    </div>



  <script>
    $(document).ready(function() {
      $('#chitiet').summernote({
        height: 450,
        placeholder: 'Nhập chi tiết sản phẩm, có thể chèn ảnh, bảng, định dạng...',
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
          onImageUpload: function(files) {
            var data = new FormData();
            data.append("file", files[0]);
            $.ajax({
              url: 'upload_image.php', // file xử lý upload ảnh
              cache: false,
              contentType: false,
              processData: false,
              data: data,
              type: "POST",
              success: function(url) {
                $('#chitiet').summernote('insertImage', url);
              },
              error: function(jqXHR, textStatus, errorThrown) {
                alert('Không upload được ảnh: ' + errorThrown);
              }
            });
          }
        }
      });

      // Form submit animation
      $('#editForm').on('submit', function() {
        $('button[type="submit"]').html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Đang lưu...');
      });
    });
  </script>
</body>

</html>