-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 04:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `buildwise`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('buildwise-cache-admin10|127.0.0.1', 'i:1;', 1780928263),
('buildwise-cache-admin10|127.0.0.1:timer', 'i:1780928263;', 1780928263),
('buildwise-cache-admin1090|127.0.0.1', 'i:2;', 1773625265),
('buildwise-cache-admin1090|127.0.0.1:timer', 'i:1773625265;', 1773625265),
('buildwise-cache-aljhun19@gmail.com|127.0.0.1', 'i:1;', 1780923254),
('buildwise-cache-aljhun19@gmail.com|127.0.0.1:timer', 'i:1780923254;', 1780923254),
('buildwise-cache-ashina|127.0.0.1', 'i:1;', 1780928287),
('buildwise-cache-ashina|127.0.0.1:timer', 'i:1780928287;', 1780928287);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `logged_in_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `user_id`, `ip_address`, `user_agent`, `logged_in_at`, `created_at`, `updated_at`) VALUES
(1, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 22:38:06', '2026-03-01 22:38:06', '2026-03-01 22:38:06'),
(2, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 22:56:42', '2026-03-01 22:56:42', '2026-03-01 22:56:42'),
(3, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 22:59:52', '2026-03-01 22:59:52', '2026-03-01 22:59:52'),
(4, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 23:03:34', '2026-03-01 23:03:34', '2026-03-01 23:03:34'),
(5, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 23:06:47', '2026-03-01 23:06:47', '2026-03-01 23:06:47'),
(6, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-01 23:09:12', '2026-03-01 23:09:12', '2026-03-01 23:09:12'),
(7, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 16:59:05', '2026-03-02 16:59:05', '2026-03-02 16:59:05'),
(8, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 22:15:48', '2026-03-02 22:15:48', '2026-03-02 22:15:48'),
(9, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-02 22:47:36', '2026-03-02 22:47:36', '2026-03-02 22:47:36'),
(10, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 00:18:58', '2026-03-03 00:18:58', '2026-03-03 00:18:58'),
(11, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 13:49:24', '2026-03-03 13:49:24', '2026-03-03 13:49:24'),
(12, 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 13:56:46', '2026-03-03 13:56:46', '2026-03-03 13:56:46'),
(13, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-03 16:55:37', '2026-03-03 16:55:37', '2026-03-03 16:55:37'),
(14, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 18:14:24', '2026-03-04 18:14:24', '2026-03-04 18:14:24'),
(15, 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-04 18:18:03', '2026-03-04 18:18:03', '2026-03-04 18:18:03'),
(16, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 20:31:33', '2026-03-05 20:31:33', '2026-03-05 20:31:33'),
(17, 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-05 22:52:28', '2026-03-05 22:52:28', '2026-03-05 22:52:28'),
(18, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-08 18:13:34', '2026-03-08 18:13:34', '2026-03-08 18:13:34'),
(19, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-08 18:13:34', '2026-03-08 18:13:34', '2026-03-08 18:13:34'),
(20, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-08 22:51:56', '2026-03-08 22:51:56', '2026-03-08 22:51:56'),
(21, 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-08 22:56:52', '2026-03-08 22:56:52', '2026-03-08 22:56:52'),
(22, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 20:09:47', '2026-03-11 20:09:47', '2026-03-11 20:09:47'),
(23, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 20:31:24', '2026-03-11 20:31:24', '2026-03-11 20:31:24'),
(24, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 20:32:49', '2026-03-11 20:32:49', '2026-03-11 20:32:49'),
(25, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-11 20:36:43', '2026-03-11 20:36:43', '2026-03-11 20:36:43'),
(26, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 21:48:34', '2026-03-12 21:48:34', '2026-03-12 21:48:34'),
(27, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 22:52:58', '2026-03-12 22:52:58', '2026-03-12 22:52:58'),
(28, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:24:06', '2026-03-12 23:24:06', '2026-03-12 23:24:06'),
(29, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:29:18', '2026-03-12 23:29:18', '2026-03-12 23:29:18'),
(30, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-15 14:43:59', '2026-03-15 14:43:59', '2026-03-15 14:43:59'),
(31, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-15 14:46:01', '2026-03-15 14:46:01', '2026-03-15 14:46:01'),
(32, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-15 17:16:30', '2026-03-15 17:16:30', '2026-03-15 17:16:30'),
(33, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-15 17:41:35', '2026-03-15 17:41:35', '2026-03-15 17:41:35'),
(34, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-16 22:02:09', '2026-03-16 22:02:09', '2026-03-16 22:02:09'),
(35, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 07:40:31', '2026-03-19 07:40:31', '2026-03-19 07:40:31'),
(36, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-19 07:52:12', '2026-03-19 07:52:12', '2026-03-19 07:52:12'),
(37, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-28 11:25:01', '2026-03-28 11:25:01', '2026-03-28 11:25:01'),
(38, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 12:11:14', '2026-04-12 12:11:14', '2026-04-12 12:11:14'),
(39, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-12 12:53:12', '2026-04-12 12:53:12', '2026-04-12 12:53:12'),
(40, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 13:53:25', '2026-04-19 13:53:25', '2026-04-19 13:53:25'),
(41, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:33:35', '2026-04-19 14:33:35', '2026-04-19 14:33:35'),
(42, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 14:37:38', '2026-04-19 14:37:38', '2026-04-19 14:37:38'),
(43, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:04:55', '2026-04-19 15:04:55', '2026-04-19 15:04:55'),
(44, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:06:26', '2026-04-19 15:06:26', '2026-04-19 15:06:26'),
(45, 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:11:31', '2026-04-19 15:11:31', '2026-04-19 15:11:31'),
(46, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 15:11:51', '2026-04-19 15:11:51', '2026-04-19 15:11:51'),
(47, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-19 16:40:21', '2026-04-19 16:40:21', '2026-04-19 16:40:21'),
(48, 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-08 14:20:55', '2026-06-08 14:20:55', '2026-06-08 14:20:55');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_02_08_153538_create_personal_access_tokens_table', 1),
(4, '2026_02_08_154226_create_permission_tables', 2),
(5, '2026_02_11_061228_create_products_table', 3),
(6, '2026_02_11_212705_create_products_table', 4),
(7, '2026_02_11_213101_create_product_activity_logs_table', 4),
(8, '2026_02_23_025707_create_sales_table', 5),
(9, '2026_03_02_000001_create_login_logs_table', 6),
(10, '2026_03_02_000002_add_profile_picture_to_users_table', 6),
(11, '2026_03_02_000003_add_order_number_to_sales_table', 6),
(12, '2026_03_16_000001_add_deleted_at_to_users_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `quantity`, `sku`, `category`, `image_path`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Trowel', 'Trowel', 99.00, 15, '1', 'Hardware & Tools', 'products/trowel-1771995507.png', 3, 3, '2026-02-22 23:30:00', '2026-02-24 21:52:42', NULL),
(2, 'Hollow Block', 'Dimension:\r\n40cm x 20cm\r\n\r\nThickness:\r\n4\"\r\n\r\nComposition:\r\nMixture of cement, gravel, and sand.', 20.00, 150, '2', 'Building Materials', 'products/hollow-block-1771995884.png', 3, 3, '2026-02-24 21:04:44', '2026-02-24 21:53:18', NULL),
(3, 'Tape Measure', '5 meter length', 100.00, 10, '3', 'Hardware & Tools', 'products/tape-measure-1772094016.png', 3, 3, '2026-02-24 21:07:56', '2026-02-26 00:20:16', NULL),
(4, 'Wallscraper', 'Wood handle\r\n\r\nSize: 6\"', 35.00, 20, '4', 'Hardware & Tools', 'products/wallscraper-1772094329.png', 3, 3, '2026-02-26 00:25:29', '2026-02-26 22:45:09', '2026-02-26 22:45:09'),
(5, 'Deformed Bar', '', 130.00, 99, '5', 'Building Materials', 'products/deformed-bar-1772094513.png', 3, 3, '2026-02-26 00:28:33', '2026-02-26 22:43:09', NULL),
(6, 'Wallscraper', '', 40.00, 50, '6', 'Hardware & Tools', 'products/wallscraper-1772174760.png', 3, 3, '2026-02-26 22:46:01', '2026-02-26 22:46:01', NULL),
(7, 'Boysen - Red Paint', 'Boysen Red paint', 99.00, 20, '7', 'Paints', NULL, 3, 3, '2026-02-26 22:46:51', '2026-03-15 17:16:03', '2026-03-15 17:16:03'),
(8, 'screw', '', 5.00, 15, '8', 'Essentials', NULL, 3, 3, '2026-02-26 22:48:37', '2026-03-15 17:15:59', '2026-03-15 17:15:59'),
(9, 'Pipe Cement', '', 90.00, 15, '9', 'Plumbing', 'products/pipe-cement-1772589116.png', 3, 3, '2026-03-03 17:51:57', '2026-03-03 17:51:57', NULL),
(10, '2gang - Outlet', '', 40.00, 17, '10', 'Electrical', 'products/2gang-outlet-1772589367.png', 3, 3, '2026-03-03 17:56:07', '2026-04-19 14:34:00', NULL),
(11, 'Light Bulb', '', 80.00, 18, '11', 'Electrical', 'products/light-bulb-1772589577.png', 3, 3, '2026-03-03 17:59:37', '2026-03-12 23:04:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_activity_logs`
--

