-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 02:54 PM
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
-- Database: `balayan_smashers`
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
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Badminton', 'badminton', 'Complete badminton equipment - rackets, shuttlecocks, shoes, and accessories', NULL, 1, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(2, 'Basketball', 'basketball', 'Basketball equipment and gear', NULL, 1, 2, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(3, 'Volleyball', 'volleyball', 'Volleyball equipment and accessories', NULL, 1, 3, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(4, 'Accessories', 'accessories', 'Sports accessories and training equipment', NULL, 1, 4, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(5, 'Yonex Strings', 'yonex-strings', 'High-quality badminton strings from Yonex with multiple color and performance options.', NULL, 1, 1, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(6, 'Ling-Mei Strings', 'ling-mei-strings', 'Durable and affordable badminton strings from Ling-Mei.', NULL, 1, 2, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(7, 'Dunlop Strings', 'dunlop-strings', 'Premium Dunlop badminton strings designed for control and durability.', NULL, 1, 3, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(8, 'Hundred Strings', 'hundred-strings', 'Reliable strings offering consistent tension and feel.', NULL, 1, 4, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(9, 'Reinforce Speed Strings', 'reinforce-speed-strings', 'High-performance strings with great repulsion power.', NULL, 1, 5, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(10, 'Victor Strings', 'victor-strings', 'Professional-grade strings from Victor with various tension and control levels.', NULL, 1, 6, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(11, 'Maxbolt Strings', 'maxbolt-strings', 'Maxbolt badminton strings for superior control and feel.', NULL, 1, 7, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(12, 'Babolat Tennis Rackets', 'babolat-tennis-rackets', 'High-quality long tennis rackets from Babolat.', NULL, 1, 8, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(13, 'Wilson Tennis Rackets', 'wilson-tennis-rackets', 'Wilson’s long tennis rackets with excellent balance and power.', NULL, 1, 9, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(14, 'Weimo Grips', 'weimo-grips', 'Comfortable synthetic grips for rackets, 3 pieces per pack.', NULL, 1, 10, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(15, 'Yonex Grips', 'yonex-grips', 'High-quality Yonex synthetic grips, 3 pieces per pack.', NULL, 1, 11, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(16, 'Wrist Bands', 'wrist-bands', 'All brands of wrist bands available.', NULL, 1, 12, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(17, 'Racket Frame Protectors', 'racket-frame-protectors', 'Durable protectors for racket frames in multiple color combinations.', NULL, 1, 13, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(18, 'Socks', 'socks', 'All brands of high-quality sports socks.', NULL, 1, 14, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(19, 'Molten Basketballs', 'molten-basketballs', 'Official Molten basketballs for training and professional use.', NULL, 1, 15, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(20, 'Mikasa Volleyballs', 'mikasa-volleyballs', 'Mikasa volleyballs and soccer balls for indoor and outdoor play.', NULL, 1, 16, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(21, 'Badminton Shuttlecocks', 'badminton-shuttlecocks', 'Various brands and grades of shuttlecocks for all levels of play.', NULL, 1, 17, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(22, 'Chess Boards', 'chess-boards', 'Durable and professional chess boards of various sizes.', NULL, 1, 18, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(23, 'Grip Rubbers', 'grip-rubbers', 'All types of racket grip rubbers for better comfort and handling.', NULL, 1, 19, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(24, 'Grip Towels', 'grip-towels', 'Thick towel-type racket grips for better sweat absorption.', NULL, 1, 20, '2025-11-07 08:54:36', '2025-11-07 08:54:36'),
(25, 'Cold Sprays', 'cold-sprays', 'Sunto cold sprays 3.5oz for muscle and joint relief.', NULL, 1, 21, '2025-11-07 08:54:36', '2025-11-07 08:54:36');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_06_232456_create_categories_table', 1),
(5, '2025_11_06_232511_create_products_table', 1),
(6, '2025_11_06_232518_create_product_images_table', 1),
(7, '2025_11_06_232525_create_orders_table', 1),
(8, '2025_11_06_232533_create_order_items_table', 1),
(9, '2025_11_06_232539_create_carts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `shipping_name` varchar(255) NOT NULL,
  `shipping_email` varchar(255) NOT NULL,
  `shipping_phone` varchar(255) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_province` varchar(255) NOT NULL,
  `shipping_zipcode` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `shipping_fee`, `total`, `payment_method`, `payment_status`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_address`, `shipping_city`, `shipping_province`, `shipping_zipcode`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, 'ORD-690D431EE57C9', 'pending', 7500.00, 100.00, 7600.00, 'cod', 'pending', 'Test Customer', 'customer@test.com', '09123456789', 'Sugod', 'Tuy', 'Batangas', '4214', NULL, '2025-11-06 16:53:50', '2025-11-06 16:53:50'),
