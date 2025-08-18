<?php
ob_start(); 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dangnhap.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'thongke.php';
$active_page = basename($page, '.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HN Laptop - Quản Lý Hệ Thống</title>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
        .nav-link {
            display: flex;
            align-items: center;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            color: #fff;
            transition-property: all;
            transition-duration: 300ms;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(67, 56, 202, 0.2);
        }

        .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1.125rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: #fff;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
        }

        .btn-success {
            background-color: #10b981;
            color: #fff;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #f43f5e;
            color: #fff;
            transition: all 0.3s;
        }

        .btn-danger:hover {
            background-color: #e11d48;
            transform: translateY(-2px);
        }

        .card {
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(30, 41, 59, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.6);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.12);
        }

        .form-input-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .stat-card {
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(30, 41, 59, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.6);
            background: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding-left: 1rem;
            padding-right: 1rem;
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            font-size: 0.875rem;
            color: #334155;
            width: 100%;
            text-align: left;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
        }

        .dropdown-item i {
            margin-right: 0.625rem;
            color: #6366f1;
            width: 1.25rem;
            text-align: center;
        }

        .modal-custom-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-custom-header i {
            font-size: 1.125rem;
        }
    </style>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
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

        /* Background patterns */
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

        /* Tooltip styling */
        .custom-tooltip {
            position: relative;
        }

        .custom-tooltip:hover:after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
        }

        /* Custom animations */
        .hover-lift {
            transition: transform 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }

        /* Sweet Alert Customization */
        .swal2-popup {
            border-radius: 16px !important;
        }

        .swal2-title {
            font-family: 'Montserrat', sans-serif !important;
        }

        /* Glass effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-slate-50">
    <!-- Top Navigation with Pattern Background -->
    <nav class="pattern-bg text-white shadow-lg">
        <div class="container mx-auto">
            <div class="flex justify-between items-center py-4">
                <!-- Logo with animated effect on hover -->
                <div class="flex items-center space-x-3 hover-lift cursor-pointer">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg animate__animated animate__fadeIn">
                        <i class="fas fa-laptop text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <span class="font-heading text-2xl font-bold tracking-wider">HN LAPTOP</span>
                        <p class="text-xs text-indigo-200 -mt-1">Quản lý hệ thống</p>
                    </div>
                </div>

                <!-- Main Navigation with hover animations -->
                <div class="hidden lg:flex space-x-2">
                    <a href="quanly.php?page=thongke.php"
                        class="nav-link <?= $active_page == 'thongke' ? 'active animate__animated animate__pulse' : '' ?>"
                        data-tooltip="Thống kê">
                        <i class="fas fa-chart-bar"></i>
                        <span>Thống Kê</span>
                    </a>

                    <a href="quanly.php?page=hanghoa.php"
                        class="nav-link <?= $active_page == 'hanghoa' ? 'active animate__animated animate__pulse' : '' ?>"
                        data-tooltip="Quản lý hàng hóa">
                        <i class="fas fa-laptop"></i>
                        <span>Hàng Hóa</span>
                    </a>

                    <a href="quanly.php?page=loaihang.php"
                        class="nav-link <?= $active_page == 'loaihang' ? 'active animate__animated animate__pulse' : '' ?>"
                        data-tooltip="Quản lý loại hàng">
                        <i class="fas fa-tags"></i>
                        <span>Loại Hàng</span>
                    </a>

                    <a href="quanly.php?page=quanly_donhang.php"
                        class="nav-link <?= $active_page == 'quanly_donhang' ? 'active animate__animated animate__pulse' : '' ?> relative custom-tooltip"
                        data-tooltip="Quản lý đơn hàng">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Đơn Hàng</span>
                    </a>

                    <a href="quanly.php?page=quanlytaikhoan.php"
                        class="nav-link <?= $active_page == 'quanlytaikhoan' ? 'active animate__animated animate__pulse' : '' ?>"
                        data-tooltip="Quản lý tài khoản">
                        <i class="fas fa-users"></i>
                        <span>Tài Khoản</span>
                    </a>

                    <!-- Dropdown menu cho Liên hệ -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            @click.away="open = false"
                            class="nav-link <?= in_array($active_page, ['admin_contact', 'admin_voucher', 'admin_post', 'admin_danhgia']) ? 'active animate__animated animate__pulse' : '' ?>"
                            data-tooltip="Quản lý liên hệ & nội dung">
                            <i class="fas fa-envelope"></i>
                            <span>Liên Hệ & Nội dung</span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>

                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute z-30 mt-1 w-56 rounded-xl bg-white shadow-lg border border-slate-200/70"
                            style="display: none;">
                            <div class="py-2 rounded-xl overflow-hidden">
                                <a href="quanly.php?page=admin_contact.php"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_contact' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                    <i class="fas fa-inbox w-5 mr-2 text-indigo-500"></i>
                                    Quản lý liên hệ
                                </a>
                                <a href="quanly.php?page=admin_voucher.php"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_voucher' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                    <i class="fas fa-ticket-alt w-5 mr-2 text-indigo-500"></i>
                                    Quản lý voucher
                                </a>
                                <a href="quanly.php?page=admin_post.php"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_post' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                    <i class="fas fa-newspaper w-5 mr-2 text-indigo-500"></i>
                                    Quản lý bài viết
                                </a>
                                <a href="quanly.php?page=admin_danhgia.php"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_danhgia' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                    <i class="fas fa-star w-5 mr-2 text-indigo-500"></i>
                                    Quản lý đánh giá
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Menu with Glass Effect -->
                <div class="flex items-center">


                    <!-- User dropdown with glass effect -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 glass-effect hover:bg-white/30 rounded-full px-4 py-2 focus:outline-none transition-all duration-300">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                <?= isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'A' ?>
                            </div>
                            <span class="hidden md:inline"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="animate__animated animate__fadeInDown animate__faster"
                            x-transition:leave="animate__animated animate__fadeOutUp animate__faster"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 z-50" style="display: none;">
                            <div class="flex items-center px-4 py-3 border-b border-slate-100">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-md mr-3">
                                    <?= isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'A' ?>
                                </div>
                                <div>
                                    <div class="font-medium text-slate-800"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?></div>
                                    <div class="text-xs text-slate-500">Quản trị viên</div>
                                </div>
                            </div>
                            <a href="#" class="dropdown-item mt-2" data-bs-toggle="modal" data-bs-target="#profileModal">
                                <i class="fas fa-user-circle"></i> Hồ sơ
                            </a>
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                <i class="fas fa-cog"></i> Cài đặt
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-question-circle"></i> Trợ giúp
                            </a>
                            <hr class="my-2 border-slate-100">
                            <a href="#" id="logoutBtn" class="dropdown-item text-rose-600 hover:bg-rose-50">
                                <i class="fas fa-sign-out-alt text-rose-500"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex justify-end pb-4">
                <button id="mobile-menu-button" class="text-white focus:outline-none p-2 rounded-lg hover:bg-white/10">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden hidden pb-4 animate__animated animate__fadeIn">
                <div class="space-y-2">
                    <a href="quanly.php?page=thongke.php"
                        class="nav-link <?= $active_page == 'thongke' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar"></i>
                        <span>Thống Kê</span>
                    </a>

                    <a href="quanly.php?page=hanghoa.php"
                        class="nav-link <?= $active_page == 'hanghoa' ? 'active' : '' ?>">
                        <i class="fas fa-laptop"></i>
                        <span>Hàng Hóa</span>
                    </a>

                    <a href="quanly.php?page=loaihang.php"
                        class="nav-link <?= $active_page == 'loaihang' ? 'active' : '' ?>">
                        <i class="fas fa-tags"></i>
                        <span>Loại Hàng</span>
                    </a>

                    <a href="quanly.php?page=quanly_donhang.php"
                        class="nav-link <?= $active_page == 'quanly_donhang' ? 'active' : '' ?> relative">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Đơn Hàng</span>
                        <span class="ml-2 bg-rose-500 text-xs rounded-full px-1.5 py-0.5">new</span>
                    </a>

                    <a href="quanly.php?page=quanlytaikhoan.php"
                        class="nav-link <?= $active_page == 'quanlytaikhoan' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Tài Khoản</span>
                    </a>

                    <!-- Mobile menu dropdown cho Liên hệ -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full nav-link <?= in_array($active_page, ['admin_contact', 'admin_voucher', 'admin_post', 'admin_danhgia']) ? 'active' : '' ?>">
                            <div class="flex justify-between items-center w-full">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope"></i>
                                    <span class="ml-2">Liên Hệ & Nội dung</span>
                                </div>
                                <i class="fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>

                        <div x-show="open" class="pl-8 mt-1 space-y-1" style="display: none;">
                            <a href="quanly.php?page=admin_contact.php"
                                class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_contact' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                <i class="fas fa-inbox mr-2 text-indigo-500"></i>
                                Quản lý liên hệ
                            </a>
                            <a href="quanly.php?page=admin_voucher.php"
                                class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_voucher' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                <i class="fas fa-ticket-alt mr-2 text-indigo-500"></i>
                                Quản lý voucher
                            </a>
                            <a href="quanly.php?page=admin_post.php"
                                class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_post' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                <i class="fas fa-newspaper mr-2 text-indigo-500"></i>
                                Quản lý bài viết
                            </a>
                            <a href="quanly.php?page=admin_danhgia.php"
                                class="flex items-center px-4 py-2 rounded-lg text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors <?= $active_page == 'admin_danhgia' ? 'bg-indigo-50 text-indigo-700 font-medium' : '' ?>">
                                <i class="fas fa-star mr-2 text-indigo-500"></i>
                                Quản lý đánh giá
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with Animation -->
    <div class="container mx-auto px-4 py-8 animate__animated animate__fadeIn">
        <!-- Page Header with Animation -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center animate__animated animate__slideInDown">
            <div>
                <h1 class="text-3xl font-heading font-bold text-slate-800 mb-1">
                    <?php
                    switch ($active_page) {
                        case 'thongke':
                            echo 'Thống Kê Hệ Thống';
                            break;
                        case 'hanghoa':
                            echo 'Quản Lý Hàng Hóa';
                            break;
                        case 'loaihang':
                            echo 'Quản Lý Loại Hàng';
                            break;
                        case 'quanly_donhang':
                            echo 'Quản Lý Đơn Hàng';
                            break;
                        case 'quanlytaikhoan':
                            echo 'Quản Lý Tài Khoản';
                            break;
                        case 'admin_contact':
                            echo 'Quản Lý Liên Hệ';
                            break;
                        case 'admin_voucher':
                            echo 'Quản Lý Voucher';
                            break;
                        case 'admin_post':
                            echo 'Quản Lý Bài Viết';
                            break;
                        case 'admin_danhgia':
                            echo 'Quản Lý Đánh Giá';
                            break;
                        default:
                            echo 'Quản Lý';
                    }
                    ?>
                </h1>
                <p class="text-slate-500">
                    <?php
                    echo 'Hôm nay: ' . date('d/m/Y');
                    ?>
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
                            <span class="text-sm font-medium text-slate-500">
                                <?php
                                switch ($active_page) {
                                    case 'thongke':
                                        echo 'Thống Kê';
                                        break;
                                    case 'hanghoa':
                                        echo 'Hàng Hóa';
                                        break;
                                    case 'loaihang':
                                        echo 'Loại Hàng';
                                        break;
                                    case 'quanly_donhang':
                                        echo 'Đơn Hàng';
                                        break;
                                    case 'quanlytaikhoan':
                                        echo 'Tài Khoản';
                                        break;
                                    case 'admin_contact':
                                        echo 'Liên Hệ';
                                        break;
                                    case 'admin_voucher':
                                        echo 'Voucher';
                                        break;
                                    case 'admin_post':
                                        echo 'Bài Viết';
                                        break;
                                    case 'admin_danhgia':
                                        echo 'Đánh Giá';
                                        break;
                                    default:
                                        echo 'Bảng Điều Khiển';
                                }
                                ?>
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>



        <!-- Content Area -->
        <div class="card p-6 animate__animated animate__fadeIn">
            <?php include $page; ?>
        </div>
    </div>

    <!-- Profile Modal with Animation -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-xl">
                <div class="modal-header bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                    <h5 class="modal-title modal-custom-header">
                        <i class="fas fa-user-circle"></i>
                        <span>Hồ sơ người dùng</span>
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <div class="text-center mb-6">
                        <div class="w-28 h-28 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center text-5xl mx-auto shadow-xl animate__animated animate__zoomIn">
                            <?= isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'A' ?>
                        </div>
                        <h4 class="mt-4 font-heading font-bold text-xl text-slate-800"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?></h4>
                        <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">Quản trị viên</span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <span class="font-medium text-slate-800">Tên đăng nhập:</span>
                            <span class="text-slate-600"><?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin' ?></span>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <span class="font-medium text-slate-800">Vai trò:</span>
                            <span class="text-slate-600">Quản trị viên</span>
                        </div>

                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <span class="font-medium text-slate-800">Đăng nhập gần đây:</span>
                            <span class="text-slate-600"><?= date('d/m/Y H:i:s') ?></span>
                        </div>
                    </div>

                    <!-- Activity List -->
                    <div class="mb-4">
                        <h5 class="font-heading font-semibold text-slate-800 mb-3">Hoạt động gần đây</h5>
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-sign-in-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-800">Đăng nhập thành công</p>
                                    <p class="text-xs text-slate-500"><?= date('d/m/Y H:i:s') ?></p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-800">Cập nhật hồ sơ</p>
                                    <p class="text-xs text-slate-500"><?= date('d/m/Y', strtotime('-2 days')) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-t-0 rounded-b-xl">
                    <button type="button" class="btn btn-outline-secondary rounded-lg" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 rounded-lg" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal with Improved UI -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-xl">
                <div class="modal-header bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                    <h5 class="modal-title modal-custom-header">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt hệ thống</span>
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <div class="space-y-6">
                        <!-- Theme Selection with Visual Examples -->
                        <div>
                            <label class="form-label font-medium text-slate-800 block mb-3">Giao diện</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="theme-option flex flex-col items-center cursor-pointer">
                                    <div class="w-full h-16 bg-white border border-slate-200 rounded-lg mb-2 flex items-center justify-center shadow-sm">
                                        <div class="w-12 h-12 bg-slate-100 rounded"></div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" id="lightTheme" class="mr-2" checked>
                                        <label for="lightTheme" class="text-sm">Sáng</label>
                                    </div>
                                </div>
                                <div class="theme-option flex flex-col items-center cursor-pointer">
                                    <div class="w-full h-16 bg-slate-800 border border-slate-700 rounded-lg mb-2 flex items-center justify-center shadow-sm">
                                        <div class="w-12 h-12 bg-slate-700 rounded"></div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" id="darkTheme" class="mr-2">
                                        <label for="darkTheme" class="text-sm">Tối</label>
                                    </div>
                                </div>
                                <div class="theme-option flex flex-col items-center cursor-pointer">
                                    <div class="w-full h-16 bg-gradient-to-r from-slate-100 to-slate-800 border border-slate-300 rounded-lg mb-2 flex items-center justify-center shadow-sm">
                                        <div class="w-12 h-12 bg-gradient-to-r from-slate-200 to-slate-700 rounded"></div>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="theme" id="autoTheme" class="mr-2">
                                        <label for="autoTheme" class="text-sm">Tự động</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Language Selection with Flags -->
                        <div>
                            <label class="form-label font-medium text-slate-800 block mb-3">Ngôn ngữ</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="lang-option flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                                    <div class="w-6 h-6 mr-3 flex-shrink-0">
                                        <img src="https://flagcdn.com/vn.svg" class="w-full h-full object-cover rounded" alt="Tiếng Việt">
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="language" id="langVi" class="mr-2" checked>
                                        <label for="langVi" class="text-sm font-medium">Tiếng Việt</label>
                                    </div>
                                </div>
                                <div class="lang-option flex items-center p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                                    <div class="w-6 h-6 mr-3 flex-shrink-0">
                                        <img src="https://flagcdn.com/gb.svg" class="w-full h-full object-cover rounded" alt="English">
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" name="language" id="langEn" class="mr-2">
                                        <label for="langEn" class="text-sm font-medium">English</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div>
                            <label class="form-label font-medium text-slate-800 block mb-3">Thông báo</label>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium">Nhận thông báo mới</p>
                                        <p class="text-xs text-slate-500">Hiển thị thông báo về đơn hàng, khách hàng và sản phẩm</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notifCheck" checked>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium">Âm thanh thông báo</p>
                                        <p class="text-xs text-slate-500">Phát âm thanh khi có thông báo mới</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="soundCheck">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium">Email thông báo</p>
                                        <p class="text-xs text-slate-500">Gửi email khi có thông báo quan trọng</p>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailCheck" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-t-0 rounded-b-xl">
                    <button type="button" class="btn btn-outline-secondary rounded-lg" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal with Improved UX -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-xl">
                <div class="modal-header bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
                    <h5 class="modal-title modal-custom-header">
                        <i class="fas fa-key"></i>
                        <span>Đổi mật khẩu</span>
                    </h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-6">
                    <form id="changePasswordForm">
                        <div class="mb-5">
                            <label for="currentPassword" class="form-label font-medium text-slate-800 block mb-2">Mật khẩu hiện tại</label>
                            <div class="relative">
                                <input type="password" class="form-control border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm pr-10 py-3" id="currentPassword" required>
                                <i class="fas fa-key form-input-icon"></i>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="newPassword" class="form-label font-medium text-slate-800 block mb-2">Mật khẩu mới</label>
                            <div class="relative">
                                <input type="password" class="form-control border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm pr-10 py-3" id="newPassword" required>
                                <i class="fas fa-lock form-input-icon"></i>
                            </div>

                            <!-- Password strength meter -->
                            <div class="mt-2">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-slate-500">Độ mạnh mật khẩu</span>
                                    <span class="text-xs text-emerald-600">Mạnh</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 80%"></div>
                                </div>
                                <div class="mt-2 text-xs text-slate-500">Mật khẩu phải có ít nhất 8 ký tự bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt</div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="confirmPassword" class="form-label font-medium text-slate-800 block mb-2">Xác nhận mật khẩu mới</label>
                            <div class="relative">
                                <input type="password" class="form-control border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm pr-10 py-3" id="confirmPassword" required>
                                <i class="fas fa-lock-open form-input-icon"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-slate-50 border-t-0 rounded-b-xl">
                    <button type="button" class="btn btn-outline-secondary rounded-lg" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 rounded-lg animate__animated animate__pulse animate__infinite" id="submitChangePassword">
                        <i class="fas fa-check mr-2"></i> Cập nhật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js (for dropdown functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle with animation
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('animate__animated', 'animate__fadeIn');
                }
            });

            // Xử lý đăng xuất với SweetAlert2
            document.getElementById('logoutBtn').addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Đăng xuất?',
                    text: "Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5A67D8',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i> Hủy',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Đang đăng xuất...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Simulate a delay then redirect
                        setTimeout(() => {
                            window.location.href = "dangxuat.php";
                        }, 800);
                    }
                });
            });

            // Xử lý đổi mật khẩu với SweetAlert2
            document.getElementById('submitChangePassword').addEventListener('click', function() {
                const currentPassword = document.getElementById('currentPassword').value;
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (!currentPassword || !newPassword || !confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Vui lòng điền đầy đủ thông tin',
                        confirmButtonColor: '#5A67D8',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Mật khẩu mới không khớp',
                        confirmButtonColor: '#5A67D8',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });
                    return;
                }

                // Đây là nơi bạn sẽ gửi AJAX request để đổi mật khẩu
                Swal.fire({
                    title: 'Đang xử lý...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Mật khẩu đã được cập nhật',
                        confirmButtonColor: '#5A67D8',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        }
                    });

                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                    modal.hide();

                    // Reset form
                    document.getElementById('changePasswordForm').reset();
                }, 1000);
            });

            // Initialize tooltips
            const tooltips = document.querySelectorAll('.custom-tooltip');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
        });
    </script>
</body>

</html>