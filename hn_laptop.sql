-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 10, 2025 lúc 07:44 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hn_laptop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `id` int(11) NOT NULL,
  `id_donhang` int(11) NOT NULL,
  `id_sanpham` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL CHECK (`so_luong` > 0),
  `gia` decimal(10,2) NOT NULL CHECK (`gia` >= 0),
  `thanh_tien` decimal(10,2) GENERATED ALWAYS AS (`so_luong` * `gia`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`id`, `id_donhang`, `id_sanpham`, `so_luong`, `gia`) VALUES
(0, 1, 36, 1, 14370100.00),
(0, 3, 36, 2, 14370100.00),
(0, 4, 36, 1, 14370100.00),
(0, 5, 69, 2, 16424100.00),
(0, 6, 69, 1, 16424100.00),
(0, 7, 69, 1, 16424100.00),
(0, 8, 69, 1, 16424100.00),
(0, 9, 69, 1, 16424100.00),
(0, 10, 52, 1, 13817100.00),
(0, 11, 52, 1, 13817100.00),
(0, 11, 36, 1, 14370100.00),
(0, 11, 37, 1, 11052100.00),
(0, 12, 47, 1, 13343100.00),
(0, 13, 37, 1, 11052100.00),
(0, 14, 36, 1, 14370100.00),
(0, 15, 36, 1, 14370100.00),
(0, 16, 36, 1, 14370100.00),
(0, 17, 36, 1, 14370100.00),
(0, 18, 36, 1, 14370100.00),
(0, 18, 45, 1, 14212100.00),
(0, 19, 52, 1, 13817100.00),
(0, 19, 69, 1, 16424100.00),
(0, 20, 37, 1, 11052100.00),
(0, 20, 60, 1, 21875100.00),
(0, 21, 52, 1, 13817100.00),
(0, 21, 60, 1, 21875100.00),
(0, 22, 53, 1, 29933100.00),
(0, 22, 60, 1, 21875100.00),
(0, 23, 36, 10, 14370100.00),
(0, 23, 37, 1, 11052100.00),
(0, 24, 52, 1, 13817100.00),
(0, 25, 36, 3, 14370100.00),
(0, 25, 37, 1, 11052100.00),
(0, 26, 46, 1, 12948100.00),
(0, 27, 36, 1, 15160100.00),
(0, 27, 37, 1, 11052100.00),
(0, 30, 38, 1, 12553100.00),
(0, 32, 44, 1, 12632100.00),
(0, 33, 44, 1, 12632100.00),
(0, 34, 37, 1, 11052100.00),
(0, 35, 37, 1, 11052100.00),
(0, 36, 45, 1, 14212100.00),
(0, 37, 36, 1, 15160100.00),
(0, 38, 37, 1, 11052100.00),
(0, 39, 37, 1, 11052100.00),
(0, 40, 36, 1, 15160100.00),
(0, 41, 37, 1, 11052100.00),
(0, 42, 36, 1, 15160100.00),
(0, 43, 36, 1, 15160100.00),
(0, 44, 36, 2, 15160100.00),
(0, 45, 37, 1, 11052100.00),
(0, 46, 37, 1, 11052100.00),
(0, 47, 38, 1, 12553100.00),
(0, 48, 52, 1, 13817100.00),
(0, 49, 38, 1, 12553100.00),
(0, 50, 45, 1, 14212100.00),
(0, 51, 46, 1, 12948100.00),
(0, 52, 37, 1, 11052100.00),
(0, 53, 40, 1, 23613100.00),
(0, 53, 41, 1, 22033100.00),
(0, 54, 46, 1, 12948100.00),
(0, 55, 37, 1, 11052100.00),
(0, 56, 36, 1, 15160100.00),
(0, 57, 36, 1, 15160100.00),
(0, 58, 37, 1, 11052100.00),
(0, 59, 36, 1, 15160100.00),
(0, 60, 44, 1, 12632100.00),
(0, 60, 37, 2, 11052100.00),
(0, 61, 36, 1, 15160100.00),
(0, 62, 37, 1, 11052100.00),
(0, 63, 36, 1, 15160100.00),
(0, 64, 36, 1, 15160100.00),
(0, 65, 36, 1, 15160100.00),
(0, 66, 36, 1, 15160100.00),
(0, 67, 36, 1, 15160100.00),
(0, 68, 37, 1, 11052100.00),
(0, 69, 40, 5, 23613100.00),
(0, 70, 38, 1, 12553100.00),
(0, 71, 36, 1, 15160100.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Chưa xem','Đã xem','Đã phản hồi') DEFAULT 'Chưa xem',
  `id_kh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `username`, `name`, `email`, `subject`, `message`, `created_at`, `status`, `id_kh`) VALUES
(1, NULL, 'Lữ Hồ Gia Huy', 'huychayspin05@gmail.com', 'product', 'qwerty', '2025-05-28 20:32:29', 'Chưa xem', NULL),
(2, 'hero06', 'Lữ Hồ Gia Huy', 'huychayspin05@gmail.com', 'product', '123456', '2025-05-28 20:46:04', 'Đã phản hồi', NULL),
(3, 'hero06', 'Lữ Hồ Gia Huy', 'huymoba27@gmail.com', 'payment', '1', '2025-05-28 21:01:14', 'Chưa xem', NULL),
(4, 'hero06', 'Lữ Hồ Gia Huy', 'huymoba27@gmail.com', 'service', '1', '2025-05-28 21:01:26', 'Chưa xem', NULL),
(5, 'nguyen1', 'Thái Nguyễn', 'tathainguyen@123.com', 'other', '123456', '2025-05-29 06:28:13', 'Chưa xem', NULL),
(6, 'nguyen1', 'Thái Nguyễn', 'tathainguyen@123.com', 'other', '123456', '2025-05-29 06:28:18', 'Chưa xem', NULL),
(7, 'nguyen1', 'Tạ Thái Nguyễn', 'tathainguyen@123.com', 'service', '123456abc', '2025-05-29 06:28:55', 'Đã phản hồi', NULL),
(8, 'nguyen1', 'Thái Nguyễn', 'tathainguyen@gmail.com', 'other', '123', '2025-05-29 06:42:52', 'Đã phản hồi', NULL),
(10, 'hero01', 'Lữ Hồ Gia Huy', 'huymoba04@gmail.com', 'service', 'Huy Sieu Cap Vjppro', '2025-05-31 17:08:32', 'Đã xem', NULL),
(14, 'hero01', 'Lữ Hồ Gia Huy', 'huymoba27@gmail.com', 'service', '1', '2025-05-31 17:15:19', 'Đã phản hồi', NULL),
(18, '', 'Thái Nguyễn', 'tathainguyen@gmail.com', 'other', '1222232', '2025-06-01 12:48:07', 'Đã phản hồi', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `id` int(11) NOT NULL,
  `idhanghoa` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `id_kh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`id`, `idhanghoa`, `username`, `rating`, `comment`, `image`, `created_at`, `id_kh`) VALUES
(18, 36, 'hero02', 5, 'Laptop hoạt động ổn định, thiết kế đẹp, màn hình rõ nét, pin dùng lâu. Phù hợp cho học tập, làm việc văn phòng và giải trí nhẹ nhàng, rất đáng mua với mức giá này.', NULL, '2025-06-08 06:16:53', 3),
(19, 36, 'hero03', 5, 'Máy chạy mượt, khởi động nhanh, bàn phím gõ êm, âm thanh tốt. Dùng lâu không nóng, đáp ứng tốt nhu cầu học tập và làm việc cơ bản hàng ngày.', 'uploads3/6844c8f9e7f5f.jpg', '2025-06-08 06:19:21', 5),
(20, 37, 'hero02', 5, 'Chiếc laptop này thực sự gây ấn tượng với mình nhờ thiết kế tinh tế, hiện đại và trọng lượng nhẹ nên rất tiện mang theo khi đi học hoặc làm việc. Màn hình hiển thị sắc nét, màu sắc trung thực, giúp mình dễ dàng làm việc với các tài liệu hoặc giải trí sau giờ học. Hiệu năng ổn định, đáp ứng tốt các nhu cầu cơ bản như lướt web, soạn thảo văn bản, xem phim. Pin sử dụng được lâu, quạt tản nhiệt chạy êm, không bị nóng máy khi sử dụng dài.', NULL, '2025-06-08 06:25:38', 3),
(21, 37, 'hero03', 4, 'Mình khá hài lòng với chiếc laptop này, từ hiệu năng cho đến chất lượng hoàn thiện. Máy khởi động nhanh, chạy đa nhiệm mượt mà, không bị giật lag khi mở nhiều tab trình duyệt cùng lúc. Bàn phím có độ nảy tốt, gõ văn bản rất thoải mái. Âm thanh to, rõ ràng, phù hợp cho việc học online hay giải trí. Ngoài ra, máy còn có nhiều cổng kết nối tiện lợi, phù hợp với nhu cầu sử dụng đa dạng của sinh viên và dân văn phòng.', 'uploads3/6844cb2881a66.jpg', '2025-06-08 06:28:40', 5),
(22, 38, 'hero03', 5, 'Sản phẩm có cấu hình mạnh, xử lý đa nhiệm tốt, thiết kế gọn nhẹ dễ mang theo. Màn hình sáng, hiển thị trung thực, rất thích hợp cho sinh viên và dân văn phòng.', NULL, '2025-06-08 06:29:18', 5),
(23, 39, 'hero03', 5, 'Sản phẩm đáp ứng tốt nhu cầu học tập và làm việc từ xa. Màn hình rộng, góc nhìn tốt nên dễ dàng chia sẻ thông tin khi họp nhóm. Máy khá mỏng nhẹ, thời lượng pin ổn định giúp mình yên tâm mang theo cả ngày mà không lo hết pin. Đặc biệt, mình rất ấn tượng với dịch vụ bảo hành nhanh chóng và hỗ trợ khách hàng tận tình từ nhà sản xuất. Nhìn chung, đây là lựa chọn hợp lý cho sinh viên và người làm văn phòng.', NULL, '2025-06-08 06:29:42', 5),
(26, 36, 'hero02', 5, 'test', NULL, '2025-06-11 00:56:22', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `ten_khach_hang` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(15) NOT NULL,
  `dia_chi` text NOT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `trang_thai` enum('Đang chờ duyệt','Đã duyệt','Đang giao','Giao hàng thành công','Đã hủy') NOT NULL,
  `id_kh` int(11) DEFAULT NULL,
  `phuong_thuc_thanh_toan` varchar(20) DEFAULT 'COD',
  `trang_thai_thanh_toan` tinyint(1) DEFAULT 0,
  `voucher_code` varchar(50) DEFAULT NULL,
  `id_voucher` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`id`, `code`, `ten_khach_hang`, `so_dien_thoai`, `dia_chi`, `tong_tien`, `ngay_tao`, `trang_thai`, `id_kh`, `phuong_thuc_thanh_toan`, `trang_thai_thanh_toan`, `voucher_code`, `id_voucher`) VALUES
