-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 01:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bms`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `from` varchar(45) NOT NULL,
  `to` varchar(45) NOT NULL,
  `hours` decimal(3,0) NOT NULL,
  `rate` decimal(3,0) NOT NULL DEFAULT 0,
  `total` decimal(3,0) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `spot_id`, `user_id`, `from`, `to`, `hours`, `rate`, `total`, `created_at`, `updated_at`) VALUES
(1, 7, 3, '2025-01-01T04:20', '2025-01-02T04:20', 24, 6, 144, '2025-01-07 17:52:58', '2025-01-07 17:52:58'),
(2, 10, 3, '2025-01-09T11:02', '2025-01-10T11:02', 24, 10, 240, '2025-01-08 00:03:04', '2025-01-08 00:03:04'),
(3, 10, 3, '2025-01-09T15:11', '2025-01-10T15:11', 24, 10, 240, '2025-01-08 04:11:48', '2025-01-08 04:11:48'),
(4, 6, 3, '2025-01-08T01:07', '2025-01-10T01:07', 48, 5, 240, '2025-01-08 21:13:25', '2025-01-08 21:13:25'),
(5, 7, 3, '2025-01-08T01:07', '2025-01-08T02:08', 1, 6, 6, '2025-01-08 21:14:58', '2025-01-08 21:14:58'),
(6, 6, 3, '2025-01-08T01:07', '2025-01-10T01:07', 48, 5, 240, '2025-01-09 20:39:29', '2025-01-09 20:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `spot_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 0,
  `comment` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `spot_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 4, 'Comment 1', NULL, NULL),
(2, 6, 2, 3, 'Comment 2', NULL, NULL),
(4, 6, 2, 3, 'C', NULL, NULL),
(5, 7, 3, 4, 'First', '2025-01-08 02:17:24', '2025-01-08 02:17:24'),
(6, 7, 3, 5, 'First Cooment', '2025-01-08 02:29:13', '2025-01-08 02:29:13'),
(7, 7, 3, 3, 'First Comment', '2025-01-08 04:12:42', '2025-01-08 04:12:42'),
(8, 7, 3, 5, 'Secure place with enough space!', '2025-01-08 20:52:15', '2025-01-08 20:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `spots`
--

CREATE TABLE `spots` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `lat` varchar(45) NOT NULL,
  `lng` varchar(45) NOT NULL,
  `rate` decimal(2,0) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spots`
--

INSERT INTO `spots` (`id`, `user_id`, `name`, `lat`, `lng`, `rate`, `description`, `image`, `created_at`, `updated_at`) VALUES
(6, 2, 'Place 1', '6.905', '79.951', 5, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', NULL, NULL, NULL),
(7, 2, 'Place 2', '6.910', '79.960', 6, 'Description 2', NULL, NULL, NULL),
(8, 2, 'Place 3', '6.890', '79.890', 8, 'Description 3', NULL, NULL, NULL),
(9, 2, 'Place 4', '6.920', '79.970', 9, 'Description 4', NULL, NULL, NULL),
(10, 2, 'HA1 4TG', '51.5883127', '-0.3460942', 10, 'Description 5', NULL, NULL, '2025-01-08 04:14:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT 3,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@gmail.com', NULL, '$2y$10$FuQr2CHq1edoqj5KHPsrSOTAXHEHpRSziAzEnbOIBAcPSNnEy4USa', NULL, 1, NULL, NULL),
(2, 'Parking', 'Owner', 'parking.owner@gmail.com', NULL, '$2y$10$yAvkUSLy/ZGGYh87xOQkheFWKgfXnZTYwVq3OO0L6uWdc2EM4lYRS', NULL, 2, '2025-01-07 14:04:55', '2025-01-07 14:04:55'),
(3, 'Driver', 'Profile', 'driver@gmail.com', NULL, '$2y$10$uiQTMQUVCfTniWRX/CubOuRY1nwTCq58PY2OgT0GYmegdPQ8d.fEO', NULL, 3, '2025-01-07 14:05:28', '2025-01-07 18:51:40'),
(4, 'Andrew', 'Steve', 'test1@gmail.com', NULL, '$2y$10$lVOPEmbOzSwmPs0vi69Hwea66PguYmA/w9P5fli8pmUcrUEYXYo9y', NULL, 3, '2025-01-08 21:10:12', '2025-01-08 21:10:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spot_id` (`spot_id`),
  ADD KEY `FK_userb` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spot_id` (`spot_id`),
  ADD KEY `FK_user` (`user_id`);

--
-- Indexes for table `spots`
--
ALTER TABLE `spots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_users` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `spots`
--
ALTER TABLE `spots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `FK_userb` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`spot_id`) REFERENCES `spots` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`spot_id`) REFERENCES `spots` (`id`);

--
-- Constraints for table `spots`
--
ALTER TABLE `spots`
  ADD CONSTRAINT `FK_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