CREATE TABLE `product_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_activity_logs`
--

INSERT INTO `product_activity_logs` (`id`, `product_id`, `user_id`, `action`, `old_values`, `new_values`, `description`, `created_at`, `updated_at`) VALUES
(35, 10, 3, 'sold', '{\"quantity\":18}', '{\"quantity\":17}', 'Order ORD-20260419-000007: Sold 1 units of \'2gang - Outlet\' for PHP 40.00', '2026-04-19 14:34:00', '2026-04-19 14:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(30) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `order_number`, `product_id`, `user_id`, `quantity`, `unit_price`, `total_price`, `sale_date`, `notes`, `created_at`, `updated_at`) VALUES
(7, 'ORD-20260419-000007', 10, 3, 1, 40.00, 40.00, '2026-04-19 14:34:00', NULL, '2026-04-19 14:34:00', '2026-04-19 14:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `mobile_number` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `birthdate`, `role`, `mobile_number`, `email`, `password`, `email_verified_at`, `failed_login_attempts`, `locked_until`, `remember_token`, `created_at`, `updated_at`, `profile_picture`, `deleted_at`) VALUES
(1, 'admin', 'System Administrator', '1990-01-01', 'admin', '09171234567', NULL, '$2y$12$LQv3c1yycaRdUvEGWHGfcOXfH1l5yEZlLqzK9YEbKqFMQKiGfYqU2', NULL, 0, NULL, NULL, '2026-02-08 09:57:08', '2026-02-08 09:57:08', NULL, NULL),
(3, 'Hakuju', 'Aljhun Angala', '2000-09-19', 'user', '+639876543211', NULL, '$2y$12$QBEjC0BbBmwY9BkBGmVyruzL2c/AO6q6moUbljbpVScL5LGRYIlea', NULL, 0, NULL, NULL, '2026-02-08 07:38:31', '2026-04-12 12:53:12', NULL, NULL),
(4, 'cvh19', 'Charlene Hipolito', '2004-07-30', 'admin', '09123456781', NULL, '$2y$12$kfjtVdSTtbQIJbZ6DG1b9.cbannYlq9BILmYzOC1tJB42WZYNJl8i', NULL, 0, NULL, NULL, '2026-02-08 19:45:15', '2026-02-08 19:45:15', NULL, NULL),
(5, 'haku', 'Aljhun Angala', '1990-05-21', 'user', '09876543211', NULL, '$2y$12$YQjIDv05aPlTBjPKsv.8kuDRTHJvrmUAEag5865GU.Jpi0.lLphRW', NULL, 0, NULL, NULL, '2026-02-09 00:34:17', '2026-02-09 00:34:17', NULL, NULL),
(7, 'Admin1', 'Admin', '2000-01-11', 'admin', '+639987654321', NULL, '$2y$12$UtT/yhuTEcyHJ6BRsq07duccu12t6.0cRWvWt88WhzltpiCWrTopa', NULL, 0, NULL, NULL, '2026-03-03 13:56:46', '2026-03-03 13:56:46', NULL, NULL),
(8, 'admin2', 'admin user', '2005-06-14', 'admin', '+639987654399', NULL, '$2y$12$xlro4X/dhmo.optgOWceS.LONFgVwzBQfdmBK6P/AOlSN70/E3/9S', NULL, 0, NULL, NULL, '2026-03-04 18:18:03', '2026-03-04 18:18:03', NULL, NULL),
(9, 'admin3', 'admin 3', '2010-06-16', 'admin', '+639987654321', NULL, '$2y$12$N/ccNgJ5HRQbBG63oVmqb./ezGH0D8shaG9RI52rybVqN25mDtSWW', NULL, 0, NULL, NULL, '2026-03-05 22:52:28', '2026-03-05 22:52:28', NULL, NULL),
(10, 'Admin6', 'Admin6', '2005-06-15', 'admin', '+639987654321', NULL, '$2y$12$9ja3zM45HSUr.kGCl0ylTeQU3Pk7J7REcr5ydpYgRN8gzV8h.Z/8S', NULL, 0, NULL, NULL, '2026-03-08 22:56:52', '2026-03-08 22:56:52', NULL, NULL),
(11, 'Admin100', 'Admin1090', '2015-03-15', 'admin', '+639876543321', NULL, '$2y$12$i6dy3ccllacnNa4BaegwrOkmgnxbcJi.0dNWADDu63CRjSyfNDSZW', NULL, 0, NULL, '0uNtCixKASiIXEwyGomB1pWwvvqdiNoIkcCGC1oI9ZSi1u2YH4u2AXJUzczx', '2026-03-15 14:46:01', '2026-06-08 14:20:51', 'profile-pictures/staff-11-admin1090-1776611455.jpg', NULL);

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_logs_user_id_logged_in_at_index` (`user_id`,`logged_in_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_created_by_foreign` (`created_by`),
  ADD KEY `products_updated_by_foreign` (`updated_by`),
  ADD KEY `products_name_index` (`name`),
  ADD KEY `products_sku_index` (`sku`),
  ADD KEY `products_category_index` (`category`);

--
-- Indexes for table `product_activity_logs`
--
ALTER TABLE `product_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `product_activity_logs_product_id_created_at_index` (`product_id`,`created_at`),
  ADD KEY `product_activity_logs_action_index` (`action`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_product_id_sale_date_index` (`product_id`,`sale_date`),
  ADD KEY `sales_sale_date_index` (`sale_date`),
  ADD KEY `sales_order_number_index` (`order_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_locked_until` (`locked_until`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_activity_logs`
--
ALTER TABLE `product_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_activity_logs`
--
ALTER TABLE `product_activity_logs`
  ADD CONSTRAINT `product_activity_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
