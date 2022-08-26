-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2022 at 01:17 AM
-- Server version: 8.0.29-cll-lve
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iotbuild_mes`
--

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(2, 'RED', 'R', '2021-11-27 07:17:28', '2021-11-27 07:17:28'),
(3, 'WHITE / NATURAL', 'lyc73274', '2021-12-05 11:43:51', '2021-12-05 11:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `company_name`, `country_name`, `country_code`, `created_at`, `updated_at`) VALUES
(1, 'Test1', 'Test', 'Malaysia', 'MY', '2022-05-30 15:14:34', '2022-05-30 15:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `customer_emails`
--

CREATE TABLE `customer_emails` (
  `id` bigint UNSIGNED NOT NULL,
  `email_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_phones`
--

CREATE TABLE `customer_phones` (
  `id` bigint UNSIGNED NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_productions`
--

CREATE TABLE `daily_productions` (
  `id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `work_status` smallint DEFAULT '1' COMMENT '1=new 2=rework',
  `total_quantity_plan` int UNSIGNED DEFAULT NULL,
  `total_quantity_produced` int UNSIGNED DEFAULT NULL,
  `total_quantity_rejected` int UNSIGNED DEFAULT NULL,
  `testing_speed` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ended` smallint DEFAULT '0' COMMENT '1=yes 0=no',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_productions`
--

INSERT INTO `daily_productions` (`id`, `department_id`, `work_status`, `total_quantity_plan`, `total_quantity_produced`, `total_quantity_rejected`, `testing_speed`, `is_ended`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 10, NULL, NULL, '12', 0, 1, 1, '2022-05-30 16:52:05', '2022-05-30 16:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_chemicals`
--

CREATE TABLE `daily_production_chemicals` (
  `id` bigint UNSIGNED NOT NULL,
  `daily_production_id` bigint UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_machines`
--

CREATE TABLE `daily_production_machines` (
  `id` bigint UNSIGNED NOT NULL,
  `daily_production_id` bigint UNSIGNED DEFAULT NULL,
  `machine_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_progresses`
--

CREATE TABLE `daily_production_progresses` (
  `id` bigint UNSIGNED NOT NULL,
  `daily_production_id` bigint UNSIGNED NOT NULL,
  `timer_type` smallint NOT NULL DEFAULT '1' COMMENT '1=production 2=break',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `difference_seconds` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_progresses`
--

INSERT INTO `daily_production_progresses` (`id`, `daily_production_id`, `timer_type`, `started_at`, `ended_at`, `difference_seconds`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2022-05-31 00:52:10', '2022-05-31 00:52:15', 5, '2022-05-30 16:52:11', '2022-05-30 16:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_stock_cards`
--

CREATE TABLE `daily_production_stock_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `daily_production_id` bigint UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_stock_cards`
--

INSERT INTO `daily_production_stock_cards` (`id`, `daily_production_id`, `stock_card_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_workers`
--

CREATE TABLE `daily_production_workers` (
  `id` bigint UNSIGNED NOT NULL,
  `daily_production_id` bigint UNSIGNED DEFAULT NULL,
  `worker_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_production_workers`
--

INSERT INTO `daily_production_workers` (`id`, `daily_production_id`, `worker_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_machine` smallint DEFAULT '0',
  `is_chemical` smallint DEFAULT '0',
  `is_assembly` smallint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `is_machine`, `is_chemical`, `is_assembly`, `created_at`, `updated_at`) VALUES
(2, 'CNC', 'CNC', 1, 0, 0, '2021-08-27 19:46:10', '2021-08-27 19:46:10'),
(3, 'CUTTING', 'CUTTING', 1, 0, 0, '2021-09-19 12:26:25', '2021-09-19 12:26:25'),
(4, 'DRILLING', 'DRILLING', 1, 0, 0, '2021-09-19 12:26:32', '2021-09-19 12:26:32'),
(5, 'BRUSH', 'BRUSH', 1, 0, 0, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(6, 'SANDING A', 'SANDING A', 0, 0, 0, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(7, 'SANDING B', 'SANDING B', 1, 0, 0, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(8, 'ASSEMBLY A', 'ASSEMBLY A', 0, 0, 1, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(9, 'ASSEMBLY B', 'ASSEMBLY B', 1, 0, 1, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(10, 'PACKING', 'PACKING', 0, 0, 1, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(11, 'FILLER', 'FILLER', 0, 1, 0, '2021-09-19 12:26:39', '2021-09-19 12:26:39'),
(12, 'SPRAY', 'SPRAY', 0, 1, 0, '2021-09-19 12:26:39', '2021-09-19 12:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_checklist`
--

CREATE TABLE `general_checklist` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_audits`
--

CREATE TABLE `inventory_audits` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `movement_type` smallint NOT NULL COMMENT '1=in 2=out',
  `quantity` int UNSIGNED NOT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `site_location_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

CREATE TABLE `job_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_no_manual` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `crd_date` date DEFAULT NULL,
  `container_vol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `combine_models_bom` smallint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `truck_in` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_orders`
--

INSERT INTO `job_orders` (`id`, `order_no_manual`, `po_no`, `qc_date`, `crd_date`, `container_vol`, `customer_id`, `combine_models_bom`, `created_at`, `updated_at`, `site_id`, `created_by`, `updated_by`, `truck_in`) VALUES
(1, 'TEST', NULL, '2022-05-30', '2022-05-30', NULL, 1, 0, '2022-05-30 15:14:38', '2022-05-30 15:14:38', 1, 1, 1, NULL),
(2, 'TEST', NULL, '2022-05-30', '2022-05-30', NULL, 1, 0, '2022-05-30 15:14:38', '2022-05-30 15:14:38', 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_bom_lists`
--

CREATE TABLE `job_order_bom_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `total_quantity` int UNSIGNED NOT NULL,
  `code_generated` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` smallint UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_quantity` int UNSIGNED DEFAULT NULL,
  `order_length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_length_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_receiving` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_produce` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_loading` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_size_same_as_bom` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order_bom_lists`
--

INSERT INTO `job_order_bom_lists` (`id`, `order_id`, `product_id`, `item_id`, `quantity`, `total_quantity`, `code_generated`, `status`, `remarks`, `length`, `length_unit`, `width`, `width_unit`, `height`, `height_unit`, `thick`, `thick_unit`, `order_quantity`, `order_length`, `order_length_unit`, `order_width`, `order_width_unit`, `order_thick`, `order_thick_unit`, `location_receiving`, `location_produce`, `location_loading`, `created_at`, `updated_at`, `order_size_same_as_bom`) VALUES
(58, 1, 1, 2, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 0),
(59, 1, 1, 5, 2, 2, 'TEST-RED-TABLECHAIR-800X800X800-A3', 1, NULL, '400', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', 2, '400', 'Millimeter', '690', 'Millimeter', '18', 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 1),
(60, 1, 1, 4, 2, 2, 'TEST-RED-TABLECHAIR-800X800X800-A2', 1, NULL, '906', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', 2, '9061', 'Millimeter', '50', 'Millimeter', '18', 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 1),
(61, 1, 1, 3, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-A1', 1, NULL, '400', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', 1, '9061', 'Millimeter', '500', 'Millimeter', '18', 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:16:18', 1),
(62, 1, 1, 6, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-B', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 0),
(63, 1, 1, 8, 2, 2, 'TEST-RED-TABLECHAIR-800X800X800-B2', 1, NULL, '762', 'Millimeter', '35', 'Millimeter', NULL, NULL, '35', 'Millimeter', 2, '762', 'Millimeter', '35', 'Millimeter', '35', 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 1),
(64, 1, 1, 7, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-B1', 1, NULL, '700', 'Millimeter', '444', 'Millimeter', NULL, NULL, '15', 'Millimeter', 1, '700', 'Millimeter', '444', 'Millimeter', '15', 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 1),
(65, 1, 1, 2, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:12:35', 0),
(66, 1, 1, 3, 1, 1, 'TEST-RED-TABLECHAIR-800X800X800-A1', NULL, NULL, '906', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', 1, NULL, 'Millimeter', NULL, 'Millimeter', NULL, 'Millimeter', NULL, NULL, NULL, '2022-06-02 18:12:35', '2022-06-02 18:16:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_products`
--

CREATE TABLE `job_order_products` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `po_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `crd_date` date DEFAULT NULL,
  `container_vol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_test` smallint DEFAULT '0',
  `product_test_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_packing` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `truck_in` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order_products`
--

INSERT INTO `job_order_products` (`id`, `order_id`, `product_id`, `quantity`, `po_no`, `qc_date`, `crd_date`, `container_vol`, `product_test`, `product_test_remarks`, `remarks`, `product_packing`, `created_at`, `updated_at`, `truck_in`) VALUES
(1, 1, 1, 1, NULL, '2022-05-30', '2022-05-30', NULL, 0, NULL, NULL, '<p>Testing</p>', '2022-05-30 15:14:38', '2022-05-30 15:14:38', '2022-05-30'),
(2, 2, 1, 1, NULL, '2022-05-30', '2022-05-30', NULL, 0, NULL, NULL, '<p>Testing</p>', '2022-05-30 15:14:38', '2022-05-30 15:14:38', '2022-05-30');

-- --------------------------------------------------------

--
-- Table structure for table `job_order_product_packing_pictures`
--

CREATE TABLE `job_order_product_packing_pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_order_product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_purchase_lists`
--

CREATE TABLE `job_order_purchase_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bom_order_quantity` int UNSIGNED DEFAULT NULL,
  `order_length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_length_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bom_quantity` int UNSIGNED NOT NULL,
  `bom_total_quantity` int UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED DEFAULT NULL,
  `stock_card_balance_quantity` int UNSIGNED DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `po_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_quantity` int UNSIGNED DEFAULT NULL,
  `item_price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `supplier_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_delievery_date` date DEFAULT NULL,
  `purchase_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_to_subcon` smallint UNSIGNED DEFAULT '0',
  `subcon_date_out` date DEFAULT NULL,
  `subcon_do_no` int UNSIGNED DEFAULT NULL,
  `subcon_quantity` int UNSIGNED DEFAULT NULL,
  `subcon_item_price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `subcon_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcon_est_delievery_date` date DEFAULT NULL,
  `subcon_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcon_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `follow_qty_order_by_bom` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order_purchase_lists`
--

INSERT INTO `job_order_purchase_lists` (`id`, `order_id`, `product_id`, `item_id`, `length`, `length_unit`, `width`, `width_unit`, `height`, `height_unit`, `thick`, `thick_unit`, `bom_order_quantity`, `order_length`, `order_length_unit`, `order_width`, `order_width_unit`, `order_thick`, `order_thick_unit`, `bom_quantity`, `bom_total_quantity`, `stock_card_id`, `stock_card_balance_quantity`, `order_date`, `po_no`, `order_quantity`, `item_price_per_unit`, `supplier_name`, `est_delievery_date`, `purchase_remarks`, `send_to_subcon`, `subcon_date_out`, `subcon_do_no`, `subcon_quantity`, `subcon_item_price_per_unit`, `subcon_name`, `subcon_est_delievery_date`, `subcon_description`, `subcon_remarks`, `created_at`, `updated_at`, `follow_qty_order_by_bom`) VALUES
(1, 1, 1, 3, '906', NULL, '500', NULL, NULL, NULL, '18', NULL, 1, '906', '', '500', '', '18', '', 1, 1, NULL, NULL, '2022-05-30', NULL, 1, NULL, NULL, '2022-05-30', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-30 15:41:07', '2022-06-02 18:16:18', 0),
(2, 1, 1, 4, '906', NULL, '50', NULL, NULL, NULL, '18', NULL, 2, '906', '', '50', '', '18', '', 2, 2, NULL, NULL, '2022-05-30', NULL, 2, NULL, NULL, '2022-05-30', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-30 15:41:07', '2022-06-02 18:16:18', 0),
(3, 1, 1, 5, '400', NULL, '50', NULL, NULL, NULL, '18', NULL, 2, '400', '', '50', '', '18', '', 2, 2, NULL, NULL, '2022-05-30', NULL, 2, NULL, NULL, '2022-05-30', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-30 15:41:07', '2022-06-02 18:16:18', 1),
(4, 1, 1, 7, '700', NULL, '444', NULL, NULL, NULL, '15', NULL, 1, '700', '', '444', '', '15', '', 1, 1, NULL, NULL, '2022-05-30', NULL, 1, NULL, NULL, '2022-05-30', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-30 15:41:07', '2022-06-02 18:16:18', 0),
(5, 1, 1, 8, '762', NULL, '35', NULL, NULL, NULL, '35', NULL, 2, '762', '', '35', '', '35', '', 2, 2, NULL, NULL, '2022-05-30', NULL, 2, NULL, NULL, '2022-05-30', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-05-30 15:41:07', '2022-06-02 18:16:18', 0),
(6, 1, 1, 3, '906', NULL, '500', NULL, NULL, NULL, '18', NULL, 1, NULL, 'Millimeter', NULL, 'Millimeter', NULL, 'Millimeter', 1, 1, NULL, NULL, '2022-06-02', NULL, NULL, NULL, NULL, '2022-06-02', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-06-02 18:16:18', '2022-06-02 18:16:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `job_order_receiving_lists`
--

CREATE TABLE `job_order_receiving_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `date_in` date NOT NULL,
  `do_no` int UNSIGNED DEFAULT NULL,
  `received_quantity` int UNSIGNED DEFAULT NULL,
  `extra_less` int UNSIGNED DEFAULT NULL,
  `balance` int UNSIGNED DEFAULT NULL,
  `balance_received_as_well` smallint UNSIGNED DEFAULT '0',
  `receiving_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_as_well` smallint UNSIGNED DEFAULT '0',
  `receiving_remarks_s` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_to_reject` smallint UNSIGNED DEFAULT '0',
  `reject_date_out` date DEFAULT NULL,
  `reject_memo_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_quantity` int UNSIGNED DEFAULT NULL,
  `reject_est_delievery_date` date DEFAULT NULL,
  `reject_receive_as_well` smallint UNSIGNED DEFAULT '0',
  `reject_cause` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_updated` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_order_receiving_lists`
--

INSERT INTO `job_order_receiving_lists` (`id`, `order_id`, `product_id`, `purchase_id`, `date_in`, `do_no`, `received_quantity`, `extra_less`, `balance`, `balance_received_as_well`, `receiving_remarks`, `received_as_well`, `receiving_remarks_s`, `send_to_reject`, `reject_date_out`, `reject_memo_no`, `reject_quantity`, `reject_est_delievery_date`, `reject_receive_as_well`, `reject_cause`, `reject_remarks`, `reject_picture_link`, `is_updated`, `created_at`, `updated_at`) VALUES
(19, 1, 1, 1, '2022-05-30', NULL, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(20, 1, 1, 2, '2022-05-30', NULL, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(21, 1, 1, 3, '2022-05-30', NULL, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(22, 1, 1, 4, '2022-05-30', NULL, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(23, 1, 1, 5, '2022-05-30', NULL, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(24, 1, 1, 5, '2022-05-31', 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(25, 1, 1, 5, '2022-05-31', 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(26, 1, 1, 5, '2022-05-31', 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(27, 1, 1, 5, '2022-05-31', 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `type` smallint NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `title`, `description`, `action_link`, `created_at`, `updated_at`) VALUES
(1, 1, 'New Job Order Added', 'New Job Order Created By: <a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> Job Order ID:1 Manual NO: ', 'https://mes.iiotfactory.io/public/job-orders/1', '2022-05-30 15:14:38', '2022-05-30 15:14:38'),
(2, 1, 'New BOM List Added', 'BOM List Created By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(3, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A TOP PANEL', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(4, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A3 TOP FRAME 2', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(5, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A2 TOP FRAME 1', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(6, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A1 TOP BASE', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(7, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: B LEFT SIDE PANEL', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(8, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: B2 POST', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(9, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: B1 SIDE BASE', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(10, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A3 TOP FRAME 2', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:38:27', '2022-05-30 15:38:27'),
(11, 1, 'New Purchase List Added', 'Purchase List Created By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/purchase/1/1', '2022-05-30 15:41:07', '2022-05-30 15:41:07'),
(12, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A3 TOP FRAME 2', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:42:03', '2022-05-30 15:42:03'),
(13, 1, 'Updated Purchase List', 'Purchase List Updated By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/purchase/1/1', '2022-05-30 15:42:26', '2022-05-30 15:42:26'),
(14, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A3 TOP FRAME 2', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:43:06', '2022-05-30 15:43:06'),
(15, 1, 'Updated Purchase List', 'Purchase List Updated By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/purchase/1/1', '2022-05-30 15:48:19', '2022-05-30 15:48:19'),
(16, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A2 TOP FRAME 1', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:49:50', '2022-05-30 15:49:50'),
(17, 1, 'Change In BOM List', 'BOM List Changed By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708 and item changed: A1 TOP BASE', 'https://mes.iiotfactory.io/public/job-order/bom/1/1', '2022-05-30 15:49:50', '2022-05-30 15:49:50'),
(18, 1, 'New Receiving List Added', 'Receiving List Created By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/receiving/1/1', '2022-05-30 16:24:23', '2022-05-30 16:24:23'),
(19, 1, 'Updated Receiving List', 'Receiving List Updated By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/receiving/1/1', '2022-05-30 16:41:43', '2022-05-30 16:41:43'),
(20, 1, 'Updated Receiving List', 'Receiving List Updated By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/receiving/1/1', '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(21, 1, 'Updated Purchase List', 'Purchase List Updated By:<a target=\"_blank\" href=\"https://mes.iiotfactory.io/public/users/1\">Admin</a> For Job Order:TEST and Product:Table Chair SW 708', 'https://mes.iiotfactory.io/public/job-order/purchase/1/1', '2022-06-02 18:16:18', '2022-06-02 18:16:18');

-- --------------------------------------------------------

--
-- Table structure for table `notification_viewed_by`
--

CREATE TABLE `notification_viewed_by` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `notification_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_viewed_by`
--

INSERT INTO `notification_viewed_by` (`id`, `user_id`, `notification_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2022-05-30 15:21:54', '2022-05-30 15:21:54'),
(2, 1, 17, '2022-05-31 15:55:03', '2022-05-31 15:55:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_issued_items`
--

CREATE TABLE `production_issued_items` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT '0',
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `site_location_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `machine_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `model_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint UNSIGNED DEFAULT NULL,
  `bomcategory_id` bigint UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `parent_id`, `price_per_unit`, `model_name`, `product_name`, `material`, `color_name`, `color_code`, `length`, `length_unit`, `width`, `width_unit`, `height`, `height_unit`, `thick`, `thick_unit`, `item_description`, `created_at`, `updated_at`, `category_id`, `subcategory_id`, `bomcategory_id`, `image`, `pdf_url`) VALUES
(1, NULL, NULL, 'Table Chair', 'SW 708', NULL, 'RED', 'RED', '800', NULL, '800', NULL, NULL, NULL, '800', NULL, NULL, NULL, NULL, 1, 1, 1, '', NULL),
(2, NULL, '50.0000', 'A', 'TOP PANEL', '', '', '', '906', 'Millimeter', '500', 'Millimeter', NULL, NULL, '762', 'Millimeter', 'top panel of a table', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(3, 2, '55.0000', 'A1', 'TOP BASE', 'MDF - P2', '', '', '906', 'Millimeter', '500', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(4, 2, '40.0000', 'A2', 'TOP FRAME 1', 'MDF - P2', '', '', '906', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(5, 2, '60.0000', 'A3', 'TOP FRAME 2', 'MDF - P2', '', '', '400', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(6, NULL, '40.0000', 'B', 'LEFT SIDE PANEL', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(7, 6, '70.0000', 'B1', 'SIDE BASE', 'MDF - P2', '', '', '700', 'Millimeter', '444', 'Millimeter', NULL, NULL, '15', 'Millimeter', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(8, 6, '60.0000', 'B2', 'POST', 'RW', '', '', '762', 'Millimeter', '35', 'Millimeter', NULL, NULL, '35', 'Millimeter', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 3, 1, 1, '', NULL),
(9, NULL, '44.0000', 'CB', 'CAM BOLT', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '2022-05-30 15:12:15', '2022-05-30 15:12:15', 4, 1, 1, '', NULL),
(10, NULL, NULL, 'SW 798 2C', 'Table Chair Modern Design', NULL, 'RED', 'R', '1200', NULL, '560', NULL, NULL, NULL, '760', NULL, NULL, '2022-05-30 18:23:13', '2022-05-30 18:23:13', 1, 1, 1, '', NULL),
(11, NULL, '50.0000', '798-2C-A', 'TOP PANEL', '', '', '', '906', 'Millimeter', '500', 'Millimeter', NULL, NULL, '762', 'Millimeter', 'top panel of a table', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(12, 11, '55.0000', '798-2C-A1', 'TOP BASE', 'MDF - P2', '', '', '906', 'Millimeter', '500', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(13, 11, '40.0000', '798-2C-A2', 'TOP FRAME 1', 'MDF - P2', '', '', '906', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(14, 11, '60.0000', '798-2C-A3', 'TOP FRAME 2', 'MDF - P2', '', '', '400', 'Millimeter', '50', 'Millimeter', NULL, NULL, '18', 'Millimeter', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(15, NULL, '40.0000', '798-2C-B', 'LEFT SIDE PANEL', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(16, 15, '70.0000', '798-2C-B1', 'SIDE BASE', 'MDF - P2', '', '', '700', 'Millimeter', '444', 'Millimeter', NULL, NULL, '15', 'Millimeter', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(17, 15, '60.0000', '798-2C-B2', 'POST', 'RW', '', '', '762', 'Millimeter', '35', 'Millimeter', NULL, NULL, '35', 'Millimeter', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 3, 1, 1, '', NULL),
(18, NULL, '44.0000', '798-2C-CB', 'CAM BOLT', '', '', '', '', '', '', '', NULL, NULL, '', '', '', '2022-05-30 18:27:59', '2022-05-30 18:27:59', 4, 1, 1, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_bom_mappings`
--

CREATE TABLE `product_bom_mappings` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `total_quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_bom_mappings`
--

INSERT INTO `product_bom_mappings` (`id`, `product_id`, `item_id`, `quantity`, `total_quantity`, `created_at`, `updated_at`, `length`, `length_unit`, `width`, `width_unit`, `thick`, `thick_unit`) VALUES
(9, 1, 2, 1, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 5, 2, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', '400', 'Millimeter', '50', 'Millimeter', '18', 'Millimeter'),
(11, 1, 4, 2, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', '906', 'Millimeter', '50', 'Millimeter', '18', 'Millimeter'),
(12, 1, 3, 1, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', '906', 'Millimeter', '500', 'Millimeter', '18', 'Millimeter'),
(13, 1, 6, 1, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 1, 8, 2, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', '762', 'Millimeter', '35', 'Millimeter', '35', 'Millimeter'),
(15, 1, 7, 1, NULL, '2022-05-30 15:12:19', '2022-05-30 15:12:19', '700', 'Millimeter', '444', 'Millimeter', '15', 'Millimeter'),
(31, 10, 11, 1, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', NULL, NULL, NULL, NULL, NULL, NULL),
(32, 10, 12, 1, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', '906', 'Millimeter', '500', 'Millimeter', '18', 'Millimeter'),
(33, 10, 13, 2, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', '906', 'Millimeter', '50', 'Millimeter', '18', 'Millimeter'),
(34, 10, 14, 2, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', '400', 'Millimeter', '50', 'Millimeter', '18', 'Millimeter'),
(35, 10, 15, 1, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 10, 16, 1, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', '700', 'Millimeter', '444', 'Millimeter', '15', 'Millimeter'),
(37, 10, 17, 2, NULL, '2022-05-30 18:29:24', '2022-05-30 18:29:24', '762', 'Millimeter', '35', 'Millimeter', '35', 'Millimeter');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `has_bom_items` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `code`, `created_at`, `updated_at`, `has_bom_items`) VALUES
(1, 'Finish Good', 'Finish Good', '2022-05-30 12:07:16', NULL, 0),
(3, 'RAW', 'RAW', '2022-05-30 15:12:15', '2022-06-06 01:27:27', 0),
(4, 'HARDWARE', 'HARDWARE', '2022-05-30 15:12:15', '2022-06-06 01:27:44', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_packings`
--

CREATE TABLE `product_packings` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `packing_details` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_packing_pictures`
--

CREATE TABLE `product_packing_pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_packing_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_pictures`
--

CREATE TABLE `product_pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_stocks`
--

INSERT INTO `product_stocks` (`id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 3, 10, '2022-05-30 16:51:23', '2022-05-30 16:51:23'),
(2, 4, 10, '2022-05-30 16:51:36', '2022-05-30 16:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_cards`
--

CREATE TABLE `product_stock_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `stock_card_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_quantity` int UNSIGNED DEFAULT NULL,
  `available_quantity` int UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `date_in` date NOT NULL,
  `date_out` date DEFAULT NULL,
  `is_rejected` smallint DEFAULT '0',
  `is_balance` smallint UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `job_product_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_stock_cards`
--

INSERT INTO `product_stock_cards` (`id`, `stock_card_number`, `ordered_quantity`, `available_quantity`, `order_id`, `product_id`, `date_in`, `date_out`, `is_rejected`, `is_balance`, `created_at`, `updated_at`, `job_product_id`) VALUES
(1, '2022/1', 10, 10, NULL, 3, '2022-05-30', NULL, 0, 0, '2022-05-30 16:51:23', '2022-05-30 16:51:23', NULL),
(2, '2022/2', 10, 10, NULL, 4, '2022-05-30', NULL, 0, 0, '2022-05-30 16:51:36', '2022-05-30 16:51:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Millimeter', 'MM', '2021-07-24 22:33:01', '2021-07-24 22:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurances`
--

CREATE TABLE `quality_assurances` (
  `id` bigint UNSIGNED NOT NULL,
  `qa_type` smallint NOT NULL COMMENT '1=IQC 2=IPQC 3=FQC',
  `qa_category` smallint DEFAULT NULL COMMENT '1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON',
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `qa_by` bigint UNSIGNED NOT NULL,
  `total_quantity` int DEFAULT NULL,
  `sample_size` int DEFAULT NULL,
  `total_defects_found_cr` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_found_mj` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_found_mn` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_cr` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_mj` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_mn` decimal(4,2) UNSIGNED DEFAULT NULL,
  `product_picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` blob,
  `comments` blob,
  `is_ended` smallint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_answers`
--

CREATE TABLE `quality_assurance_answers` (
  `id` bigint UNSIGNED NOT NULL,
  `quality_assurance_id` bigint UNSIGNED NOT NULL,
  `qa_form_question_id` bigint UNSIGNED NOT NULL,
  `answer` smallint NOT NULL COMMENT '1=accepted 2=rejected 3=reworks 4=scrap 5=subcon',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cr` decimal(4,2) UNSIGNED DEFAULT NULL,
  `mi` decimal(4,2) UNSIGNED DEFAULT NULL,
  `mn` decimal(4,2) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_forms`
--

CREATE TABLE `quality_assurance_forms` (
  `id` bigint UNSIGNED NOT NULL,
  `form_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qa_type` smallint NOT NULL COMMENT '1=IQC 2=IPQC 3=FQC',
  `qa_category` smallint DEFAULT NULL COMMENT '1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON',
  `guide_std_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_assurance_forms`
--

INSERT INTO `quality_assurance_forms` (`id`, `form_name`, `description`, `qa_type`, `qa_category`, `guide_std_file`, `created_at`, `updated_at`) VALUES
(1, 'IQC', 'IQC Form for Raw Material', 1, 1, 'assets/images/general_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(2, 'IQC', 'IQC Form for Hardware', 1, 2, 'assets/images/general_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(3, 'IQC', 'IQC Form for Polyform', 1, 3, 'assets/images/general_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(4, 'IQC', 'IQC Form for Carton', 1, 4, 'assets/images/general_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(5, 'IPQC', 'IPQC Form', 2, NULL, 'assets/images/general_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(6, 'FQC', 'FQC Form 1', 3, NULL, 'assets/images/special_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27'),
(7, 'FQC', 'FQC Form 2', 3, NULL, 'assets/images/special_level_STD.png', '2021-10-10 01:17:27', '2021-10-10 01:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_form_questions`
--

CREATE TABLE `quality_assurance_form_questions` (
  `id` bigint UNSIGNED NOT NULL,
  `qa_form_id` bigint UNSIGNED NOT NULL,
  `defect_category` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_remarks` smallint DEFAULT '0' COMMENT '0=no 1=yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_assurance_form_questions`
--

INSERT INTO `quality_assurance_form_questions` (`id`, `qa_form_id`, `defect_category`, `question`, `is_remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'RAW MATERIAL', 'Product Size Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(2, 1, 'RAW MATERIAL', 'Product Quantity Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(3, 1, 'RAW MATERIAL', 'Product Grade same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(4, 1, 'RAW MATERIAL', 'Product Moisture Content tested by MC meter', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(5, 1, 'RAW MATERIAL', 'Product Surface Check/Crack', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(6, 1, 'RAW MATERIAL', 'Product Veneer Quality', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(7, 2, 'HARDWARE', 'Product Size Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(8, 2, 'HARDWARE', 'Product Quantity Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(9, 2, 'HARDWARE', 'Product Grade same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(10, 2, 'HARDWARE', 'Product Loose tested', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(11, 2, 'HARDWARE', 'Product Rust/Oxide', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(12, 2, 'HARDWARE', 'Product non-operating (folding/extension)', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(13, 3, 'POLYFOAM', 'Product Size Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(14, 3, 'POLYFOAM', 'Product Quantity Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(15, 3, 'POLYFOAM', 'Product Density same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(16, 3, 'POLYFOAM', 'Product Quality; Broken pieces', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(17, 3, 'POLYFOAM', 'Product Cleanliness', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(18, 4, 'CARTON', 'Product Size Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(19, 4, 'CARTON', 'Product Quantity Receive same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(20, 4, 'CARTON', 'Product Density same as PO', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(21, 4, 'CARTON', 'Product Marking & Label; Arrangement', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(22, 4, 'CARTON', 'Product Marking & Label; Spelling/Font/Color', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(23, 4, 'CARTON', 'Product Marking & Label; Barcode', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(24, 5, 'PRODUCT APPEARANCE REJECT', 'Sharp Edges', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(25, 5, 'PRODUCT APPEARANCE REJECT', 'Splinters', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(26, 5, 'PRODUCT APPEARANCE REJECT', 'Rough Surface', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(27, 5, 'PRODUCT APPEARANCE REJECT', 'Sanding Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(28, 5, 'PRODUCT APPEARANCE REJECT', 'Dents', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(29, 5, 'PRODUCT APPEARANCE REJECT', 'Scratches', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(30, 5, 'PRODUCT APPEARANCE REJECT', 'Color Variation', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(31, 5, 'PRODUCT APPEARANCE REJECT', 'Rust/Oxides formed at metal', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(32, 5, 'PRODUCT APPEARANCE REJECT', 'Burn Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(33, 5, 'PRODUCT APPEARANCE REJECT', 'Tool Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(34, 5, 'PRODUCT APPEARANCE REJECT', 'Touch-up Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(35, 5, 'PRODUCT APPEARANCE REJECT', 'Bug holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(36, 5, 'PRODUCT APPEARANCE REJECT', 'Pin holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(37, 5, 'PRODUCT APPEARANCE REJECT', 'Splits', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(38, 5, 'PRODUCT APPEARANCE REJECT', 'Surface check/Crack', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(39, 5, 'PRODUCT APPEARANCE REJECT', 'Component fracture/ Compression failure', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(40, 5, 'PRODUCT CONSTRUCTION REJECT', 'Component missing', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(41, 5, 'PRODUCT CONSTRUCTION REJECT', 'Component malformed', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(42, 5, 'PRODUCT CONSTRUCTION REJECT', 'Loose screw and bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(43, 5, 'PRODUCT CONSTRUCTION REJECT', 'Wrong screw or bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(44, 5, 'PRODUCT CONSTRUCTION REJECT', 'Faulty screw and bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(45, 5, 'PRODUCT CONSTRUCTION REJECT', 'Missing screw holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(46, 5, 'PRODUCT CONSTRUCTION REJECT', 'Screw holes not drilled through', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(47, 5, 'PRODUCT CONSTRUCTION REJECT', 'Loose components as a result of faulty joints', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(48, 5, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating folding mechanism', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(49, 5, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating extension mechanism', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(50, 5, 'PRODUCT CONSTRUCTION REJECT', 'Product wobble/ unstable', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(51, 5, 'PRODUCT CONSTRUCTION REJECT', 'Construction/ shape difference from sample', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(52, 5, 'PRODUCT CONSTRUCTION REJECT', 'Joints misaligned', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(53, 5, 'PRODUCT CONSTRUCTION REJECT', 'Wrong tenon and mortise size', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(54, 5, 'PRODUCT CONSTRUCTION REJECT', 'Faulty joints', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(55, 5, 'PRODUCT CONSTRUCTION REJECT', 'Uneven surface', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(56, 5, 'PRODUCT CONSTRUCTION REJECT', 'Chips', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(57, 6, 'PRODUCT APPEARANCE REJECT', 'Moisture Contents (8-12%)', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(58, 6, 'PRODUCT APPEARANCE REJECT', 'Sharp Edges', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(59, 6, 'PRODUCT APPEARANCE REJECT', 'Rough Surface', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(60, 6, 'PRODUCT APPEARANCE REJECT', 'Sanding Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(61, 6, 'PRODUCT APPEARANCE REJECT', 'Dents', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(62, 6, 'PRODUCT APPEARANCE REJECT', 'Chips', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(63, 6, 'PRODUCT APPEARANCE REJECT', 'Scratches', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(64, 6, 'PRODUCT APPEARANCE REJECT', 'Color Variation', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(65, 6, 'PRODUCT APPEARANCE REJECT', 'Rust/Oxides formed at metal', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(66, 6, 'PRODUCT APPEARANCE REJECT', 'Burn Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(67, 6, 'PRODUCT APPEARANCE REJECT', 'Tool Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(68, 6, 'PRODUCT APPEARANCE REJECT', 'Touch-up Mark', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(69, 6, 'PRODUCT APPEARANCE REJECT', 'Bug holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(70, 6, 'PRODUCT APPEARANCE REJECT', 'Pin holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(71, 6, 'PRODUCT APPEARANCE REJECT', 'Splits', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(72, 6, 'PRODUCT APPEARANCE REJECT', 'Surface check/Crack', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(73, 6, 'PRODUCT APPEARANCE REJECT', 'Component fracture/ Compression failure', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(74, 6, 'PRODUCT CONSTRUCTION REJECT', 'Component missing', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(75, 6, 'PRODUCT CONSTRUCTION REJECT', 'Component malformed', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(76, 6, 'PRODUCT CONSTRUCTION REJECT', 'Loose screw and bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(77, 6, 'PRODUCT CONSTRUCTION REJECT', 'Wrong screw or bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(78, 6, 'PRODUCT CONSTRUCTION REJECT', 'Faulty screw and bolts', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(79, 6, 'PRODUCT CONSTRUCTION REJECT', 'Missing screw holes', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(80, 6, 'PRODUCT CONSTRUCTION REJECT', 'Screw holes not drilled through', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(81, 6, 'PRODUCT CONSTRUCTION REJECT', 'Loose components as a result of faulty joints', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(82, 6, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating folding mechanism', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(83, 6, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating extension mechanism', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(84, 6, 'PRODUCT CONSTRUCTION REJECT', 'Product wobble/ unstable', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(85, 6, 'PRODUCT CONSTRUCTION REJECT', 'Construction/ shape difference from sample', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(86, 6, 'PRODUCT CONSTRUCTION REJECT', 'Joints misaligned', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(87, 6, 'PRODUCT CONSTRUCTION REJECT', 'Wrong tenon and mortise size', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(88, 6, 'PRODUCT CONSTRUCTION REJECT', 'Faulty joints', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(89, 6, 'PRODUCT CONSTRUCTION REJECT', 'Uneven surface', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(90, 6, 'PRODUCT PACKAGING REJECT', 'Missing carton markings as per customers\' specification', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(91, 6, 'PRODUCT PACKAGING REJECT', 'Wrong spelling/ font/ color of the carton', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(92, 6, 'PRODUCT PACKAGING REJECT', 'Wrong carton box', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(93, 6, 'PRODUCT PACKAGING REJECT', 'Missing wrapping protection (If necessary)', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(94, 6, 'PRODUCT PACKAGING REJECT', 'Missing hang tag (if necessary)', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(95, 6, 'PRODUCT PACKAGING REJECT', 'Dirty hangtag/packing accessories', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(96, 6, 'PRODUCT PACKAGING REJECT', 'Non-compliance in packaging', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(97, 6, 'PRODUCT PACKAGING REJECT', 'Non-compliance in accessories', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(98, 6, 'PRODUCT PACKAGING REJECT', 'Accessories are nailed onto products for the wrong buyers', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(99, 6, 'PRODUCT PACKAGING REJECT', 'Missing/Non-compliant instruction sheet(If necessary)', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(100, 6, 'PRODUCT PACKAGING REJECT', 'Moisture/water damaged master carton box', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(101, 6, 'PRODUCT PACKAGING REJECT', 'Damaged carton box', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(102, 6, 'PRODUCT PACKAGING REJECT', 'Damaged/wrinkled color label on carton', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(103, 6, 'PRODUCT PACKAGING REJECT', 'Poor label printing', 0, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(104, 7, NULL, 'Arrival Container Condition', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(105, 7, NULL, 'Booking Number', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(106, 7, NULL, 'Seal Number', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(107, 7, NULL, 'Samples/ Extra 2% Fixings/ Replacement Loaded - Enter Details', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(108, 7, NULL, 'Quantity Products load - As Refer to Loading Lists', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13'),
(109, 7, NULL, 'Container Out Condition - As Per Arrival Condition', 1, '2021-10-10 01:19:13', '2021-10-10 01:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_pictures`
--

CREATE TABLE `quality_assurance_pictures` (
  `id` bigint UNSIGNED NOT NULL,
  `quality_assurance_id` bigint UNSIGNED NOT NULL,
  `picture_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '{\"permissions\": {\"users\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"roles\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"multi-site\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"site-locations\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"departments\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"suppliers\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"colors\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"materials\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"machines\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"job-orders\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\",\"manage-bom-list\":\"on\",\"manage-purchase-order\":\"on\",\"manage-receiving-order\":\"on\"},\"bom-list\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"inventory\":{\"issue-for-production\":\"on\",\"audit\":\"on\"},\"stock-cards\":{\"view\":\"on\"},\"customers\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"products\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\",\"map-bom-list\":\"on\"},\"product-categories\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"product-units\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"quality-assurance\":{\"perform-QA\":\"on\",\"view\":\"on\",\"reports\":\"on\"},\"daily-production\":{\"edit\":\"on\",\"manage\":\"on\",\"view\":\"on\"},\"shipping\":{\"create-shipment\":\"on\",\"progress-tracking\":\"on\"},\"costing\":{\"daily-production-report\":\"on\"},\"notifications\":{\"view\":\"on\"},\"logs\":{\"view\":\"on\"},\"system-settings\":{\"edit\":\"on\"}}}', '2021-07-24 19:18:28', '2022-05-25 15:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint UNSIGNED NOT NULL,
  `load_to` smallint NOT NULL COMMENT '1=contena 2=lorry',
  `order_id` bigint UNSIGNED NOT NULL,
  `truck_out_date` date DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `booking_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seal_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `do_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ended` smallint DEFAULT '0' COMMENT '1=yes 0=no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_items`
--

CREATE TABLE `shipping_items` (
  `id` bigint UNSIGNED NOT NULL,
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `worker_id` bigint UNSIGNED DEFAULT NULL,
  `total_plan_qty` int UNSIGNED DEFAULT NULL,
  `actual_loaded_qty` int UNSIGNED DEFAULT NULL,
  `is_ended` smallint UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_left_overs`
--

CREATE TABLE `shipping_left_overs` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_id` bigint UNSIGNED NOT NULL,
  `po_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_progresses`
--

CREATE TABLE `shipping_progresses` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_item_id` bigint UNSIGNED NOT NULL,
  `timer_type` smallint DEFAULT '1' COMMENT '1=loading 2=break',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `difference_seconds` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_replacement_parts`
--

CREATE TABLE `shipping_replacement_parts` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_stock_cards`
--

CREATE TABLE `shipping_stock_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `shipping_id` bigint UNSIGNED NOT NULL,
  `stock_card_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Main Office', 'MO1', '2021-07-13 19:19:20', '2021-07-13 19:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `site_locations`
--

CREATE TABLE `site_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_locations`
--

INSERT INTO `site_locations` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(2, 'Loca1', 'LOC1', '2021-08-27 19:45:55', '2021-08-27 19:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Khan Supplies', '2021-11-06 12:59:58', '2021-11-06 13:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `action_on` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `action_on`, `action_type`, `action_from`, `action_by`, `created_at`, `updated_at`) VALUES
(1, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-29 15:41:19', '2022-05-29 15:41:19'),
(2, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-29 15:41:21', '2022-05-29 15:41:21'),
(3, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-29 15:41:24', '2022-05-29 15:41:24'),
(4, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-29 15:41:25', '2022-05-29 15:41:25'),
(5, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-29 15:41:27', '2022-05-29 15:41:27'),
(6, 'Product Units', 'View all units list', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-29 15:41:30', '2022-05-29 15:41:30'),
(7, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-29 15:41:32', '2022-05-29 15:41:32'),
(8, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-29 15:41:36', '2022-05-29 15:41:36'),
(9, 'Supplier', 'Suppliers list view', 'https://mes.iiotfactory.io/public/suppliers', 1, '2022-05-29 15:41:38', '2022-05-29 15:41:38'),
(10, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-30 15:02:48', '2022-05-30 15:02:48'),
(11, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 15:03:04', '2022-05-30 15:03:04'),
(12, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:03:11', '2022-05-30 15:03:11'),
(13, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:03:20', '2022-05-30 15:03:20'),
(14, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-30 15:04:07', '2022-05-30 15:04:07'),
(15, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:04:20', '2022-05-30 15:04:20'),
(16, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-30 15:04:23', '2022-05-30 15:04:23'),
(17, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:04:28', '2022-05-30 15:04:28'),
(18, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 15:04:34', '2022-05-30 15:04:34'),
(19, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 15:04:37', '2022-05-30 15:04:37'),
(20, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 15:04:57', '2022-05-30 15:04:57'),
(21, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 15:05:00', '2022-05-30 15:05:00'),
(22, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 15:05:25', '2022-05-30 15:05:25'),
(23, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 15:05:32', '2022-05-30 15:05:32'),
(24, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:08:01', '2022-05-30 15:08:01'),
(25, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-30 15:08:04', '2022-05-30 15:08:04'),
(26, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:09:22', '2022-05-30 15:09:22'),
(27, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:09:24', '2022-05-30 15:09:24'),
(28, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-30 15:09:26', '2022-05-30 15:09:26'),
(29, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 15:12:03', '2022-05-30 15:12:03'),
(30, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:07', '2022-05-30 15:12:07'),
(31, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:08', '2022-05-30 15:12:08'),
(32, 'Products', 'Update BOM Mapping by File Upload for product model: Table Chair', 'https://mes.iiotfactory.io/public/products/bom-mapping/upload/1', 1, '2022-05-30 15:12:15', '2022-05-30 15:12:15'),
(33, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:16', '2022-05-30 15:12:16'),
(34, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:16', '2022-05-30 15:12:16'),
(35, 'Products', 'Update BOM Mapping for product model: Table Chair', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:19', '2022-05-30 15:12:19'),
(36, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:20', '2022-05-30 15:12:20'),
(37, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-30 15:12:20', '2022-05-30 15:12:20'),
(38, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:12:25', '2022-05-30 15:12:25'),
(39, 'Job Orders', 'Open create job order form', 'https://mes.iiotfactory.io/public/job-orders/create', 1, '2022-05-30 15:12:28', '2022-05-30 15:12:28'),
(40, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:13:13', '2022-05-30 15:13:13'),
(41, 'Customers', 'Added customer successfully', 'https://mes.iiotfactory.io/public/customers', 1, '2022-05-30 15:14:34', '2022-05-30 15:14:34'),
(42, 'Job Orders', 'Added job order successfully', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:14:38', '2022-05-30 15:14:38'),
(43, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:14:39', '2022-05-30 15:14:39'),
(44, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:14:58', '2022-05-30 15:14:58'),
(45, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:13', '2022-05-30 15:15:13'),
(46, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:48', '2022-05-30 15:15:48'),
(47, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:48', '2022-05-30 15:15:48'),
(48, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:51', '2022-05-30 15:15:51'),
(49, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:53', '2022-05-30 15:15:53'),
(50, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:15:57', '2022-05-30 15:15:57'),
(51, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:21:32', '2022-05-30 15:21:32'),
(52, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:21:34', '2022-05-30 15:21:34'),
(53, 'Notifications', 'View all notifications list', 'https://mes.iiotfactory.io/public/notifications', 1, '2022-05-30 15:21:50', '2022-05-30 15:21:50'),
(54, 'Notifications', 'View Notification id: 1', 'https://mes.iiotfactory.io/public/notifications/1', 1, '2022-05-30 15:21:54', '2022-05-30 15:21:54'),
(55, 'Notifications', 'View all notifications list', 'https://mes.iiotfactory.io/public/notifications', 1, '2022-05-30 15:21:57', '2022-05-30 15:21:57'),
(56, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:22:01', '2022-05-30 15:22:01'),
(57, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:22:11', '2022-05-30 15:22:11'),
(58, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-30 15:22:14', '2022-05-30 15:22:14'),
(59, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:22:29', '2022-05-30 15:22:29'),
(60, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-30 15:22:59', '2022-05-30 15:22:59'),
(61, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 15:23:02', '2022-05-30 15:23:02'),
(62, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:23:09', '2022-05-30 15:23:09'),
(63, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:27:26', '2022-05-30 15:27:26'),
(64, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:27:27', '2022-05-30 15:27:27'),
(65, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:27:31', '2022-05-30 15:27:31'),
(66, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:27:31', '2022-05-30 15:27:31'),
(67, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:27:43', '2022-05-30 15:27:43'),
(68, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:27:47', '2022-05-30 15:27:47'),
(69, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:27:56', '2022-05-30 15:27:56'),
(70, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:27:59', '2022-05-30 15:27:59'),
(71, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:28:00', '2022-05-30 15:28:00'),
(72, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:28:15', '2022-05-30 15:28:15'),
(73, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:28:16', '2022-05-30 15:28:16'),
(74, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:28:22', '2022-05-30 15:28:22'),
(75, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:28:25', '2022-05-30 15:28:25'),
(76, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:28:26', '2022-05-30 15:28:26'),
(77, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:36:33', '2022-05-30 15:36:33'),
(78, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:36:39', '2022-05-30 15:36:39'),
(79, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:36:40', '2022-05-30 15:36:40'),
(80, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:38:27', '2022-05-30 15:38:27'),
(81, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:38:27', '2022-05-30 15:38:27'),
(82, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:38:38', '2022-05-30 15:38:38'),
(83, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:38:38', '2022-05-30 15:38:38'),
(84, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:38:51', '2022-05-30 15:38:51'),
(85, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:38:51', '2022-05-30 15:38:51'),
(86, 'Purchase Order List', 'Add/Update Purchase List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:41:07', '2022-05-30 15:41:07'),
(87, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:41:07', '2022-05-30 15:41:07'),
(88, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:41:08', '2022-05-30 15:41:08'),
(89, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:42:03', '2022-05-30 15:42:03'),
(90, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:42:03', '2022-05-30 15:42:03'),
(91, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:42:10', '2022-05-30 15:42:10'),
(92, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:42:13', '2022-05-30 15:42:13'),
(93, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:42:13', '2022-05-30 15:42:13'),
(94, 'Purchase Order List', 'Add/Update Purchase List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:42:26', '2022-05-30 15:42:26'),
(95, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:42:27', '2022-05-30 15:42:27'),
(96, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:42:27', '2022-05-30 15:42:27'),
(97, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:42:43', '2022-05-30 15:42:43'),
(98, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:42:43', '2022-05-30 15:42:43'),
(99, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:43:06', '2022-05-30 15:43:06'),
(100, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:43:06', '2022-05-30 15:43:06'),
(101, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:43:10', '2022-05-30 15:43:10'),
(102, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:43:13', '2022-05-30 15:43:13'),
(103, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:43:13', '2022-05-30 15:43:13'),
(104, 'Purchase Order List', 'Add/Update Purchase List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:48:19', '2022-05-30 15:48:19'),
(105, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:48:20', '2022-05-30 15:48:20'),
(106, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:48:20', '2022-05-30 15:48:20'),
(107, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:49:05', '2022-05-30 15:49:05'),
(108, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:49:06', '2022-05-30 15:49:06'),
(109, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:49:50', '2022-05-30 15:49:50'),
(110, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:49:51', '2022-05-30 15:49:51'),
(111, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:49:57', '2022-05-30 15:49:57'),
(112, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:49:57', '2022-05-30 15:49:57'),
(113, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:50:26', '2022-05-30 15:50:26'),
(114, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:50:30', '2022-05-30 15:50:30'),
(115, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:50:31', '2022-05-30 15:50:31'),
(116, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 15:50:44', '2022-05-30 15:50:44'),
(117, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-30 15:50:48', '2022-05-30 15:50:48'),
(118, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:50:49', '2022-05-30 15:50:49'),
(119, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 15:50:56', '2022-05-30 15:50:56'),
(120, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-30 15:51:15', '2022-05-30 15:51:15'),
(121, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:51:16', '2022-05-30 15:51:16'),
(122, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 15:52:55', '2022-05-30 15:52:55'),
(123, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create', 1, '2022-05-30 15:52:59', '2022-05-30 15:52:59'),
(124, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 15:53:10', '2022-05-30 15:53:10'),
(125, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create', 1, '2022-05-30 15:53:12', '2022-05-30 15:53:12'),
(126, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 15:53:31', '2022-05-30 15:53:31'),
(127, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create', 1, '2022-05-30 15:53:32', '2022-05-30 15:53:32'),
(128, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 15:53:58', '2022-05-30 15:53:58'),
(129, 'Receiving Order List', 'Add Receiving List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:24:23', '2022-05-30 16:24:23'),
(130, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:24:23', '2022-05-30 16:24:23'),
(131, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:24:27', '2022-05-30 16:24:27'),
(132, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:24:28', '2022-05-30 16:24:28'),
(133, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 16:35:54', '2022-05-30 16:35:54'),
(134, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-30 16:35:59', '2022-05-30 16:35:59'),
(135, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:36:05', '2022-05-30 16:36:05'),
(136, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:39:54', '2022-05-30 16:39:54'),
(137, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:39:55', '2022-05-30 16:39:55'),
(138, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:40:37', '2022-05-30 16:40:37'),
(139, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:40:38', '2022-05-30 16:40:38'),
(140, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:41:12', '2022-05-30 16:41:12'),
(141, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:41:15', '2022-05-30 16:41:15'),
(142, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:41:20', '2022-05-30 16:41:20'),
(143, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:41:21', '2022-05-30 16:41:21'),
(144, 'Receiving Order List', 'Add Receiving List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:41:43', '2022-05-30 16:41:43'),
(145, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:41:43', '2022-05-30 16:41:43'),
(146, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:41:52', '2022-05-30 16:41:52'),
(147, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:41:53', '2022-05-30 16:41:53'),
(148, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:42:15', '2022-05-30 16:42:15'),
(149, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:42:15', '2022-05-30 16:42:15'),
(150, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:42:29', '2022-05-30 16:42:29'),
(151, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:42:32', '2022-05-30 16:42:32'),
(152, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:42:33', '2022-05-30 16:42:33'),
(153, 'Receiving Order List', 'Add Receiving List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(154, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 16:45:18', '2022-05-30 16:45:18'),
(155, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-30 16:45:24', '2022-05-30 16:45:24'),
(156, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-30 16:45:25', '2022-05-30 16:45:25'),
(157, 'Inventory', 'Open Inventory report generate page', 'https://mes.iiotfactory.io/public/inventory/reports', 1, '2022-05-30 16:45:50', '2022-05-30 16:45:50'),
(158, 'Inventory', 'Open Inventory report generate page', 'https://mes.iiotfactory.io/public/inventory/reports', 1, '2022-05-30 16:45:53', '2022-05-30 16:45:53'),
(159, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 16:47:36', '2022-05-30 16:47:36'),
(160, 'Production', 'Daily production page open', 'https://mes.iiotfactory.io/public/production/daily', 1, '2022-05-30 16:49:41', '2022-05-30 16:49:41'),
(161, 'Inventory', 'Inventory Issued for Production list view', 'https://mes.iiotfactory.io/public/inventory/for-production', 1, '2022-05-30 16:51:02', '2022-05-30 16:51:02'),
(162, 'Stock Cards', 'Stock Cards list view', 'https://mes.iiotfactory.io/public/stock-cards', 1, '2022-05-30 16:51:08', '2022-05-30 16:51:08'),
(163, 'Stock Cards', 'Add new stock card form open', 'https://mes.iiotfactory.io/public/stock-cards/create', 1, '2022-05-30 16:51:10', '2022-05-30 16:51:10'),
(164, 'Stock Cards', 'Add new Stock Card successfully', 'https://mes.iiotfactory.io/public/stock-cards/store', 1, '2022-05-30 16:51:23', '2022-05-30 16:51:23'),
(165, 'Stock Cards', 'Stock Cards list view', 'https://mes.iiotfactory.io/public/stock-cards', 1, '2022-05-30 16:51:23', '2022-05-30 16:51:23'),
(166, 'Stock Cards', 'Add new stock card form open', 'https://mes.iiotfactory.io/public/stock-cards/create', 1, '2022-05-30 16:51:28', '2022-05-30 16:51:28'),
(167, 'Stock Cards', 'Add new Stock Card successfully', 'https://mes.iiotfactory.io/public/stock-cards/store', 1, '2022-05-30 16:51:36', '2022-05-30 16:51:36'),
(168, 'Stock Cards', 'Stock Cards list view', 'https://mes.iiotfactory.io/public/stock-cards', 1, '2022-05-30 16:51:37', '2022-05-30 16:51:37'),
(169, 'Production', 'Daily production page open', 'https://mes.iiotfactory.io/public/production/daily', 1, '2022-05-30 16:51:42', '2022-05-30 16:51:42'),
(170, 'Production', 'Daily production Item created successfully', 'https://mes.iiotfactory.io/public/production/daily', 1, '2022-05-30 16:52:05', '2022-05-30 16:52:05'),
(171, 'Production', 'Working on item, Production ID: 1', 'https://mes.iiotfactory.io/public/production/daily/1', 1, '2022-05-30 16:52:06', '2022-05-30 16:52:06'),
(172, 'Production', 'Daily production Progress Started successfully', 'https://mes.iiotfactory.io/public/production/daily/progress/start', 1, '2022-05-30 16:52:11', '2022-05-30 16:52:11'),
(173, 'Production', 'Daily production Progress Ended successfully', 'https://mes.iiotfactory.io/public/production/daily/progress/stop/1', 1, '2022-05-30 16:52:15', '2022-05-30 16:52:15'),
(174, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-30 17:54:29', '2022-05-30 17:54:29'),
(175, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 17:54:35', '2022-05-30 17:54:35'),
(176, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 17:54:38', '2022-05-30 17:54:38'),
(177, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 17:54:44', '2022-05-30 17:54:44'),
(178, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 17:56:21', '2022-05-30 17:56:21'),
(179, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 17:56:23', '2022-05-30 17:56:23'),
(180, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 17:56:39', '2022-05-30 17:56:39'),
(181, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:03:10', '2022-05-30 18:03:10'),
(182, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:03:20', '2022-05-30 18:03:20'),
(183, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:03:32', '2022-05-30 18:03:32'),
(184, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:03:39', '2022-05-30 18:03:39'),
(185, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 18:04:39', '2022-05-30 18:04:39'),
(186, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 18:05:43', '2022-05-30 18:05:43'),
(187, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:06:34', '2022-05-30 18:06:34'),
(188, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:06:51', '2022-05-30 18:06:51'),
(189, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 18:06:55', '2022-05-30 18:06:55'),
(190, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:11:24', '2022-05-30 18:11:24'),
(191, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-30 18:19:17', '2022-05-30 18:19:17'),
(192, 'Job Orders', 'Job Orders list Report View', 'https://mes.iiotfactory.io/public/job-orders/report', 1, '2022-05-30 18:19:34', '2022-05-30 18:19:34'),
(193, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:19:36', '2022-05-30 18:19:36'),
(194, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-30 18:19:37', '2022-05-30 18:19:37'),
(195, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:19:40', '2022-05-30 18:19:40'),
(196, 'Product Units', 'View all units list', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-30 18:19:42', '2022-05-30 18:19:42'),
(197, 'Material', 'Materials list view', 'https://mes.iiotfactory.io/public/materials', 1, '2022-05-30 18:19:43', '2022-05-30 18:19:43'),
(198, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:19:45', '2022-05-30 18:19:45'),
(199, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-30 18:19:47', '2022-05-30 18:19:47'),
(200, 'Supplier', 'Suppliers list view', 'https://mes.iiotfactory.io/public/suppliers', 1, '2022-05-30 18:19:49', '2022-05-30 18:19:49'),
(201, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-30 18:19:50', '2022-05-30 18:19:50'),
(202, 'Stock Cards', 'Stock Cards list view', 'https://mes.iiotfactory.io/public/stock-cards', 1, '2022-05-30 18:19:52', '2022-05-30 18:19:52'),
(203, 'Inventory', 'Inventory Issued for Production list view', 'https://mes.iiotfactory.io/public/inventory/for-production', 1, '2022-05-30 18:19:54', '2022-05-30 18:19:54'),
(204, 'Inventory', 'Audit Form view', 'https://mes.iiotfactory.io/public/inventory/audit-items', 1, '2022-05-30 18:19:55', '2022-05-30 18:19:55'),
(205, 'Inventory', 'Open Inventory report generate page', 'https://mes.iiotfactory.io/public/inventory/reports', 1, '2022-05-30 18:19:57', '2022-05-30 18:19:57'),
(206, 'Costing', 'Open Dashboard report generate page', 'https://mes.iiotfactory.io/public/costings/report/dashboard', 1, '2022-05-30 18:19:58', '2022-05-30 18:19:58'),
(207, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:32', '2022-05-30 18:20:32'),
(208, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:37', '2022-05-30 18:20:37'),
(209, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:42', '2022-05-30 18:20:42'),
(210, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-30 18:20:49', '2022-05-30 18:20:49'),
(211, 'Product Categories', 'Added product category successfully', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:54', '2022-05-30 18:20:54'),
(212, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:54', '2022-05-30 18:20:54'),
(213, 'Product Categories', 'Delete product category successfull id: 5', 'https://mes.iiotfactory.io/public/product/categories/5', 1, '2022-05-30 18:20:59', '2022-05-30 18:20:59'),
(214, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:20:59', '2022-05-30 18:20:59'),
(215, 'Product Units', 'View all units list', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-30 18:21:05', '2022-05-30 18:21:05'),
(216, 'Product Units', 'Open create product unit form', 'https://mes.iiotfactory.io/public/product/units/create', 1, '2022-05-30 18:21:07', '2022-05-30 18:21:07'),
(217, 'Product Units', 'Added product unit successfully', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-30 18:21:16', '2022-05-30 18:21:16'),
(218, 'Product Units', 'View all units list', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-30 18:21:16', '2022-05-30 18:21:16'),
(219, 'Product Units', 'Delete product unit successfull id: 2', 'https://mes.iiotfactory.io/public/product/units/2', 1, '2022-05-30 18:21:20', '2022-05-30 18:21:20'),
(220, 'Product Units', 'View all units list', 'https://mes.iiotfactory.io/public/product/units', 1, '2022-05-30 18:21:21', '2022-05-30 18:21:21'),
(221, 'Material', 'Materials list view', 'https://mes.iiotfactory.io/public/materials', 1, '2022-05-30 18:21:25', '2022-05-30 18:21:25'),
(222, 'Material', 'Add new form open', 'https://mes.iiotfactory.io/public/materials/create', 1, '2022-05-30 18:21:28', '2022-05-30 18:21:28'),
(223, 'Material', 'Add new Material successfully', 'https://mes.iiotfactory.io/public/materials', 1, '2022-05-30 18:21:35', '2022-05-30 18:21:35'),
(224, 'Material', 'Materials list view', 'https://mes.iiotfactory.io/public/materials', 1, '2022-05-30 18:21:35', '2022-05-30 18:21:35'),
(225, 'Material', 'Delete material successfully id: 1', 'https://mes.iiotfactory.io/public/materials/1', 1, '2022-05-30 18:21:39', '2022-05-30 18:21:39'),
(226, 'Material', 'Materials list view', 'https://mes.iiotfactory.io/public/materials', 1, '2022-05-30 18:21:40', '2022-05-30 18:21:40'),
(227, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:21:43', '2022-05-30 18:21:43'),
(228, 'Color', 'Add new form open', 'https://mes.iiotfactory.io/public/colors/create', 1, '2022-05-30 18:21:45', '2022-05-30 18:21:45'),
(229, 'Color', 'Add new form open', 'https://mes.iiotfactory.io/public/colors/create', 1, '2022-05-30 18:21:48', '2022-05-30 18:21:48'),
(230, 'Color', 'Add new Color successfully', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:22:00', '2022-05-30 18:22:00'),
(231, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:22:00', '2022-05-30 18:22:00'),
(232, 'Color', 'Open Edit form of color id: 4', 'https://mes.iiotfactory.io/public/colors/4/edit', 1, '2022-05-30 18:22:03', '2022-05-30 18:22:03'),
(233, 'Color', 'Edit color successfully id: 4', 'https://mes.iiotfactory.io/public/colors/4', 1, '2022-05-30 18:22:05', '2022-05-30 18:22:05'),
(234, 'Color', 'Open Edit form of color id: 4', 'https://mes.iiotfactory.io/public/colors/4/edit', 1, '2022-05-30 18:22:05', '2022-05-30 18:22:05'),
(235, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:22:08', '2022-05-30 18:22:08'),
(236, 'Color', 'Delete color successfully id: 4', 'https://mes.iiotfactory.io/public/colors/4', 1, '2022-05-30 18:22:11', '2022-05-30 18:22:11'),
(237, 'Color', 'Colors list view', 'https://mes.iiotfactory.io/public/colors', 1, '2022-05-30 18:22:11', '2022-05-30 18:22:11'),
(238, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:22:19', '2022-05-30 18:22:19'),
(239, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-30 18:22:29', '2022-05-30 18:22:29'),
(240, 'Products', 'Added product successfully', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:23:15', '2022-05-30 18:23:15'),
(241, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:23:15', '2022-05-30 18:23:15'),
(242, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:23:34', '2022-05-30 18:23:34'),
(243, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:23:35', '2022-05-30 18:23:35'),
(244, 'Products', 'View product details id: 2', 'https://mes.iiotfactory.io/public/products/2', 1, '2022-05-30 18:24:13', '2022-05-30 18:24:13'),
(245, 'Products', 'View product details id: 4', 'https://mes.iiotfactory.io/public/products/4', 1, '2022-05-30 18:24:23', '2022-05-30 18:24:23'),
(246, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:25:04', '2022-05-30 18:25:04'),
(247, 'Products', 'Edit product form opened id: 9', 'https://mes.iiotfactory.io/public/products/9/edit', 1, '2022-05-30 18:25:13', '2022-05-30 18:25:13'),
(248, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:25:22', '2022-05-30 18:25:22'),
(249, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-05-30 18:25:34', '2022-05-30 18:25:34'),
(250, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:26:24', '2022-05-30 18:26:24'),
(251, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:26:41', '2022-05-30 18:26:41'),
(252, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-05-30 18:26:44', '2022-05-30 18:26:44'),
(253, 'Products', 'Update BOM Mapping by File Upload for product model: SW 798 2C', 'https://mes.iiotfactory.io/public/products/bom-mapping/upload/10', 1, '2022-05-30 18:27:59', '2022-05-30 18:27:59'),
(254, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:27:59', '2022-05-30 18:27:59'),
(255, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:27:59', '2022-05-30 18:27:59'),
(256, 'Products', 'Update BOM Mapping for product model: SW 798 2C', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:28:02', '2022-05-30 18:28:02'),
(257, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:28:02', '2022-05-30 18:28:02'),
(258, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:28:03', '2022-05-30 18:28:03'),
(259, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:28:45', '2022-05-30 18:28:45'),
(260, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-05-30 18:28:49', '2022-05-30 18:28:49'),
(261, 'Product Categories', 'Edit product category successfull id: 4', 'https://mes.iiotfactory.io/public/product/categories/4', 1, '2022-05-30 18:28:52', '2022-05-30 18:28:52'),
(262, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-05-30 18:28:52', '2022-05-30 18:28:52'),
(263, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:28:53', '2022-05-30 18:28:53'),
(264, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-05-30 18:28:56', '2022-05-30 18:28:56'),
(265, 'Product Categories', 'Edit product category successfull id: 3', 'https://mes.iiotfactory.io/public/product/categories/3', 1, '2022-05-30 18:28:58', '2022-05-30 18:28:58'),
(266, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-05-30 18:28:59', '2022-05-30 18:28:59'),
(267, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:29:00', '2022-05-30 18:29:00'),
(268, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-30 18:29:02', '2022-05-30 18:29:02'),
(269, 'Products', 'Update BOM Mapping for product model: SW 798 2C', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:29:24', '2022-05-30 18:29:24'),
(270, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:29:24', '2022-05-30 18:29:24'),
(271, 'Products', 'Open BOM Mapping for product id: 10', 'https://mes.iiotfactory.io/public/products/bom-mapping/10', 1, '2022-05-30 18:29:25', '2022-05-30 18:29:25'),
(272, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-30 18:29:26', '2022-05-30 18:29:26'),
(273, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-31 02:37:57', '2022-05-31 02:37:57'),
(274, 'Receiving Order List', 'View all Receiving Order List', 'https://mes.iiotfactory.io/public/job-order/receiving', 1, '2022-05-31 02:38:02', '2022-05-31 02:38:02'),
(275, 'Receiving Order List', 'Open create Receiving Order List form', 'https://mes.iiotfactory.io/public/job-order/receiving/create?order_id=1&product_id=1', 1, '2022-05-31 02:38:06', '2022-05-31 02:38:06'),
(276, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 02:38:07', '2022-05-31 02:38:07'),
(277, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-31 15:22:36', '2022-05-31 15:22:36'),
(278, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:23:40', '2022-05-31 15:23:40'),
(279, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-31 15:23:46', '2022-05-31 15:23:46'),
(280, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:24:32', '2022-05-31 15:24:32'),
(281, 'Product Categories', 'Open create product category form', 'https://mes.iiotfactory.io/public/product/categories/create', 1, '2022-05-31 15:24:44', '2022-05-31 15:24:44'),
(282, 'Product Categories', 'Added product category successfully', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:25:01', '2022-05-31 15:25:01'),
(283, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:25:01', '2022-05-31 15:25:01'),
(284, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:25:06', '2022-05-31 15:25:06'),
(285, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-31 15:25:08', '2022-05-31 15:25:08'),
(286, 'Products', 'Added product successfully', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:26:17', '2022-05-31 15:26:17'),
(287, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:26:18', '2022-05-31 15:26:18'),
(288, 'Products', 'Edit product form opened id: 19', 'https://mes.iiotfactory.io/public/products/19/edit', 1, '2022-05-31 15:26:29', '2022-05-31 15:26:29'),
(289, 'Products', 'Edit product successfull id: 19', 'https://mes.iiotfactory.io/public/products/19', 1, '2022-05-31 15:26:57', '2022-05-31 15:26:57'),
(290, 'Products', 'Edit product form opened id: 19', 'https://mes.iiotfactory.io/public/products/19/edit', 1, '2022-05-31 15:26:57', '2022-05-31 15:26:57'),
(291, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:27:02', '2022-05-31 15:27:02'),
(292, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-31 15:27:07', '2022-05-31 15:27:07'),
(293, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:27:17', '2022-05-31 15:27:17'),
(294, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:27:18', '2022-05-31 15:27:18'),
(295, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:27:47', '2022-05-31 15:27:47'),
(296, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:27:47', '2022-05-31 15:27:47'),
(297, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:28:05', '2022-05-31 15:28:05'),
(298, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:28:06', '2022-05-31 15:28:06'),
(299, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:28:36', '2022-05-31 15:28:36'),
(300, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:28:36', '2022-05-31 15:28:36'),
(301, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-31 15:28:51', '2022-05-31 15:28:51'),
(302, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:29:07', '2022-05-31 15:29:07'),
(303, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-31 15:29:41', '2022-05-31 15:29:41'),
(304, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:29:48', '2022-05-31 15:29:48'),
(305, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-31 15:30:08', '2022-05-31 15:30:08'),
(306, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:30:17', '2022-05-31 15:30:17'),
(307, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-31 15:34:01', '2022-05-31 15:34:01'),
(308, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:34:08', '2022-05-31 15:34:08'),
(309, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-05-31 15:43:10', '2022-05-31 15:43:10'),
(310, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:43:18', '2022-05-31 15:43:18'),
(311, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:46:08', '2022-05-31 15:46:08'),
(312, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-31 15:46:11', '2022-05-31 15:46:11'),
(313, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:46:14', '2022-05-31 15:46:14'),
(314, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-05-31 15:46:21', '2022-05-31 15:46:21'),
(315, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-31 15:47:21', '2022-05-31 15:47:21'),
(316, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:47:24', '2022-05-31 15:47:24');
INSERT INTO `system_logs` (`id`, `action_on`, `action_type`, `action_from`, `action_by`, `created_at`, `updated_at`) VALUES
(317, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:47:25', '2022-05-31 15:47:25'),
(318, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:47:51', '2022-05-31 15:47:51'),
(319, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-31 15:47:57', '2022-05-31 15:47:57'),
(320, 'Products', 'Added product successfully', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:48:44', '2022-05-31 15:48:44'),
(321, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:48:44', '2022-05-31 15:48:44'),
(322, 'Products', 'Open create product form', 'https://mes.iiotfactory.io/public/products/create', 1, '2022-05-31 15:48:55', '2022-05-31 15:48:55'),
(323, 'Products', 'Added product successfully', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:49:37', '2022-05-31 15:49:37'),
(324, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 15:49:37', '2022-05-31 15:49:37'),
(325, 'Products', 'Edit product form opened id: 21', 'https://mes.iiotfactory.io/public/products/21/edit', 1, '2022-05-31 15:49:46', '2022-05-31 15:49:46'),
(326, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-31 15:50:32', '2022-05-31 15:50:32'),
(327, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-31 15:50:36', '2022-05-31 15:50:36'),
(328, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:50:36', '2022-05-31 15:50:36'),
(329, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-31 15:51:44', '2022-05-31 15:51:44'),
(330, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 15:51:47', '2022-05-31 15:51:47'),
(331, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:51:48', '2022-05-31 15:51:48'),
(332, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-31 15:53:13', '2022-05-31 15:53:13'),
(333, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-31 15:53:16', '2022-05-31 15:53:16'),
(334, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:53:16', '2022-05-31 15:53:16'),
(335, 'Notifications', 'View all notifications list', 'https://mes.iiotfactory.io/public/notifications', 1, '2022-05-31 15:54:58', '2022-05-31 15:54:58'),
(336, 'Notifications', 'View Notification id: 17', 'https://mes.iiotfactory.io/public/notifications/17', 1, '2022-05-31 15:55:03', '2022-05-31 15:55:03'),
(337, 'Notifications', 'View all notifications list', 'https://mes.iiotfactory.io/public/notifications', 1, '2022-05-31 15:55:18', '2022-05-31 15:55:18'),
(338, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-05-31 15:55:22', '2022-05-31 15:55:22'),
(339, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-05-31 15:55:28', '2022-05-31 15:55:28'),
(340, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 15:55:28', '2022-05-31 15:55:28'),
(341, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:58:36', '2022-05-31 15:58:36'),
(342, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-05-31 15:58:42', '2022-05-31 15:58:42'),
(343, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:58:45', '2022-05-31 15:58:45'),
(344, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-05-31 15:58:48', '2022-05-31 15:58:48'),
(345, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:58:50', '2022-05-31 15:58:50'),
(346, 'Product Categories', 'Edit product category form opened id: 2', 'https://mes.iiotfactory.io/public/product/categories/2/edit', 1, '2022-05-31 15:58:52', '2022-05-31 15:58:52'),
(347, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:58:54', '2022-05-31 15:58:54'),
(348, 'Product Categories', 'Edit product category form opened id: 1', 'https://mes.iiotfactory.io/public/product/categories/1/edit', 1, '2022-05-31 15:58:57', '2022-05-31 15:58:57'),
(349, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-05-31 15:58:59', '2022-05-31 15:58:59'),
(350, 'Product Categories', 'Edit product category form opened id: 6', 'https://mes.iiotfactory.io/public/product/categories/6/edit', 1, '2022-05-31 16:00:07', '2022-05-31 16:00:07'),
(351, 'Product Categories', 'Edit product category successfull id: 6', 'https://mes.iiotfactory.io/public/product/categories/6', 1, '2022-05-31 16:00:10', '2022-05-31 16:00:10'),
(352, 'Product Categories', 'Edit product category form opened id: 6', 'https://mes.iiotfactory.io/public/product/categories/6/edit', 1, '2022-05-31 16:00:10', '2022-05-31 16:00:10'),
(353, 'Product Categories', 'Edit product category successfull id: 6', 'https://mes.iiotfactory.io/public/product/categories/6', 1, '2022-05-31 16:00:14', '2022-05-31 16:00:14'),
(354, 'Product Categories', 'Edit product category successfull id: 6', 'https://mes.iiotfactory.io/public/product/categories/6', 1, '2022-05-31 16:00:16', '2022-05-31 16:00:16'),
(355, 'Product Categories', 'Edit product category form opened id: 6', 'https://mes.iiotfactory.io/public/product/categories/6/edit', 1, '2022-05-31 16:00:16', '2022-05-31 16:00:16'),
(356, 'Product Categories', 'Edit product category successfull id: 6', 'https://mes.iiotfactory.io/public/product/categories/6', 1, '2022-05-31 16:00:18', '2022-05-31 16:00:18'),
(357, 'Product Categories', 'Edit product category form opened id: 6', 'https://mes.iiotfactory.io/public/product/categories/6/edit', 1, '2022-05-31 16:00:18', '2022-05-31 16:00:18'),
(358, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:00:26', '2022-05-31 16:00:26'),
(359, 'Products', 'Edit product form opened id: 15', 'https://mes.iiotfactory.io/public/products/15/edit', 1, '2022-05-31 16:00:42', '2022-05-31 16:00:42'),
(360, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:00:49', '2022-05-31 16:00:49'),
(361, 'Products', 'Delete product successfull id: 21', 'https://mes.iiotfactory.io/public/products/21', 1, '2022-05-31 16:00:56', '2022-05-31 16:00:56'),
(362, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:00:57', '2022-05-31 16:00:57'),
(363, 'Products', 'Delete product successfull id: 19', 'https://mes.iiotfactory.io/public/products/19', 1, '2022-05-31 16:01:01', '2022-05-31 16:01:01'),
(364, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:01:01', '2022-05-31 16:01:01'),
(365, 'Products', 'Delete product successfull id: 20', 'https://mes.iiotfactory.io/public/products/20', 1, '2022-05-31 16:01:10', '2022-05-31 16:01:10'),
(366, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:01:11', '2022-05-31 16:01:11'),
(367, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-31 16:01:22', '2022-05-31 16:01:22'),
(368, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 16:01:25', '2022-05-31 16:01:25'),
(369, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 16:01:26', '2022-05-31 16:01:26'),
(370, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 16:09:03', '2022-05-31 16:09:03'),
(371, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 16:09:04', '2022-05-31 16:09:04'),
(372, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-05-31 16:54:41', '2022-05-31 16:54:41'),
(373, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 16:54:46', '2022-05-31 16:54:46'),
(374, 'Products', 'Edit product form opened id: 1', 'https://mes.iiotfactory.io/public/products/1/edit', 1, '2022-05-31 16:54:56', '2022-05-31 16:54:56'),
(375, 'Products', 'Edit product form opened id: 10', 'https://mes.iiotfactory.io/public/products/10/edit', 1, '2022-05-31 16:55:01', '2022-05-31 16:55:01'),
(376, 'Products', 'Edit product form opened id: 10', 'https://mes.iiotfactory.io/public/products/10/edit', 1, '2022-05-31 16:55:52', '2022-05-31 16:55:52'),
(377, 'Products', 'Delete product picture successfull id: 1', 'https://mes.iiotfactory.io/public/products/ajax-delete-picture/1', 1, '2022-05-31 16:55:56', '2022-05-31 16:55:56'),
(378, 'Products', 'Edit product form opened id: 10', 'https://mes.iiotfactory.io/public/products/10/edit', 1, '2022-05-31 16:55:56', '2022-05-31 16:55:56'),
(379, 'Products', 'Edit product successfull id: 10', 'https://mes.iiotfactory.io/public/products/10', 1, '2022-05-31 16:56:06', '2022-05-31 16:56:06'),
(380, 'Products', 'Edit product form opened id: 10', 'https://mes.iiotfactory.io/public/products/10/edit', 1, '2022-05-31 16:56:06', '2022-05-31 16:56:06'),
(381, 'Products', 'Delete product picture successfull id: 5', 'https://mes.iiotfactory.io/public/products/ajax-delete-picture/5', 1, '2022-05-31 16:56:09', '2022-05-31 16:56:09'),
(382, 'Products', 'Edit product form opened id: 10', 'https://mes.iiotfactory.io/public/products/10/edit', 1, '2022-05-31 16:56:10', '2022-05-31 16:56:10'),
(383, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-05-31 16:57:07', '2022-05-31 16:57:07'),
(384, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 16:57:09', '2022-05-31 16:57:09'),
(385, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 16:57:09', '2022-05-31 16:57:09'),
(386, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-05-31 16:58:46', '2022-05-31 16:58:46'),
(387, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-05-31 16:58:47', '2022-05-31 16:58:47'),
(388, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 17:06:30', '2022-05-31 17:06:30'),
(389, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-31 17:06:34', '2022-05-31 17:06:34'),
(390, 'Products', 'Open BOM Mapping for product id: 1', 'https://mes.iiotfactory.io/public/products/bom-mapping/1', 1, '2022-05-31 17:06:34', '2022-05-31 17:06:34'),
(391, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-05-31 17:06:43', '2022-05-31 17:06:43'),
(392, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-06-01 13:41:16', '2022-06-01 13:41:16'),
(393, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-01 13:41:27', '2022-06-01 13:41:27'),
(394, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-01 13:41:31', '2022-06-01 13:41:31'),
(395, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-01 13:41:31', '2022-06-01 13:41:31'),
(396, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-01 13:43:21', '2022-06-01 13:43:21'),
(397, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-06-02 04:15:39', '2022-06-02 04:15:39'),
(398, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-06-02 17:49:15', '2022-06-02 17:49:15'),
(399, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-06-02 17:49:23', '2022-06-02 17:49:23'),
(400, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:49:27', '2022-06-02 17:49:27'),
(401, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-06-02 17:49:31', '2022-06-02 17:49:31'),
(402, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:49:36', '2022-06-02 17:49:36'),
(403, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-06-02 17:50:54', '2022-06-02 17:50:54'),
(404, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:50:59', '2022-06-02 17:50:59'),
(405, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:50:59', '2022-06-02 17:50:59'),
(406, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:51:08', '2022-06-02 17:51:08'),
(407, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:51:08', '2022-06-02 17:51:08'),
(408, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-06-02 17:51:36', '2022-06-02 17:51:36'),
(409, 'Products', 'Edit product form opened id: 9', 'https://mes.iiotfactory.io/public/products/9/edit', 1, '2022-06-02 17:51:44', '2022-06-02 17:51:44'),
(410, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:52:01', '2022-06-02 17:52:01'),
(411, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 17:52:05', '2022-06-02 17:52:05'),
(412, 'Product Categories', 'Edit product category successfull id: 4', 'https://mes.iiotfactory.io/public/product/categories/4', 1, '2022-06-02 17:52:14', '2022-06-02 17:52:14'),
(413, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 17:52:14', '2022-06-02 17:52:14'),
(414, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 17:52:24', '2022-06-02 17:52:24'),
(415, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:52:30', '2022-06-02 17:52:30'),
(416, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:52:34', '2022-06-02 17:52:34'),
(417, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:52:35', '2022-06-02 17:52:35'),
(418, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:53:07', '2022-06-02 17:53:07'),
(419, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 17:53:12', '2022-06-02 17:53:12'),
(420, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:53:28', '2022-06-02 17:53:28'),
(421, 'Product Categories', 'Edit product category form opened id: 2', 'https://mes.iiotfactory.io/public/product/categories/2/edit', 1, '2022-06-02 17:53:34', '2022-06-02 17:53:34'),
(422, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:53:46', '2022-06-02 17:53:46'),
(423, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:53:50', '2022-06-02 17:53:50'),
(424, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:53:50', '2022-06-02 17:53:50'),
(425, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:54:52', '2022-06-02 17:54:52'),
(426, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:54:52', '2022-06-02 17:54:52'),
(427, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-06-02 17:55:10', '2022-06-02 17:55:10'),
(428, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:55:37', '2022-06-02 17:55:37'),
(429, 'Product Categories', 'Edit product category form opened id: 6', 'https://mes.iiotfactory.io/public/product/categories/6/edit', 1, '2022-06-02 17:55:42', '2022-06-02 17:55:42'),
(430, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:55:45', '2022-06-02 17:55:45'),
(431, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 17:55:48', '2022-06-02 17:55:48'),
(432, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:55:51', '2022-06-02 17:55:51'),
(433, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 17:55:55', '2022-06-02 17:55:55'),
(434, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:55:59', '2022-06-02 17:55:59'),
(435, 'Product Categories', 'Edit product category form opened id: 2', 'https://mes.iiotfactory.io/public/product/categories/2/edit', 1, '2022-06-02 17:56:08', '2022-06-02 17:56:08'),
(436, 'Product Categories', 'Edit product category successfull id: 2', 'https://mes.iiotfactory.io/public/product/categories/2', 1, '2022-06-02 17:56:11', '2022-06-02 17:56:11'),
(437, 'Product Categories', 'Edit product category form opened id: 2', 'https://mes.iiotfactory.io/public/product/categories/2/edit', 1, '2022-06-02 17:56:11', '2022-06-02 17:56:11'),
(438, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:56:17', '2022-06-02 17:56:17'),
(439, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:56:20', '2022-06-02 17:56:20'),
(440, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:56:21', '2022-06-02 17:56:21'),
(441, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:56:35', '2022-06-02 17:56:35'),
(442, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 17:56:39', '2022-06-02 17:56:39'),
(443, 'Product Categories', 'Edit product category successfull id: 3', 'https://mes.iiotfactory.io/public/product/categories/3', 1, '2022-06-02 17:56:42', '2022-06-02 17:56:42'),
(444, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 17:56:42', '2022-06-02 17:56:42'),
(445, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:56:48', '2022-06-02 17:56:48'),
(446, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:56:51', '2022-06-02 17:56:51'),
(447, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:56:51', '2022-06-02 17:56:51'),
(448, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:57:15', '2022-06-02 17:57:15'),
(449, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 17:57:16', '2022-06-02 17:57:16'),
(450, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 17:57:19', '2022-06-02 17:57:19'),
(451, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 17:57:20', '2022-06-02 17:57:20'),
(452, 'Products', 'View product details id: 18', 'https://mes.iiotfactory.io/public/products/18', 1, '2022-06-02 17:57:34', '2022-06-02 17:57:34'),
(453, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-06-02 17:57:44', '2022-06-02 17:57:44'),
(454, 'Products', 'View product details id: 12', 'https://mes.iiotfactory.io/public/products/12', 1, '2022-06-02 17:57:54', '2022-06-02 17:57:54'),
(455, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 17:58:46', '2022-06-02 17:58:46'),
(456, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-06-02 17:59:03', '2022-06-02 17:59:03'),
(457, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-06-02 17:59:57', '2022-06-02 17:59:57'),
(458, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-06-02 18:00:08', '2022-06-02 18:00:08'),
(459, 'Products', 'Edit product form opened id: 2', 'https://mes.iiotfactory.io/public/products/2/edit', 1, '2022-06-02 18:00:16', '2022-06-02 18:00:16'),
(460, 'Products', 'Edit product form opened id: 2', 'https://mes.iiotfactory.io/public/products/2/edit', 1, '2022-06-02 18:01:13', '2022-06-02 18:01:13'),
(461, 'Products', 'View all products list', 'https://mes.iiotfactory.io/public/products', 1, '2022-06-02 18:01:22', '2022-06-02 18:01:22'),
(462, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 18:03:19', '2022-06-02 18:03:19'),
(463, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 18:03:23', '2022-06-02 18:03:23'),
(464, 'Product Categories', 'Edit product category successfull id: 3', 'https://mes.iiotfactory.io/public/product/categories/3', 1, '2022-06-02 18:03:26', '2022-06-02 18:03:26'),
(465, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-02 18:03:27', '2022-06-02 18:03:27'),
(466, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 18:03:29', '2022-06-02 18:03:29'),
(467, 'Product Categories', 'Edit product category form opened id: 1', 'https://mes.iiotfactory.io/public/product/categories/1/edit', 1, '2022-06-02 18:03:34', '2022-06-02 18:03:34'),
(468, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 18:03:37', '2022-06-02 18:03:37'),
(469, 'Product Categories', 'Delete product category successfull id: 2', 'https://mes.iiotfactory.io/public/product/categories/2', 1, '2022-06-02 18:03:43', '2022-06-02 18:03:43'),
(470, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 18:03:43', '2022-06-02 18:03:43'),
(471, 'Product Categories', 'Delete product category successfull id: 6', 'https://mes.iiotfactory.io/public/product/categories/6', 1, '2022-06-02 18:03:48', '2022-06-02 18:03:48'),
(472, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-02 18:03:49', '2022-06-02 18:03:49'),
(473, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 18:03:53', '2022-06-02 18:03:53'),
(474, 'Product Categories', 'Edit product category successfull id: 4', 'https://mes.iiotfactory.io/public/product/categories/4', 1, '2022-06-02 18:03:56', '2022-06-02 18:03:56'),
(475, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-02 18:03:56', '2022-06-02 18:03:56'),
(476, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:04:00', '2022-06-02 18:04:00'),
(477, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 18:04:04', '2022-06-02 18:04:04'),
(478, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 18:04:04', '2022-06-02 18:04:04'),
(479, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-06-02 18:04:15', '2022-06-02 18:04:15'),
(480, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:11:25', '2022-06-02 18:11:25'),
(481, 'Job Orders', 'View all job orders list', 'https://mes.iiotfactory.io/public/job-orders', 1, '2022-06-02 18:11:32', '2022-06-02 18:11:32'),
(482, 'Products', 'View product details id: 3', 'https://mes.iiotfactory.io/public/products/3', 1, '2022-06-02 18:12:16', '2022-06-02 18:12:16'),
(483, 'Products', 'Add BOM List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:12:35', '2022-06-02 18:12:35'),
(484, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:12:36', '2022-06-02 18:12:36'),
(485, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-02 18:12:41', '2022-06-02 18:12:41'),
(486, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 18:12:42', '2022-06-02 18:12:42'),
(487, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-06-02 18:12:56', '2022-06-02 18:12:56'),
(488, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-06-02 18:12:59', '2022-06-02 18:12:59'),
(489, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 18:13:01', '2022-06-02 18:13:01'),
(490, 'Products', 'View product details id: 3', 'https://mes.iiotfactory.io/public/products/3', 1, '2022-06-02 18:13:19', '2022-06-02 18:13:19'),
(491, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-06-02 18:15:22', '2022-06-02 18:15:22'),
(492, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 18:15:22', '2022-06-02 18:15:22'),
(493, 'Products', 'View product details id: 3', 'https://mes.iiotfactory.io/public/products/3', 1, '2022-06-02 18:15:29', '2022-06-02 18:15:29'),
(494, 'Purchase Order List', 'Add/Update Purchase List for Job Order id:1 And Product id:1', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-06-02 18:16:18', '2022-06-02 18:16:18'),
(495, 'Purchase Order List', 'Open create Purchase Order List form', 'https://mes.iiotfactory.io/public/job-order/purchase/create?order_id=1&product_id=1', 1, '2022-06-02 18:16:18', '2022-06-02 18:16:18'),
(496, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-02 18:16:19', '2022-06-02 18:16:19'),
(497, 'Purchase Order List', 'View all Purchase Order List', 'https://mes.iiotfactory.io/public/job-order/purchase', 1, '2022-06-02 18:18:54', '2022-06-02 18:18:54'),
(498, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:18:57', '2022-06-02 18:18:57'),
(499, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-06-02 18:18:59', '2022-06-02 18:18:59'),
(500, 'Job Orders', 'View job order details id: 2', 'https://mes.iiotfactory.io/public/job-orders/2', 1, '2022-06-02 18:19:04', '2022-06-02 18:19:04'),
(501, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-06-02 18:19:09', '2022-06-02 18:19:09'),
(502, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:20:16', '2022-06-02 18:20:16'),
(503, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-02 18:20:19', '2022-06-02 18:20:19'),
(504, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create', 1, '2022-06-02 18:20:22', '2022-06-02 18:20:22'),
(505, 'Job Orders', 'View job order details id: 2', 'https://mes.iiotfactory.io/public/job-orders/2', 1, '2022-06-02 18:20:26', '2022-06-02 18:20:26'),
(506, 'Auth', 'User logged in successfully!', 'https://mes.iiotfactory.io/public/login', 1, '2022-06-06 01:26:59', '2022-06-06 01:26:59'),
(507, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-06 01:27:09', '2022-06-06 01:27:09'),
(508, 'Product Categories', 'Edit product category form opened id: 1', 'https://mes.iiotfactory.io/public/product/categories/1/edit', 1, '2022-06-06 01:27:15', '2022-06-06 01:27:15'),
(509, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-06 01:27:17', '2022-06-06 01:27:17'),
(510, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-06 01:27:21', '2022-06-06 01:27:21'),
(511, 'Product Categories', 'Edit product category successfull id: 3', 'https://mes.iiotfactory.io/public/product/categories/3', 1, '2022-06-06 01:27:27', '2022-06-06 01:27:27'),
(512, 'Product Categories', 'Edit product category form opened id: 3', 'https://mes.iiotfactory.io/public/product/categories/3/edit', 1, '2022-06-06 01:27:27', '2022-06-06 01:27:27'),
(513, 'Product Categories', 'View all categories list', 'https://mes.iiotfactory.io/public/product/categories', 1, '2022-06-06 01:27:30', '2022-06-06 01:27:30'),
(514, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-06 01:27:36', '2022-06-06 01:27:36'),
(515, 'Product Categories', 'Edit product category successfull id: 4', 'https://mes.iiotfactory.io/public/product/categories/4', 1, '2022-06-06 01:27:44', '2022-06-06 01:27:44'),
(516, 'Product Categories', 'Edit product category form opened id: 4', 'https://mes.iiotfactory.io/public/product/categories/4/edit', 1, '2022-06-06 01:27:44', '2022-06-06 01:27:44'),
(517, 'BOM List', 'View all BOM List', 'https://mes.iiotfactory.io/public/job-order/bom', 1, '2022-06-06 01:27:48', '2022-06-06 01:27:48'),
(518, 'BOM List', 'Open create Bom List form', 'https://mes.iiotfactory.io/public/job-order/bom/create?order_id=1&product_id=1', 1, '2022-06-06 01:27:53', '2022-06-06 01:27:53'),
(519, 'Job Orders', 'View job order details id: 1', 'https://mes.iiotfactory.io/public/job-orders/1', 1, '2022-06-06 01:27:54', '2022-06-06 01:27:54'),
(520, 'Products', 'View product details id: 9', 'https://mes.iiotfactory.io/public/products/9', 1, '2022-06-06 01:28:08', '2022-06-06 01:28:08');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `basic_time_start` time DEFAULT NULL,
  `basic_time_end` time DEFAULT NULL,
  `over_time_start` time DEFAULT NULL,
  `over_time_end` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `basic_time_start`, `basic_time_end`, `over_time_start`, `over_time_end`, `created_at`, `updated_at`) VALUES
(1, '08:00:00', '17:00:00', '17:01:00', '07:59:00', '2021-10-07 09:37:28', '2021-11-17 14:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `site_id` bigint UNSIGNED DEFAULT NULL,
  `basic_salary` decimal(10,4) DEFAULT NULL,
  `overtime_salary` decimal(10,4) DEFAULT NULL,
  `last_seen_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `site_id`, `basic_salary`, `overtime_salary`, `last_seen_at`) VALUES
(1, 'Admin', 'admin@test.com', '2021-07-24 19:18:41', '$2y$10$q/fspxufKpV1FjANpk9BkeaxOcx7ZOaLuTcZBFIvgfWil3aug4PcC', '51UtIPFvHgnspmvreoZ9NPJu97aAmzNLbeXm8MTw3IjyKTQ35o2oANfclAdz', '2021-07-24 19:18:57', '2022-05-31 15:55:19', 1, 1, '123.2324', '542.3300', '2022-05-31 23:55:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_emails`
--
ALTER TABLE `customer_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_emails_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_phones`
--
ALTER TABLE `customer_phones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_phones_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `daily_productions`
--
ALTER TABLE `daily_productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_productions_department_id_foreign` (`department_id`),
  ADD KEY `daily_productions_created_by_foreign` (`created_by`),
  ADD KEY `daily_productions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `daily_production_chemicals`
--
ALTER TABLE `daily_production_chemicals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_chemicals_daily_production_id_foreign` (`daily_production_id`),
  ADD KEY `daily_production_chemicals_stock_card_id_foreign` (`stock_card_id`);

--
-- Indexes for table `daily_production_machines`
--
ALTER TABLE `daily_production_machines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_machines_daily_production_id_foreign` (`daily_production_id`),
  ADD KEY `daily_production_machines_machine_id_foreign` (`machine_id`);

--
-- Indexes for table `daily_production_progresses`
--
ALTER TABLE `daily_production_progresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_progresses_daily_production_id_foreign` (`daily_production_id`);

--
-- Indexes for table `daily_production_stock_cards`
--
ALTER TABLE `daily_production_stock_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_stock_cards_daily_production_id_foreign` (`daily_production_id`),
  ADD KEY `daily_production_stock_cards_stock_card_id_foreign` (`stock_card_id`);

--
-- Indexes for table `daily_production_workers`
--
ALTER TABLE `daily_production_workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_production_workers_daily_production_id_foreign` (`daily_production_id`),
  ADD KEY `daily_production_workers_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_checklist`
--
ALTER TABLE `general_checklist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_audits_stock_card_id_foreign` (`stock_card_id`),
  ADD KEY `inventory_audits_site_id_foreign` (`site_id`),
  ADD KEY `inventory_audits_site_location_id_foreign` (`site_location_id`),
  ADD KEY `inventory_audits_department_id_foreign` (`department_id`);

--
-- Indexes for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `job_orders_site_id_foreign` (`site_id`),
  ADD KEY `job_orders_created_by_foreign` (`created_by`),
  ADD KEY `job_orders_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `job_order_bom_lists`
--
ALTER TABLE `job_order_bom_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_bom_lists_order_id_foreign` (`order_id`),
  ADD KEY `job_order_bom_lists_product_id_foreign` (`product_id`),
  ADD KEY `job_order_bom_lists_item_id_foreign` (`item_id`);

--
-- Indexes for table `job_order_products`
--
ALTER TABLE `job_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_products_order_id_foreign` (`order_id`),
  ADD KEY `job_order_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `job_order_product_packing_pictures`
--
ALTER TABLE `job_order_product_packing_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_product_packing_pictures_job_order_product_id_foreign` (`job_order_product_id`);

--
-- Indexes for table `job_order_purchase_lists`
--
ALTER TABLE `job_order_purchase_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_purchase_lists_order_id_foreign` (`order_id`),
  ADD KEY `job_order_purchase_lists_product_id_foreign` (`product_id`),
  ADD KEY `job_order_purchase_lists_item_id_foreign` (`item_id`),
  ADD KEY `job_order_purchase_lists_stock_card_id_foreign` (`stock_card_id`);

--
-- Indexes for table `job_order_receiving_lists`
--
ALTER TABLE `job_order_receiving_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_order_receiving_lists_order_id_foreign` (`order_id`),
  ADD KEY `job_order_receiving_lists_product_id_foreign` (`product_id`),
  ADD KEY `job_order_receiving_lists_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_viewed_by`
--
ALTER TABLE `notification_viewed_by`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_viewed_by_user_id_foreign` (`user_id`),
  ADD KEY `notification_viewed_by_notification_id_foreign` (`notification_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `production_issued_items`
--
ALTER TABLE `production_issued_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_issued_items_stock_card_id_foreign` (`stock_card_id`),
  ADD KEY `production_issued_items_site_id_foreign` (`site_id`),
  ADD KEY `production_issued_items_site_location_id_foreign` (`site_location_id`),
  ADD KEY `production_issued_items_department_id_foreign` (`department_id`),
  ADD KEY `production_issued_items_machine_id_foreign` (`machine_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`),
  ADD KEY `products_bomcategory_id_foreign` (`bomcategory_id`),
  ADD KEY `products_parent_id` (`parent_id`);

--
-- Indexes for table `product_bom_mappings`
--
ALTER TABLE `product_bom_mappings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_bom_mappings_product_id_foreign` (`product_id`),
  ADD KEY `product_bom_mappings_item_id_foreign` (`item_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_packings`
--
ALTER TABLE `product_packings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_packings_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_packing_pictures`
--
ALTER TABLE `product_packing_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_packing_pictures_product_packing_id_foreign` (`product_packing_id`);

--
-- Indexes for table `product_pictures`
--
ALTER TABLE `product_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_pictures_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_stock_cards`
--
ALTER TABLE `product_stock_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stock_cards_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quality_assurances`
--
ALTER TABLE `quality_assurances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_assurances_department_id_foreign` (`department_id`),
  ADD KEY `quality_assurances_stock_card_id_foreign` (`stock_card_id`),
  ADD KEY `quality_assurances_qa_by_foreign` (`qa_by`);

--
-- Indexes for table `quality_assurance_answers`
--
ALTER TABLE `quality_assurance_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_assurance_answers_quality_assurance_id_foreign` (`quality_assurance_id`),
  ADD KEY `quality_assurance_answers_qa_form_question_id_foreign` (`qa_form_question_id`);

--
-- Indexes for table `quality_assurance_forms`
--
ALTER TABLE `quality_assurance_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quality_assurance_form_questions`
--
ALTER TABLE `quality_assurance_form_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_assurance_form_questions_qa_form_id_foreign` (`qa_form_id`);

--
-- Indexes for table `quality_assurance_pictures`
--
ALTER TABLE `quality_assurance_pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quality_assurance_pictures_quality_assurance_id_foreign` (`quality_assurance_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shippings_order_id_foreign` (`order_id`);

--
-- Indexes for table `shipping_items`
--
ALTER TABLE `shipping_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_items_shipping_id_foreign` (`shipping_id`),
  ADD KEY `shipping_items_product_id_foreign` (`product_id`),
  ADD KEY `shipping_items_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `shipping_left_overs`
--
ALTER TABLE `shipping_left_overs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_left_overs_shipping_id_foreign` (`shipping_id`),
  ADD KEY `shipping_left_overs_product_id_foreign` (`product_id`);

--
-- Indexes for table `shipping_progresses`
--
ALTER TABLE `shipping_progresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_progresses_shipping_item_id_foreign` (`shipping_item_id`);

--
-- Indexes for table `shipping_replacement_parts`
--
ALTER TABLE `shipping_replacement_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_replacement_parts_shipping_id_foreign` (`shipping_id`),
  ADD KEY `shipping_replacement_parts_product_id_foreign` (`product_id`);

--
-- Indexes for table `shipping_stock_cards`
--
ALTER TABLE `shipping_stock_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_stock_cards_shipping_id_foreign` (`shipping_id`),
  ADD KEY `shipping_stock_cards_stock_card_id_foreign` (`stock_card_id`);

--
-- Indexes for table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_locations`
--
ALTER TABLE `site_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_logs_action_by_foreign` (`action_by`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_site_id_foreign` (`site_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_emails`
--
ALTER TABLE `customer_emails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_phones`
--
ALTER TABLE `customer_phones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_productions`
--
ALTER TABLE `daily_productions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_production_chemicals`
--
ALTER TABLE `daily_production_chemicals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_machines`
--
ALTER TABLE `daily_production_machines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_progresses`
--
ALTER TABLE `daily_production_progresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_production_stock_cards`
--
ALTER TABLE `daily_production_stock_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `daily_production_workers`
--
ALTER TABLE `daily_production_workers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_checklist`
--
ALTER TABLE `general_checklist`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_orders`
--
ALTER TABLE `job_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_order_bom_lists`
--
ALTER TABLE `job_order_bom_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `job_order_products`
--
ALTER TABLE `job_order_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_order_product_packing_pictures`
--
ALTER TABLE `job_order_product_packing_pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_purchase_lists`
--
ALTER TABLE `job_order_purchase_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_order_receiving_lists`
--
ALTER TABLE `job_order_receiving_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `notification_viewed_by`
--
ALTER TABLE `notification_viewed_by`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `production_issued_items`
--
ALTER TABLE `production_issued_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_bom_mappings`
--
ALTER TABLE `product_bom_mappings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_packings`
--
ALTER TABLE `product_packings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_packing_pictures`
--
ALTER TABLE `product_packing_pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_pictures`
--
ALTER TABLE `product_pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_stock_cards`
--
ALTER TABLE `product_stock_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quality_assurances`
--
ALTER TABLE `quality_assurances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_assurance_answers`
--
ALTER TABLE `quality_assurance_answers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_assurance_forms`
--
ALTER TABLE `quality_assurance_forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quality_assurance_form_questions`
--
ALTER TABLE `quality_assurance_form_questions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `quality_assurance_pictures`
--
ALTER TABLE `quality_assurance_pictures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_items`
--
ALTER TABLE `shipping_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_left_overs`
--
ALTER TABLE `shipping_left_overs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_progresses`
--
ALTER TABLE `shipping_progresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_replacement_parts`
--
ALTER TABLE `shipping_replacement_parts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_stock_cards`
--
ALTER TABLE `shipping_stock_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_locations`
--
ALTER TABLE `site_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_emails`
--
ALTER TABLE `customer_emails`
  ADD CONSTRAINT `customer_emails_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `customer_phones`
--
ALTER TABLE `customer_phones`
  ADD CONSTRAINT `customer_phones_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `daily_productions`
--
ALTER TABLE `daily_productions`
  ADD CONSTRAINT `daily_productions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `daily_productions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `daily_productions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `daily_production_chemicals`
--
ALTER TABLE `daily_production_chemicals`
  ADD CONSTRAINT `daily_production_chemicals_daily_production_id_foreign` FOREIGN KEY (`daily_production_id`) REFERENCES `daily_productions` (`id`),
  ADD CONSTRAINT `daily_production_chemicals_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `daily_production_machines`
--
ALTER TABLE `daily_production_machines`
  ADD CONSTRAINT `daily_production_machines_daily_production_id_foreign` FOREIGN KEY (`daily_production_id`) REFERENCES `daily_productions` (`id`),
  ADD CONSTRAINT `daily_production_machines_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`);

--
-- Constraints for table `daily_production_progresses`
--
ALTER TABLE `daily_production_progresses`
  ADD CONSTRAINT `daily_production_progresses_daily_production_id_foreign` FOREIGN KEY (`daily_production_id`) REFERENCES `daily_productions` (`id`);

--
-- Constraints for table `daily_production_stock_cards`
--
ALTER TABLE `daily_production_stock_cards`
  ADD CONSTRAINT `daily_production_stock_cards_daily_production_id_foreign` FOREIGN KEY (`daily_production_id`) REFERENCES `daily_productions` (`id`),
  ADD CONSTRAINT `daily_production_stock_cards_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `daily_production_workers`
--
ALTER TABLE `daily_production_workers`
  ADD CONSTRAINT `daily_production_workers_daily_production_id_foreign` FOREIGN KEY (`daily_production_id`) REFERENCES `daily_productions` (`id`),
  ADD CONSTRAINT `daily_production_workers_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  ADD CONSTRAINT `inventory_audits_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `inventory_audits_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  ADD CONSTRAINT `inventory_audits_site_location_id_foreign` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`id`),
  ADD CONSTRAINT `inventory_audits_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `job_orders`
--
ALTER TABLE `job_orders`
  ADD CONSTRAINT `job_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `job_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `job_orders_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  ADD CONSTRAINT `job_orders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `job_order_bom_lists`
--
ALTER TABLE `job_order_bom_lists`
  ADD CONSTRAINT `job_order_bom_lists_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `job_order_bom_lists_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_bom_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `job_order_products`
--
ALTER TABLE `job_order_products`
  ADD CONSTRAINT `job_order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `job_order_product_packing_pictures`
--
ALTER TABLE `job_order_product_packing_pictures`
  ADD CONSTRAINT `job_order_product_packing_pictures_job_order_product_id_foreign` FOREIGN KEY (`job_order_product_id`) REFERENCES `job_order_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_order_purchase_lists`
--
ALTER TABLE `job_order_purchase_lists`
  ADD CONSTRAINT `job_order_purchase_lists_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `job_order_purchase_lists_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_purchase_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `job_order_purchase_lists_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `job_order_receiving_lists`
--
ALTER TABLE `job_order_receiving_lists`
  ADD CONSTRAINT `job_order_receiving_lists_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `job_orders` (`id`),
  ADD CONSTRAINT `job_order_receiving_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `job_order_receiving_lists_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `job_order_purchase_lists` (`id`);

--
-- Constraints for table `notification_viewed_by`
--
ALTER TABLE `notification_viewed_by`
  ADD CONSTRAINT `notification_viewed_by_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`),
  ADD CONSTRAINT `notification_viewed_by_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `production_issued_items`
--
ALTER TABLE `production_issued_items`
  ADD CONSTRAINT `production_issued_items_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `production_issued_items_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`),
  ADD CONSTRAINT `production_issued_items_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`),
  ADD CONSTRAINT `production_issued_items_site_location_id_foreign` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`id`),
  ADD CONSTRAINT `production_issued_items_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_bomcategory_id_foreign` FOREIGN KEY (`bomcategory_id`) REFERENCES `product_categories` (`id`),
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`),
  ADD CONSTRAINT `products_parent_id` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `product_categories` (`id`);

--
-- Constraints for table `product_bom_mappings`
--
ALTER TABLE `product_bom_mappings`
  ADD CONSTRAINT `product_bom_mappings_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_bom_mappings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_packings`
--
ALTER TABLE `product_packings`
  ADD CONSTRAINT `product_packings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_packing_pictures`
--
ALTER TABLE `product_packing_pictures`
  ADD CONSTRAINT `product_packing_pictures_product_packing_id_foreign` FOREIGN KEY (`product_packing_id`) REFERENCES `product_packings` (`id`);

--
-- Constraints for table `product_pictures`
--
ALTER TABLE `product_pictures`
  ADD CONSTRAINT `product_pictures_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD CONSTRAINT `product_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_stock_cards`
--
ALTER TABLE `product_stock_cards`
  ADD CONSTRAINT `product_stock_cards_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `quality_assurances`
--
ALTER TABLE `quality_assurances`
  ADD CONSTRAINT `quality_assurances_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `quality_assurances_qa_by_foreign` FOREIGN KEY (`qa_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `quality_assurances_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `quality_assurance_answers`
--
ALTER TABLE `quality_assurance_answers`
  ADD CONSTRAINT `quality_assurance_answers_qa_form_question_id_foreign` FOREIGN KEY (`qa_form_question_id`) REFERENCES `quality_assurance_form_questions` (`id`),
  ADD CONSTRAINT `quality_assurance_answers_quality_assurance_id_foreign` FOREIGN KEY (`quality_assurance_id`) REFERENCES `quality_assurances` (`id`);

--
-- Constraints for table `quality_assurance_form_questions`
--
ALTER TABLE `quality_assurance_form_questions`
  ADD CONSTRAINT `quality_assurance_form_questions_qa_form_id_foreign` FOREIGN KEY (`qa_form_id`) REFERENCES `quality_assurance_forms` (`id`);

--
-- Constraints for table `quality_assurance_pictures`
--
ALTER TABLE `quality_assurance_pictures`
  ADD CONSTRAINT `quality_assurance_pictures_quality_assurance_id_foreign` FOREIGN KEY (`quality_assurance_id`) REFERENCES `quality_assurances` (`id`);

--
-- Constraints for table `shippings`
--
ALTER TABLE `shippings`
  ADD CONSTRAINT `shippings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `job_orders` (`id`);

--
-- Constraints for table `shipping_items`
--
ALTER TABLE `shipping_items`
  ADD CONSTRAINT `shipping_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `shipping_items_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`),
  ADD CONSTRAINT `shipping_items_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `shipping_left_overs`
--
ALTER TABLE `shipping_left_overs`
  ADD CONSTRAINT `shipping_left_overs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `shipping_left_overs_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`);

--
-- Constraints for table `shipping_progresses`
--
ALTER TABLE `shipping_progresses`
  ADD CONSTRAINT `shipping_progresses_shipping_item_id_foreign` FOREIGN KEY (`shipping_item_id`) REFERENCES `shipping_items` (`id`);

--
-- Constraints for table `shipping_replacement_parts`
--
ALTER TABLE `shipping_replacement_parts`
  ADD CONSTRAINT `shipping_replacement_parts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `shipping_replacement_parts_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`);

--
-- Constraints for table `shipping_stock_cards`
--
ALTER TABLE `shipping_stock_cards`
  ADD CONSTRAINT `shipping_stock_cards_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`),
  ADD CONSTRAINT `shipping_stock_cards_stock_card_id_foreign` FOREIGN KEY (`stock_card_id`) REFERENCES `product_stock_cards` (`id`);

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_action_by_foreign` FOREIGN KEY (`action_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