(1, 'd805d1ae64', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 14370100.00, '2025-02-22 02:17:04', 'Giao hàng thành công', 5, 'QR Code', 1, NULL, NULL),
(2, 'MHD123', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 100000.00, '2025-02-22 03:06:30', 'Đã hủy', 5, 'COD', 0, NULL, NULL),
(3, '84c7e0893c', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 28740200.00, '2025-02-22 03:07:32', 'Giao hàng thành công', 5, 'COD', 0, NULL, NULL),
(4, '3b2862316d', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 14370100.00, '2025-02-22 03:09:54', 'Đã hủy', 5, 'COD', 0, NULL, NULL),
(5, 'ee5402993e', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 32848200.00, '2025-04-22 03:15:25', 'Giao hàng thành công', 5, 'COD', 2, NULL, NULL),
(6, 'de2bed9e54', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 16424100.00, '2025-03-22 03:23:26', 'Đã hủy', 19, 'COD', 3, NULL, NULL),
(7, '14e5546006', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 16424100.00, '2025-03-22 03:33:02', 'Đã hủy', 1, 'COD', 0, NULL, NULL),
(8, '0a6240702d', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 16424100.00, '2025-03-22 03:42:06', 'Đã hủy', 19, 'COD', 0, NULL, NULL),
(9, '62dd08c253', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 16424100.00, '2025-03-22 03:49:16', 'Đã hủy', 5, 'COD', 0, NULL, NULL),
(10, 'f596bc5d8a', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 13817100.00, '2025-03-22 11:59:50', 'Giao hàng thành công', 5, 'COD', 0, NULL, NULL),
(11, '45a0b51673', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 39239300.00, '2025-03-23 14:38:55', 'Giao hàng thành công', 5, 'COD', 0, NULL, NULL),
(12, '4e8ac75428', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 13343100.00, '2025-03-25 21:11:42', 'Giao hàng thành công', 1, 'COD', 0, NULL, NULL),
(13, '8269107ebd', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 11052100.00, '2025-03-25 21:19:49', 'Giao hàng thành công', 19, 'COD', 0, NULL, NULL),
(14, 'f450e66210', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 14370100.00, '2025-03-25 21:20:05', 'Giao hàng thành công', 3, 'COD', 0, NULL, NULL),
(15, '3397f00acf', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 14370100.00, '2025-01-25 21:22:45', 'Đã hủy', 9, 'COD', 0, NULL, NULL),
(16, '80bb45de86', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 14370100.00, '2025-01-25 21:23:48', 'Đã hủy', 3, 'COD', 0, NULL, NULL),
(17, '6bf710882c', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 14370100.00, '2025-01-25 21:25:38', 'Giao hàng thành công', 19, 'COD', 0, NULL, NULL),
(18, '9a47d90683', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 28582200.00, '2025-03-26 01:18:28', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(19, '58eed89cba', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 30241200.00, '2025-03-26 12:24:00', 'Giao hàng thành công', 3, 'COD', 0, NULL, NULL),
(20, 'ec00c1d532', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 32927200.00, '2025-03-26 14:08:48', 'Đã hủy', 9, 'COD', 0, NULL, NULL),
(21, 'bb15fefd67', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 35692200.00, '2025-03-27 14:42:55', 'Giao hàng thành công', 9, 'COD', 0, NULL, NULL),
(22, 'c8393f1a56', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 51808200.00, '2025-03-29 14:38:29', 'Giao hàng thành công', 3, 'COD', 0, NULL, NULL),
(23, '73a87d5736', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 99999999.99, '2025-03-31 17:15:17', 'Đã hủy', 1, 'COD', 0, NULL, NULL),
(24, '4355791d26', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 13817100.00, '2025-04-06 21:42:29', 'Đã hủy', 16, 'COD', 0, NULL, NULL),
(25, '5c75dc3550', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 54162400.00, '2025-04-13 15:16:32', 'Giao hàng thành công', 3, 'COD', 0, NULL, NULL),
(26, '3aa8cad7f9', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 12948100.00, '2025-05-27 14:21:45', 'Đã duyệt', 9, 'COD', 0, NULL, NULL),
(27, '704504581d', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 26212200.00, '2025-05-27 20:06:14', 'Giao hàng thành công', 19, 'COD', 0, NULL, NULL),
(30, 'e80a6fbed3', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 12553100.00, '2025-05-28 13:31:12', 'Đã hủy', 16, 'COD', 0, NULL, NULL),
(32, '16c85718d0', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 12632100.00, '2025-05-28 17:13:05', 'Giao hàng thành công', 5, 'COD', 0, NULL, NULL),
(33, '7442882c52', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 12632100.00, '2025-05-30 21:47:04', 'Giao hàng thành công', 19, 'COD', 0, NULL, NULL),
(34, 'f260faa57a', 'Nguyễn Minh Quân', '0839123456', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà, Ba Đình, Hà Nội', 11052100.00, '2025-05-31 01:21:45', 'Giao hàng thành công', 5, 'COD', 0, NULL, NULL),
(35, 'daf92c79b0', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 11052100.00, '2025-06-01 15:50:37', 'Đang chờ duyệt', 9, 'COD', 0, NULL, NULL),
(36, '419e1693dd', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 14212100.00, '2025-06-01 16:03:37', 'Đã hủy', 3, NULL, 0, NULL, NULL),
(37, '4693751ba4', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 15160100.00, '2025-06-01 16:03:49', 'Đang chờ duyệt', 9, NULL, 0, NULL, NULL),
(38, 'd416d2f688', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 11052100.00, '2025-06-01 16:03:56', 'Đang chờ duyệt', 1, NULL, 0, NULL, NULL),
(39, 'de5ebdba89', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 11052100.00, '2025-06-01 16:04:56', 'Giao hàng thành công', 9, NULL, 0, NULL, NULL),
(40, '7f78bc541d', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 15160100.00, '2025-06-01 16:05:56', 'Đã duyệt', 16, 'COD', 0, NULL, NULL),
(41, 'e122f61897', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 11052100.00, '2025-06-01 16:06:27', 'Giao hàng thành công', 3, 'COD', 0, NULL, NULL),
(42, '931dffa009', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 15160100.00, '2025-06-01 16:08:50', 'Đang giao', 19, 'COD', 0, NULL, NULL),
(43, '004bb0eb90', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 15160100.00, '2025-06-01 16:12:36', 'Đang giao', 1, 'QR Code', 1, NULL, NULL),
(44, '1005977334', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 30320200.00, '2025-06-01 16:12:51', 'Đang giao', 19, 'COD', 1, NULL, NULL),
(45, 'f29f1ed6f9', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 11052100.00, '2025-06-01 16:15:16', 'Đã duyệt', 3, 'QR Code', 1, NULL, NULL),
(46, '2b9562e76c', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 11052100.00, '2025-06-01 16:15:42', 'Đã duyệt', 3, 'COD', 0, NULL, NULL),
(47, '6cab75eb30', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 12553100.00, '2025-06-01 16:17:40', 'Đã hủy', 9, 'QR Code', 2, NULL, NULL),
(48, 'ffe869bc32', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 13817100.00, '2025-06-01 16:18:10', 'Đã duyệt', 16, 'COD', 3, NULL, NULL),
(49, 'db9b130edc', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 12553100.00, '2025-06-01 16:19:27', 'Đang giao', 19, 'QR Code', 1, NULL, NULL),
(50, '5698c27f7c', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 14212100.00, '2025-06-01 18:26:00', 'Đã hủy', 1, 'QR Code', 3, NULL, NULL),
(51, '345462f771', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 12948100.00, '2025-06-01 18:30:07', 'Giao hàng thành công', 1, 'QR Code', 1, NULL, NULL),
(52, '991e023067', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 11052100.00, '2025-06-05 14:01:01', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(53, '8acb386ff5', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Gò Vấp, TP. Hồ Chí Minh', 45646200.00, '2025-06-05 14:04:20', 'Đang giao', 3, 'QR Code', 1, NULL, NULL),
(54, 'e50344922c', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 12948100.00, '2025-06-05 14:33:45', 'Đang giao', 9, 'QR Code', 1, NULL, NULL),
(55, '3bb1b759d2', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 11052100.00, '2025-06-06 00:29:51', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(56, '922f060bb7', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 13644090.00, '2025-06-06 18:34:07', 'Đang giao', 16, 'COD', 0, NULL, NULL),
(57, '60b8abf511', 'Đặng Trung Kiên', '0839456789', '90 Đường 30/4, Phường Xuân Khánh, Ninh Kiều, Cần Thơ', 13644090.00, '2025-06-06 18:47:25', 'Giao hàng thành công', 19, 'COD', 0, NULL, NULL),
(58, '7596d391cd', 'Trần Gia Hưng', '0839234567', '56 Đường Võ Văn Tần, Phường 6, Quận 3, TP. Hồ Chí Minh', 9946890.00, '2025-06-06 18:55:07', 'Đã hủy', 9, 'COD', 0, NULL, NULL),
(59, '5d7d234810', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 13644090.00, '2025-06-06 18:55:22', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(60, 'd027bddce6', 'Lê Anh Tuấn', '0839345678', '78 Đường Bạch Đằng, Phường Hải Châu 1, Hải Châu, Đà Nẵng', 34736300.00, '2025-06-06 20:09:42', 'Giao hàng thành công', 16, 'QR Code', 3, NULL, NULL),
(61, '47df9d7145', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 15160100.00, '2025-06-06 23:22:41', 'Giao hàng thành công', 1, 'QR Code', 1, NULL, NULL),
(62, '33a5457a5a', 'Khách hàng', '0832222222', 'Đường 1, Quận 1, Hồ Chí Minh', 10499495.00, '2025-06-06 23:29:36', 'Giao hàng thành công', 1, 'QR Code', 3, NULL, NULL),
(63, '7fb535daef', 'Gia Huy', '0832222309', 'Đường số 9, Nam Tu Liem, Ha Noi', 15160100.00, '2025-06-07 10:35:55', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(64, '8bcb1d3fa3', 'Gia Huy', '0832222309', 'Đường số 9, Nam Tu Liem, Ha Noi', 15150100.00, '2025-06-07 10:47:37', 'Giao hàng thành công', 16, 'COD', 0, NULL, NULL),
(65, '0cdef3d5d2', 'Gia Huy', '0832222309', 'Đường số 9, Nam Tu Liem, Ha Noi', 13644090.00, '2025-06-07 11:00:14', 'Giao hàng thành công', 16, 'QR Code', 1, 'TESTVC10', NULL),
(66, '7782821fe6', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Go Vap, Ho Chi Minh', 15160100.00, '2024-06-10 20:14:20', 'Giao hàng thành công', 3, 'COD', 0, '', NULL),
(67, 'bafd27637b', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Go Vap, Ho Chi Minh', 14402095.00, '2025-06-13 20:14:16', 'Đã duyệt', 3, 'QR Code', 1, 'TEST2', NULL),
(68, 'c527bc34d1', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Go Vap, Ho Chi Minh', 11052100.00, '2025-07-25 14:38:28', 'Giao hàng thành công', 3, 'QR Code', 1, '', NULL),
(69, '02901a2c73', 'Nguyễn Văn A', '0839411900', '123 Đường Quang Trung, Phường 10, Go Vap, Ho Chi Minh', 99999999.99, '2025-07-25 15:22:33', 'Giao hàng thành công', 3, 'QR Code', 1, 'TESTVC10', NULL),
(70, '399be4faa6', 'nguyen van d', '0913733312', 'Trương Vĩnh Nguyên, Cai Rang, Can Tho', 11297790.00, '2025-08-09 16:31:28', 'Đã hủy', 20, 'COD', 0, 'TESTVC10', NULL),
(71, 'e30d307e31', 'nguyen van d', '0913733312', 'Trương Vĩnh Nguyên, Cai Rang, Can Tho', 15160100.00, '2025-08-09 16:31:53', 'Đang chờ duyệt', 20, 'QR Code', 0, '', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hanghoa`
--

CREATE TABLE `hanghoa` (
  `idhanghoa` int(11) NOT NULL,
  `tenhanghoa` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `giathamkhao` decimal(10,2) NOT NULL,
  `idloaihang` int(11) NOT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `chitiet` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hanghoa`
--

INSERT INTO `hanghoa` (`idhanghoa`, `tenhanghoa`, `mota`, `giathamkhao`, `idloaihang`, `hinhanh`, `chitiet`) VALUES
(36, ' Dell Latitude 7400', 'Core i7-8665U, 16GB, 256GB, VGA Intel UHD Graphics 620, 14.0 FHD IPS', 19190000.00, 1, '2010_laptopaz_dell_xps_15_9500.png', '<h3 align=\"center\" style=\"text-align: left; font-weight: bold; font-size: 1.17em; font-family: Roboto, sans-serif; margin-block-start: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell Latitude 7400 dành cho ai?</span></h3><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Bạn là chuyên viên sáng tạo, doanh nhân, hay lập trình viên? Bạn đang cần tìm cho mình một chiếc máy tính có yêu cầu cao về hiệu năng sử dụng nhưng vẫn phải đảm bảo máy có thiết kế nhỏ gọn để thuận tiện mang theo bất cứ lúc nào thì Dell Latitude 7400 chính là sự lựa chọn hoàn hảo dành cho bạn. Để tìm hiểu kỹ hơn về chiếc laptop ultrabook cao cấp thì bạn đừng bỏ lỡ thông tin dưới đây bạn nhé.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;</span><img src=\"uploads2/1749341540_2010_i9_0-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thiết kế sang trọng tạo nên sự khác biết</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Khi nhắc đến các sản phẩm của</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;người ta sẽ liên tưởng ngay đến những chiếc laptop ultrabook cao cấp được thiết kế với mặt nhôm với những đường cắt CNC tinh tế và&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;cũng không phải là ngoại lệ. Phần nắp nhôm của máy được phù màu bạch kim sang trọng cùng logo Dell nổi bật. Bên cạnh đó, phần kê tay của máy được phủ một lớp carbon mang lại sự thoải mái tốt nhất cho người sử dụng.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341608_2010_i9_2-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Thiết kế sang trọng</em></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tổng thể chiếc laptop ultrabook có trọng lượng khá nhẹ chỉ khoảng 1,8kg đối với bản thường, và 2,05kg với phiên bản pin 86Wh. Kích thước máy 18 mm x 344.72 mm x 230.14 mm. Với kích thước và trọng lượng nhỏ gọn,&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;giúp hạn chế khả năng vô đập, chống sốc tốt giúp bạn thoải mái mang đi bất cứ đâu mà không lo máy bị hỏng.</span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Màn hình vô cùng ấn tượng&nbsp;</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Sở hữu màn hình InfinityEdge 15,6 inch cùng độ phân giải màn hình tăng lên 1920 x 1200 cực sắc nét. Đồng thời, tuy nhà sản xuất đã giảm diện tích máy nhỏ hơn nhưng lại tăng diện tích màn hình của XPS 15 9500 lên 5% và chuyển sang tỷ lệ màn hình 16:10 nhằm tận dụng tối đa không gian màn hình từ đó mang đến cho người dùng những trải nghiệm vô ấn tượng.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341674_2010_i9_5-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Màn hình sắc nét</em></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ngoài ra, màn hình của&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được bảo vệ bởi một lớp kính Corning Gorilla Glass 6 giúp thao tác cảm ứng trên màn hình diễn ra mượt mà hơn.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;cho chất lượng hiển hình ảnh, video 4k vô cùng sắc nét, chân thực với khả năng tái tạo 132% gam mày cùng độ sáng đạt 500 nit.</span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Bàn phím đồng đều và Touchpad lớn&nbsp;</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tuy bàn phím của Dell</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">&nbsp;Latitude</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;không bằng ThinkPad nhưng các phím bấm vẫn giữ được khoảng cách đồng đều, độ nhảy phím tốt, hành trình phím đủ sâu, keycap được làm mịn, phẳng mang đến cảm giác thoải mái khi gõ cho người dùng. Chưa kể nút nguồn cũng kiêm luôn vai trò cảm biến vân tay vô cùng tiện dụng chỉ sau 1 cái chạm.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341734_2010_i9_4-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Bàn phím touchpad mượt mà</em></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Touchpad lớn với kích thước 15 x 8.9 cm giúp bạn dễ dàng điều khiển toàn bộ màn hình mà không lo chạm đến cạnh Touchpad. Có thể nói, giờ đây Touchpad của</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được đánh giá là mang lại trải nghiệm cho người dùng chẳng kém gì trackpad huyền thoại của macbook cả.</span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell Latitude 7400 có đa dạng cổng kết nối</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Chiếc máy tính xách tay ultrabook cao cấp này được trang bị 3 cổng Type-C. Trong đó, bên trái cạnh máy là hai cổng hỗ trợ Thunderbolt 3 và bên phải cạnh máy là 1 jack audio combo 3.5mm và 1 khe cắm thẻ nhớ SD.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341903_2010_5-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Khả năng tái tạo âm thanh tuyệt vời</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Nếu như các dòng Dell Latitude khiến người dùng chưa thực sự hài lòng về phần âm thanh thì&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;lại làm được điều này. Với 4 loa &nbsp;Waves NX 3D, trong đó có 02 loa toàn dải và 02 loa &nbsp;tweeter mang đến chất lượng âm thanh to, sống động, bạn có thể nghe được những bản nhạc EDM hết công suất mà âm thanh vẫn rất êm tai, bass ổn. Có thể nói đây là dòng máy tính xách tay được trang bị những loa tốt nhất hiện nay.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/product/2010_i9_3.png\" alt=\"\" style=\"height: auto; max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important;\"></span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Hiệu năng mạnh mẽ&nbsp;</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được trang bị bộ vi xử lý CPU Intel® Core™ i7 10750H đời mới nhất cho khả năng xử lý nhanh mọi tác vụ dù là công việc hay là chơi game. Với các phần mềm thiết kế đồ hoạ như PTS, AI, AutoCAD, hay các tựa game như LOL, DOTA2,…máy vẫn mang đến trải nghiệm mượt mà cho người dùng.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/product/2010_i9_1.jpg\" alt=\"\" style=\"height: auto; max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Hiệu năng kinh ngạc</em></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ổ cứng SSD&nbsp;2TB SSD PCIe cùng RAM 64GB cho khả năng đa nhiệm cực tốt. Bạn có thể bật đồng thời nhiều trình duyệt web hay các ứng dụng, phần mềm khác nhau mà không sợ máy bị chậm hay lag.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Card đồ họa&nbsp;NVIDIA® GeForce® GTX 1650Ti 4GB GDDR6 phù hợp cho những bạn có nhu cầu cao về thiết kế, chỉnh sửa ảnh và edit video.&nbsp;</span></p><h2 style=\"font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-weight: bold; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thời lượng pin của&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\"><b>Dell Latitude 7400</b></span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Trong các bài thử nghiệm của về thời lượng pin được tiến hành bởi LaptopMag cho thấy&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;có thời lượng pin khá dài, lên đến 8 tiếng đồng hồ khi bạn lướt web liên tục qua Wifi ở độ sáng 150 nits. &nbsp;Chính vì vậy, bạn có thể sử dụng máy trong suốt thời gian dài không sợ hết pin khi không có nguồn điện bạn nhé.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341753_2010_i9_0-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Thời lượng pin tốt</em></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ngoài ra, cũng theo thử nghiệm của LaptopMag cho thấy khi sử dụng các tác vụ nhẹ như lướt web, xem video thì nhiệt độ máy khá mát mẻ. Chỉ khi bạn sử dụng các các vụ nặng trong vòng nhiều giờ liên tục thì máy mới có hiện tượng bị nóng. Theo mình thấy thì điều này là không thể tránh khỏi.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749341774_2010_i9_2-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tổng kết</span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Nói tóm lại,&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được đánh giá là một trong những chiếc máy tính xách tay dẫn đầu thời đại mà xứng đáng trở thành một chiếc ultrabook mà bất kỳ ai cũng nên sở hữu ngay. Để mua&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7400</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;với giá cả tốt nhất thì còn chần chừ gì mà không đến ngay HN-LAPTOP bạn nhé.</span></p>'),
(37, 'Dell Latitude 7430', 'Core i7-1265U, 16GB, 256GB, Iris Xe, 14.0\" FHD', 13990000.00, 1, '2487_laptopaz_dell_xps_9520_1.png', '<p style=\"margin: 1em 0px; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Laptop Dell Latitude 7430 sẽ phù hợp với đối tượng nào?</span></span><br></p><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell Latitude 7430 mẫu Laptop Multimedia mới nhất đến từ nhà Dell</span><span style=\"font-size: 12pt;\"><font face=\"arial, helvetica, sans-serif\">&nbsp;được thừa hưởng những điều tốt nhất từ “đàn anh” của mình</font><font face=\"arial, helvetica, sans-serif\">, nhưng năm nay với sự cải tiến đáng kể về mặt hiệu năng với sự ”góp” mặt của Intel CPU 12th Gen,&nbsp;</font></span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-size: 12pt;\"><font face=\"arial, helvetica, sans-serif\">&nbsp;chắc chắn sẽ đem lại cho bạn những trải nghiệm tuyệt vời hơn thế.</font></span></div><p></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/product/2484_laptopaz_dell_xps_9520_1s.jpg\" alt=\"\" width=\"600\" style=\"height: auto; max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Thiết kế hiện đại, sang trọng</span></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ở Dell người dùng vẫn luôn thấy sự nhất quán trong lối thiết kế trong từng sản phẩm, nó không dễ bị nhầm lẫn giữa các dòng Laptop khác trên thị trường mà vẫn luôn có lối thiết kế của riêng mình, và&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;cũng đã làm được điều đó. Vẫn làm bằng chất liệu Nhôm nguyên khối cùng với những đường cắt Diamond tinh xảo đem lại cho ngoại hình của chiếc laptop sự chắc chắn và sang trọng. Khung máy được gia cố bằng hợp kim Magnesium chắc chắn, với mặt trên được phủ một lớp carbon fiber đã quá quen thuộc với các dòng Latitude trước đây. Ngoài ra máy có cân nặng khoảng 1.92Kg khá nhẹ cho người dùng có thể mang chiếc Laptop này đi muôn nơi.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342204_2484_laptopaz_dell_xps_9520_6-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Màn hình hiển thị chất lượng tuyệt vời</span></span><br></p><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ở&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;người dùng có thể được trải nghiệm hơn những gì đã mong đợi: Chất lượng hiển thị khá tốt. Ở phiên bản màn hình 4K (tùy cấu hình) cho độ sáng 500 nits và bao phủ 100% Adobe RGB, 94% DCI-P3 cùng với công nghệ HDR với Dolby Vision giúp cho các điểm nổi bật sáng hơn tới 40 lần và màu đen tối hơn đến 10 lần. Nếu bạn là người dùng làm việc thiên về bên nghệ thuật hoặc cần sự chính xác màu gần như tuyệt đối thì phiên bản 4K chính là sự lựa chọn tuyệt vời.</span></div><p></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell đã thiết kế một Webcam chỉ nhỏ chỉ 2,25mm trên một chiếc máy có 4 viền siêu mỏng, và chúng có ống kính tới 4 thành phần đã mang lại video sắc nét ở tất cả các khu vực của khung hình, đặc biệt là trong điều kiện ánh sáng mờ, giúp cải thiện độ nhiễu.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342301_2484_laptopaz_dell_xps_9520_2-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Bàn phím và TouchPad</span></span><br></p><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">TouchPad với công nghệ Window Precision và được Dell phủ kính cho người dùn có cảm giác rê chuột, đa nhiệm hay tracking mượt mà và chính xác không khác gì TrackPad trên MacBook Pro. Hành trình phím thiết kế ổn, lực phản hồi tốt đem lại cảm nhận tốt khi gõ phím và gõ trong thời gian dài đối với người dùng làm việc văn phòng.</span></div><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></div><div style=\"text-align: center;\"><img src=\"uploads2/1749342552_2484_laptopaz_dell_xps_9520_3-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></div><p></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Hiệu năng vượt trội nhờ con chip Intel thế hệ thứ 12</span></span><br></p><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Một trong những ưu điểm giúp&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;trở nên nổi bật hơn rất nhiều so với các đối thủ trong cùng phân khúc là hiệu suất được trang bị cho chiếc laptop. Chiếc máy tính này sở hữu con chip Intel tùy chọn Core i5-12500H và Core i7-700H</span><span style=\"font-size: 12pt;\"><font face=\"arial, helvetica, sans-serif\">&nbsp;Gen 12 mới nhất.</font></span></div><p></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Về hiệu suất đồ hoạ,&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;đi kèm với tuỳ chọn cấu hình GPU bao gồm Card đồ hoạ rời NVIDIA® GeForce RTX ™ 3050 (cho phiên bản chip Intel Core i7). Đây là card đồ chuyên dụng thường được trang bị trên cho các dòng laptop gaming hay đồ hoạ. Và với sức mạnh vượt trội, GPU cho khả năng chạy mượt các phần mềm đồ họa 3D nặng hay các tựa game AAA mà không gặp bất cứ khó khăn gì.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span><img src=\"uploads2/1749342580_2484_laptopaz_dell_xps_9520_5-Photoroom.png\" style=\"width: 50%;\"></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Cổng kết nối</span></span><br></p><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell đã trang bị cho&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;hệ thống cổng kết nối khá đầy đủ, điều này giúp cho người dùng có cảm giác thuận tiện khi kết nối với các thiết bị khác:&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">cổng Type-C hỗ trợ Thunderbolt 4,&nbsp;cổng USB 3.2 Gen 2 Type-C, jack 3,5 và khe thẻ SD vẫn được giữ lại. Việc giữ lại khe SD là một điểm cộng lớn khi&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;vốn được sinh ra dành cho đối tượng sử dụng là người làm sáng tạo nội dung, hình ảnh.</span></div><div style=\"text-align: center;\"><img src=\"uploads2/1749342595_2484_laptopaz_dell_xps_9520_4-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></div><p></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Tổng kết:&nbsp;</span></span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 7430</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;có thể coi là phiên bản nâng cấp hoàn toàn mới so với người tiền nhiệm. Không chỉ thay đổi trong thiết kế mà nó còn mang đến hiệu suất vượt trội nhờ con chip thế hệ thứ 12 mới nhất, đem lại trải nghiệm vô cùng tuyệt vời, xứng đáng với giá tiền mà bản thân nó sở hữu.</span></p>'),
(38, 'Dell Latitude 9520', 'Core i7-1185G7, 32GB, 256GB, Iris Xe, 15\" FHD IPS', 15890000.00, 1, '2757_laptopaz_5430_chinh.png', '<h3 style=\"font-weight: bold; font-size: 1.17em; font-family: Roboto, sans-serif; margin-block-start: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><font color=\"#000000\" style=\"\">Dell Latitude 9520: Laptop văn phòng, cải tiến thiết kế</font></span></h3><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell Latitude 9520 là một trong những dòng máy tính xách tay chạy chip Intel mới nhất của Dell trong năm 2023. Đây là mẫu máy có thiết kế nhỏ gọn, bắt mắt và hiệu năng tốt với một mức giá vô cùng hấp dẫn và có nhiều thay đổi so với phiên bản tiền nhiệm.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\"><span style=\"font-size: 14pt;\">Thiết kế</span><br><div style=\"text-align: justify;\"><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được thiết kế với phong cách chuyên nghiệp và tinh xảo và cải tiến so với phiên bản tiền nhiệm trước đó.&nbsp;</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;có thiết kế đẹp, mạnh mẽ và độ mỏng ấn tượng. Bản lề&nbsp;cũng đã được cải tiến so với phiên bản Dell Inspiron 14 5420 khi mà có thêm phần nhô ra và đã&nbsp;ổn định ở một số góc độ nhất định.</span></div></span></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04646.jpg\" alt=\"\" style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important; width: 50%;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Máy được hoàn thiện từ bộ vỏ nhôm cao cấp làm tăng độ sang trọng, lại vừa có độ bền cao. Máy có kích thước gọn hơn do được trang bị màn hình viền mỏng hơn ở cạnh trên và dưới. Máy sử dụng tỉ lệ màn 16:10 mới lên cho tổng thể máy nhìn vuông hơn thời thượng hơn. Bản lề được thiết kế rất vững chắc được chế tạo chính xác có thể nâng được giúp tăng độ nghiêng bàn phím giúp việc dễ dàng đánh máy và thao tác phím hiệu quả hơn cũng như tăng hiệu quả tản nhiệt cho máy.&nbsp;</span></p><h5 style=\"font-family: Roboto, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Màn hình&nbsp;</span></span></h5><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-size: 12pt;\">&nbsp;có màn hình 14”&nbsp;FHD+ (16:10) cho góc nhìn rộng lên đến 178 độ.</span><span style=\"font-size: 12pt; font-family: Roboto, sans-serif !important;\"><span style=\"font-family: arial, helvetica, sans-serif;\">&nbsp;</span>Màn hình có viền mỏng cho tỷ lệ màn hình trên thân máy lớn, bạn sẽ được đắm chìm trong màn ảnh với độ chân thực cũng như độ chính xác màu cao. Công nghệ ComfortView cho trải nghiệm hiển thị mượt mà, dịu mắt giúp người dùng sử dụng máy nhiều tiếng mà không lo tới các hiện tượng mỏi mắt.</span></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04593.jpg\" alt=\"\" style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto; width: 50%;\"><img src=\"https://laptopaz.vn/media/product/2757_20230208_dell_inspiron_14_5430_front.jpg\" alt=\"\" style=\"height: auto; max-width: 100%; display: block; margin-left: auto; margin-right: auto;\"></span></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Bàn phím và touchpad trên Dell Latitude 9520</span></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Cũng với thiết kế bàn phím như phiên bản tiền nhiệm,&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;cho thấy được sự chắc chắn và cứng cáp ngày từ lần đầu người dùng trải nghiệm nhập liệu trực tiếp trên chiếc laptop này. Cụ thể, bàn phím trên máy có độ nảy cao, hành trình phím đủ sâu và keycap mịn.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04611.jpg\" alt=\"\" style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important; width: 50%;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-size: 12pt; font-family: Roboto, sans-serif !important;\">Một điểm cộng nữa đáng để nhắc đến khi phần touchpad của&nbsp;</span></span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-size: 12pt; font-family: Roboto, sans-serif !important;\">&nbsp;có kích thước lớn, giúp những thao tác đa điểm của người dùng được thoải mái và dễ dàng nhất. Đồng thời bề mặt touchpad cũng được phủ một lớp kính mịn, được hỗ trợ Driver Windows Precision nên độ chính xác cũng luôn được duy trì.</span></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-size: 14pt; font-family: Roboto, sans-serif !important;\"><span style=\"font-weight: bolder;\">Cổng kết nối</span></span><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;vẫn được trang bị đầy đủ các cổng kết nối như:</span></p><ul style=\"margin: 1em 0px; font-family: Roboto, sans-serif; list-style: initial; font-size: 14px; padding-left: 40px !important;\"><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">2x USB 3.2 Gen 1 Type A</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">1x&nbsp;USB Type-C Thunderbolt 4.0 port with DisplayPort and Power Delivery</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">1x HDMI 1.4 port</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">1x&nbsp;&nbsp;SD-card slot</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">1x Jack tai nghe 3.5mm</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">1x&nbsp;Power-adapter port 4.5 mm x 2.9 mm DC-in</span></li></ul><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Mang đến khả năng kết nối với những thiết bị ngoại vi như: chuột, phím, loa,... một cách thoải mái và nâng cao sự trải nghiệm giành cho người dùng.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04633.jpg\" alt=\"\" width=\"900\" height=\"675\" style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important; width: 50%;\"></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px; text-align: center;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 10pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Cạnh phải sản phẩm</em></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px; text-align: center;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 10pt;\">&nbsp;<em style=\"font-family: Roboto, sans-serif !important;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04629.jpg\" alt=\"\" width=\"898\" height=\"673\" style=\"max-width: 100%; width: 50%;\"></em></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px; text-align: center;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 10pt;\"><em style=\"font-family: Roboto, sans-serif !important;\">Cạnh trái sản phẩm</em></span></p><h2 style=\"font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b>Cấu hình</b></span></span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Máy được mang hiệu năng mạnh mẽ CPU Intel Core i5-1340P một con CPU mới nhất của nhà Intel mang đến khả năng phản hồi đáng kinh ngạc và đa nhiệm mượt mà, liền mạch... Bộ đôi có 10 nhân 12 luồng. Với con CPU mới nhất này chắc chắn rằng&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">sẽ hoạt động ổn định, đủ mạnh để bạn làm việc trong thời gian dài một cách ổn định.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Card đồ họa tích hợp&nbsp;Intel Iris Xe Graphics giúp máy đáp ứng đầy đủ mọi tác vụ học tập, văn phòng cơ bản đến phức tạp.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/lib/2757_DSC04600.jpg\" alt=\"\" style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto; font-family: Roboto, sans-serif !important; width: 50%;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;sở hữu 16GB Ram LPDDR5, cho khả năng đa nhiệm luôn trơn tru, cùng với đó SSD được trang bị trên chiếc&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell Latitude 9520</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;là loại 512GB PCIe NVMe giúp bạn có thể xử lý cùng lúc khối lượng lớn, lưu trữ thông tin, dữ liệu công việc vô cùng nhanh chóng và tiện lợi.</span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\"><span style=\"font-size: 14pt;\">Tổng kết</span><br><div style=\"text-align: justify;\"><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;là&nbsp;một chiếc laptop thiết kế sang trọng, gọn nhẹ, màn hình chất lượng đi kèm hiệu năng mạnh mẽ.&nbsp;Thật sự trong phân khúc giá tầm trung,&nbsp;</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;là một mẫu sản phẩm rất đáng để người dùng đầu tư. Mong rằng những chia sẻ về chiếc&nbsp;</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;5430 có thể mang lại những điều hữu ích cho người dùng trong việc lựa chọn cho mình một chiếc laptop phù hợp. Đừng quên tham khảo giá&nbsp;</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif;\">Dell Latitude 9520</span><span style=\"font-weight: 400; font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;trên hệ thống của hàng của HN-LAPTOP với rất nhiều ưu đãi!</span></div></span></span></p>');
INSERT INTO `hanghoa` (`idhanghoa`, `tenhanghoa`, `mota`, `giathamkhao`, `idloaihang`, `hinhanh`, `chitiet`) VALUES
(39, 'Dell G3 3579', 'Core i5-8300H, 8GB, 128GB + 500GB, VGA 4GB GTX 1050, 15.6\' FHD', 13390000.00, 1, '3201_dell_precision_7680.png', '<h2 style=\"font-weight: bold; font-size: 1.5em; font-family: Roboto, sans-serif; margin-block-start: 0.83em; margin: 0.83em 0px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><font color=\"#000000\">Dell G3 3579 - Hiệu năng bền bỉ</font></span></h2><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dell G3 3579 mới ra mắt năm 2023 là dòng máy trạm có thiết kế sang trọng nhưng mang trong cấu hình mạnh mẽ khi được trang bị con chip Intel thế hệ 13 HX. Với hiệu năng xử lý công việc vô cùng ấn tượng với hiệu suất cao hơn.</span></p><h4 style=\"font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b>Thiết kế sang trọng, mạnh mẽ</b></span></span></h4><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;Workstation bạn sẽ có một chiếc máy trạm đúng nghĩa, to dày, chắc chắn với chất liệu được làm từ nhôm nguyên khối cùng với logo Dell được CNC tỉ mỉ ở mặt sau càng tôn lên sự sang trọng.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342850_3199_dell-precision-7680-gen-13th-1683279550-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tính bền bỉ còn được thể hiện khi chiếc&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;này đạt được tiêu chuẩn Quân đội MIL-STD 810G giúp nó có thể làm việc tốt trong bất kì điều kiện khắc nghiệt nào.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Còn về thiết kế tổng quan thì chiếc máy sẽ có trọng lượng 2.6kg và dày 258,02mm, hơi khó để di chuyển nhưng điều này là cần thiết cho việc tản nhiệt bởi hiệu năng mà&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3 3579&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">được trang bị rất lớn.</span></p><h4 style=\"font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b>Có nhiều lựa chọn với màn hình</b></span></span></h4><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Để người dùng có nhiều sự lựa chọn phù hợp với nhu cầu của mình thì Dell đã cung cấp cho dòng máy&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;hai dạng màn hình 16 inch:</span></p><ul style=\"margin: 1em 0px; font-family: Roboto, sans-serif; list-style: initial; font-size: 14px; padding-left: 40px !important;\"><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">16\" FHD+ 1920x1200 WLED, WVA, 60Hz, anti-glare, non-touch, 100% DCI-P3, 500 nits, IR Camera with Mic&nbsp;</span></li><li style=\"text-align: justify; list-style: initial !important;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">16\" UHD+ 3840x2400 OLED, WVA, 60Hz, anti-glare, touch,100% DCI-P3, 400 nits, IR Camera, with Mic</span></li></ul><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Không cần phải lo lắng về chất lượng trải nghiệm, bởi ngay từ màn hình FHD thì Dell đã điều chỉnh để người dùng có cảm giác tốt nhất về thị giác. Đặc biệt lưu tâm đến độ chính xác về màu sắc cho đến việc hiển thị ở những môi trường không phù hợp, phức tạp, thiếu sáng hoặc quá sáng.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Máy cũng hỗ trợ IR giúp người sử dụng có thể mở khóa bằng gương mặt thông qua Windows Hello để tăng cường độ bảo mật. Tuy nhiên thì phần viền màn hình còn được đánh giá là hơi dày.</span></p><h4 style=\"text-align: justify; font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b>Các cổng kết nối</b></span></span></h4><h4 style=\"text-align: center; font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><img src=\"uploads2/1749342893_3199_precision-7680-ports-600x600_1699590503-Photoroom.png\" style=\"width: 25%;\"><img src=\"uploads2/1749342903_3199_precision-7680-new-tphcm-600x600_1699590503-Photoroom.png\" style=\"width: 25%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b><br></b></span></span></h4><div style=\"font-family: Roboto, sans-serif; font-size: 14px;\"></div><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Các cổng kết nối của&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;được trang bị khá đầy đủ, để có thể đáp ứng nhu cầu người sử dụng bao gồm:&nbsp;2x ThunderBolt™ 4, 3x USB 3.2, 1x HDMI 2.1, 1x RJ45, 1x Headset, 1x SD-card slot</span></p><h4 style=\"text-align: justify; font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Arial;\"><b>Trải nghiệm bàn phím</b></span></span></h4><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Độ nảy cao cùng với hành trình phím chắc chắn sẽ đem đến cảm giác gõ tốt với người sử dụng. Với 3 chế độ đèn phím sẽ giúp người sử dụng có thể dễ dàng tùy chỉnh trong các môi trường làm việc.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với dòng WorkStation cỡ lớn nên Dell cũng trang bị trên phiên bản này layout số phù hợp với các công việc cần nhập liệu nhiều, cùng với đó là trackpoint cùng sẽ không xuất hiện. TouchPad lớn được phủ kính cho cảm giác tracking chính xác nhờ vào công nghệ Windows Precision.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342926_3199_precision-7680-2023-600x600_1699590503-Photoroom.png\" style=\"width: 600px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"></p><h4 style=\"font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Roboto, sans-serif !important;\"><b>Hiệu năng ngoài mong đợi</b></span></span></h4><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Đây chắc hẳn là con máy nhất của Dell tính tới thời điểm hiện tại với Intel Gen 13th XH. Việc sử dụng con chip hiệu năng cao đuôi HX sẽ giúp cho hiệu năng&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;tăng lên vượt trội.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với Intel® Core™ i7-13850HX (30MB Cache, 28 Threads, 20 Cores (8P+12E) up to 5.3GHz, 55w, vPro)&nbsp;hiệu năng cao đáp ứng tốt cho mọi công việc mà bạn muốn nó xử lý trong thời gian ngắn nhất.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342970_3199_dell-precision-7680-gen-13th-1683279544-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với trang bị Card đồ họa chuyên dùng cho các dòng máy trạm và cao cấp RTX A2000 (8GB) GDDR6 giúp máy có thể hoạt động với hiệu suất cao và liên tục.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ngoài ra, người sử dụng có thể nâng cấp RAM tối đa lên tới 128GB cùng với đó là lên tới 4TB SSD. Máy cũng hỗ trợ RAM LPDDR5 và SSD PCle Gen 4 mới nhất.&nbsp;</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;đạt được chứng chỉ ISV cũng là điểm cộng giúp cho đây là phiên bản được hỗ trợ và tối ưu tốt hơn về các ứng dụng 3D chuyên nghiệp như 3Ds Max, AutoCAD, Revit,...</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342982_3199_dell-precision-7680-gen-13th-3-1683279544-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"></p><h4 style=\"font-size: 14px; font-family: Roboto, sans-serif; margin-block-start: 1.33em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 14pt;\"><span style=\"font-family: Roboto, sans-serif !important;\"><b>Thời lượng sử dụng pin</b></span></span></h4><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với con chip hiệu năng cao thì khả năng tản nhiệt cũng rất được. Bộ tản nhiệt được làm lớn hơn cùng với các ống đồng heatsink bên trong giúp tản nhiệt độ luôn được duy trì ổn định.</span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 1rem;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;có thể hoạt động liên tục trong khoảng 5 giờ, nhưng vẫn sẽ phụ thuộc vào các tác vụ mà bạn sử dụng.&nbsp; Chiếc máy tuy có mức giá cao hơn khi so với các dòng khác. Nhưng nhìn chung,&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">&nbsp;vẫn là một chiếc máy tuyệt vời phục vụ cho những công việc đòi hỏi cấu hình cao, hiệu năng tốt.</span></p><p style=\"text-align: center; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"uploads2/1749342997_3199_dell-precision-7680-gen-13th-2-1683279544-Photoroom.png\" style=\"width: 50%;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><br></span></p><p style=\"margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"></span></p><p style=\"text-align: justify; margin: 1em 0px; font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-weight: bolder; font-family: Roboto, sans-serif !important;\">Kết Luận:</span>&nbsp;Được đánh giá là một trong những “con cưng của hãng” nên&nbsp;</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 16px; text-align: left;\">Dell G3 3579</span><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">hội tụ cho mình đầy đủ các yếu tố từ thiết kế hiện đại, tính di động cao, đi kèm hiệu năng mạnh mẽ nên không quá bất ngờ khi chiếc laptop này đã và đang được nhiều người dùng yêu thích. Hiện tại HN-LAPTOP đang có rất nhiều chương trình ưu đãi với những phần quà vô cùng giá trị. Các bạn có thể tham khảo tại&nbsp;<font color=\"#333333\" face=\"Roboto, sans-serif\">Website</font>&nbsp;của HN-LAPTOP nhé!</span></p>'),
(40, 'Dell XPS 9570', 'Core i7-8750H, 16GB, 512GB, VGA GTX 1050Ti, 15.6\' 4K touch', 29890000.00, 1, '3319_2197_laptopaz_dell_latitude_5400.png', NULL),
(41, 'Dell XPS 9560', 'Core i7-7700HQ, 16GB, 512GB, VGA NVIDIA GTX 1050, 15.6 inch 4K IPS', 27890000.00, 1, '3356_2924_dell_gaming_g15_5520_2022.png', NULL),
(42, 'Dell XPS 15 7590', 'Core i7-9750H, 16GB, 512GB, GTX 1650, 15.6\'\' FHD IPS', 25990000.00, 1, '3388_dell_inspiron_7445_2_in_1_2024.png', NULL),
(43, 'Dell Precision 7550', 'Core i7-10850H, 16GB, 512GB, VGA NVIDIA RTX 3000, 15.6\" FHD IPS', 18890000.00, 1, 'dell_alienware_m16_r1__2023_.png', NULL),
(44, 'Gaming HP Victus 15', 'Core i5-12450H, 8GB, 512GB, RTX 3050 4GB, 15.6\" FHD 144Hz', 15990000.00, 2, '2206_laptopaz_hp_omen_15_en1013dx_1.png', NULL),
(45, ' HP Envy x360 2-in-1 14', 'Core 7 150U, 16GB, 512GB, Intel Graphics, 14\" FHD Touch', 17990000.00, 2, '2462_laptopaz_hp_envy_13_ba1010nr_1.png', NULL),
(46, 'HP Pavilion 14', 'Core i5-1235U, 8GB, 512GB, Intel Iris Xe Graphics, 14\" FHD IPS', 16390000.00, 2, '2503_laptopaz_hp_pavilion_14_dv2033tu_1.png', NULL),
(47, 'HP 15', 'Core i5-1135G7, 12GB, 512GB, Integrated, 15.6\" HD', 16890000.00, 2, '2679_2463_laptopaz_hp_victus16_man15_6inch_1.png', NULL),
(48, 'Gaming HP Victus 15', 'Core i5-12500H, 8GB, 512GB, RTX 3050, 15.6\" FHD 60Hz', 16590000.00, 2, '3263_hp_envy_x360_2in1_16_2024.png', NULL),
(49, 'HP Victus 16 2022', 'Core i7-12700H, 16GB, 512GB, RTX 3060, 16.1 FHD 144Hz', 20500000.00, 2, '3338_hp_victus_15_6_inch_fhd_144hz_gaming_laptop_amd_ryzen_5_8645hs_nvidia_geforce_rtx_4050_8gb_ddr4_512gb_ssd_mica_silver_2024_04abf048_d9d8_4116_a0bf_68803329aa55_cffd7bcccd71823b93806434d2d23b60.png', NULL),
(50, 'HP Omen 15', 'Ryzen 7 - 5800H, 16GB, 1TB, RTX 3070, 15.6 FHD IPS 144Hz', 35700000.00, 2, '3345_victus_15_amd.png', NULL),
(51, 'Gaming HP Victus 2024', 'Ryzen 5 8645HS, 8GB, 512GB, RTX 4050 6GB, 15.6\" FHD 144Hz', 18900000.00, 2, 'Laptop Gaming HP Victus 15-fa0033dx.png', NULL),
(52, ' Lenovo LOQ 2024 15IAX9', 'Core i5-12450HX, 12GB, 512GB, RTX 2050 4GB, 15.6\" FHD 144Hz', 17490000.00, 3, '3446_3242_loq_2024.png', NULL),
(53, 'Lenovo Legion Y9000P 2024', 'Core i9-14900HX, 32GB, 1TB, RTX 4060 8GB, 16\" 2K+ 240Hz', 37890000.00, 4, '3147_legion_y7000p_2024.png', NULL),
(54, 'Lenovo Legion 5 2024 16IRX9', 'Core i7-14650HX, 16GB, 512GB, RTX 4060 8GB, 16\'\' 2K+ 165Hz', 38590000.00, 4, '3370_legion_5_2024.png', NULL),
(55, 'LENOVO THINKPAD P51', 'Core i7-7820HQ, 16GB, 512GB, VGA 4GB NVIDIA M2200M, 15.6 inch FHD', 22890000.00, 3, '3477_3395_thinkbook_x_ai_2024_h7_1717750769_copy.png', NULL),
(56, 'Lenovo Legion 5 2022 15ARH7', 'Ryzen 5-6600H, 8GB, 512GB, RTX 3050, 15.6\" FHD 165Hz', 19890000.00, 4, '2823_2816_e2e2e23.png', NULL),
(57, 'Lenovo Ideapad Gaming 3', 'Ryzen 5-5600H, 8GB, 512GB, RTX 3050, 15.6\" FHD IPS 120Hz', 25690000.00, 3, 'Lenovo Ideapad Gaming 3.png', NULL),
(58, 'Lenovo T470', 'Core i5-6300U, 8GB, 256GB, VGA Intel UHD 620, 14 inch FHD', 19380000.00, 3, '1945_lenovo_yoga_7i_14itl5_core_i5_bo_vien.png', NULL),
(59, ' Lenovo Thinkbook 16 G7 2024', 'Ryzen 7 8845H, 16GB, 1TB, 16.0\" 2K+ IPS 120Hz', 20490000.00, 3, '3292_lenovo_thinkbook_14_g6__2024_.png', NULL),
(60, 'MSI Cyborg 15 A12VF 267VN ', 'Core i7-12650H, 8GB, 512GB, RTX 4060 8GB, 15.6\" FHD 144Hz', 27690000.00, 5, '2973_msi_cyborg_15_a12ucx.png', NULL),
(61, 'MSI Thin GF63 12UCX', 'Core i5-12450H, 8GB, 1TB, RTX 2050 4GB, 15.6\" FHD 144Hz', 14990000.00, 5, '2115_msi_modern_14_b11mo_682vn_bo_vien.png', NULL),
(62, 'MSI Modern 15 2023 ', 'Ryzen 7-7730U, 16GB, 512GB, AMD Graphics, 15.6\'\' FHD IPS', 14990000.00, 5, '2421_laptopaz_msi_raider_ge76_1.jpg', NULL),
(63, 'MSI Bravo 15 B7ED 010VN', 'Ryzen 5-7535HS, 16GB, 512GB, Radeon RX6550M 4GB, 15.6\'\' FHD 144Hz', 14990000.00, 5, '2526_laptopaz_msi_gl66_11uk_1.png', NULL),
(64, ' MSI GF63 12UCX-841VN', 'Core i5-12450H, 8GB, 512GB, RTX 2050 4GB, 15.6\" FHD 144Hz', 18980000.00, 5, '2818_2154_laptopaz_msi_modern_14_b10mw_605vn_1.png', NULL),
(65, ' MSI Prestige 14Evo', 'Core i5-1240P, 8GB, 512GB, Iris Xe Graphics, 14\'\' FHD IPS 60Hz', 15490000.00, 5, '3029_msi_thin_gf63_12ucx_841vn.png', NULL),
(66, 'MSI GE76 Raider', 'Core i7-11800H, 16GB, 1TB, RTX 3060, 17.3\" FHD 144Hz', 25760000.00, 5, '3039_msi_bravo_15_b7ed_010vn.png', NULL),
(67, 'MSI Pulse GL66 ', 'Core i5-11400H, 8GB, 512GB, RTX 3050, 15.6\" FHD IPS', 24690000.00, 5, 'laptopaz_ge76_raider_1.png', NULL),
(68, 'Acer Predator Helios Neo 2024', 'Core i7-14650HX, 16GB, 1TB, RTX 4060 8GB, 16\" 2K+ 165Hz', 29890000.00, 6, '3456_3178_acer_predator_helios_neo_2024.png', NULL),
(69, 'Acer Nitro V 15', 'Core i5-13420H, 8GB, 512GB, RTX 4050 6GB, 15.6\" FHD IPS 144Hz', 20790000.00, 6, '3561_3547_3359_acer_nitro_v_15_anv15_51.png', NULL),
(70, 'Acer Nitro 5 AN515-55-50V2', 'Core i5-10300H, 16GB, 512GB, GTX1650Ti 4GB DDR6, 15.6\' FHD 144Hz', 12990000.00, 6, '2896_2277_nitro_5_2020.png', NULL),
(71, ' Acer Nitro V 16 ProPanel ', 'Ryzen 7-8845HS, 16GB, 512GB, RTX 4060 8GB, 16\" 2K+ IPS 165Hz', 36990000.00, 6, '3535_3277_acer_aspire_5_a515.png', NULL),
(72, 'Acer Aspire 5 Spin 14', 'Core i7-1355U, 16GB, 512GB, Intel Iris Xe Graphics, 14\" FHD+ Touch', 22990000.00, 6, '2018_acer_aspire_a515_56_51ae_core_i5_sua_bo_vien.png', NULL),
(73, ' Acer Swift 14', 'Core i7-13700H, 32GB, 1TB, Intel Graphics, 14\" 2K+ Touch', 38990000.00, 6, 'Acer Swift 3 SF313-53-56UU.png', NULL),
(74, 'Acer Swift Go 14 AI Gen 2', 'Core Ultra 5 125H, 16GB, 512GB, Intel Arc Graphics, 14\" 2K+', 23790000.00, 6, 'Acer Nitro 5 AN515-54-50TP.png', NULL),
(75, 'Acer Nitro 5 Tiger AN515-58', 'Core i5 - 12500H, 16GB, 512GB, RTX 3050, 15.6\" FHD IPS 165Hz 100% sRGB', 16990000.00, 6, 'Acer Predator Helios Neo 2023.png', NULL),
(76, ' Lenovo ThinkBook 14 G5+ ARP', 'Ryzen 7 7735H, 16GB, 512GB, 14\" 2K+ 90Hz', 16490000.00, 3, '1953_laptopaz_lenovo_thinkpad_p1_gen3_1.png', NULL),
(77, 'Lenovo LOQ Essential 15IAX9E', 'Core i5-12450HX, 12GB, 512GB, RTX 3050 6GB, 15.6\" FHD 144Hz', 18990000.00, 3, '2022_lenovo_ideapad_slim_3_14itl6_bo_vien.png', NULL),
(78, ' Lenovo Thinkbook 16 G6+', 'Intel Ultra 7 155H, 32GB, 1TB, Intel Arc Graphics, 16.0\" 2.5K IPS 120Hz', 26490000.00, 3, '3392_thinkbook_16p_00cd.png', NULL),
(79, 'Lenovo Legion Y7000P 2024', 'Core i7-14650HX, 16GB, 1TB, RTX 4060 8GB, 16\'\' 2K+ 165Hz', 29890000.00, 4, 'Lenovo Legion 5 Pro 2022 16ARH7H.png', NULL),
(80, 'Lenovo Legion Pro 5 R9000P', 'Ryzen 9-7945HX, 16GB, 1TB, RTX 4060 8GB, 16\" 2K+ 240Hz', 31890000.00, 4, '2845_az_legion_2023.png', NULL),
(81, 'Lenovo Legion Slim 5 2024', 'Ryzen 7-8845HS, 16GB, 512GB, RTX 4060 8GB, 16\'\' 2K+ 165Hz', 30990000.00, 4, '3371_17114_lenovo_legion_slim_5_16ahp9__0.png', NULL),
(82, 'Lenovo Legion 5 R7000 ARP8', 'Ryzen 7-7735H, 16GB, 512GB, RTX 4060 8GB, 15.6\'\' 2K+ 165Hz', 29990000.00, 4, 'LEGION 2919_slim_5_2023.png', NULL),
(83, ' Lenovo Legion R9000X', 'Ryzen 7 - 5800H, 16GB, 512GB, RTX 3060, 15.6\'\' WQXGA 165Hz', 25990000.00, 4, 'Lenovo Legion 5 15ACH6.png', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_trang_thai_donhang`
--

CREATE TABLE `lich_su_trang_thai_donhang` (
  `id` int(11) NOT NULL,
  `donhang_id` int(11) NOT NULL,
  `trang_thai_cu` varchar(100) DEFAULT NULL,
  `trang_thai_moi` varchar(100) NOT NULL,
  `nguoi_thay_doi` varchar(100) DEFAULT NULL,
  `thoi_gian_thay_doi` timestamp NOT NULL DEFAULT current_timestamp(),
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_trang_thai_donhang`
--

INSERT INTO `lich_su_trang_thai_donhang` (`id`, `donhang_id`, `trang_thai_cu`, `trang_thai_moi`, `nguoi_thay_doi`, `thoi_gian_thay_doi`, `ghi_chu`) VALUES
(1, 60, 'Đang giao', 'Giao hàng thành công', 'hero01', '2025-06-13 12:15:31', ''),
(2, 53, 'Đang chờ duyệt', 'Đã duyệt', 'hero01', '2025-06-13 12:15:58', ''),
(3, 53, 'Đã duyệt', 'Đang giao', 'hero01', '2025-06-13 12:23:50', ''),
(4, 67, 'Đang chờ duyệt', 'Đã duyệt', 'hero01', '2025-06-13 13:16:27', ''),
(5, 68, 'Đang chờ duyệt', 'Đã duyệt', 'hero01', '2025-07-25 07:40:17', ''),
(6, 68, 'Đã duyệt', 'Đang giao', 'hero01', '2025-07-25 07:40:21', ''),
(7, 69, 'Đang chờ duyệt', 'Đã duyệt', 'hero01', '2025-07-25 08:23:51', ''),
(8, 69, 'Đã duyệt', 'Đang giao', 'hero01', '2025-07-25 08:23:54', ''),
(9, 69, 'Đang giao', 'Giao hàng thành công', 'hero01', '2025-07-25 08:23:59', ''),
(10, 68, 'Đang giao', 'Giao hàng thành công', 'hero01', '2025-08-09 10:06:55', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaihang`
--

CREATE TABLE `loaihang` (
  `idloaihang` int(11) NOT NULL,
  `tenloaihang` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loaihang`
--

INSERT INTO `loaihang` (`idloaihang`, `tenloaihang`, `mota`) VALUES
(1, 'Laptop Dell', 'Laptop Văn Phòng'),
(2, 'Laptop HP', 'Laptop Văn Phòng'),
(3, 'Laptop Lenovo', 'Laptop Văn Phòng'),
(4, 'Laptop Legion', 'Laptop Gaming'),
(5, 'Laptop MSI', 'Laptop Gaming'),
(6, 'Laptop Acer', 'Laptop Gaming');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `author_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `image`, `content`, `published_at`, `status`, `author_id`, `created_at`, `updated_at`) VALUES
(22, ' Intel khiến giới công nghệ bất ngờ: Tăng tốc chip không cần nâng cấp phần cứng nhờ tiến trình 14A', NULL, 'uploads/1749582691_z6683062348217_dea144335cd8d77a88c0cbe489a560b6.jpg', '<h2 class=\"knc-sapo\" style=\"text-align: justify; font-weight: bold; font-size: 1.5em; margin-block-start: 0.83em; margin-top: 0.83em; margin-bottom: 0.83em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tăng hiệu suất chip nhưng không tăng bóng bán dẫn, cũng không tiêu tốn thêm điện năng - nghe như nghịch lý, nhưng đó chính là điều Intel vừa hé lộ với tiến trình 14A</span></h2><h2 class=\"knc-sapo\" style=\"text-align: justify; margin-block-start: 0.83em; margin-top: 0.83em; margin-bottom: 0.83em;\"><p style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em; text-align: left;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0205_1111.png\" alt=\"\" width=\"918\" height=\"602\" style=\"text-align: justify; border-style: none;\"></span></p><div id=\"ContentDetail\" class=\"knc-content\" style=\"text-align: left;\"><p class=\"\" data-start=\"283\" data-end=\"689\" style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tại sự kiện Intel Foundry Direct 2025 diễn ra ở San Jose (Mỹ), Intel đã chính thức công bố thông tin chi tiết đầu tiên về tiến trình bán dẫn 14A, thế hệ kế tiếp sau 18A và dự kiến bước vào giai đoạn sản xuất thử nghiệm vào năm 2027. Đây là một trong những nỗ lực mới nhất của Intel nhằm giành lại lợi thế trong cuộc đua công nghệ chip, vốn đang ngày càng gay gắt với sự vươn lên mạnh mẽ từ TSMC và Samsung.</span></p><p class=\"\" data-start=\"283\" data-end=\"689\" style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0205_4444.png\" alt=\"\" width=\"922\" height=\"605\" style=\"text-align: justify; border-style: none;\"></span></p><p class=\"\" data-start=\"691\" data-end=\"1244\" style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Theo Intel, tiến trình 14A sẽ mang lại mức cải thiện hiệu suất trên mỗi watt từ 15 đến 20% so với 18A. Khi được tối ưu theo hướng tiết kiệm năng lượng, 14A có thể giúp giảm tới 35% điện năng tiêu thụ ở cùng mức hiệu năng, hoặc duy trì mức tiêu thụ nhưng tăng xung nhịp - tùy thuộc vào cách tinh chỉnh từng thiết kế chip. Những cải thiện này phần lớn đến từ hệ thống cấp điện mới mang tên PowerDirect, cho phép cấp nguồn trực tiếp từ mặt sau của tấm nền bán dẫn - một trong những xu hướng thiết kế được đánh giá rất tiềm năng trong lĩnh vực chip cao cấp.</span></p><font color=\"#212529\" face=\"Roboto, sans-serif\"><span style=\"font-size: 14px;\"><img src=\"https://laptopaz.vn/media/news/0205_5555.png\" alt=\"\" width=\"787\" height=\"438\" style=\"text-align: justify; border-style: none;\"></span></font><div style=\"text-align: justify;\"><font color=\"#212529\" face=\"Roboto, sans-serif\"><span style=\"font-size: 14px;\"><br></span></font></div><p class=\"\" data-start=\"1246\" data-end=\"1713\" style=\"text-align: justify; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Bên cạnh đó, 14A cũng được cải tiến mạnh mẽ về mật độ transistor, với mức tăng 1.3 lần so với thế hệ trước. Dải điện áp hoạt động được mở rộng để hỗ trợ linh hoạt hơn trong thiết kế điện - tần. Intel đồng thời nâng cấp kiến trúc transistor RibbonFET lên thế hệ mới với tên gọi RibbonFET 2, được cho là sẽ cải thiện khả năng đóng cắt và tăng mật độ mà không làm ảnh hưởng đến tính ổn định, dù hiện tại hãng vẫn chưa tiết lộ chi tiết cấu trúc của thế hệ transistor này.</span></p><p class=\"\" data-start=\"1246\" data-end=\"1713\" style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0205_3333.png\" alt=\"\" width=\"927\" height=\"513\" style=\"text-align: justify; border-style: none;\"></span></p><p class=\"\" data-start=\"1715\" data-end=\"2409\" style=\"text-align: justify; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tuy nhiên, điểm nổi bật và gây nhiều chú ý nhất chính là công nghệ Turbo Cell - một phương pháp thiết kế logic mới được Intel phát triển để giải quyết triệt để các nút thắt cổ chai hiệu suất, đặc biệt là ở các đoạn đường tín hiệu quan trọng (critical path). Trong cấu trúc của vi xử lý, các đường tín hiệu này có độ trễ lớn nhất và chính chúng quyết định tần số tối đa mà toàn bộ chip có thể vận hành. Thông thường, các nhà thiết kế sẽ sử dụng transistor tốc độ cao tại các khu vực này, nhưng phải đánh đổi bằng điện năng cao và mật độ thấp. Turbo Cell mang đến một giải pháp trung dung, cho phép tăng dòng dẫn transistor trong các khu vực hiệu năng cao mà vẫn duy trì được mật độ thiết kế tốt.</span></p><p class=\"\" data-start=\"1715\" data-end=\"2409\" style=\"color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0205_2222.png\" alt=\"\" width=\"926\" height=\"522\" style=\"text-align: justify; border-style: none;\"></span></p><p class=\"\" data-start=\"2411\" data-end=\"3148\" style=\"text-align: justify; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thay vì chọn giữa hiệu năng và điện năng như trước đây, Turbo Cell cho phép kết hợp các tế bào logic tiết kiệm điện với những tế bào hiệu suất cao trong cùng một khối thiết kế, tùy chỉnh theo nhu cầu từng vùng của con chip. Đặc biệt, công nghệ này phát huy hiệu quả tối đa khi kết hợp với các thư viện cell thấp - vốn thường được dùng để tiết kiệm diện tích và giảm điện năng trong CPU và GPU - bằng cách nâng chiều cao các cell này thành dạng “kép” để tăng hiệu suất mà không mở rộng diện tích thiết kế. Turbo Cell cũng hỗ trợ nhiều cách cấu hình khác nhau với khả năng thay đổi chiều rộng ribbon hoặc ghép các ribbon lại thành cấu trúc lớn hơn để tăng dòng dẫn - mang đến cho kiến trúc sư chip một bộ công cụ rất linh hoạt để tùy biến.</span></p><p class=\"\" data-start=\"3150\" data-end=\"3562\" style=\"text-align: justify; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; font-weight: 400; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Dù còn hai năm nữa mới đến giai đoạn sản xuất thử nghiệm, tiến trình 14A của Intel cho thấy sự quyết liệt trong việc giải quyết bài toán hiệu năng và điện năng - hai trụ cột quan trọng nhất trong thiết kế chip hiện đại. Trong khi các đối thủ đang tiếp tục thu hẹp khoảng cách, Intel đang đặt cược vào PowerDirect, RibbonFET 2 và Turbo Cell như ba thành tố then chốt để định hình lại tương lai chip hiệu năng cao.</span></p></div></h2>', NULL, 'active', 1, '2025-06-08 07:46:53', '2025-06-11 02:11:31'),
(23, 'HUAWEI gây bão với chip Kirin X90 dành cho PC - Tự tay làm cả phần cứng lẫn hệ điều hành, quyết thoát Mỹ toàn diện', NULL, 'uploads/1749582655_z6683073265276_5c07c8a39b688e33dea37d03b992c50a.jpg', '<p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Sau khi công bố HarmonyOS cho PC, Huawei tiếp tục khiến cả giới công nghệ xôn xao với thông tin sẽ trình làng con chip Kirin X90 – bộ vi xử lý do chính hãng phát triển cho máy tính cá nhân, đánh dấu bước tiến lớn trong chiến lược tự chủ toàn bộ chuỗi công nghệ.</font></p><p style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span class=\"_fadeIn_m1hgl_8\" style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/1005_KX91.jpg\" alt=\"\" width=\"1280\" height=\"720\" style=\"text-align: justify; border-style: none; width: 100%;\"></span></p><p data-start=\"565\" data-end=\"630\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-weight: bolder;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span class=\"_fadeIn_m1hgl_8\">Kirin&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">X90:&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">Vi&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">xử&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">lý \"</span><span class=\"_fadeIn_m1hgl_8\">cây&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">nhà&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">lá&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">vườn\"&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">đầu&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">tiên&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">dành&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">cho&nbsp;</span><span class=\"_fadeIn_m1hgl_8\">PC</span></span></span></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Theo một nguồn tin rò rỉ từ Trung Quốc, Kirin X90 là con chip được Huawei thiết kế dành riêng cho nền tảng máy tính, mang tên mã “Charlotte Pro”. Đáng chú ý, tên mã này rất giống với Kirin 9010 đang dùng trên dòng Mate 70 – cho thấy Huawei có thể tái sử dụng kiến trúc CPU di động, nhưng nâng cấp để phù hợp với máy tính để bàn và laptop.</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Cấu hình rò rỉ: 10 nhân – 20 luồng, hỗ trợ chuẩn mã hóa nội địa</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Kiến trúc: 10 nhân theo cấu trúc 4+4+2</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Hỗ trợ siêu phân luồng (SMT) – tổng cộng 20 luồng xử lý</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Tích hợp các chuẩn mã hóa Trung Quốc như SM3, SM4 – hướng tới thị trường nội địa và doanh nghiệp chính phủ</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">RAM hỗ trợ: tối đa 32 GB LPDDR5-6400, bus 128-bit, băng thông đạt 100GB/s</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">GPU dự đoán: 10 nhân, tên mã “Ma Liang 920”</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Lưu trữ: hỗ trợ SSD lên đến 2TB</font></p><p data-start=\"632\" data-end=\"982\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Cổng kết nối: 3 cổng USB-4 tốc độ cao</font></p><p data-start=\"632\" data-end=\"982\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><img src=\"https://laptopaz.vn/media/news/1005_Anhmanhinh2025-05-09luc16.16.27.png\" alt=\"\" width=\"1264\" height=\"476\" style=\"text-align: justify; font-family: arial, helvetica, sans-serif; font-size: 12pt; border-style: none;\"></p><p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Kirin X90 sẽ là bộ vi xử lý đầu tiên vận hành HarmonyOS cho PC, dự kiến ra mắt vào ngày 20/5 trên mẫu MateBook Pro mới. Sự kết hợp giữa phần cứng \"nhà làm\" và hệ điều hành nội địa giúp Huawei kiểm soát sâu toàn bộ hệ thống – từ hiệu năng, bảo mật cho đến khả năng tối ưu năng lượng.</font></p><p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\"><br></font></p><p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Dù cấu hình trên lý thuyết khá ấn tượng, hiệu suất thực tế của Kirin X90 vẫn là ẩn số. Liệu con chip này có đủ sức đối đầu với Intel, AMD hay Apple Silicon? Huawei vẫn chưa công bố các bài benchmark chính thức. Nhưng rõ ràng, với chiến lược “tự chủ công nghệ 100%”, Huawei đang cho thấy họ không còn muốn phụ thuộc vào bất kỳ ai.</font></p><p style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/1005_x902.jpg\" alt=\"\" width=\"900\" height=\"504\" style=\"text-align: justify; border-style: none;\"></span></p><p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><font color=\"#212529\" face=\"arial, helvetica, sans-serif\">Từ việc tự phát triển chip cho đến ra mắt hệ điều hành riêng, Huawei đang xây dựng một hệ sinh thái PC khép kín – không Intel, không Windows, không Google. Kirin X90 là mắt xích tiếp theo trong hành trình đó. Và ngày 20/5 tới đây, mọi ánh mắt sẽ đổ dồn về Huawei: liệu họ đang tạo ra một cuộc cách mạng mới, hay chỉ là \"ông lớn\" thử sức ở sân chơi quá khốc liệt?</font></p>', NULL, 'active', 1, '2025-06-08 07:53:31', '2025-06-11 02:10:55'),
(24, 'LDPlayer gây sốt Vietnam GameVerse 2025: Trải nghiệm giả lập đỉnh cao làm nóng cộng đồng game Việt!', NULL, 'uploads/1749578613_z6687636174347_555d1be14f3fd6938e40c1fe3d189693.jpg', '<p style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tại Vietnam GameVerse 2025 – sự kiện đỉnh cao quy tụ tinh hoa ngành game Việt, LDPlayer lần đầu tiên xuất hiện với vai trò nhà tài trợ Đồng, đánh dấu bước tiến quan trọng trong hành trình gắn kết cùng cộng đồng game thủ Việt. Sự góp mặt này không chỉ khẳng định vị thế dẫn đầu của LDPlayer trong lĩnh vực giả lập Android cho game mobile mà còn mở ra chương mới đầy hứa hẹn cho trải nghiệm chơi game trên nền tảng giả lập.</span></p><p style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0406_Anhmanhinh2025-06-04luc11.28.36.jpeg\" alt=\"\" width=\"1468\" height=\"826\" style=\"text-align: center; border-style: none; width: 100%;\"></span></p><p data-start=\"100\" data-end=\"481\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span data-start=\"100\" data-end=\"172\" style=\"font-weight: bolder;\">Giả lập hàng đầu – Trải nghiệm đích thực dành cho game thủ sành điệu</span></span></p><p data-start=\"100\" data-end=\"481\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">LDPlayer đã khẳng định vị thế trong cộng đồng game thủ nhờ khả năng xử lý mượt mà, tương thích lên đến 99.9% tựa game mobile, cùng hiệu năng tối ưu và hỗ trợ đa nền tảng vượt trội. Nhưng điểm tạo nên sự khác biệt thật sự chính là cam kết “không có đường tắt – chỉ có tối ưu riêng biệt cho từng tựa game”.</span></p><p data-start=\"483\" data-end=\"585\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tại Vietnam GameVerse 2025, LDPlayer mang đến không gian trải nghiệm đỉnh cao với dàn game đình đám:</span></p><ul data-start=\"587\" data-end=\"854\" style=\"margin-top: 1em; margin-bottom: 1em; list-style-type: initial; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px; padding-left: 40px !important;\"><li data-start=\"587\" data-end=\"673\" style=\"list-style: initial !important;\"><p data-start=\"589\" data-end=\"673\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thiên Nhai Minh Nguyệt Đao Mobile, Thiên Long Bát Bộ VNG, MU Lục Địa VNG (VNG)</span></p></li><li data-start=\"674\" data-end=\"715\" style=\"list-style: initial !important;\"><p data-start=\"676\" data-end=\"715\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Đại Tiên Minh Đi Đâu Thế (Funtap)</span></p></li><li data-start=\"716\" data-end=\"816\" style=\"list-style: initial !important;\"><p data-start=\"718\" data-end=\"816\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Chiến Giới 4D, Thần Ma Loạn Vũ, Mucbang Tam Quốc, Lục Tung Tam Quốc, Phong Ma Đạo Sĩ (Vplay)</span></p></li><li data-start=\"817\" data-end=\"854\" style=\"list-style: initial !important;\"><p data-start=\"819\" data-end=\"854\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thiếu Nữ Scarlet (Magic Game)</span></p></li></ul><p data-start=\"856\" data-end=\"1086\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tại booth LDPlayer, người chơi được trải nghiệm FPS siêu cao, đồ họa sắc nét sống động cùng độ trễ gần như bằng 0 trên màn hình PC rộng lớn – mang đến một cách chơi game mobile hoàn toàn mới mẻ và khác biệt, chỉ có tại LDPlayer.</span></p><p data-start=\"856\" data-end=\"1086\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0406_Anhmanhinh2025-06-04luc11.27.41.png\" alt=\"\" width=\"1476\" height=\"1078\" style=\"text-align: justify; border-style: none;\"></span></p><p data-start=\"96\" data-end=\"469\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span data-start=\"96\" data-end=\"149\" style=\"font-weight: bolder;\">Chất lượng được khẳng định bởi cộng đồng game thủ</span></span></p><p data-start=\"96\" data-end=\"469\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Với hàng triệu lượt tải và vị trí dẫn đầu bảng xếp hạng giả lập tại Việt Nam, LDPlayer đã trở thành lựa chọn vàng của đông đảo streamer, YouTuber và game thủ chuyên nghiệp. Hàng nghìn đánh giá tích cực từ người dùng chính là minh chứng rõ nét cho hiệu năng ổn định và trải nghiệm vượt trội mà nền tảng này mang lại.</span></p><p data-start=\"471\" data-end=\"698\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Một game thủ tham dự sự kiện chia sẻ chân thực:</span></div><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><div style=\"text-align: justify;\"><em data-start=\"521\" data-end=\"696\" style=\"font-size: 12pt;\">“Tôi chơi rất nhiều game mobile mới và đã thử qua không ít giả lập, nhưng LDPlayer là ổn định nhất. Trải nghiệm trên PC cực mượt mà – hoàn toàn xứng đáng với số tiền bỏ ra!”</em></div></span></p><p data-start=\"471\" data-end=\"698\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em data-start=\"521\" data-end=\"696\"><img src=\"https://laptopaz.vn/media/news/0406_Anhmanhinh2025-06-04luc11.27.59.png\" alt=\"\" width=\"1474\" height=\"1106\" style=\"text-align: justify; border-style: none;\"></em></span></p><p data-start=\"88\" data-end=\"352\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span data-start=\"88\" data-end=\"161\" style=\"font-weight: bolder;\">LDPlayer – Cầu nối vững chắc giữa nhà phát hành và cộng đồng game thủ</span></span></p><p data-start=\"88\" data-end=\"352\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Không chỉ dừng lại ở việc phục vụ người chơi, LDPlayer còn định hướng xây dựng một hệ sinh thái bền vững, trở thành đối tác chiến lược đồng hành lâu dài cùng các nhà phát hành game Việt.</span></p><p data-start=\"354\" data-end=\"612\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><div style=\"text-align: justify;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Ông Robert Shawn, đại diện LDPlayer khu vực Đông Nam Á, khẳng định:</span></div><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><div style=\"text-align: justify;\"><em data-start=\"424\" data-end=\"610\" style=\"font-size: 12pt;\">\"Chúng tôi cam kết sát cánh cùng cộng đồng và các nhà phát triển game mobile Việt Nam, cùng nhau kiến tạo những giá trị mới, nâng tầm sản phẩm và chinh phục những thành công vượt bậc.\"</em></div></span></p><p data-start=\"354\" data-end=\"612\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><em data-start=\"424\" data-end=\"610\"><img src=\"https://laptopaz.vn/media/news/0406_Anhmanhinh2025-06-04luc11.28.20.png\" alt=\"\" width=\"1476\" height=\"1104\" style=\"text-align: justify; border-style: none;\"></em></span></p><p data-start=\"86\" data-end=\"544\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span data-start=\"86\" data-end=\"169\" style=\"font-weight: bolder;\">Hướng đến tương lai: Giả lập thế hệ mới – Trải nghiệm đột phá cho game thủ Việt</span></span></p><p data-start=\"86\" data-end=\"544\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Khi game mobile ngày càng đòi hỏi cấu hình mạnh mẽ, LDPlayer nổi lên như lựa chọn chiến lược hàng đầu, giúp game thủ tận hưởng thế giới game trên màn hình lớn với hiệu suất tối ưu tuyệt đối. Sắp tới, phiên bản mới của LDPlayer với hệ điều hành nâng cấp sẽ ra mắt, hứa hẹn mang đến những cải tiến vượt bậc, mở rộng không gian trải nghiệm đỉnh cao cho game mobile trên PC.</span></p><p data-start=\"546\" data-end=\"794\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Thông điệp chủ đạo tại Vietnam GameVerse 2025 –&nbsp;<em data-start=\"594\" data-end=\"630\">“Giả lập có 1-0-2 cho game mobile”</em>&nbsp;– chính là lời cam kết kiên định của LDPlayer trong việc không ngừng đổi mới, để mỗi game thủ Việt đều có được trải nghiệm chơi game hoàn hảo và độc nhất vô nhị.</span></p><p data-start=\"546\" data-end=\"794\" style=\"margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><img src=\"https://laptopaz.vn/media/news/0406_Anhmanhinh2025-06-04luc11.28.51.png\" alt=\"\" width=\"1476\" height=\"820\" style=\"text-align: justify; border-style: none;\"></span></p><p data-start=\"546\" data-end=\"794\" style=\"text-align: justify; margin-top: 1em; margin-bottom: 1em; color: rgb(33, 37, 41); font-family: Roboto, sans-serif; font-size: 14px;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\">Tại GameVerse 2025, LDPlayer không chỉ đơn thuần là một gian hàng công nghệ – đó là điểm hẹn của cộng đồng game mobile Việt, nơi họ tìm thấy người bạn đồng hành thấu hiểu, luôn sát cánh và sẵn sàng bứt phá cùng ngành game nước nhà.</span></p>', NULL, 'active', 1, '2025-06-11 01:03:33', '2025-06-11 01:03:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ho_va_ten` varchar(100) DEFAULT NULL,
  `quan_huyen` varchar(100) DEFAULT NULL,
  `dia_chi_chi_tiet` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `tinh_thanh_pho` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `ho_va_ten`, `quan_huyen`, `dia_chi_chi_tiet`, `username`, `phone`, `gender`, `role`, `password`, `status`, `tinh_thanh_pho`, `email`) VALUES
(1, NULL, 'Quận 1', 'Đường 1', 'hero01', '0832222222', 'Nam', 'admin', '1', 1, 'Hồ Chí Minh', NULL),
(3, 'Nguyễn Văn A', 'Gò Vấp', '123 Đường Quang Trung, Phường 10', 'hero02', '0839411900', 'Nam', 'user', '1', 1, 'TP. Hồ Chí Minh', 'hero02@gmail.com'),
(5, 'Nguyễn Minh Quân', 'Ba Đình', '12 Đường Hoàng Hoa Thám, Phường Ngọc Hà', 'hero03', '0839123456', 'Nam', 'user', '123456', 1, 'Hà Nội', 'minhquan.nguyen@gmail.com'),
(9, 'Trần Gia Hưng', 'Quận 3', '56 Đường Võ Văn Tần, Phường 6', 'hero04', '0839234567', 'Nam', 'user', '123456', 1, 'TP. Hồ Chí Minh', 'giahung.tran@gmail.com'),
(16, 'Lê Anh Tuấn', 'Hải Châu', '78 Đường Bạch Đằng, Phường Hải Châu 1', 'hero05', '0839345678', 'Nam', 'user', '123456', 1, 'Đà Nẵng', 'anhtuan.le@gmail.com'),
(19, 'Đặng Trung Kiên', 'Ninh Kiều', '90 Đường 30/4, Phường Xuân Khánh', 'hero06', '0839456789', 'Nam', 'user', '123456', 1, 'Cần Thơ', 'trungkien.dang@gmail.com'),
(20, 'nguyen van d', 'Cái Răng', 'Trương Vĩnh Nguyên', 'final test', '0913733312', 'Nam', 'user', '123456', 1, 'Cần Thơ', 'nguyenvand123@gmail.com');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `type` enum('percent','cash') DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expired_at` datetime DEFAULT NULL,
  `total_uses` int(11) DEFAULT 0,
  `used_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `voucher`
--

INSERT INTO `voucher` (`id`, `code`, `value`, `type`, `is_active`, `expired_at`, `total_uses`, `used_count`) VALUES
(1, 'TESTVC10', 10, 'percent', 1, '2028-09-30 23:59:00', 99, 3),
(2, 'TEST2', 16, 'percent', 1, '2028-09-14 21:47:00', 99, 1),
(3, 'TEST3', 10000, 'cash', 1, '2028-07-06 21:48:00', 99, 0),
(4, '4BF07F774D', 5, 'percent', 1, '2025-06-20 21:27:00', 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `voucher_user`
--

CREATE TABLE `voucher_user` (
  `id` int(11) NOT NULL,
  `id_kh` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `used_at` datetime DEFAULT current_timestamp(),
  `use_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `voucher_user`
--

INSERT INTO `voucher_user` (`id`, `id_kh`, `voucher_id`, `used_at`, `use_count`) VALUES
(1, 16, 1, '2025-06-09 20:19:53', 1),
(2, 3, 2, '2025-06-13 20:14:16', 1),
(3, 3, 1, '2025-07-25 15:22:33', 1),
(4, 20, 1, '2025-08-09 16:31:28', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD KEY `id_sanpham` (`id_sanpham`),
  ADD KEY `id_donhang` (`id_donhang`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `id_3` (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contacts_id_kh` (`id_kh`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idhanghoa` (`idhanghoa`),
  ADD KEY `fk_danhgia_users` (`id_kh`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `id` (`id`),
  ADD KEY `id_kh` (`id_kh`),
  ADD KEY `fk_donhang_id_voucher` (`id_voucher`);

--
-- Chỉ mục cho bảng `hanghoa`
--
ALTER TABLE `hanghoa`
  ADD PRIMARY KEY (`idhanghoa`),
  ADD KEY `idloaihang` (`idloaihang`),
  ADD KEY `idhanghoa` (`idhanghoa`);

--
-- Chỉ mục cho bảng `lich_su_trang_thai_donhang`
--
ALTER TABLE `lich_su_trang_thai_donhang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_donhang_id` (`donhang_id`),
  ADD KEY `idx_thoi_gian` (`thoi_gian_thay_doi`);

--
-- Chỉ mục cho bảng `loaihang`
--
ALTER TABLE `loaihang`
  ADD PRIMARY KEY (`idloaihang`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_post_author` (`author_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Chỉ mục cho bảng `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `voucher_user`
--
ALTER TABLE `voucher_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_voucher` (`id_kh`,`voucher_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT cho bảng `hanghoa`
--
ALTER TABLE `hanghoa`
  MODIFY `idhanghoa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT cho bảng `lich_su_trang_thai_donhang`
--
ALTER TABLE `lich_su_trang_thai_donhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `loaihang`
--
ALTER TABLE `loaihang`
  MODIFY `idloaihang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `voucher_user`
--
ALTER TABLE `voucher_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `chitietdonhang_ibfk_1` FOREIGN KEY (`id_sanpham`) REFERENCES `hanghoa` (`idhanghoa`),
  ADD CONSTRAINT `chitietdonhang_ibfk_2` FOREIGN KEY (`id_donhang`) REFERENCES `donhang` (`id`);

--
-- Các ràng buộc cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_contacts_id_kh` FOREIGN KEY (`id_kh`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `danhgia_ibfk_1` FOREIGN KEY (`idhanghoa`) REFERENCES `hanghoa` (`idhanghoa`),
  ADD CONSTRAINT `fk_danhgia_users` FOREIGN KEY (`id_kh`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `fk_donhang_id_kh` FOREIGN KEY (`id_kh`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_donhang_id_voucher` FOREIGN KEY (`id_voucher`) REFERENCES `voucher` (`id`);

--
-- Các ràng buộc cho bảng `hanghoa`
--
ALTER TABLE `hanghoa`
  ADD CONSTRAINT `hanghoa_ibfk_1` FOREIGN KEY (`idloaihang`) REFERENCES `loaihang` (`idloaihang`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lich_su_trang_thai_donhang`
--
ALTER TABLE `lich_su_trang_thai_donhang`
  ADD CONSTRAINT `lich_su_trang_thai_donhang_ibfk_1` FOREIGN KEY (`donhang_id`) REFERENCES `donhang` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `voucher_user`
--
ALTER TABLE `voucher_user`
  ADD CONSTRAINT `voucher_user_ibfk_1` FOREIGN KEY (`id_kh`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `voucher_user_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
