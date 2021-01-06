-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2020 at 09:39 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel8_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_unique` tinyint(1) NOT NULL DEFAULT 0,
  `is_filterable` tinyint(1) NOT NULL DEFAULT 0,
  `is_configurable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `code`, `name`, `type`, `validation`, `is_required`, `is_unique`, `is_filterable`, `is_configurable`, `created_at`, `updated_at`) VALUES
(1, 'size', 'Size', 'select', NULL, 0, 0, 1, 1, '2020-12-09 15:11:55', '2020-12-09 15:11:55'),
(2, 'color', 'Color', 'select', NULL, 0, 0, 1, 1, '2020-12-09 15:12:59', '2020-12-09 15:12:59');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_options`
--

CREATE TABLE `attribute_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_options`
--

INSERT INTO `attribute_options` (`id`, `attribute_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'S', '2020-12-09 15:12:18', '2020-12-09 15:12:18'),
(2, 1, 'M', '2020-12-09 15:12:22', '2020-12-09 15:12:22'),
(3, 1, 'L', '2020-12-09 15:12:25', '2020-12-09 15:12:25'),
(4, 1, 'XL', '2020-12-09 15:12:28', '2020-12-09 15:12:28'),
(5, 2, 'Merah', '2020-12-09 15:13:30', '2020-12-09 15:13:30'),
(6, 2, 'Putih', '2020-12-09 15:13:37', '2020-12-09 15:13:37'),
(7, 2, 'Hitam', '2020-12-09 15:13:40', '2020-12-09 15:13:40'),
(8, 2, 'Biru Navy', '2020-12-09 15:13:47', '2020-12-09 15:13:47'),
(9, 2, 'Biru Muda', '2020-12-09 15:13:52', '2020-12-09 15:13:52'),
(10, 2, 'Kuning', '2020-12-09 15:13:57', '2020-12-09 15:13:57'),
(11, 2, 'Hijau', '2020-12-09 15:14:01', '2020-12-09 15:14:01');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Pakaian Pria', 'pakaian-pria', 0, '2020-12-09 15:19:11', '2020-12-09 15:19:11'),
(3, 'Kaos Pria', 'kaos-pria', 1, '2020-12-09 15:19:27', '2020-12-09 15:19:27'),
(4, 'Kemeja Pria', 'kemeja-pria', 1, '2020-12-09 15:19:49', '2020-12-09 15:19:49'),
(5, 'Elektronik', 'elektronik', 0, '2020-12-09 15:20:34', '2020-12-09 15:20:34'),
(6, 'Smartphone', 'smartphone', 5, '2020-12-09 15:20:44', '2020-12-09 15:20:44'),
(7, 'Laptop', 'laptop', 5, '2020-12-09 15:20:54', '2020-12-09 15:20:54'),
(8, 'Pakaian Wanita', 'pakaian-wanita', 0, '2020-12-10 03:52:15', '2020-12-10 03:52:15'),
(9, 'Sweater Pria', 'sweater-pria', 1, '2020-12-13 10:08:40', '2020-12-13 10:08:40'),
(10, 'Gamis Muslim', 'gamis-muslim', 8, '2020-12-14 07:09:02', '2020-12-14 07:09:02'),
(11, 'Pakaian Anak-anak', 'pakaian-anak-anak', 0, '2020-12-22 08:20:45', '2020-12-22 08:20:45'),
(12, 'Pakaian anak laki-laki', 'pakaian-anak-laki-laki', 11, '2020-12-22 08:21:07', '2020-12-22 08:22:10'),
(13, 'Pakaian anak perempuan', 'pakaian-anak-perempuan', 11, '2020-12-22 08:21:50', '2020-12-22 08:21:57'),
(14, 'Batik Wanita', 'batik-wanita', 8, '2020-12-22 08:28:02', '2020-12-22 08:28:02'),
(15, 'Batik Pria', 'batik-pria', 1, '2020-12-22 08:28:14', '2020-12-22 08:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(2, 9, 3, '2020-12-21 13:14:50', '2020-12-21 13:14:50'),
(3, 9, 4, '2020-12-21 13:14:52', '2020-12-21 13:14:52'),
(4, 9, 7, '2020-12-21 13:17:22', '2020-12-21 13:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(6, 'default', '{\"uuid\":\"d49c6dcb-894e-4b98-88fc-2c45ecab6709\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608446467, 1608446467),
(7, 'default', '{\"uuid\":\"9d9da2ff-c126-4f48-b9d6-00d3e534964d\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:19;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608450459, 1608450459),
(8, 'default', '{\"uuid\":\"387b1948-7e61-4031-a1e7-303ef848f83c\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:20;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608451367, 1608451367),
(9, 'default', '{\"uuid\":\"1854c9e4-47ce-47ab-a2c2-78e971b6cd91\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:21;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608452092, 1608452092),
(10, 'default', '{\"uuid\":\"54668ac5-bc6b-4fd0-aa7a-627463d9996f\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:22;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608452321, 1608452321),
(11, 'default', '{\"uuid\":\"ea396786-b5b1-48f1-b601-f1f9083e28d1\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:23;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608452708, 1608452708),
(12, 'default', '{\"uuid\":\"1bec5d27-ddda-4744-a34f-617426668546\",\"displayName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMailOrderReceived\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendMailOrderReceived\\\":11:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:24;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:7:\\\"\\u0000*\\u0000user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1608453023, 1608453023);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(39, '2014_10_12_000000_create_users_table', 1),
(40, '2014_10_12_100000_create_password_resets_table', 1),
(41, '2019_08_19_000000_create_failed_jobs_table', 1),
(42, '2020_12_06_042633_create_categories_table', 1),
(43, '2020_12_07_102900_create_products_table', 1),
(44, '2020_12_07_104451_create_attributes_table', 1),
(45, '2020_12_07_104712_create_product_attribute_values_table', 1),
(46, '2020_12_07_105532_create_product_inventories_table', 1),
(47, '2020_12_07_105846_create_product_categories_table', 1),
(48, '2020_12_07_110046_create_product_images_table', 1),
(49, '2020_12_07_130432_rename_column_in_products_table', 1),
(50, '2020_12_07_141627_alter_column_in_products_table', 1),
(51, '2020_12_08_103830_add_column_to_attributes_table', 1),
(52, '2020_12_08_183051_create_attribute_options_table', 1),
(53, '2020_12_09_150352_remove_column_product_attribute_value_id_in_product_inventories', 1),
(54, '2020_12_09_151152_add_parent_id_and_type_to_products_table', 1),
(55, '2020_12_09_152119_alter_as_nullable_column_in_products_table', 1),
(56, '2020_12_09_152728_alter_attribute_relation_in_product_attribute_values_table', 1),
(57, '2020_12_09_205535_create_permission_tables', 1),
(58, '2020_12_11_111506_add_full_text_search_to_products_table', 2),
(59, '2020_12_11_210405_add_parent_product_id_to_product_attribute_values_table', 3),
(60, '2020_12_13_135841_rename_column_and_add_columns_in_users_table', 4),
(61, '2020_12_14_142407_create_orders_table', 5),
(62, '2020_12_14_142929_create_order_items_table', 5),
(63, '2020_12_14_143147_create_payments_table', 5),
(64, '2020_12_14_143506_create_shipments_table', 5),
(65, '2020_12_19_151224_create_jobs_table', 6),
(66, '2020_12_20_130854_add_payment_token_to_orders_table', 7),
(67, '2020_12_20_131151_add_status_to_payments_table', 7),
(68, '2020_12_21_193627_create_favorites_table', 8),
(69, '2020_12_22_101832_create_slides_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` datetime NOT NULL,
  `payment_due` datetime NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_total_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(16,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(16,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_province_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_postcode` int(11) DEFAULT NULL,
  `shipping_courier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `cancelled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancellation_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `code`, `status`, `order_date`, `payment_due`, `payment_status`, `payment_token`, `payment_url`, `base_total_price`, `tax_amount`, `tax_percent`, `discount_amount`, `discount_percent`, `shipping_cost`, `grand_total`, `note`, `customer_first_name`, `customer_last_name`, `customer_address1`, `customer_address2`, `customer_phone`, `customer_email`, `customer_city_id`, `customer_province_id`, `customer_postcode`, `shipping_courier`, `shipping_service_name`, `approved_by`, `approved_at`, `cancelled_by`, `cancelled_at`, `cancellation_note`, `deleted_at`, `created_at`, `updated_at`) VALUES
(8, 9, 'INV/20201214/XII/XIV/00001', 'created', '2020-12-14 16:18:08', '2020-12-21 16:18:08', 'unpaid', NULL, NULL, '179300.00', '17930.00', '10.00', '0.00', '0.00', '18000.00', '215230.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'gian@gmail.com', '455', '3', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, '2020-12-21 09:01:10', '2020-12-14 09:18:08', '2020-12-21 09:01:10'),
(10, 9, 'INV/20201219/XII/XIX/00001', 'created', '2020-12-19 16:09:52', '2020-12-26 16:09:52', 'unpaid', NULL, NULL, '159500.00', '15950.00', '10.00', '0.00', '0.00', '9000.00', '184450.00', 'baju mu warna merah', 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '151', '6', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, '2020-12-21 09:01:41', '2020-12-19 09:09:52', '2020-12-21 09:01:41'),
(11, 9, 'INV/20201219/XII/XIX/00002', 'created', '2020-12-19 16:26:04', '2020-12-26 16:26:04', 'unpaid', NULL, NULL, '192500.00', '19250.00', '10.00', '0.00', '0.00', '18000.00', '229750.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '501', '5', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, '2020-12-21 09:01:15', '2020-12-19 09:26:04', '2020-12-21 09:01:15'),
(18, 9, 'INV/20201220/XII/XX/00001', 'created', '2020-12-20 13:41:05', '2020-12-27 13:41:05', 'unpaid', '4ceb6b07-6566-491f-b1ca-64c837e72bd3', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/4ceb6b07-6566-491f-b1ca-64c837e72bd3', '82500.00', '8250.00', '10.00', '0.00', '0.00', '8000.00', '98750.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '151', '6', 15520, 'jne', 'JNE - OKE', NULL, NULL, NULL, NULL, NULL, '2020-12-21 09:01:49', '2020-12-20 06:41:05', '2020-12-21 09:01:49'),
(19, 9, 'INV/20201220/XII/XX/00002', 'created', '2020-12-20 14:47:39', '2020-12-27 14:47:39', 'unpaid', '4cf754bb-b324-4d9a-bf2f-b19263af3bb9', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/4cf754bb-b324-4d9a-bf2f-b19263af3bb9', '132000.00', '13200.00', '10.00', '0.00', '0.00', '9000.00', '154200.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '152', '6', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-20 07:47:39', '2020-12-20 07:47:39'),
(20, 9, 'INV/20201220/XII/XX/00003', 'cancelled', '2020-12-20 15:02:46', '2020-12-27 15:02:46', 'unpaid', 'c5f15bc9-8fd0-4692-8d7c-42f1240be537', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/c5f15bc9-8fd0-4692-8d7c-42f1240be537', '96800.00', '9680.00', '10.00', '0.00', '0.00', '18000.00', '124480.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '501', '5', 15520, 'jne', 'JNE - REG', NULL, NULL, 1, '2020-12-21 16:01:33', 'too late', NULL, '2020-12-20 08:02:46', '2020-12-21 09:01:33'),
(21, 9, 'INV/20201220/XII/XX/00004', 'created', '2020-12-20 15:14:51', '2020-12-27 15:14:51', 'unpaid', '64d2efec-b4b5-4f18-94ac-26a67f3681a3', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/64d2efec-b4b5-4f18-94ac-26a67f3681a3', '159500.00', '15950.00', '10.00', '0.00', '0.00', '11000.00', '186450.00', 'sweater hitam', 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '109', '9', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-20 08:14:51', '2020-12-20 08:14:52'),
(22, 9, 'INV/20201220/XII/XX/00005', 'completed', '2020-12-20 15:18:40', '2020-12-27 15:18:40', 'paid', '89dfaf6b-87ce-4836-b44f-53fae01dc90f', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/89dfaf6b-87ce-4836-b44f-53fae01dc90f', '192500.00', '19250.00', '10.00', '0.00', '0.00', '18000.00', '229750.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '455', '3', 15520, 'jne', 'JNE - CTCYES', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-20 08:18:40', '2020-12-22 07:53:48'),
(23, 9, 'INV/20201220/XII/XX/00006', 'completed', '2020-12-20 15:25:07', '2020-12-27 15:25:07', 'paid', '0a5301e8-3ad8-4498-9da7-e3d0fdccddd3', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/0a5301e8-3ad8-4498-9da7-e3d0fdccddd3', '209000.00', '20900.00', '10.00', '0.00', '0.00', '9000.00', '238900.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '153', '6', 15520, 'jne', 'JNE - REG', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-20 08:25:07', '2020-12-22 07:53:32'),
(24, 9, 'INV/20201220/XII/XX/00007', 'cancelled', '2020-12-20 15:30:23', '2020-12-27 15:30:23', 'unpaid', '091ed796-8265-468e-846a-297793fac0c6', 'https://app.sandbox.midtrans.com/snap/v2/vtweb/091ed796-8265-468e-846a-297793fac0c6', '82500.00', '8250.00', '10.00', '0.00', '0.00', '9000.00', '99750.00', NULL, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '79', '9', 15520, 'jne', 'JNE - REG', NULL, NULL, 1, '2020-12-21 15:36:59', 'late', NULL, '2020-12-20 08:30:23', '2020-12-21 09:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `base_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `base_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_percent` decimal(16,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`attributes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `base_price`, `base_total`, `tax_amount`, `tax_percent`, `discount_amount`, `discount_percent`, `sub_total`, `sku`, `type`, `name`, `weight`, `attributes`, `created_at`, `updated_at`) VALUES
(8, 8, 8, 1, '75000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'KP-SS-001', 'simple', 'Kaos Windows', '300.00', '[]', '2020-12-14 09:18:08', '2020-12-14 09:18:08'),
(9, 8, 9, 1, '88000.00', '88000.00', '0.00', '0.00', '0.00', '0.00', '88000.00', 'KP-SS-002', 'simple', 'Kaos Programmer', '300.00', '[]', '2020-12-14 09:18:08', '2020-12-14 09:18:08'),
(11, 10, 10, 1, '145000.00', '145000.00', '0.00', '0.00', '0.00', '0.00', '145000.00', 'SW-CT-001', 'simple', 'Sweater Pria warna hitam', '500.00', '[]', '2020-12-19 09:09:52', '2020-12-19 09:09:52'),
(12, 11, 12, 1, '175000.00', '175000.00', '0.00', '0.00', '0.00', '0.00', '175000.00', 'GM-KR-002', 'simple', 'Gamis muslim Kimora tipe 2', '700.00', '[]', '2020-12-19 09:26:04', '2020-12-19 09:26:04'),
(19, 18, 8, 1, '75000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'KP-SS-001', 'simple', 'Kaos Windows', '300.00', '[]', '2020-12-20 06:41:05', '2020-12-20 06:41:05'),
(20, 19, 5, 1, '120000.00', '120000.00', '0.00', '0.00', '0.00', '0.00', '120000.00', 'JR-MU-2-5', 'configurable', 'Jersey Manchester United - M - Merah', '500.00', '{\"size\":\"M\",\"color\":\"Merah\"}', '2020-12-20 07:47:39', '2020-12-20 07:47:39'),
(21, 20, 9, 1, '88000.00', '88000.00', '0.00', '0.00', '0.00', '0.00', '88000.00', 'KP-SS-002', 'simple', 'Kaos Programmer', '300.00', '[]', '2020-12-20 08:02:46', '2020-12-20 08:02:46'),
(22, 21, 10, 1, '145000.00', '145000.00', '0.00', '0.00', '0.00', '0.00', '145000.00', 'SW-CT-001', 'simple', 'Sweater Pria warna hitam', '500.00', '[]', '2020-12-20 08:14:51', '2020-12-20 08:14:51'),
(23, 22, 11, 1, '175000.00', '175000.00', '0.00', '0.00', '0.00', '0.00', '175000.00', 'GM-KR-001', 'simple', 'Gamis muslim Kimora tipe 1', '700.00', '[]', '2020-12-20 08:18:40', '2020-12-20 08:18:40'),
(24, 23, 13, 1, '190000.00', '190000.00', '0.00', '0.00', '0.00', '0.00', '190000.00', 'GM-RN-001', 'simple', 'Gamis muslim Renata', '800.00', '[]', '2020-12-20 08:25:07', '2020-12-20 08:25:07'),
(25, 24, 8, 1, '75000.00', '75000.00', '0.00', '0.00', '0.00', '0.00', '75000.00', 'KP-SS-001', 'simple', 'Kaos Windows', '300.00', '[]', '2020-12-20 08:30:23', '2020-12-20 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('gian@gmail.com', '$2y$10$VybwEBEry2QgBzpLEA2MVOcLIfgSFEml8p1M3ALKm3khY2rp9640W', '2020-12-13 09:25:02');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payloads` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payloads`)),
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `va_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biller_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_users', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(2, 'add_users', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(3, 'edit_users', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(4, 'delete_users', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(5, 'view_roles', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(6, 'add_roles', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(7, 'edit_roles', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(8, 'delete_roles', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(9, 'view_products', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(10, 'add_products', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(11, 'edit_products', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(12, 'delete_products', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(13, 'view_orders', 'web', '2020-12-09 14:43:05', '2020-12-09 14:43:05'),
(14, 'add_orders', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(15, 'edit_orders', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(16, 'delete_orders', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(17, 'view_categories', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(18, 'add_categories', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(19, 'edit_categories', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(20, 'delete_categories', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(21, 'view_attributes', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(22, 'add_attributes', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(23, 'edit_attributes', 'web', '2020-12-09 14:43:06', '2020-12-09 14:43:06'),
(24, 'delete_attributes', 'web', '2020-12-09 14:43:07', '2020-12-09 14:43:07'),
(25, 'add_shipments', 'web', NULL, NULL),
(26, 'view_shipments', 'web', NULL, NULL),
(27, 'edit_shipments', 'web', NULL, NULL),
(28, 'delete_shipments', 'web', NULL, NULL),
(29, 'view_slides', 'web', NULL, NULL),
(30, 'add_slides', 'web', NULL, NULL),
(31, 'edit_slides', 'web', NULL, NULL),
(32, 'delete_slides', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `weight` decimal(15,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `parent_id`, `user_id`, `sku`, `type`, `name`, `slug`, `price`, `weight`, `width`, `height`, `length`, `short_description`, `description`, `status`, `created_at`, `updated_at`) VALUES
(3, NULL, 1, 'SG-S9-001', 'simple', 'Samsung Galaxy S9+', 'samsung-galaxy-s9', '8500000.00', '800.00', NULL, NULL, NULL, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.', 1, '2020-12-09 14:52:00', '2020-12-10 14:45:55'),
(4, NULL, 1, 'JR-MU', 'configurable', 'Jersey Manchester United', 'jersey-manchester-united', NULL, NULL, NULL, NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content of a pag', 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy.', 1, '2020-12-09 15:14:46', '2020-12-10 14:36:41'),
(5, 4, 1, 'JR-MU-2-5', 'simple', 'Jersey Manchester United - M - Merah', 'jersey-manchester-united-m-merah', '120000.00', '500.00', NULL, NULL, NULL, NULL, NULL, 1, '2020-12-09 15:14:46', '2020-12-09 15:15:23'),
(6, 4, 1, 'JR-MU-2-7', 'simple', 'Jersey Manchester United - M - Hitam', 'jersey-manchester-united-m-hitam', '90000.00', '500.00', NULL, NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content', 'opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes', 1, '2020-12-09 15:14:46', '2020-12-11 10:46:32'),
(7, NULL, 1, 'AP-IP-001', 'simple', 'Iphone 11 pro max', 'iphone-11-pro-max', '12000000.00', '0.40', NULL, NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opp', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore al', 1, '2020-12-11 09:50:44', '2020-12-11 09:52:28'),
(8, NULL, 1, 'KP-SS-001', 'simple', 'Kaos Windows', 'kaos-windows', '75000.00', '300.00', NULL, NULL, NULL, 'ummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard', 'It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, '2020-12-13 10:04:02', '2020-12-13 10:04:55'),
(9, NULL, 1, 'KP-SS-002', 'simple', 'Kaos Programmer', 'kaos-programmer', '88000.00', '300.00', NULL, NULL, NULL, 'lt model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved', 'lt model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 1, '2020-12-13 10:06:03', '2020-12-13 10:06:56'),
(10, NULL, 1, 'SW-CT-001', 'simple', 'Sweater Pria warna hitam', 'sweater-pria-warna-hitam', '145000.00', '500.00', NULL, NULL, NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration', 'rity have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks', 1, '2020-12-13 10:09:41', '2020-12-13 10:10:23'),
(11, NULL, 1, 'GM-KR-001', 'simple', 'Gamis muslim Kimora tipe 1', 'gamis-muslim-kimora-tipe-1', '175000.00', '700.00', NULL, NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks', 1, '2020-12-14 07:11:13', '2020-12-14 07:14:43'),
(12, NULL, 1, 'GM-KR-002', 'simple', 'Gamis muslim Kimora tipe 2', 'gamis-muslim-kimora-tipe-2', '175000.00', '700.00', NULL, NULL, NULL, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks', 1, '2020-12-14 07:15:20', '2020-12-14 07:16:04'),
(13, NULL, 1, 'GM-RN-001', 'simple', 'Gamis muslim Renata', 'gamis-muslim-renata', '190000.00', '800.00', NULL, NULL, NULL, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 'need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore', 1, '2020-12-14 07:17:32', '2020-12-14 07:17:58'),
(14, NULL, 1, 'LP-AS-001', 'simple', 'Laptop Asus Zenbook Core i7', 'laptop-asus-zenbook-core-i7', '17500000.00', '1000.00', NULL, NULL, NULL, 'It is a long established fact that a reader will be distracted by the readable content', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:10:48', '2020-12-22 08:11:55'),
(15, NULL, 1, 'LP-AP-001', 'simple', 'Macbook Pro 2020 core i7', 'macbook-pro-2020-core-i7', '22500000.00', '900.00', NULL, NULL, NULL, 'opposed to using \'Content here, content here\', making it look like readable English', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:13:04', '2020-12-22 08:14:02'),
(16, NULL, 1, 'KM-BN-001', 'simple', 'Kemeja Benhill warna biru muda M', 'kemeja-benhill-warna-biru-muda-m', '135000.00', '300.00', NULL, NULL, NULL, 'opposed to using \'Content here, content here\', making it look like readable English', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:15:52', '2020-12-22 08:16:39'),
(17, NULL, 1, 'KM-BN-002', 'simple', 'Kemeja Benhill warna biru navy M', 'kemeja-benhill-warna-biru-navy-m', '135000.00', '400.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters,', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:17:29', '2020-12-22 08:18:11'),
(18, NULL, 1, 'PK-WH-001', 'simple', 'Baju anak atasan perempuan warna pink', 'baju-anak-atasan-perempuan-warna-pink', '89000.00', '200.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters,', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:23:24', '2020-12-22 08:23:56'),
(19, NULL, 1, 'PK-LA-001', 'simple', 'pakaian anak laki-laki satu setel', 'pakaian-anak-laki-laki-satu-setel', '120000.00', '250.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:26:33', '2020-12-22 08:27:10'),
(20, NULL, 1, 'BW-BT-001', 'simple', 'Baju batik wanita hitam biru type 1', 'baju-batik-wanita-hitam-biru-type-1', '165000.00', '300.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:29:07', '2020-12-22 08:29:51'),
(21, NULL, 1, 'BW-BT-002', 'simple', 'Baju batik wanita coklat hitam type 2', 'baju-batik-wanita-coklat-hitam-type-2', '165000.00', '300.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable EnglishThe point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:30:19', '2020-12-22 08:30:49'),
(22, NULL, 1, 'BP-BT-001', 'simple', 'Baju batik pria lengan pendek type 1', 'baju-batik-pria-lengan-pendek-type-1', '135000.00', '300.00', NULL, NULL, NULL, 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 'The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable EnglishThe point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2020-12-22 08:32:15', '2020-12-22 08:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `text_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boolean_value` tinyint(1) DEFAULT NULL,
  `integer_value` int(11) DEFAULT NULL,
  `float_value` decimal(8,2) DEFAULT NULL,
  `datetime_value` datetime DEFAULT NULL,
  `date_value` date DEFAULT NULL,
  `json_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `parent_product_id`, `product_id`, `attribute_id`, `text_value`, `boolean_value`, `integer_value`, `float_value`, `datetime_value`, `date_value`, `json_value`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 1, 'M', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 15:14:46', '2020-12-09 15:14:46'),
(2, 4, 5, 2, 'Merah', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 15:14:46', '2020-12-09 15:14:46'),
(3, 4, 6, 1, 'M', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 15:14:46', '2020-12-09 15:14:46'),
(4, 4, 6, 2, 'Hitam', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 15:14:46', '2020-12-09 15:14:46');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
(1, 7, 6),
(2, 3, 6),
(3, 4, 3),
(4, 8, 3),
(5, 9, 3),
(6, 10, 9),
(7, 11, 8),
(8, 11, 10),
(9, 12, 8),
(10, 12, 10),
(11, 13, 8),
(12, 13, 10),
(13, 14, 5),
(14, 14, 7),
(15, 15, 5),
(16, 15, 7),
(17, 16, 1),
(18, 16, 4),
(19, 17, 1),
(20, 17, 4),
(21, 18, 11),
(22, 18, 13),
(23, 19, 11),
(24, 19, 12),
(25, 20, 8),
(26, 20, 14),
(27, 21, 8),
(28, 21, 14),
(29, 22, 1),
(30, 22, 15);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `path`, `created_at`, `updated_at`) VALUES
(1, 3, 'uploads/images/samsung-galaxy-s9_1607610741.jpg', '2020-12-10 14:32:21', '2020-12-10 14:32:21'),
(5, 7, 'uploads/images/iphone-11-pro-max_1607680271.jpg', '2020-12-11 09:51:11', '2020-12-11 09:51:11'),
(6, 6, 'uploads/images/jersey-manchester-united-m-hitam_1607683608.jpg', '2020-12-11 10:46:48', '2020-12-11 10:46:48'),
(7, 5, 'uploads/images/jersey-manchester-united-m-merah_1607683636.jpg', '2020-12-11 10:47:16', '2020-12-11 10:47:16'),
(8, 4, 'uploads/images/jersey-manchester-united_1607683671.jpg', '2020-12-11 10:47:51', '2020-12-11 10:47:51'),
(9, 8, 'uploads/images/kaos-windows_1607853915.jpg', '2020-12-13 10:05:15', '2020-12-13 10:05:15'),
(10, 9, 'uploads/images/kaos-programmer_1607853974.png', '2020-12-13 10:06:14', '2020-12-13 10:06:14'),
(11, 10, 'uploads/images/sweater-pria-warna-hitam_1607854237.jpeg', '2020-12-13 10:10:37', '2020-12-13 10:10:37'),
(12, 11, 'uploads/images/gamis-muslim-kimora-tipe-1_1607929898.jpg', '2020-12-14 07:11:38', '2020-12-14 07:11:38'),
(13, 12, 'uploads/images/gamis-muslim-kimora-tipe-2_1607930132.jpg', '2020-12-14 07:15:32', '2020-12-14 07:15:32'),
(14, 13, 'uploads/images/gamis-muslim-renata_1607930295.jpg', '2020-12-14 07:18:15', '2020-12-14 07:18:15'),
(15, 14, 'uploads/images/laptop-asus-zenbook-core-i7_1608624664.jpg', '2020-12-22 08:11:04', '2020-12-22 08:11:04'),
(16, 15, 'uploads/images/macbook-pro-2020-core-i7_1608624810.jpg', '2020-12-22 08:13:30', '2020-12-22 08:13:30'),
(17, 16, 'uploads/images/kemeja-benhill-warna-biru-muda-m_1608624962.jpg', '2020-12-22 08:16:02', '2020-12-22 08:16:02'),
(18, 17, 'uploads/images/kemeja-benhill-warna-biru-navy-m_1608625060.jpg', '2020-12-22 08:17:40', '2020-12-22 08:17:40'),
(19, 18, 'uploads/images/baju-anak-atasan-perempuan-warna-pink_1608625412.jpg', '2020-12-22 08:23:32', '2020-12-22 08:23:32'),
(20, 19, 'uploads/images/pakaian-anak-laki-laki-satu-setel_1608625602.jpeg', '2020-12-22 08:26:42', '2020-12-22 08:26:42'),
(21, 20, 'uploads/images/baju-batik-wanita-hitam-biru-type-1_1608625760.jpg', '2020-12-22 08:29:20', '2020-12-22 08:29:20'),
(22, 21, 'uploads/images/baju-batik-wanita-coklat-hitam-type-2_1608625828.jpg', '2020-12-22 08:30:28', '2020-12-22 08:30:28'),
(23, 22, 'uploads/images/baju-batik-pria-lengan-pendek-type-1_1608625944.jpg', '2020-12-22 08:32:24', '2020-12-22 08:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventories`
--

CREATE TABLE `product_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_inventories`
--

INSERT INTO `product_inventories` (`id`, `product_id`, `qty`, `created_at`, `updated_at`) VALUES
(1, 3, 15, '2020-12-09 14:53:34', '2020-12-09 14:53:34'),
(2, 5, 6, '2020-12-09 15:15:23', '2020-12-20 07:47:39'),
(3, 6, 12, '2020-12-09 15:15:23', '2020-12-09 15:15:23'),
(4, 7, 15, '2020-12-11 09:52:28', '2020-12-11 09:52:28'),
(5, 8, 17, '2020-12-13 10:04:55', '2020-12-21 09:01:49'),
(6, 9, 13, '2020-12-13 10:06:56', '2020-12-21 09:01:33'),
(7, 10, 19, '2020-12-13 10:10:23', '2020-12-21 09:01:41'),
(8, 11, 4, '2020-12-14 07:14:43', '2020-12-22 07:53:48'),
(9, 12, 8, '2020-12-14 07:16:04', '2020-12-21 09:01:15'),
(10, 13, 16, '2020-12-14 07:17:58', '2020-12-22 07:53:32'),
(11, 14, 11, '2020-12-22 08:11:55', '2020-12-22 08:11:55'),
(12, 15, 9, '2020-12-22 08:14:02', '2020-12-22 08:14:02'),
(13, 16, 21, '2020-12-22 08:16:39', '2020-12-22 08:16:39'),
(14, 17, 12, '2020-12-22 08:18:11', '2020-12-22 08:18:11'),
(15, 18, 14, '2020-12-22 08:23:56', '2020-12-22 08:23:56'),
(16, 19, 15, '2020-12-22 08:27:10', '2020-12-22 08:27:10'),
(17, 20, 30, '2020-12-22 08:29:51', '2020-12-22 08:29:51'),
(18, 21, 23, '2020-12-22 08:30:49', '2020-12-22 08:30:49'),
(19, 22, 21, '2020-12-22 08:32:49', '2020-12-22 08:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2020-12-09 14:43:32', '2020-12-09 14:43:32'),
(2, 'Operator', 'web', '2020-12-09 14:43:34', '2020-12-09 14:43:34'),
(3, 'Customer', 'web', '2020-12-10 04:23:33', '2020-12-10 04:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 2),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(17, 2),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `track_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_weight` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `shipped_by` bigint(20) UNSIGNED DEFAULT NULL,
  `shipped_at` datetime DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `user_id`, `order_id`, `track_number`, `status`, `total_qty`, `total_weight`, `first_name`, `last_name`, `address1`, `address2`, `phone`, `email`, `city_id`, `province_id`, `postcode`, `shipped_by`, `shipped_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 9, 8, NULL, 'pending', 2, 600, 'gian2', 'nurwana2', 'Kp. Mangga', NULL, '089662043519', 'gian@gmail.com', '501', '5', 15520, NULL, NULL, NULL, '2020-12-14 09:18:08', '2020-12-14 09:18:08'),
(3, 9, 10, NULL, 'pending', 1, 500, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '151', '6', 15520, NULL, NULL, NULL, '2020-12-19 09:09:52', '2020-12-19 09:09:52'),
(4, 9, 11, NULL, 'pending', 1, 700, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '501', '5', 15520, NULL, NULL, NULL, '2020-12-19 09:26:04', '2020-12-19 09:26:04'),
(5, 9, 18, NULL, 'pending', 1, 300, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '151', '6', 15520, NULL, NULL, NULL, '2020-12-20 06:41:06', '2020-12-20 06:41:06'),
(6, 9, 19, NULL, 'pending', 1, 500, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '152', '6', 15520, NULL, NULL, NULL, '2020-12-20 07:47:39', '2020-12-20 07:47:39'),
(7, 9, 20, NULL, 'pending', 1, 300, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '501', '5', 15520, NULL, NULL, NULL, '2020-12-20 08:02:46', '2020-12-20 08:02:46'),
(8, 9, 21, NULL, 'pending', 1, 500, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '109', '9', 15520, NULL, NULL, NULL, '2020-12-20 08:14:52', '2020-12-20 08:14:52'),
(9, 9, 22, NULL, 'pending', 1, 700, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '455', '3', 15520, NULL, NULL, NULL, '2020-12-20 08:18:41', '2020-12-20 08:18:41'),
(10, 9, 23, NULL, 'pending', 1, 800, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '153', '6', 15520, NULL, NULL, NULL, '2020-12-20 08:25:08', '2020-12-20 08:25:08'),
(11, 9, 24, NULL, 'pending', 1, 300, 'Gian', 'Nurwana', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', NULL, '089662043519', 'giannrw19@gmail.com', '79', '9', 15520, NULL, NULL, NULL, '2020-12-20 08:30:23', '2020-12-20 08:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_large` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `small` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `user_id`, `title`, `url`, `position`, `status`, `body`, `original`, `extra_large`, `small`, `created_at`, `updated_at`) VALUES
(2, 1, 'Go Furniture', 'uploads/images/original/table-and-chair_1608609115.jpg', 1, 'active', 'Dapatkan furniture dengan harga murah sekarang', NULL, NULL, NULL, '2020-12-22 03:51:55', '2020-12-22 03:59:06'),
(3, 1, 'Sofa Cantik', 'uploads/images/original/gambar2_1608609226.jpg', 2, 'active', 'Sofa keren, yuk cek sekarang!', NULL, NULL, NULL, '2020-12-22 03:53:46', '2020-12-22 03:59:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `company`, `address1`, `address2`, `province_id`, `city_id`, `postcode`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, 'admin@gmail.com', NULL, '2020-12-09 14:43:34', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'naYpI3ChLg8z71Rq0D6giT7InK9UMmRjFszzJESK1FrtoD9hxgWU0xyP03ln', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 14:43:34', '2020-12-09 14:43:34'),
(2, 'Operator', NULL, 'operator@gmail.com', NULL, '2020-12-09 14:43:35', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9Z3LJSDbCJLMNhX4lBCaVtesEAZIoUZawkGxsVDx838Lqcrx714vqqroBk2Q', NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-09 14:43:35', '2020-12-09 14:43:35'),
(9, 'Gian', 'Nurwana', 'giannrw19@gmail.com', '089662043519', NULL, '$2y$10$8KM2vkDwhavps7vkIJjAfuqtYLAKIIOKClx.F.vH6R/grpeUbQ/EW', NULL, 'Nurwana TECH', 'Kp Pisangan Desa Sarakan Kec. Sepatan Kab. Tangerang', 'House', 3, 455, 15520, '2020-12-14 07:22:03', '2020-12-22 04:18:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_options_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_product_id_foreign` (`product_id`),
  ADD KEY `favorites_user_id_product_id_index` (`user_id`,`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_approved_by_foreign` (`approved_by`),
  ADD KEY `orders_cancelled_by_foreign` (`cancelled_by`),
  ADD KEY `orders_code_index` (`code`),
  ADD KEY `orders_code_order_date_index` (`code`,`order_date`),
  ADD KEY `orders_payment_token_index` (`payment_token`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_sku_index` (`sku`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_number_unique` (`number`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_number_index` (`number`),
  ADD KEY `payments_method_index` (`method`),
  ADD KEY `payments_token_index` (`token`),
  ADD KEY `payments_payment_type_index` (`payment_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`),
  ADD KEY `products_parent_id_foreign` (`parent_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `search` (`name`,`slug`,`short_description`,`description`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_product_id_foreign` (`product_id`),
  ADD KEY `product_attribute_values_attribute_id_foreign` (`attribute_id`),
  ADD KEY `product_attribute_values_parent_product_id_foreign` (`parent_product_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_product_id_foreign` (`product_id`),
  ADD KEY `product_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_inventories_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipments_user_id_foreign` (`user_id`),
  ADD KEY `shipments_order_id_foreign` (`order_id`),
  ADD KEY `shipments_shipped_by_foreign` (`shipped_by`),
  ADD KEY `shipments_track_number_index` (`track_number`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slides_user_id_foreign` (`user_id`);

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
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attribute_options`
--
ALTER TABLE `attribute_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `product_inventories`
--
ALTER TABLE `product_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD CONSTRAINT `attribute_options_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`),
  ADD CONSTRAINT `product_attribute_values_parent_product_id_foreign` FOREIGN KEY (`parent_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD CONSTRAINT `product_inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `shipments_shipped_by_foreign` FOREIGN KEY (`shipped_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shipments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `slides_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
