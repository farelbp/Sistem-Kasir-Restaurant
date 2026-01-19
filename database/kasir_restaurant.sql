-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 01:24 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir_restaurant`
--
CREATE DATABASE IF NOT EXISTS `kasir_restaurant` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `kasir_restaurant`;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Main Course', 1, 1, '2026-01-17 14:22:21', '2026-01-18 04:02:18'),
(2, 'Beverage', 2, 1, '2026-01-17 14:22:21', '2026-01-18 04:02:32'),
(3, 'Snack', 3, 1, '2026-01-18 04:02:58', '2026-01-18 04:02:58'),
(4, 'Dessert', 4, 1, '2026-01-18 04:35:22', '2026-01-18 04:35:22'),
(5, 'Makanan', 1, 1, '2026-01-19 00:33:54', '2026-01-19 00:33:54'),
(6, 'Minuman', 2, 1, '2026-01-19 00:33:54', '2026-01-19 00:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_tickets`
--

CREATE TABLE `kitchen_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `ticket_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_no` int UNSIGNED NOT NULL,
  `queue_date` date NOT NULL,
  `status` enum('new','preparing','ready','served','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `printed_count` int UNSIGNED NOT NULL DEFAULT '0',
  `last_printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kitchen_tickets`
--

INSERT INTO `kitchen_tickets` (`id`, `transaction_id`, `ticket_no`, `queue_no`, `queue_date`, `status`, `printed_count`, `last_printed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ASTA-20260118-0001', 1, '2026-01-18', 'new', 1, '2026-01-18 05:02:01', '2026-01-18 05:02:00', '2026-01-18 05:02:01'),
(2, 2, 'ASTA-20260118-0002', 2, '2026-01-18', 'new', 1, '2026-01-18 05:12:23', '2026-01-18 05:12:23', '2026-01-18 05:12:23'),
(3, 3, 'ASTA-20260119-0001', 1, '2026-01-19', 'new', 2, '2026-01-19 00:50:43', '2026-01-19 00:46:46', '2026-01-19 00:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_16_000002_create_categories_table', 1),
(5, '2026_01_16_000003_create_products_table', 1),
(6, '2026_01_16_000004_create_tables_table', 1),
(7, '2026_01_16_000005_create_transactions_table', 1),
(8, '2026_01_16_000006_create_transaction_items_table', 1),
(9, '2026_01_16_000007_create_payments_table', 1),
(10, '2026_01_16_000008_create_kitchen_tickets_table', 1),
(11, '2026_01_16_000009_create_stock_movements_table', 1),
(12, '2026_01_16_000010_create_restocks_table', 1),
(13, '2026_01_16_000011_create_restock_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `method` enum('cash','transfer_manual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `status` enum('paid','pending_verification') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `cash_received` decimal(12,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proof_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` bigint UNSIGNED DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `transaction_id`, `method`, `status`, `paid_amount`, `cash_received`, `change_amount`, `reference_no`, `proof_url`, `received_by`, `verified_by`, `verified_at`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'cash', 'paid', 50000.00, 50000.00, 0.00, NULL, NULL, 2, NULL, NULL, '2026-01-18 05:03:10', '2026-01-18 05:03:10', '2026-01-18 05:03:10'),
(2, 2, 'cash', 'paid', 20000.00, 25000.00, 5000.00, NULL, NULL, 2, NULL, NULL, '2026-01-18 05:14:56', '2026-01-18 05:14:56', '2026-01-18 05:14:56'),
(3, 3, 'transfer_manual', 'paid', 48000.00, 0.00, 0.00, '50000', NULL, 2, 2, '2026-01-19 00:47:42', '2026-01-19 00:47:42', '2026-01-19 00:47:27', '2026-01-19 00:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `stock_qty` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `sku`, `price`, `cost`, `image_url`, `stock_enabled`, `stock_qty`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nasi Goreng Empunala', NULL, 50000.00, 15000.00, 'products/NNkpCz2VrcXh1PlAHCmEfrvvby4Kgy3JP2u2bCh4.webp', 0, 10, 1, '2026-01-17 14:22:21', '2026-01-19 00:42:32'),
(2, 2, 'Original Tea', NULL, 18000.00, 5000.00, 'products/JuHokPVjiZYG5cZpCkKyn1rxAC1PNjfGb3I2zTbv.jpg', 0, 50, 1, '2026-01-17 14:22:21', '2026-01-18 04:41:42'),
(3, 1, 'Mie Goreng Jawa', NULL, 45000.00, 37190.00, 'products/Q13SlRC3JVIenXcyj42VPAwezQHL2gWLsJL4oN3q.jpg', 1, 15, 1, '2026-01-18 04:17:02', '2026-01-18 04:17:02'),
(4, 1, 'Sop Buntut', NULL, 66000.00, 54545.00, 'products/7SKGnvhux0oEZ9euIbxNYHF0h0Yw2P8guwsTJ3jy.jpg', 1, 7, 1, '2026-01-18 04:18:41', '2026-01-18 04:18:41'),
(5, 1, 'Sup Iga Bakar', NULL, 75000.00, 61983.00, 'products/qujBQztXR4JAx7CEIA9bmvP6kUwzmXbmGaRuuWCi.webp', 1, 4, 1, '2026-01-18 04:21:17', '2026-01-18 04:21:17'),
(6, 1, 'Nasi Ulam Jimbaran', NULL, 70000.00, 57851.00, 'products/G48Mn4wfvtD5kAoKoF9XjQeHXsxzm6TYiTZ2SWlK.webp', 1, 10, 1, '2026-01-18 04:23:17', '2026-01-18 04:23:17'),
(7, 1, 'Sapo Tahu Seafood', NULL, 55000.00, 45454.00, 'products/R5Lvs8DDujikH5RzdMy7gpXuZ4mmrq4MdclC9rwd.jpg', 1, 20, 1, '2026-01-18 04:25:37', '2026-01-18 04:25:37'),
(8, 1, 'Soto Ayam Lamongan', NULL, 50000.00, 41322.00, 'products/Gosfa7qzccjqWPS2Ui141II15RGbb3rdHohOMTot.webp', 1, 30, 1, '2026-01-18 04:28:07', '2026-01-18 04:28:07'),
(9, 3, 'French Fries', NULL, 25000.00, 10000.00, 'products/q1g8VYKTD6n9hfpSCVVCPC0zoNwCfBjvUtW0tACl.jpg', 1, 24, 1, '2026-01-18 04:29:37', '2026-01-18 05:03:10'),
(10, 3, 'MIx Of Gorengan', NULL, 35000.00, 10000.00, 'products/JWCLYgy7TPCvF93S7Spo3dOuuBTZTAE0Vp7ei3Ba.webp', 1, 10, 1, '2026-01-18 04:30:52', '2026-01-18 04:30:52'),
(11, 3, 'Mix Platter', NULL, 30000.00, 10000.00, 'products/QhoSzURCmsF8m0dnCUxko2KXbR2gOE4ENOZLSWcJ.jpg', 1, 7, 1, '2026-01-18 04:33:07', '2026-01-18 04:33:07'),
(12, 3, 'Tahu Cabe Garam', NULL, 30000.00, 10000.00, 'products/06mVkarA9GsqfjjrMObQrZhHAt892bA9MmXmqXYz.jpg', 1, 14, 1, '2026-01-18 04:34:27', '2026-01-19 00:47:42'),
(13, 4, 'Puding Karamel', NULL, 35000.00, 10000.00, 'products/mDUZ1OT7CQKYmWDZhXRNSN3RCtarCNtGI5o1IkxY.jpg', 1, 7, 1, '2026-01-18 04:36:39', '2026-01-18 04:36:39'),
(14, 4, 'Banana Split', NULL, 30000.00, 10000.00, 'products/b1ZY21HinZ8ugBjJyRQO98FTz7eWv3uQ8NzklBRl.webp', 1, 4, 1, '2026-01-18 04:38:19', '2026-01-18 04:38:19'),
(15, 2, 'Lemon Tea', NULL, 20000.00, 5000.00, 'products/whk3fQ9A4KV1qmqsWbDBBgAA4cPH10kwoQ5rDKaD.jpg', 1, 30, 1, '2026-01-18 04:42:55', '2026-01-18 04:42:55'),
(16, 2, 'Lychee Tea', NULL, 20000.00, 5000.00, 'products/pCfvT41eDk8k7QgkNmcEAtVvaVRvyJvnS2ItwtXi.webp', 1, 29, 1, '2026-01-18 04:44:38', '2026-01-18 05:14:56'),
(17, 2, 'Americano', NULL, 25000.00, 5000.00, 'products/VWrJIukTo6mJSZYucHYnPrWAjKkJPsnNG0KAgvRi.webp', 1, 19, 1, '2026-01-18 04:45:59', '2026-01-18 05:03:10'),
(18, 2, 'Espresso', NULL, 30000.00, 5000.00, 'products/Z7yFTjUoYFj5Gkb2VFdAMRFSqZXLEzeio7IBtnef.jpg', 1, 20, 1, '2026-01-18 04:48:00', '2026-01-18 04:48:00'),
(19, 2, 'Cappuccino', NULL, 35000.00, 5000.00, 'products/IyKKIOKc2mngOFlHofJI5So6OiuHqeMQiQWB9FAr.webp', 1, 35, 1, '2026-01-18 04:49:42', '2026-01-18 04:49:42'),
(20, 2, 'Mineral Water', NULL, 10000.00, 2000.00, 'products/b8RU3qHJNdgw4Tuhlf3lpF6mNrI2nhFwxsPSOs5p.webp', 1, 100, 1, '2026-01-18 04:51:10', '2026-01-18 04:51:10'),
(21, 5, 'Nasi Goreng', NULL, 25000.00, 14000.00, NULL, 0, 0, 0, '2026-01-19 00:33:54', '2026-01-19 00:48:43'),
(22, 6, 'Es Teh', NULL, 8000.00, 2500.00, NULL, 0, 0, 0, '2026-01-19 00:33:54', '2026-01-19 00:48:51');

-- --------------------------------------------------------

--
-- Table structure for table `restocks`
--

CREATE TABLE `restocks` (
  `id` bigint UNSIGNED NOT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restock_date` date NOT NULL,
  `total_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restocks`
--

INSERT INTO `restocks` (`id`, `supplier_name`, `restock_date`, `total_cost`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, '2026-01-18', 413220.00, 1, '2026-01-18 04:52:47', '2026-01-18 04:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `restock_items`
--

CREATE TABLE `restock_items` (
  `id` bigint UNSIGNED NOT NULL,
  `restock_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restock_items`
--

INSERT INTO `restock_items` (`id`, `restock_id`, `product_id`, `qty`, `unit_cost`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 41322.00, 413220.00, '2026-01-18 04:52:47', '2026-01-18 04:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('UBRmu3HHDIw8eLB2YFPNkp7djwViKWhWZ4Dwrsvh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOHV2VW9LZWhHUWNJUXhMUEJ1UFpuTnlndHVxdW4xQ1JBNFd4ZjdwRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9rYXNpci9raXRjaGVuLXRvZGF5IjtzOjU6InJvdXRlIjtzOjE5OiJrYXNpci5raXRjaGVuX3RvZGF5Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMjoiYXV0aF91c2VyX2lkIjtpOjI7fQ==', 1768784112);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','adjust','sale') COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `ref_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint UNSIGNED DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `qty`, `ref_type`, `ref_id`, `note`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 9, 'sale', -1, 'transaction', 1, 'Sale ASTA-20260118-0001', 2, '2026-01-18 05:03:10', '2026-01-18 05:03:10'),
(2, 17, 'sale', -1, 'transaction', 1, 'Sale ASTA-20260118-0001', 2, '2026-01-18 05:03:10', '2026-01-18 05:03:10'),
(3, 16, 'sale', -1, 'transaction', 2, 'Sale ASTA-20260118-0002', 2, '2026-01-18 05:14:56', '2026-01-18 05:14:56'),
(4, 12, 'sale', -1, 'transaction', 3, 'Sale (transfer verified) ASTA-20260119-0001', 2, '2026-01-19 00:47:42', '2026-01-19 00:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `code`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'T01', 'Meja 1', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(2, 'T02', 'Meja 2', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(3, 'T03', 'Meja 3', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(4, 'T04', 'Meja 4', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(5, 'T05', 'Meja 5', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(6, 'T06', 'Meja 6', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(7, 'T07', 'Meja 7', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(8, 'T08', 'Meja 8', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(9, 'T09', 'Meja 9', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(10, 'T10', 'Meja 10', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(11, 'T11', 'Meja 11', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21'),
(12, 'T12', 'Meja 12', 1, '2026-01-17 14:22:21', '2026-01-17 14:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `bill_date` date DEFAULT NULL,
  `bill_running_no` int UNSIGNED DEFAULT NULL,
  `bill_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_date` date DEFAULT NULL,
  `queue_no` int UNSIGNED DEFAULT NULL,
  `status` enum('draft','sent_to_kitchen','pending_verification','paid','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `cashier_id` bigint UNSIGNED NOT NULL,
  `table_id` bigint UNSIGNED DEFAULT NULL,
  `order_type` enum('dine_in','takeaway','delivery') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `service` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sent_to_kitchen_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `bill_date`, `bill_running_no`, `bill_no`, `queue_date`, `queue_no`, `status`, `cashier_id`, `table_id`, `order_type`, `customer_name`, `notes`, `subtotal`, `discount`, `tax`, `service`, `grand_total`, `sent_to_kitchen_at`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, '2026-01-18', 1, 'ASTA-20260118-0001', '2026-01-18', 1, 'paid', 2, 1, 'dine_in', NULL, NULL, 50000.00, 0.00, 0.00, 0.00, 50000.00, '2026-01-18 05:02:00', '2026-01-18 05:03:10', '2026-01-17 14:28:14', '2026-01-18 05:03:10'),
(2, '2026-01-18', 2, 'ASTA-20260118-0002', '2026-01-18', 2, 'paid', 2, 2, 'dine_in', NULL, NULL, 20000.00, 0.00, 0.00, 0.00, 20000.00, '2026-01-18 05:12:23', '2026-01-18 05:14:56', '2026-01-18 05:12:02', '2026-01-18 05:14:56'),
(3, '2026-01-19', 1, 'ASTA-20260119-0001', '2026-01-19', 1, 'paid', 2, 1, 'dine_in', NULL, NULL, 48000.00, 0.00, 0.00, 0.00, 48000.00, '2026-01-19 00:46:46', '2026-01-19 00:47:42', '2026-01-19 00:45:36', '2026-01-19 00:47:42');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `qty` int UNSIGNED NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `product_name`, `unit_price`, `unit_cost`, `qty`, `note`, `subtotal`, `created_at`, `updated_at`) VALUES
(2, 1, 17, 'Americano', 25000.00, 5000.00, 1, NULL, 25000.00, '2026-01-18 04:58:51', '2026-01-18 04:58:51'),
(3, 1, 9, 'French Fries', 25000.00, 10000.00, 1, NULL, 25000.00, '2026-01-18 04:59:03', '2026-01-18 04:59:03'),
(5, 2, 16, 'Lychee Tea', 20000.00, 5000.00, 1, NULL, 20000.00, '2026-01-18 05:12:09', '2026-01-18 05:12:09'),
(7, 3, 2, 'Original Tea', 18000.00, 5000.00, 1, NULL, 18000.00, '2026-01-19 00:46:29', '2026-01-19 00:46:29'),
(8, 3, 12, 'Tahu Cabe Garam', 30000.00, 10000.00, 1, NULL, 30000.00, '2026-01-19 00:46:38', '2026-01-19 00:46:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$5TQAxb4h97RJ8ij8Up8EFOwH8iQFcqco2kJP46IONnRiRRV3L/ymi', 'Admin', 'admin', 1, '2026-01-17 14:22:21', '2026-01-19 00:33:53'),
(2, 'kasir', '$2y$12$4TLJrtknu1O6vm4DZevv2.MXKZ0wqTmlbwALdOMPYZXGu8G.l3sRq', 'Kasir', 'kasir', 1, '2026-01-17 14:22:21', '2026-01-19 00:33:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indexes for table `kitchen_tickets`
--
ALTER TABLE `kitchen_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kitchen_tickets_transaction_id_unique` (`transaction_id`),
  ADD UNIQUE KEY `kitchen_tickets_ticket_no_unique` (`ticket_no`),
  ADD KEY `kitchen_tickets_queue_date_queue_no_index` (`queue_date`,`queue_no`),
  ADD KEY `kitchen_tickets_queue_date_index` (`queue_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_received_by_foreign` (`received_by`),
  ADD KEY `payments_verified_by_foreign` (`verified_by`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_is_active_index` (`category_id`,`is_active`);

--
-- Indexes for table `restocks`
--
ALTER TABLE `restocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restocks_created_by_foreign` (`created_by`),
  ADD KEY `restocks_restock_date_index` (`restock_date`);

--
-- Indexes for table `restock_items`
--
ALTER TABLE `restock_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restock_items_restock_id_foreign` (`restock_id`),
  ADD KEY `restock_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_created_by_foreign` (`created_by`),
  ADD KEY `stock_movements_product_id_type_index` (`product_id`,`type`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tables_code_unique` (`code`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_bill_no_unique` (`bill_no`),
  ADD KEY `transactions_cashier_id_foreign` (`cashier_id`),
  ADD KEY `transactions_table_id_foreign` (`table_id`),
  ADD KEY `transactions_queue_date_queue_no_index` (`queue_date`,`queue_no`),
  ADD KEY `transactions_bill_date_index` (`bill_date`),
  ADD KEY `transactions_queue_date_index` (`queue_date`),
  ADD KEY `transactions_status_index` (`status`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`),
  ADD KEY `transaction_items_transaction_id_product_id_index` (`transaction_id`,`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchen_tickets`
--
ALTER TABLE `kitchen_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `restocks`
--
ALTER TABLE `restocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `restock_items`
--
ALTER TABLE `restock_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kitchen_tickets`
--
ALTER TABLE `kitchen_tickets`
  ADD CONSTRAINT `kitchen_tickets_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `restocks`
--
ALTER TABLE `restocks`
  ADD CONSTRAINT `restocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `restock_items`
--
ALTER TABLE `restock_items`
  ADD CONSTRAINT `restock_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `restock_items_restock_id_foreign` FOREIGN KEY (`restock_id`) REFERENCES `restocks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
