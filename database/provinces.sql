-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 26, 2025 at 03:05 AM
-- Server version: 11.3.2-MariaDB
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `owpms_backup`
--

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `region_id` int(11) NOT NULL,
  `province_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `region_id`, `province_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Metro Manila', NULL, NULL),
(2, 3, 'Ilocos Norte', NULL, NULL),
(3, 3, 'Ilocos Sur', NULL, NULL),
(4, 3, 'La Union', NULL, NULL),
(5, 3, 'Pangasinan', NULL, NULL),
(6, 4, 'Batanes', NULL, NULL),
(7, 4, 'Cagayan', NULL, NULL),
(8, 4, 'Isabela', NULL, NULL),
(9, 4, 'Nueva Vizcaya', NULL, NULL),
(10, 4, 'Quirino', NULL, NULL),
(11, 5, 'Bataan', NULL, NULL),
(12, 5, 'Bulacan', NULL, NULL),
(13, 5, 'Nueva Ecija', NULL, NULL),
(14, 5, 'Pampanga', NULL, NULL),
(15, 5, 'Tarlac', NULL, NULL),
(16, 5, 'Zambales', NULL, NULL),
(17, 5, 'Aurora', NULL, NULL),
(18, 6, 'Batangas', NULL, NULL),
(19, 6, 'Cavite', NULL, NULL),
(20, 6, 'Laguna', NULL, NULL),
(21, 6, 'Quezon', NULL, NULL),
(22, 6, 'Rizal', NULL, NULL),
(23, 7, 'Marinduque', NULL, NULL),
(24, 7, 'Occidental Mindoro', NULL, NULL),
(25, 7, 'Oriental Mindoro', NULL, NULL),
(26, 7, 'Palawan', NULL, NULL),
(27, 7, 'Romblon', NULL, NULL),
(28, 8, 'Albay', NULL, NULL),
(29, 8, 'Camarines Norte', NULL, NULL),
(30, 8, 'Camarines Sur', NULL, NULL),
(31, 8, 'Catanduanes', NULL, NULL),
(32, 8, 'Masbate', NULL, NULL),
(33, 8, 'Sorsogon', NULL, NULL),
(34, 9, 'Aklan', NULL, NULL),
(35, 9, 'Antique', NULL, NULL),
(36, 9, 'Capiz', NULL, NULL),
(37, 9, 'Iloilo', NULL, NULL),
(38, 9, 'Negros Occidental', NULL, NULL),
(39, 9, 'Guimaras', NULL, NULL),
(40, 10, 'Bohol', NULL, NULL),
(41, 10, 'Cebu', NULL, NULL),
(42, 10, 'Negros Oriental', NULL, NULL),
(43, 10, 'Siquijor', NULL, NULL),
(44, 11, 'Eastern Samar', NULL, NULL),
(45, 11, 'Leyte', NULL, NULL),
(46, 11, 'Northern Samar', NULL, NULL),
(47, 11, 'Samar', NULL, NULL),
(48, 11, 'Southern Leyte', NULL, NULL),
(49, 11, 'Biliran', NULL, NULL),
(50, 12, 'Zamboanga del Norte', NULL, NULL),
(51, 12, 'Zamboanga del Sur', NULL, NULL),
(52, 12, 'Zamboanga Sibugay', NULL, NULL),
(53, 13, 'Bukidnon', NULL, NULL),
(54, 13, 'Camiguin', NULL, NULL),
(55, 13, 'Lanao del Norte', NULL, NULL),
(56, 13, 'Misamis Occidental', NULL, NULL),
(57, 13, 'Misamis Oriental', NULL, NULL),
(58, 14, 'Davao del Norte', NULL, NULL),
(59, 14, 'Davao del Sur', NULL, NULL),
(60, 14, 'Davao Oriental', NULL, NULL),
(61, 14, 'Davao de Oro', NULL, NULL),
(62, 14, 'Davao Occidental', NULL, NULL),
(63, 15, 'Cotabato', NULL, NULL),
(64, 15, 'South Cotabato', NULL, NULL),
(65, 15, 'Sultan Kudarat', NULL, NULL),
(66, 15, 'Sarangani', NULL, NULL),
(67, 2, 'Abra', NULL, NULL),
(68, 2, 'Benguet', NULL, NULL),
(69, 2, 'Ifugao', NULL, NULL),
(70, 2, 'Kalinga', NULL, NULL),
(71, 2, 'Mountain Province', NULL, NULL),
(72, 2, 'Apayao', NULL, NULL),
(73, 17, 'Basilan', NULL, NULL),
(74, 17, 'Lanao del Sur', NULL, NULL),
(75, 17, 'Maguindanao', NULL, NULL),
(76, 17, 'Sulu', NULL, NULL),
(77, 17, 'Tawi-Tawi', NULL, NULL),
(78, 16, 'Agusan del Norte', NULL, NULL),
(79, 16, 'Agusan del Sur', NULL, NULL),
(80, 16, 'Surigao del Norte', NULL, NULL),
(81, 16, 'Surigao del Sur', NULL, NULL),
(82, 16, 'Dinagat Islands', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
