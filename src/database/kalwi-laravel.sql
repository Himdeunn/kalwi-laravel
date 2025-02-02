-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 02, 2025 at 01:24 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kalwi-laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Ranks', 'ranks', '2025-01-29 07:01:50', '2025-01-29 07:01:50'),
(2, 'Package', 'package', '2025-01-29 13:37:41', '2025-01-29 13:40:24'),
(3, 'Membership', 'membership', '2025-01-29 13:37:41', '2025-01-29 13:40:24'),
(4, 'Subsciption', 'subscription', '2025-01-29 13:40:09', '2025-01-29 13:40:09'),
(5, 'Perks', 'perks', '2025-01-30 02:29:55', '2025-01-30 02:29:55'),
(6, 'Kits', 'kits', '2025-01-30 02:43:46', '2025-01-30 02:43:46'),
(7, 'Role', 'role', '2025-01-30 02:45:01', '2025-01-30 02:45:01'),
(8, 'Armor', 'armor', '2025-01-30 02:48:35', '2025-01-30 02:48:35'),
(9, 'Game', 'game', '2025-01-30 03:16:21', '2025-01-30 03:16:21'),
(10, 'Tes', 'tes', '2025-02-01 07:11:23', '2025-02-01 07:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `code_referral`
--

CREATE TABLE `code_referral` (
  `id` int NOT NULL,
  `code_number` varchar(20) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_by` int NOT NULL,
  `usage_count` int NOT NULL DEFAULT '0',
  `profit_share` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `code_referral`
--

INSERT INTO `code_referral` (`id`, `code_number`, `discount_percentage`, `created_by`, `usage_count`, `profit_share`, `created_at`, `updated_at`) VALUES
(1, 'AEGOMO', 6.00, 1, 0, 0.00, '2025-01-31 05:39:45', '2025-02-01 05:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name_product` varchar(255) NOT NULL,
  `real_price_product` decimal(10,2) NOT NULL,
  `description_product` text,
  `icon_product` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `discount_price_product` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name_product`, `real_price_product`, `description_product`, `icon_product`, `created_at`, `update_at`, `discount_price_product`) VALUES
(1, 3, 'Elite', 5464.00, 'testingkajnej', 'next (2).png', '2025-02-01 16:47:16', '2025-02-02 11:48:08', 2351.00),
(4, 8, 'Obsidian', 531261.00, 'asfaeveeva', 'nextjs-light.png', '2025-02-02 11:42:23', '2025-02-02 11:42:23', 135325.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `purchase_product` int DEFAULT NULL,
  `wallet` decimal(10,2) NOT NULL DEFAULT '0.00',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `purchase_product`, `wallet`, `email`, `password`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Tegar', 'admin', NULL, 0.00, 'admin@example.com', '$2y$10$3B9YH13KYeaNN4VMfLzR2.TKW1YMawfcMV2iKljOe7ysKY/ODL79W', 3, '2025-01-28 07:49:33', '2025-02-02 10:50:23'),
(2, 'John', 'Doe', 'JD', NULL, 0.00, 'user@example.com', '$2y$10$6ob12E8dx5OsVlzOY.R35.9OEsPwxqJWlbbbodu4YmCW6ZFe8282i', 1, '2025-01-28 07:49:33', '2025-01-28 07:52:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `code_referral`
--
ALTER TABLE `code_referral`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_number` (`code_number`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_purchase_product` (`purchase_product`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `code_referral`
--
ALTER TABLE `code_referral`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `code_referral`
--
ALTER TABLE `code_referral`
  ADD CONSTRAINT `code_referral_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_purchase_product` FOREIGN KEY (`purchase_product`) REFERENCES `product` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
