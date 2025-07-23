-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 12, 2024 lúc 04:47 PM
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
-- Cơ sở dữ liệu: `store_db`
--

DELIMITER $$
--
-- Thủ tục
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_product` (
    IN `p_name` VARCHAR(100),
    IN `p_description` TEXT,
    IN `p_price` DECIMAL(10,3),
    IN `p_image_url` VARCHAR(255),
    IN `p_type` VARCHAR(20),
    IN `p_outstanding` TINYINT(1)
)
BEGIN
    DECLARE max_id INT;

    SELECT IFNULL(MAX(id), 0) INTO max_id FROM products;

    SET @sql = CONCAT('ALTER TABLE products AUTO_INCREMENT = ', max_id + 1);
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    INSERT INTO products (name, description, price, image_url, type, outstanding)
    VALUES (p_name, p_description, p_price, p_image_url, p_type, p_outstanding);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_product` (IN `p_id` INT)   BEGIN
    DECLARE row_count INT;
    
    START TRANSACTION;
    
    DELETE FROM products WHERE id = p_id;
    
    SELECT COUNT(*) INTO row_count FROM products;
    
    IF row_count > 0 THEN
        SET @sql = 'SET @count = 0; UPDATE products SET id = (@count:=@count+1) ORDER BY id';
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
        
        SET @sql = 'ALTER TABLE products AUTO_INCREMENT = 1';
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
    
    COMMIT;
END$$

DELIMITER ;


CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(1, 2, 1, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_info`
--

CREATE TABLE `customer_info` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `status` enum('pending','confirmed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `payment_method`, `name`, `phone`, `email`, `address`) VALUES
(1, 3, 190.000, 'confirmed', '2024-09-12 13:01:32', 'cod', 'Trường', '0332210729', 'truongu123@gmail.com', 'ap Tan Lap, xa Phuoc Tan, Bien Hoa, Dong Nai'),
(2, 7, 410.000, 'pending', '2024-09-12 13:03:05', 'cod', 'Lê tứ', '(+84) 975 385 271', 'letu@gmail.com', 'sài gòn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(8, 1, 5, 1, 190.000),
(9, 2, 5, 1, 190.000),
(10, 2, 7, 1, 220.000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,3) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `type` enum('club','national') DEFAULT 'club',
  `outstanding` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `type`, `outstanding`, `created_at`, `updated_at`, `active`) VALUES
(1, 'DTQG Việt Nam', 'mô tả', 220.000, 'images/Nation/66e251bcda66b.jpg', 'national', 1, '2024-09-09 08:58:27', '2024-09-12 05:19:09', 1),
(2, 'Mu 2024', 'mu', 200.000, 'images/Club/66e2518e5cab8.png', 'club', 0, '2024-09-11 11:31:24', '2024-09-12 02:28:52', 1),
(3, 'Anh 2024', 'Được làm bởi chất liệu vải Thailand cao cấp cho khả năng hút ẩm và chống nhăn cực kỳ tốt./nVải Thailand có khả năng cách nhiệt và thoáng khí tốt. Bề mặt áo mượt mà, không xù, luôn sáng bóng, cảm giác dễ chịu. Hơn nữa khả năng kháng bụi bẩn cao giúp tránh bám bẩn trong quá trình sử dụng./nDễ dàng giặt sạch, nhanh khô', 220.000, 'images/Nation/66e2448fb6564.png', 'national', 0, '2024-09-12 01:31:59', '2024-09-12 01:32:48', 1),
(4, 'Milan Away 23-24', 'mô tả', 300.000, 'images/Club/66e2454734b54.png', 'club', 0, '2024-09-12 01:35:03', '2024-09-12 01:35:03', 1),
(5, 'Liverpool 24-25', 'áo liver', 190.000, 'images/Club/66e26e91271de.png', 'club', 0, '2024-09-12 04:31:13', '2024-09-12 04:31:13', 1),
(6, 'Chelsea 22-23', 'áo chè xanh', 210.000, 'images/Club/66e2716c875f5.png', 'club', 0, '2024-09-12 04:43:24', '2024-09-12 04:43:24', 1),
(7, 'Đức 2014', 'Vô địch wc14 là ai ạ?', 220.000, 'images/Nation/66e2733bc0633.jpg', 'national', 0, '2024-09-12 04:51:07', '2024-09-12 04:51:07', 1),
(8, 'Argentina 22', 'argentina', 500.000, 'images/Nation/66e273730cdcd.png', 'national', 0, '2024-09-12 04:52:03', '2024-09-12 04:52:03', 1),
(9, 'Psg', 'paris', 170.000, 'images/Club/66e273a5c36f2.jpg', 'club', 0, '2024-09-12 04:52:53', '2024-09-12 04:52:53', 1),
(10, 'Pháp 2018', 'Nhà vua 2018', 550.000, 'images/Nation/66e273c55ccdf.jpg', 'national', 0, '2024-09-12 04:53:25', '2024-09-12 04:53:25', 1),
(11, 'Ba Lan', 'ba lan', 90.000, 'images/Nation/66e29e6bb013a.png', 'national', 0, '2024-09-12 07:55:23', '2024-09-12 07:55:23', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `created_at`) VALUES
(1, 'admin', '0616', 'admin@gmail.com', 1, '2024-09-09 08:58:27'),
(2, 'doubleT', '1234', 'eto@gmail.com', 0, '2024-09-09 08:58:27'),
(3, 'num1', '$2y$10$eJOQWS2x6VEMZ15Joir.SuXZEzndxSJAOaGx3P1KwzGPs/i27TlN.', '', 0, '2024-09-12 06:27:51'),
(7, 'TT', '$2y$10$HF7cjCPRIDkWGgrJQpJLBO1lLXfPP97BHifizAXu0Orm4PNkdCy/a', '22h1120143@ut.edu.vn', 0, '2024-09-12 08:25:04'),
(9, 'taola', '$2y$10$S02Ygtm43BVijgW48LgjzuFo1/vN8d8PIX4KFak8G2VGMrCvfdww2', 'taola@gmail.com', 0, '2024-09-12 14:39:06'),
(10, 'Tứ', '$2y$10$1V1aybHxUwpYNodgP9Ts5epkSLOjuPzrFav4fDOVlfZBH/kmJWLxa', 'Tu@yahoo.com', 0, '2024-09-12 14:44:07');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `customer_info`
--
ALTER TABLE `customer_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `customer_info`
--
ALTER TABLE `customer_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `customer_info`
--
ALTER TABLE `customer_info`
  ADD CONSTRAINT `customer_info_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