(3, 2, 'ORD-690D487DEC0BC', 'delivered', 2500.00, 100.00, 2600.00, 'cod', 'paid', 'Test Customer', 'customer@test.com', '09123456789', 'Sugod', 'Tuy', 'Batangas', '4214', NULL, '2025-11-06 17:16:45', '2025-11-06 20:50:08'),
(4, 1, 'ORD-690D89A5F3302', 'pending', 2500.00, 100.00, 2600.00, 'bank_transfer', 'pending', 'Admin User', 'admin@balayan-smashers.com', '09066238257', 'Calzada, Ermita', 'Balayan', 'Batangas', '4213', NULL, '2025-11-06 21:54:45', '2025-11-06 21:54:45'),
(5, 3, 'ORD-690DD49B16E59', 'delivered', 800.00, 100.00, 900.00, 'cod', 'paid', 'Jhon Paulo Guevarra', 'guevarrajhonpaulo2@gmail.com', '09454054321', 'Sugod', 'Tuy', 'Batangas', '4214', NULL, '2025-11-07 03:14:35', '2025-11-07 05:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(3, 3, 8, 'Mikasa MVA200 Volleyball', 2500.00, 1, 2500.00, '2025-11-06 17:16:45', '2025-11-06 17:16:45'),
(4, 4, 8, 'Mikasa MVA200 Volleyball', 2500.00, 1, 2500.00, '2025-11-06 21:54:46', '2025-11-06 21:54:46'),
(5, 5, 20, 'Aerobite', 800.00, 1, 800.00, '2025-11-07 03:14:35', '2025-11-07 03:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `specifications`, `price`, `sale_price`, `sku`, `stock`, `low_stock_threshold`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Li-Ning Aeronaut 9000 Racket', 'li-ning-aeronaut-9000-racket', 'Lightweight badminton racket for speed and control', 'Weight: 82g, Flex: Medium, Balance: Even Balance, Grip: S2', 500.00, 400.00, 'LN-AERO9K', 20, 5, 1, 1, '2025-11-06 15:50:09', '2025-11-06 21:52:19'),
(3, 1, 'Yonex Mavis 350 Shuttlecocks (Yellow)', 'yonex-mavis-350', 'Nylon shuttlecocks for practice and recreational play', 'Material: Nylon, Speed: Medium, Quantity: 6 pieces per tube', 450.00, NULL, 'YNX-MAV350-YL', 50, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(4, 1, 'Victor SH-A920 Badminton Shoes', 'victor-sh-a920-shoes', 'Professional badminton shoes with excellent grip and stability', 'Available sizes: 39-44, Color: White/Blue, Technology: VSR, Breathing Mesh', 3500.00, 2999.00, 'VIC-SHA920-WB', 30, 5, 1, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(5, 1, 'Yonex AC402EX Badminton Grip', 'yonex-ac402ex-badminton-grip', 'Super Grap replacement grip for better control', 'Length: 1200mm, Thickness: 0.6mm, Material: Polyurethane', 250.00, NULL, 'YNX-AC402EX', 100, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 21:46:33'),
(6, 2, 'Spalding TF-1000 Legacy Basketball', 'spalding-tf-1000-legacy-basketball', 'Official size and weight basketball with superior grip', 'Size: 7 (Official), Material: Composite Leather, Indoor Use', 3200.00, NULL, 'SPL-TF1000', 25, 5, 1, 1, '2025-11-06 15:50:09', '2025-11-06 17:18:35'),
(7, 19, 'Molten GG7X Basketball', 'molten-gg7x', 'FIBA approved basketball for competitive play', 'Size: 7, Material: Premium Composite, Indoor/Outdoor', 800.00, NULL, 'MLT-GG7X', 18, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(8, 3, 'Mikasa MVA200 Volleyball', 'mikasa-mva200', 'Official Olympic and FIVB game ball', 'Size: 5 (Official), Material: Premium Synthetic Leather, Indoor Use', 2500.00, NULL, 'MKS-MVA200', 18, 5, 1, 1, '2025-11-06 15:50:09', '2025-11-06 21:54:46'),
(9, 3, 'Molten V5M5000 Volleyball', 'molten-v5m5000', 'FIVB approved volleyball for international competition', 'Size: 5, Material: Microfiber Composite, Indoor Use', 2200.00, NULL, 'MLT-V5M5000', 15, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(10, 4, 'Sports Water Bottle 1L', 'sports-water-bottle-1l', 'Durable sports water bottle with leak-proof cap', 'Capacity: 1000ml, Material: BPA-Free Plastic, Colors: Various', 350.00, NULL, 'ACC-WB-1L', 60, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(11, 4, 'Athletic Sports Towel', 'athletic-sports-towel', 'Quick-dry microfiber sports towel', 'Size: 80cm x 40cm, Material: Microfiber, Highly Absorbent', 280.00, NULL, 'ACC-TOWEL-MF', 40, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(12, 4, 'Knee Support Pads (Pair)', 'knee-support-pads', 'Elastic knee support for injury prevention', 'Sizes: S, M, L, XL, Material: Neoprene, Compression Support', 450.00, NULL, 'ACC-KNEE-SUP', 35, 5, 0, 1, '2025-11-06 15:50:09', '2025-11-06 15:50:09'),
(13, 1, 'BG 5', 'bg-5', 'Yonex BG5 badminton string available in Turquoise, Red, Orange, White, and Black.', 'Colors: Turquoise, Red, Orange, White, Black', 480.00, NULL, 'BG5-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(14, 1, 'BG 80 Power', 'bg-80-power', 'Yonex BG80 Power string in Bright Orange for explosive repulsion and control.', 'Color: Bright Orange', 750.00, NULL, 'BG80P-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(15, 1, 'BG 65', 'bg-65', 'Yonex BG65 badminton string, known for durability and all-around play.', 'Colors: Amber, Yellow, Lavender, White', 495.00, NULL, 'BG65-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(16, 1, 'BG 65 Titanium', 'bg-65-titanium', 'Yonex BG65Ti string with titanium coating for sharp feel and control.', 'Colors: Pink, Black, White, Blue, Red', 595.00, NULL, 'BG65T-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(17, 1, 'BG 66 Ultimax', 'bg-66-ultimax', 'Yonex BG66 Ultimax high-repulsion string with soft feeling.', 'Colors: Orange, Blue, Red, Black, Metallic White', 720.00, NULL, 'BG66U-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(18, 1, 'Exbolt 63', 'exbolt-63', 'Yonex Exbolt 63 with thin gauge for faster repulsion.', 'Color: Red', 850.00, NULL, 'EX63-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(19, 1, 'Exbolt 65', 'exbolt-65', 'Yonex Exbolt 65 with great balance of durability and control.', 'Color: Black', 750.00, NULL, 'EX65-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(20, 1, 'Aerobite', 'aerobite', 'Yonex Aerobite hybrid string for maximum spin and control.', 'Colors: Blue/White, Lime Green/White', 800.00, NULL, 'ABT-001', 99, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 03:14:35'),
(21, 1, 'Exbolt 68', 'exbolt-68', 'Yonex Exbolt 68 for durability and solid hitting feel.', 'Colors: Lime Green, Flash Red, White', 750.00, NULL, 'EX68-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(22, 2, 'LM 70', 'lm-70', 'Ling-Mei LM70 string offering reliable control and comfort.', 'Color: Pink', 360.00, NULL, 'LM70-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(23, 2, 'LM 65', 'lm-65', 'Ling-Mei LM65 string with multiple color choices.', 'Colors: White, Turquoise, Lavender, Pink', 360.00, NULL, 'LM65-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(24, 3, 'Iconic X-Life 66', 'iconic-x-life-66', 'Dunlop Iconic X-Life 66 string for maximum durability and feel.', 'Color: Black', 480.00, NULL, 'DXL66-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(25, 4, 'X-Pro 66', 'x-pro-66', 'Hundred X-Pro 66 string available in Ultramarine Blue, Ice White, and Steel Gray.', 'Colors: Ultramarine Blue, Ice White, Steel Gray', 360.00, NULL, 'HXP66-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(26, 5, 'Super Micro 66', 'super-micro-66', 'Reinforce Speed Super Micro 66 string with high repulsion and control.', 'Color: Cyan', 360.00, NULL, 'RSM66-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(27, 6, 'VS-63CS', 'vs-63cs', 'Victor VS-63CS hybrid string, great for power and control.', 'Colors: White/Vibrant Yellow', 690.00, NULL, 'V63CS-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(28, 6, 'VS-63CBC', 'vs-63cbc', 'Victor VS-63CBC hybrid string for precision play.', 'Colors: Pink/Yellow', 690.00, NULL, 'V63CBC-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(29, 6, 'VS-500', 'vs-500', 'Victor VS-500 all-around performance string.', 'Colors: Pink, White, Blue, Black', 550.00, NULL, 'VS500-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(30, 6, 'VBS-68', 'vbs-68', 'Victor VBS-68 durable badminton string.', 'Colors: Yellow, White, Blue', 600.00, NULL, 'VBS68-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(31, 6, 'VBS-61', 'vbs-61', 'Victor VBS-61 with high repulsion and soft feel.', 'Colors: Yellow, Green, Blue', 600.00, NULL, 'VBS61-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(32, 6, 'VBS-70', 'vbs-70', 'Victor VBS-70 cool design string with great control.', 'Colors: Cool Blue, Black, Cold Green, White', 600.00, NULL, 'VBS70-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(33, 7, 'MBS 20', 'mbs-20', 'Maxbolt MBS 20 badminton string for improved power and control.', 'Colors: Black, White, Lime Green, Pink', 400.00, NULL, 'MBS20-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(34, 8, 'Zylon Matrix', 'zylon-matrix', 'Babolat long tennis racket with blue/white/black colorway.', 'Colors: Blue/White/Black', 1500.00, NULL, 'BZM-001', 50, 5, 1, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(35, 9, 'Pro Open', 'pro-open', 'Wilson Pro Open long tennis racket, great for control and spin.', 'Colors: Blue/White/Black', 1500.00, NULL, 'WPO-001', 50, 5, 1, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(36, 10, 'Weimo Synthetic Grip (3pcs)', 'weimo-synthetic-grip', 'Weimo synthetic grip pack with 3 pieces.', NULL, 165.00, NULL, 'WMG-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(37, 11, 'Yonex Synthetic Grip (3pcs)', 'yonex-synthetic-grip', 'Yonex synthetic grip pack with 3 pieces.', NULL, 210.00, NULL, 'YXG-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(38, 12, 'Wrist Bands', 'wrist-bands', 'High-quality wrist bands for sports.', NULL, 150.00, NULL, 'WB-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(39, 13, 'Racket Frame Protector', 'racket-frame-protector', 'Frame protectors in various color combinations.', 'Colors: Red/White, White/Black, Lime Green/White, Yellow/White', 50.00, NULL, 'RFP-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(40, 14, 'Sports Socks', 'sports-socks', 'All brand sports socks available.', NULL, 180.00, NULL, 'SOX-001', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(41, 15, 'BG 2000', 'bg-2000', 'Molten BG2000 basketball for training.', NULL, 1200.00, NULL, 'MBG2000-001', 50, 5, 1, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(42, 15, 'BGR 7', 'bgr-7', 'Molten BGR7 basketball for indoor and outdoor play.', NULL, 995.00, NULL, 'MBGR7-001', 50, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(43, 15, 'Professional Hi-Point', 'professional-hi-point', 'Molten professional Hi-Point basketball.', NULL, 895.00, NULL, 'MHP-001', 50, 5, 1, 1, '2025-11-07 08:58:08', '2025-11-07 03:39:41'),
(44, 15, 'BG 4500', 'bg-4500', 'Molten BG4500 professional basketball.', NULL, 4500.00, NULL, 'MBG4500-001', 50, 5, 1, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(45, 16, 'BZ020W', 'bz020w', 'Mikasa BZ020W volleyball.', NULL, 1000.00, NULL, 'MK-BZ020W', 50, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(46, 16, 'B360W', 'b360w', 'Mikasa B360W professional volleyball.', NULL, 1750.00, NULL, 'MK-B360W', 50, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(47, 16, 'B123W', 'b123w', 'Mikasa B123W volleyball for competition play.', NULL, 1500.00, NULL, 'MK-B123W', 50, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(48, 17, 'XP2 Silver', 'xp2-silver', 'Badminton feather shuttlecock XP2 Silver – 100 per piece.', NULL, 1150.00, NULL, 'XP2SILVER', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(49, 17, 'XP2 Red Feather', 'xp2-red-feather', 'Badminton feather shuttlecock XP2 Red Feather – 100 per piece.', NULL, 1100.00, NULL, 'XP2RED', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(50, 17, 'TCX 5000', 'tcx-5000', 'Badminton shuttlecock TCX 5000 – 100 per piece.', NULL, 1100.00, NULL, 'TCX5000', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(51, 17, 'DMantis 45', 'dmantis-45', 'Badminton shuttlecock DMantis 45 – 80 per piece.', NULL, 885.00, NULL, 'DM45', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(52, 17, 'Anyball', 'anyball', 'Badminton shuttlecock Anyball – 60 per piece.', NULL, 600.00, NULL, 'ANYBALL', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(53, 17, 'Yonex Shuttlecock', 'yonex-shuttlecock', 'Yonex badminton shuttlecock – 60 per piece.', NULL, 600.00, NULL, 'YONEXSHUTTLE', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(54, 18, 'Chess Board 50cm', 'chess-board-50cm', '50cm professional chess board.', NULL, 800.00, NULL, 'CHESS50', 20, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(55, 19, 'Grip Rubber', 'grip-rubber', 'All types of grip rubber for rackets.', NULL, 60.00, NULL, 'GRIPRUB', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(56, 20, 'Grip Thick Towel', 'grip-thick-towel', 'Towel-type grip for rackets.', NULL, 60.00, NULL, 'GRIPTOWEL', 100, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08'),
(57, 21, 'Sunto Cold Spray 3.5oz', 'sunto-cold-spray', 'Sunto 3.5oz cold spray for sports recovery.', NULL, 180.00, NULL, 'SUNTO-001', 50, 5, 0, 1, '2025-11-07 08:58:08', '2025-11-07 08:58:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `order`, `created_at`, `updated_at`) VALUES
(1, 6, 'products/CRTerPaBBeUxULnEAtlspjlAPFieyIJ7Wp2YNkJJ.png', 0, 0, '2025-11-06 17:18:35', '2025-11-06 17:18:35'),
(3, 5, 'products/HScBeuOBbcpOmasCCYcsMSZ55aJeZvR7Nd7xnCS2.png', 0, 0, '2025-11-06 21:46:33', '2025-11-06 21:46:33'),
(4, 43, 'products/mbEZNzSLjuXFR3IY3Up5xKGm1PBd3lYXzkYUfU2G.png', 0, 0, '2025-11-07 03:39:42', '2025-11-07 03:39:42');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `phone`, `address`, `city`, `province`, `zipcode`) VALUES
(1, 'Admin User', 'admin@balayan-smashers.com', '2025-11-06 15:50:09', '$2y$12$XCslPQOwvK08HTCtXor.eOnB7kZm4DpY.anBbGc6xj2dxN/CGy0be', 'XaDRlgHX54yQzu7j3gy4m2IOuzsjWC0iNwPFhkit0x4gbgckNIRelTCEegl4', '2025-11-06 15:50:09', '2025-11-06 15:50:09', 'admin', '09066238257', 'Calzada, Ermita', 'Balayan', 'Batangas', '4213'),
(2, 'Test Customer', 'customer@test.com', '2025-11-06 15:50:09', '$2y$12$8p8UMCe4xOUL/B69M8UwXe/1/V1HuNUDdIpf95s55/Ov7lX8L6Qda', NULL, '2025-11-06 15:50:09', '2025-11-06 15:50:09', 'customer', '09123456789', NULL, NULL, NULL, NULL),
(3, 'Jhon Paulo Guevarra', 'guevarrajhonpaulo2@gmail.com', NULL, '$2y$12$RS9H.Wu0P21XAVdujHfOAenIdYkDxhj4Rj6mSMZfnhoWnGcqqIhpG', NULL, '2025-11-07 02:58:44', '2025-11-07 02:58:44', 'customer', NULL, NULL, NULL, NULL, NULL),
(4, 'Boiser', 'boiser2@gmail.com', NULL, '$2y$12$H5XqaDJenmdPOTGY/CndlecFL.ZB2xIheSy0s4j7lEgyjv500Ebb.', NULL, '2025-11-07 03:15:34', '2025-11-07 03:15:34', 'customer', NULL, NULL, NULL, NULL, NULL),
(5, 'JP', 'jp@gmail.com', NULL, '$2y$12$v3H455I0nuVY12H.qkNYse0Ph2Ftnn5swnIA8CfIA3aNswbdSWenG', NULL, '2025-11-07 03:23:22', '2025-11-07 03:23:22', 'customer', NULL, NULL, NULL, NULL, NULL),
(6, 'Gueva', 'gueva@gmail.com', NULL, '$2y$12$qob9E3OG7geKje.l3h6z/OwQjcZBnIjp7Hwh9IfWJsMuFLBdkvPvm', NULL, '2025-11-07 03:35:37', '2025-11-07 03:35:37', 'customer', NULL, NULL, NULL, NULL, NULL);

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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_user_id_foreign` (`user_id`),
  ADD KEY `cart_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
