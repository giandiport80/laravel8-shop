-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2021 at 04:18 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
