-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2026 at 11:51 AM
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
-- Database: `it_complaints_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `technician_id` int(11) DEFAULT NULL,
  `category` enum('Hardware','Software','Network','Other') NOT NULL,
  `description` text NOT NULL,
  `floor` varchar(50) NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `employee_id`, `technician_id`, `category`, `description`, `floor`, `attachment`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 'Hardware', 'Mouse not working', '', NULL, 'resolved', '2026-08-07 03:54:52', '2026-08-07 04:26:24'),
(2, 2, 3, 'Software', 'License problem', '', NULL, 'resolved', '2026-08-07 04:16:04', '2026-08-07 04:50:22'),
(3, 2, 3, 'Network', 'no internet', '', 'uploads/attach_6a75accb41772.png', 'resolved', '2026-08-07 10:00:43', '2026-09-07 05:00:52'),
(4, 5, 3, 'Hardware', 'test', '', NULL, 'resolved', '2026-09-07 05:01:09', '2026-09-07 05:01:20'),
(5, 6, 3, 'Hardware', 'My mouse stopped clicking on the left side.', '', NULL, 'in_progress', '2026-09-07 06:08:57', '2026-09-18 07:45:55'),
(6, 7, 3, 'Software', 'Adobe Premiere is crashing on startup.', '', NULL, 'in_progress', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(7, 6, 3, 'Network', 'Cannot connect to the office Wi-Fi.', '', NULL, 'resolved', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(8, 7, NULL, 'Other', 'Need a new standing desk adapter.', '', NULL, 'pending', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(9, 6, 3, 'Software', 'License expired for Microsoft Office.', '', NULL, 'in_progress', '2026-09-07 06:08:57', '2026-09-18 07:46:44'),
(10, 7, 3, 'Hardware', 'Monitor has a green vertical line on the right side.', '', NULL, 'in_progress', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(11, 6, 3, 'Network', 'VPN is dropping connection every 5 minutes.', '', NULL, 'resolved', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(12, 7, NULL, 'Software', 'Unable to install the new update for the CRM.', '', NULL, 'pending', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(13, 6, 3, 'Hardware', 'Keyboard is missing the F5 key.', '', NULL, 'resolved', '2026-09-07 06:08:57', '2026-09-18 07:31:34'),
(14, 7, 3, 'Other', 'Printer on the 3rd floor is out of toner.', '', NULL, 'resolved', '2026-09-07 06:08:57', '2026-09-07 06:08:57'),
(15, 6, 3, 'Hardware', 'My mouse got split into two :)', '5th Floor', NULL, 'resolved', '2026-09-17 04:07:32', '2026-09-18 07:45:50'),
(16, 8, NULL, 'Hardware', 'test', '2', NULL, 'pending', '2026-09-17 04:23:49', '2026-09-17 04:23:49');

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
(1, '2026_09_18_041922_create_complaints_table', 0),
(2, '2026_09_18_041922_create_notifications_table', 0),
(3, '2026_09_18_041922_create_users_table', 0),
(4, '2026_09_18_041925_add_foreign_keys_to_complaints_table', 0),
(5, '2026_09_18_041925_add_foreign_keys_to_notifications_table', 0),
(6, '2026_09_18_071050_create_sessions_table', 1),
(7, '2026_09_18_071058_create_cache_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Your ticket #1 has been claimed and is now In Progress.', 1, '2026-08-07 04:22:18'),
(2, 2, 'Your ticket #2 has been claimed and is now In Progress.', 1, '2026-08-07 04:26:00'),
(3, 2, 'Good news! Your ticket #1 has been resolved.', 1, '2026-08-07 04:26:24'),
(4, 2, 'Good news! Your ticket #2 has been resolved.', 1, '2026-08-07 04:50:22'),
(5, 2, 'Your ticket #3 has been claimed and is now In Progress.', 0, '2026-09-07 04:48:24'),
(6, 2, 'Good news! Your ticket #3 has been resolved.', 0, '2026-09-07 05:00:52'),
(7, 5, 'Your ticket #4 has been claimed and is now In Progress.', 1, '2026-09-07 05:01:18'),
(8, 5, 'Good news! Your ticket #4 has been resolved.', 1, '2026-09-07 05:01:20'),
(9, 6, 'Good news! Your ticket #15 has been resolved.', 0, '2026-09-18 07:45:50'),
(10, 6, 'Your ticket #9 has been claimed and is now In Progress.', 0, '2026-09-18 07:46:44');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1qyob2rpkfSpKis7iYwx3HBpv0LMq2SO9s40DGq8', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJic1dlaDdPcDlDT1ZoY0JIRmJGU3hyclh0NWYxSXVRNWN0N2VkbFkxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90ZWNobmljaWFuXC9kYXNoYm9hcmQiLCJyb3V0ZSI6InRlY2huaWNpYW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjN9', 1789717605),
('AyJ64vQzYUhVkBwY0kEiwWQ6uBJ3dvLsNyX3f8Oa', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqS0FQdTFNUVdEWkRqVE5IeWFLQjNMcWpVQU1iRFp1bUVDeUtvVTNPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6M30=', 1789975334),
('dtpSD3AdvRRCxxlQH3KgcfSWklnliVItFlKui2PI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJsS21aQ1dGZXVRNzRRcGhvbzBOcUhjTXp4N2R0SjVLNXZQcXhmNFpqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1789975522),
('e6AbHU4tYY88n4jcNAbKPKYgv1gK4iDz8buaEk5H', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJCZFdXaTR2UXZuZXRjYlhwWVFBUGVMWnlEOWtYMnZOeE9vRVdJRjh0IiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjMsIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1789975335),
('kV6MCABYyz4v7ncFZPdXoTc7Zmpwv9ywAUeFFRAL', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJXUjVUWTF1WDkzYU1QV00ydjBUTVBCM2h0NEVIbXdPdjFjQmY1UlBLIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjMsIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90ZWNobmljaWFuXC9kYXNoYm9hcmQiLCJyb3V0ZSI6InRlY2huaWNpYW4uZGFzaGJvYXJkIn19', 1789975337);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','technician','admin') DEFAULT 'employee',
  `phone_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `phone_number`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '0000000000', '2026-08-07 03:39:45'),
(2, 'aufar', '$2y$10$mOUCYJxwzvCl9uBHuf8TdOR4i5X.ZDnUR.qJnQByD4mYfREhnR5cu', 'employee', '0897654321', '2026-08-07 03:47:14'),
(3, 'tech1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technician', '1234567890', '2026-08-07 04:21:24'),
(4, 'admin1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '0000000000', '2026-08-07 04:34:04'),
(5, 'test', '$2y$10$K13d34pAWf9ReQvjGZjiUOMekSQAIMCUx.Khg/v8IWRVICtkc9AIa', 'employee', '0897654321', '2026-09-07 04:15:57'),
(6, 'emp1', '$2y$10$lDIqBvT21dezal9CnnUI1umgVLkXw/v4xbhYknbBGrccC6w9han.e', 'employee', '111-222-3333', '2026-09-07 06:08:57'),
(7, 'emp2', '$2y$10$lDIqBvT21dezal9CnnUI1umgVLkXw/v4xbhYknbBGrccC6w9han.e', 'employee', '444-555-6666', '2026-09-07 06:08:57'),
(8, 'testuser', '$2y$10$hY76lr5KQO0rl8SfRLGjk.zOPmMf2YMNjA7l1ykcnyir1e80NpiOO', 'employee', '12345', '2026-09-17 04:18:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `technician_id` (`technician_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
