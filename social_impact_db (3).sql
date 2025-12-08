-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 10:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_impact_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `account_type` enum('bank','ewallet','cash') DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `account_holder_name` varchar(100) DEFAULT NULL,
  `current_balance` decimal(15,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_name`, `account_type`, `account_number`, `account_holder_name`, `current_balance`, `is_active`, `created_at`) VALUES
(1, 'BCA Utama', 'bank', '8830-1234-5678', 'Yayasan Social Act', 100000.00, 1, '2025-12-07 06:43:27'),
(2, 'Gopay', 'ewallet', '09882882', 'Faatih Yusron', 455000.00, 1, '2025-12-08 07:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` enum('super_admin','finance','field_coordinator') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'super@social.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', '2025-12-07 07:20:11', '2025-12-07 07:20:11'),
(2, 'finance', 'finance@social.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'finance', '2025-12-07 07:20:11', '2025-12-07 07:20:11'),
(3, 'content', 'content@social.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'field_coordinator', '2025-12-07 07:20:11', '2025-12-07 07:20:11'),
(4, 'Bambang', 'bambang@social.org', '$2y$10$1zXH07cYyb9TmGXfA/R73O9FhjU74CzF7Mbhu6pCSblZkNYXF4CNK', 'field_coordinator', '2025-12-07 07:46:39', '2025-12-07 07:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(100) DEFAULT NULL,
  `donor_email` varchar(100) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `transfer_proof_url` varchar(255) DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `message` text DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `donor_email`, `amount`, `account_id`, `transfer_proof_url`, `is_anonymous`, `message`, `status`, `verified_by`, `created_at`, `verified_at`) VALUES
(1, 'YAnu', 'yanu@gmail.com', 500000.00, 1, NULL, 0, 'buat kamu', 'rejected', 1, '2025-12-08 08:46:42', '2025-12-08 09:49:50'),
(2, 'YAnu', 'yanu@gmail.com', 100000.00, 1, '768cd23ae38f8d7c36f64693f1887056.PNG', 0, 'test', 'rejected', 1, '2025-12-08 08:49:38', '2025-12-08 09:50:19'),
(3, 'YAnu', 'yanu@gmail.com', 100000.00, 1, '3d1a8c7febdf960c3b32c6faf9783adc.PNG', 0, 'test', 'verified', 1, '2025-12-08 08:52:33', '2025-12-08 09:52:45');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `category` enum('operational','equipment','logistics','administration','others') DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `receipt_image_url` varchar(255) DEFAULT NULL,
  `item_image_url` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `title`, `description`, `amount`, `category`, `transaction_date`, `account_id`, `receipt_image_url`, `item_image_url`, `created_by`, `created_at`) VALUES
(2, 'Biaya Minuman Kegiatan 13 Desember', '', 30000.00, 'operational', '2025-12-04', 2, 'd06ab557b74fa9cfd1dbd93e0ef8c1f4.png', 'b9383207f755a595b29594a4faec2a98.png', 1, '2025-12-08 01:41:19'),
(3, 'test', '', 10000.00, 'operational', '2025-12-08', 2, NULL, '569b65cac2b8a11c4609b1890dbf43eb.png', 1, '2025-12-08 02:01:49'),
(4, 'testttt', 'karet', 5000.00, 'operational', '2025-12-08', 2, '90ecabdc41d7d4bd8aa1ddc06c2e9c37.png', 'ee95ee8e6767aed55c41c9b7821012d0.png', 1, '2025-12-08 02:03:04');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` enum('L','P') NOT NULL,
  `age` int(3) NOT NULL,
  `domicile` varchar(100) NOT NULL,
  `experience` text DEFAULT NULL,
  `motivation` text NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_events`
--

CREATE TABLE `volunteer_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `registration_link` varchar(255) DEFAULT NULL,
  `banner_image_url` varchar(255) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_events`
--

INSERT INTO `volunteer_events` (`id`, `event_name`, `description`, `event_date`, `location`, `registration_link`, `banner_image_url`, `status`, `created_at`) VALUES
(2, 'Bersih Bersih Kerajaan', '', '2025-12-10 18:04:00', 'JL Musholla', NULL, NULL, 'completed', '2025-12-07 11:05:07'),
(3, 'Bersih Bersih Kerajaan', '', '2025-12-07 18:23:00', 'JL Musholla', NULL, NULL, 'completed', '2025-12-07 11:23:23'),
(4, 'Bersih Bersih Kerajaan', 'Pokoknya bersih bersih dah\r\n', '2025-12-11 19:27:00', 'JL Musholla', NULL, NULL, 'upcoming', '2025-12-07 12:28:07'),
(5, 'Sampah sungai mumtaz', 'jelek bgt tampilan form yg bener aja', '2004-02-11 12:00:00', 'Jakarta', NULL, 'ad74657fcc4aa4b9c43f545311a22072.png', 'upcoming', '2025-12-08 03:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `waste_reports`
--

CREATE TABLE `waste_reports` (
  `id` int(11) NOT NULL,
  `reporter_name` varchar(100) DEFAULT NULL,
  `reporter_contact` varchar(50) DEFAULT NULL,
  `location_address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_before_url` varchar(255) DEFAULT NULL,
  `image_after_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','in_progress','resolved','rejected') DEFAULT 'pending',
  `cleaned_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_reports`
--

INSERT INTO `waste_reports` (`id`, `reporter_name`, `reporter_contact`, `location_address`, `latitude`, `longitude`, `description`, `image_before_url`, `image_after_url`, `status`, `cleaned_at`, `created_at`, `views`) VALUES
(1, '', '', 'Bawah Jembatan Merah', -6.19444900, 106.82292000, '', 'default.jpg', 'default_after.jpg', 'resolved', '2025-12-07', '2025-12-07 04:26:55', 2),
(2, '', '', 'Bawah Jembatan Merah', -6.19444900, 106.82292000, '', 'default.jpg', 'default_after.jpg', 'resolved', '2025-12-07', '2025-12-07 04:37:53', 1),
(3, '', '', 'as', -6.17723200, 106.84118300, '', 'default.jpg', NULL, 'rejected', NULL, '2025-12-07 04:40:59', 0),
(4, '', '', 'Bawah Jembatan Merah', -6.19404000, 106.84882200, '', 'default.jpg', NULL, 'rejected', NULL, '2025-12-07 04:46:15', 0),
(5, '', '', 'Disono', -6.28108200, 106.91310900, '', 'default.jpg', 'default_after.jpg', 'resolved', '2025-12-07', '2025-12-07 04:52:11', 0),
(6, '', '', 'Bawah Jembatan Merah', -6.27367500, 106.69489600, '', 'default.jpg', 'default_after.jpg', 'resolved', '2025-12-07', '2025-12-07 05:53:27', 0),
(7, '', '', 'Bawah Jembatan Merah', -6.27367500, 106.69489600, '', 'default.jpg', 'default_after.jpg', 'resolved', '2025-12-07', '2025-12-07 05:53:36', 0),
(8, 'Budi', '088123123123', 'Bawah Jembatan Merah', -6.27294300, 106.94091800, '', 'default.jpg', NULL, 'pending', NULL, '2025-12-07 06:52:49', 0),
(9, 'Yunai', '9898989', 'sungai nganjuk', -6.31222700, 106.78161600, 'emgh', 'default.jpg', NULL, 'pending', NULL, '2025-12-07 07:17:47', 0),
(10, 'Yunai', '9898989', 'sungai nganjuk', -7.55895600, 110.85231200, 'kureng', 'default.jpg', NULL, 'pending', NULL, '2025-12-07 21:52:21', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waste_reports`
--
ALTER TABLE `waste_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `waste_reports`
--
ALTER TABLE `waste_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD CONSTRAINT `volunteers_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `volunteer_events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
