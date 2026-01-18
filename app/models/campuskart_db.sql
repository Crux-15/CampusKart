-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 06:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campuskart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `product_id`, `user_id`, `created_at`) VALUES
(1, 3, 5, '2026-01-17 15:09:46'),
(2, 7, 7, '2026-01-18 15:21:42'),
(3, 6, 7, '2026-01-18 15:22:59');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `product_id`, `message`, `created_at`) VALUES
(1, 5, 3, 4, 'Hello brother, how are you?\r\nCan you give me a discount?', '2026-01-17 16:38:06'),
(2, 3, 5, 4, 'Ok for you, it will be 50% off', '2026-01-17 16:44:40'),
(3, 3, 5, 4, 'Now Happy?', '2026-01-17 16:44:53'),
(4, 5, 3, 4, 'ok happy', '2026-01-17 16:49:46'),
(5, 5, 3, 6, 'I need this one', '2026-01-17 16:50:00'),
(13, 3, 5, 6, 'here is', '2026-01-17 17:11:57'),
(14, 7, 3, 6, 'aaaaaaa ammmmmmmmmmiiiiiiiiiii bokaaa', '2026-01-18 15:23:12'),
(15, 3, 7, 6, 'wow!!!!! you realise!!!!!!11', '2026-01-18 15:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `condition_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `title`, `price`, `category`, `condition_type`, `description`, `image`, `status`, `created_at`) VALUES
(3, 3, 'Raspberry Pi 5', 5000.00, 'Electronics', '', 'Raspberry Pi 5\r\nSADMAN SAKIB', '1768592666_images.png', 'available', '2026-01-17 01:44:26'),
(4, 3, 'Calculator', 499.00, 'Stationery', '', 'My Calculator', '1768596262_download.png', 'available', '2026-01-17 02:44:22'),
(6, 3, 'BIC Lighter', 20.00, 'Accessories', '', 'My Bic Lighter', '1768598564_BIC-Classic-Pocket-Lighter-50-Count_1a7c7331-7b0c-4ee7-a38a-f130b1dad85d.24e95247b126782f6273f0d5e4150f09.png', 'available', '2026-01-17 03:22:44'),
(7, 6, 'hola', 410.00, 'Books', '', 'dsfaefrw', '1768647188_Gemini_Generated_Image_z3v3ykz3v3ykz3v3.png', 'available', '2026-01-17 16:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `department` varchar(50) NOT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `gender` varchar(10) NOT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `created_at` datetime DEFAULT current_timestamp(),
  `secondary_email` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `student_id`, `mobile`, `department`, `batch`, `gender`, `role`, `created_at`, `secondary_email`, `profile_image`) VALUES
(1, 'Sadman Sakib', 'sadman@aiub.edu', '123456', '23-50636-1', '01870640240', 'CSE', '23-1', 'Male', 'admin', '2026-01-16 23:25:16', NULL, NULL),
(2, 'Aritri Roy Priota', 'aritri_priota@aiub.edu', '654321', '23-51531-1', '01918256264', 'CSE', '23-1', 'Female', 'admin', '2026-01-16 23:26:38', NULL, NULL),
(3, 'Tirtho', 'tirtho@student.aiub.edu', '741025', '23-50637-1', '01978646674', 'CSE', NULL, 'Male', 'student', '2026-01-17 00:41:02', '', 'user_3_1768594160.png'),
(5, 'aritri', 'aritri@student.aiub.edu', '123654', '23-51530-1', '01918556862', 'CSE', NULL, 'Female', 'student', '2026-01-17 14:57:14', '', 'user_5_1768640311.jpeg'),
(6, 'SADMAN', 'asdas@aiub.edu', '555555', '23-11111-1', '01870540850', 'CSE', '23-1', 'Male', 'student', '2026-01-17 16:51:40', NULL, NULL),
(7, 'Dipa Roy', 'dipa@university.edu', '123456', '23-22222-1', '01712087697', 'BBA', NULL, 'Female', 'student', '2026-01-18 15:21:21', '', 'user_7_1768728210.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
