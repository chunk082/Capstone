-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2026 at 02:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `TokenRedemption`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_03_27_223000_add_image_url_to_products_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `tokens_spent` int(11) NOT NULL,
  `status` enum('pending','processing','shipped','completed','rejected') DEFAULT 'pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `transaction_id`, `user_id`, `product_id`, `tokens_spent`, `status`, `tracking_number`, `created_at`, `updated_at`) VALUES
(1, 'TRX-UW8MH20CXO', 4, 4, 100, 'completed', NULL, '2026-04-25 02:22:21', '2026-05-02 04:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `token_cost` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image_url`, `token_cost`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Nintendo Switch 2', 'Start your next gaming adventure with the Nintendo Switch 2 console—packed with upgrades and fun ways to connect and play together. Your games will leap to life on the vivid, 7.9-inch 1080p screen that showcases the system’s powerful processing and graphics performance. The updated dock also supports up to 4K resolution and frame rates up to 120fps with compatible games and TVs.', 'https://images.pexels.com/photos/34482313/pexels-photo-34482313.jpeg?auto=compress&cs=tinysrgb&h=350', 800, 3, 1, '2026-03-28 04:06:23', '2026-03-28 04:06:23'),
(4, 'iPad mini', 'Apple iPad mini has everything there is to love about iPad in a delightfully light, compact design. It features a 100% recycled aluminum enclosure and stunning all‑screen design, and it goes anywhere you go — fitting perfectly into a backpack or purse. With groundbreaking privacy protections, this iPad mini gives you peace of mind that no one else can access your data — not even Apple.', 'https://images.pexels.com/photos/6849081/pexels-photo-6849081.jpeg?auto=compress&cs=tinysrgb&h=350', 100, 0, 1, '2026-03-28 04:12:45', '2026-04-25 02:22:21'),
(5, 'AirPods Pro', 'AirPods Pro. Good Sound Quality', 'https://images.pexels.com/photos/4006158/pexels-photo-4006158.jpeg?auto=compress&cs=tinysrgb&h=350', 300, 5, 1, '2026-03-28 04:51:17', '2026-03-28 04:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `user_id` int(11) NOT NULL,
  `balance` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`user_id`, `balance`, `created_at`, `updated_at`) VALUES
(3, 40, '2026-02-21 20:22:13', '2026-02-22 23:51:12'),
(4, 0, '2026-02-22 23:51:38', '2026-04-25 02:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `token_logs`
--

CREATE TABLE `token_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `granted_by` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `token_logs`
--

INSERT INTO `token_logs` (`id`, `user_id`, `granted_by`, `amount`, `reason`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 10, 'Testing', '2026-02-21 20:22:13', '2026-02-21 20:22:13'),
(2, 3, 4, 25, 'Testing', '2026-02-21 20:23:49', '2026-02-21 20:23:49'),
(3, 3, 4, 5, 'Demo Testing', '2026-02-22 23:51:12', '2026-02-22 23:51:12'),
(4, 4, 4, 100, 'Demo Test', '2026-02-22 23:51:38', '2026-02-22 23:51:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','hype','admin') DEFAULT 'employee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(3, 'Chunk', 'demo@dev.org', '$2y$12$LVDRsSWoGBhxwe1g5lkZv.sIihhPftJGQ9E7W8gw8TCFLFGSXJ/vW', 'employee', '2026-02-01 19:34:16', '2026-02-21 07:03:09'),
(4, 'Demo Account', 'admin@demo.org', '$2y$12$EtqJXa2vPygIny.mwSjTguaKTaIpSBqM/erwLUhfhU0/K4dGZ3K06', 'admin', '2026-02-21 04:06:58', '2026-02-20 23:08:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `token_logs`
--
ALTER TABLE `token_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token_logs_user_id` (`user_id`),
  ADD KEY `idx_token_logs_granted_by` (`granted_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `token_logs`
--
ALTER TABLE `token_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `token_logs`
--
ALTER TABLE `token_logs`
  ADD CONSTRAINT `fk_token_logs_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_token_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `token_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `token_logs_ibfk_2` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
