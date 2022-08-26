--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(2, 'RED', 'R', '2021-11-27 15:17:28', '2021-11-27 15:17:28'),
(3, 'WHITE / NATURAL', 'lyc73274', '2021-12-05 19:43:51', '2021-12-05 19:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `company_name`, `country_name`, `country_code`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', 'test', 'test', '2021-11-20 10:48:48', '2021-11-20 10:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `customer_emails`
--

CREATE TABLE `customer_emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_phones`
--

CREATE TABLE `customer_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_productions`
--

CREATE TABLE `daily_productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `work_status` smallint(6) DEFAULT 1 COMMENT '1=new 2=rework',
  `total_quantity_plan` int(10) UNSIGNED DEFAULT NULL,
  `total_quantity_produced` int(10) UNSIGNED DEFAULT NULL,
  `total_quantity_rejected` int(10) UNSIGNED DEFAULT NULL,
  `testing_speed` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ended` smallint(6) DEFAULT 0 COMMENT '1=yes 0=no',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_chemicals`
--

CREATE TABLE `daily_production_chemicals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_production_id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_machines`
--

CREATE TABLE `daily_production_machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `machine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_progresses`
--

CREATE TABLE `daily_production_progresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_production_id` bigint(20) UNSIGNED NOT NULL,
  `timer_type` smallint(6) NOT NULL DEFAULT 1 COMMENT '1=production 2=break',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `difference_seconds` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_stock_cards`
--

CREATE TABLE `daily_production_stock_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_production_id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_production_workers`
--

CREATE TABLE `daily_production_workers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `daily_production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `worker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_machine` smallint(1) DEFAULT 0,
  `is_chemical` smallint(1) DEFAULT 0,
  `is_assembly` smallint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `is_machine`, `is_chemical`, `is_assembly`, `created_at`, `updated_at`) VALUES
(2, 'CNC', 'CNC', 1, 0, 0, '2021-08-28 03:46:10', '2021-08-28 03:46:10'),
(3, 'CUTTING', 'CUTTING', 1, 0, 0, '2021-09-19 20:26:25', '2021-09-19 20:26:25'),
(4, 'DRILLING', 'DRILLING', 1, 0, 0, '2021-09-19 20:26:32', '2021-09-19 20:26:32'),
(5, 'BRUSH', 'BRUSH', 1, 0, 0, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(6, 'SANDING A', 'SANDING A', 0, 0, 0, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(7, 'SANDING B', 'SANDING B', 1, 0, 0, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(8, 'ASSEMBLY A', 'ASSEMBLY A', 0, 0, 1, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(9, 'ASSEMBLY B', 'ASSEMBLY B', 1, 0, 1, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(10, 'PACKING', 'PACKING', 0, 0, 1, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(11, 'FILLER', 'FILLER', 0, 1, 0, '2021-09-19 20:26:39', '2021-09-19 20:26:39'),
(12, 'SPRAY', 'SPRAY', 0, 1, 0, '2021-09-19 20:26:39', '2021-09-19 20:26:39');

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
-- Table structure for table `inventory_audits`
--

CREATE TABLE `inventory_audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `movement_type` smallint(6) NOT NULL COMMENT '1=in 2=out',
  `quantity` int(10) UNSIGNED NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_orders`
--

CREATE TABLE `job_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no_manual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `crd_date` date DEFAULT NULL,
  `container_vol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `combine_models_bom` smallint(5) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_bom_lists`
--

CREATE TABLE `job_order_bom_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `total_quantity` int(10) UNSIGNED NOT NULL,
  `code_generated` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` smallint(5) UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_quantity` int(10) UNSIGNED DEFAULT NULL,
  `order_length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_receiving` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_produce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_loading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_products`
--

CREATE TABLE `job_order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `po_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `crd_date` date DEFAULT NULL,
  `container_vol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_test` smallint(6) DEFAULT 0,
  `product_test_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_packing` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_product_packing_pictures`
--

CREATE TABLE `job_order_product_packing_pictures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `picture_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_order_product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_purchase_lists`
--

CREATE TABLE `job_order_purchase_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bom_order_quantity` int(10) UNSIGNED DEFAULT NULL,
  `order_length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bom_quantity` int(10) UNSIGNED NOT NULL,
  `bom_total_quantity` int(10) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_card_balance_quantity` int(10) UNSIGNED DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `po_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_quantity` int(10) UNSIGNED DEFAULT NULL,
  `item_price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_delievery_date` date DEFAULT NULL,
  `purchase_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_to_subcon` smallint(5) UNSIGNED DEFAULT 0,
  `subcon_date_out` date DEFAULT NULL,
  `subcon_do_no` int(10) UNSIGNED DEFAULT NULL,
  `subcon_quantity` int(10) UNSIGNED DEFAULT NULL,
  `subcon_item_price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `subcon_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcon_est_delievery_date` date DEFAULT NULL,
  `subcon_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subcon_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_receiving_lists`
--

CREATE TABLE `job_order_receiving_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `date_in` date NOT NULL,
  `do_no` int(10) UNSIGNED DEFAULT NULL,
  `received_quantity` int(10) UNSIGNED DEFAULT NULL,
  `extra_less` int(10) UNSIGNED DEFAULT NULL,
  `balance` int(10) UNSIGNED DEFAULT NULL,
  `balance_received_as_well` smallint(5) UNSIGNED DEFAULT 0,
  `receiving_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_as_well` smallint(5) UNSIGNED DEFAULT 0,
  `receiving_remarks_s` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_to_reject` smallint(5) UNSIGNED DEFAULT 0,
  `reject_date_out` date DEFAULT NULL,
  `reject_memo_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_quantity` int(10) UNSIGNED DEFAULT NULL,
  `reject_est_delievery_date` date DEFAULT NULL,
  `reject_receive_as_well` smallint(5) UNSIGNED DEFAULT 0,
  `reject_cause` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_picture_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Driller', 'D1', '2021-10-10 13:23:21', '2021-10-10 13:23:21'),
(2, 'Long Cutter', 'LC1', '2021-10-10 13:23:29', '2021-10-10 13:23:29'),
(3, 'Color Bot', 'CB1', '2021-10-10 13:23:42', '2021-10-10 13:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Rubberwood', '2021-11-27 15:17:53', '2021-11-27 15:17:53');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2021_06_24_023114_create_roles_table', 1),
(5, '2021_06_24_024038_create_system_logs_table', 1),
(6, '2021_06_24_034417_add_role_id_users_table', 1),
(7, '2021_06_27_015509_create_sites_table', 1),
(8, '2021_06_27_015621_add_site_id_in_users_table', 1),
(9, '2021_07_03_203509_create_products_table', 1),
(10, '2021_07_03_203517_create_customers_table', 1),
(11, '2021_07_03_203525_create_job_orders_table', 1),
(12, '2021_07_06_005810_create_customer_emails_table', 1),
(13, '2021_07_06_005901_create_customer_phones_table', 1),
(14, '2021_07_06_010159_create_product_pictures_table', 1),
(15, '2021_07_06_012822_create_job_order_products_table', 1),
(16, '2021_07_11_204649_add_site_id_in_job_orders_table', 1),
(17, '2021_07_12_035417_add_created_by_in_job_orders_table', 1),
(18, '2021_07_22_014551_create_product_categories_table', 1),
(19, '2021_07_22_014600_add_category_colums_in_products_table', 1),
(20, '2021_07_24_012452_create_job_order_product_packing_pictures_table', 1),
(21, '2021_07_25_230153_create_product_units_table', 1),
(23, '2021_07_31_010552_create_product_bom_mappings_table', 2),
(24, '2021_07_31_062142_create_job_order_bom_lists_table', 3),
(25, '2021_07_31_213035_create_notifications_table', 4),
(26, '2021_07_31_215437_add_last_seen_column_in_users_table', 5),
(27, '2021_08_03_022127_create_product_stock_cards_table', 6),
(28, '2021_08_03_033406_create_job_order_purchase_lists_table', 6),
(29, '2021_08_11_101303_create_job_order_receiving_lists_table', 7),
(30, '2021_08_28_080818_create_site_locations_table', 8),
(31, '2021_08_28_080827_create_departments_table', 8),
(32, '2021_08_28_080836_create_machines_table', 8),
(33, '2021_08_28_080837_create_production_issued_items_table', 8),
(34, '2021_09_19_222229_create_product_stocks_table', 9),
(35, '2021_09_20_015833_create_daily_productions_table', 10),
(36, '2021_09_20_020212_create_daily_production_workers_table', 10),
(37, '2021_09_20_020219_create_daily_production_machines_table', 10),
(38, '2021_09_30_014716_create_daily_production_progresses', 11),
(40, '2021_10_06_020148_create_system_settings_table', 12),
(41, '2021_10_09_194154_create_daily_production_stock_cards_table', 13),
(42, '2021_10_09_213613_create_daily_production_chemicals_table', 14),
(43, '2021_10_10_214751_create_quality_assurances_table', 15),
(44, '2021_10_10_215003_create_quality_assurance_forms_table', 15),
(45, '2021_10_10_215008_create_quality_assurance_form_questions_table', 15),
(46, '2021_10_10_215012_create_quality_assurance_answers_table', 15),
(70, '2021_10_13_032825_create_shippings_table', 16),
(71, '2021_10_13_032947_create_shipping_left_overs_table', 16),
(72, '2021_10_13_033008_create_shipping_replacement_parts_table', 16),
(73, '2021_10_13_033643_create_shipping_stock_cards_table', 16),
(74, '2021_10_14_025233_create_shipping_items_table', 16),
(75, '2021_10_14_034432_create_shipping_progresses_table', 16),
(76, '2021_11_07_014200_create_notification_viewed_by_table', 17),
(77, '2021_11_07_015329_create_suppliers_table', 18),
(78, '2021_11_07_213143_create_product_packings_table', 19),
(79, '2021_11_07_213300_create_product_packing_pictures_table', 19),
(81, '2021_11_08_014112_create_inventory_audits_table', 20),
(84, '2021_11_27_195942_create_materials_table', 21),
(85, '2021_11_27_200027_create_colors_table', 21),
(86, '2021_11_28_013002_create_quality_assurance_pictures_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` smallint(6) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_viewed_by`
--

CREATE TABLE `notification_viewed_by` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_issued_items`
--

CREATE TABLE `production_issued_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_location_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `machine_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price_per_unit` decimal(10,4) UNSIGNED DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `length_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `height_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thick_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bomcategory_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `parent_id`, `price_per_unit`, `model_name`, `product_name`, `material`, `color_name`, `color_code`, `length`, `length_unit`, `width`, `width_unit`, `height`, `height_unit`, `thick`, `thick_unit`, `item_description`, `created_at`, `updated_at`, `category_id`, `subcategory_id`, `bomcategory_id`) VALUES
(1, NULL, NULL, 'A', 'TOP PANEL SET', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p>top panel of a table</p>', '2021-10-09 22:07:57', '2021-10-09 22:16:05', 2, 5, NULL),
(2, 1, NULL, 'A1', 'TOP PANEL CENTER', 'RUBBERWOOD', NULL, NULL, '800', 'Millimeter', '310', 'Millimeter', NULL, NULL, '18', 'Millimeter', '<p>testing top panel center</p>', '2021-10-09 22:09:02', '2021-10-09 22:26:30', 1, 5, NULL),
(3, 1, NULL, 'A2', 'TOP PANEL SIDE', 'RUBBERWOOD', NULL, NULL, '800', 'Millimeter', '445', 'Millimeter', NULL, NULL, '18', 'Millimeter', '<p>testing top panel side of table</p>', '2021-10-09 22:10:02', '2021-10-09 22:14:32', 1, 5, NULL),
(4, NULL, NULL, 'E', 'TABLE LEG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 22:10:43', '2021-10-09 22:16:18', 2, 5, NULL),
(5, 4, NULL, 'E1', 'LEG BEHIND', 'RUBBERWOOD', NULL, NULL, '555', 'Millimeter', '45', 'Millimeter', NULL, NULL, '20', 'Millimeter', NULL, '2021-10-09 22:12:05', '2021-10-09 22:14:54', 1, 5, NULL),
(6, 4, NULL, 'E2', 'LEG CENTER', 'RUBBERWOOD', NULL, NULL, '300', 'Millimeter', '45', 'Millimeter', NULL, NULL, '20', 'Millimeter', NULL, '2021-10-09 22:12:37', '2021-10-09 22:15:39', 1, 5, NULL),
(7, NULL, NULL, 'PRD', 'HINGES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 22:13:42', '2021-10-09 22:17:33', 1, 5, 3),
(8, NULL, NULL, 'PACK', 'BARREL NUT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 22:18:44', '2021-10-09 22:29:17', 1, 5, 3),
(9, NULL, '13.0000', 'SW 708 CRD', 'Table 2', 'Rubberwood', 'RED', 'R', '1100', 'Millimeter', '700', 'Millimeter', NULL, NULL, '660', 'Millimeter', '<p>testing table product update all data</p>', '2021-10-09 22:20:29', '2021-12-05 20:12:14', 2, 5, NULL),
(10, NULL, '13.0000', 'SW 708 CRD', 'Chair 2', 'Rubberwood', 'WHITE / NATURAL', 'lyc73274', '10', 'Millimeter', '20', 'Millimeter', NULL, NULL, '30', 'Millimeter', '<p>testing table product update all data</p>', '2021-10-09 22:24:23', '2021-12-05 20:12:14', 2, 5, NULL),
(11, NULL, NULL, 'A', 'CHAIR FRONT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p>CHAIR FRONT of a chair</p>', '2021-10-09 22:25:22', '2021-10-09 22:25:22', 2, 6, NULL),
(12, 11, NULL, 'A1', 'FRONT LEG TOP APON', 'RUBBERWOOD', NULL, NULL, '380', 'Millimeter', '58', 'Millimeter', NULL, NULL, '20', 'Millimeter', '<p>testing FRONT LEG TOP APON</p>', '2021-10-09 22:26:08', '2021-10-09 22:27:47', 1, 6, NULL),
(13, 11, NULL, 'A2', 'FRONT LEG 1', 'RUBBERWOOD', NULL, NULL, '745', 'Millimeter', '45', 'Millimeter', NULL, NULL, '22', 'Millimeter', '<p>testing FRONT LEG 1</p>', '2021-10-09 22:27:09', '2021-10-10 00:02:39', 1, 6, NULL),
(14, NULL, NULL, 'PRD', 'RIVET', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 22:29:37', '2021-10-09 22:29:37', 1, 6, 3),
(15, NULL, NULL, 'PRD', 'CHAIR HINGES', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-09 22:29:54', '2021-10-09 22:29:54', 1, 6, 3),
(16, NULL, '5.0000', 'NC WP-01', 'White Spray', 'Tangan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '<p>Tangan spray color in white</p>', '2021-10-10 14:01:13', '2021-10-15 23:29:14', 1, 7, NULL),
(17, NULL, NULL, 'SW 709 CRD', 'Testing Table', 'test material', 'red', 'RED', '11', 'Millimeter', '22', 'Millimeter', NULL, NULL, '33', 'Millimeter', '<p>testestesf d</p>', '2021-11-17 19:36:20', '2021-11-17 19:36:20', 2, 5, NULL),
(18, NULL, NULL, 'EC', 'EXTERNAL CARTON', 'Carton', NULL, NULL, '835', 'Millimeter', '380', 'Millimeter', NULL, NULL, '568', 'Millimeter', '<p>Its Packing Carton</p>', '2021-11-17 21:23:19', '2021-11-17 21:23:19', 1, 4, 4),
(19, NULL, '123.0000', 'test', 'test', 'Rubberwood', 'RED', 'R', '123', 'Millimeter', '123', 'Millimeter', NULL, NULL, '123', 'Millimeter', '<p>test</p>', '2021-11-27 15:39:43', '2021-11-27 15:39:43', 1, 2, NULL),
(20, NULL, NULL, 'sad', 'asd', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 11:22:34', '2021-12-05 11:22:34', NULL, NULL, NULL),
(21, NULL, NULL, 'asd', 'asd', NULL, 'RED', 'R', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 11:22:48', '2021-12-05 11:22:48', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_bom_mappings`
--

CREATE TABLE `product_bom_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `total_quantity` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Raw', 'RAW', '2021-10-09 22:05:04', '2021-10-09 22:05:04'),
(2, 'Furnished', 'FURNISHED', '2021-10-09 22:05:13', '2021-10-09 22:05:13'),
(3, 'Hardware', 'HARDWARE', '2021-10-09 22:05:20', '2021-10-09 22:05:20'),
(4, 'Packing Material', 'PACKING MATERIAL', '2021-10-09 22:05:28', '2021-10-09 22:05:46'),
(5, 'Table', 'Table', '2021-10-09 22:06:39', '2021-10-09 22:06:39'),
(6, 'Chair', 'CHAIR', '2021-10-09 22:06:44', '2021-10-09 22:06:44'),
(7, 'Chemicals', 'CHEMICALS', '2021-10-10 13:59:15', '2021-10-10 13:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_packings`
--

CREATE TABLE `product_packings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `packing_details` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_packing_pictures`
--

CREATE TABLE `product_packing_pictures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `picture_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_packing_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_pictures`
--

CREATE TABLE `product_pictures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `picture_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_cards`
--

CREATE TABLE `product_stock_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_quantity` int(10) UNSIGNED DEFAULT NULL,
  `available_quantity` int(10) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `date_in` date NOT NULL,
  `date_out` date DEFAULT NULL,
  `is_rejected` smallint(1) DEFAULT 0,
  `is_balance` smallint(1) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Millimeter', 'MM', '2021-07-25 06:33:01', '2021-07-25 06:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurances`
--

CREATE TABLE `quality_assurances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qa_type` smallint(6) NOT NULL COMMENT '1=IQC 2=IPQC 3=FQC',
  `qa_category` smallint(6) DEFAULT NULL COMMENT '1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON',
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `qa_by` bigint(20) UNSIGNED NOT NULL,
  `total_quantity` int(11) DEFAULT NULL,
  `sample_size` int(11) DEFAULT NULL,
  `total_defects_found_cr` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_found_mj` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_found_mn` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_cr` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_mj` decimal(4,2) UNSIGNED DEFAULT NULL,
  `total_defects_allowed_mn` decimal(4,2) UNSIGNED DEFAULT NULL,
  `product_picture_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` blob DEFAULT NULL,
  `comments` blob DEFAULT NULL,
  `is_ended` smallint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_answers`
--

CREATE TABLE `quality_assurance_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quality_assurance_id` bigint(20) UNSIGNED NOT NULL,
  `qa_form_question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` smallint(6) NOT NULL COMMENT '1=accepted 2=rejected 3=reworks 4=scrap 5=subcon',
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
  `form_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qa_type` smallint(6) NOT NULL COMMENT '1=IQC 2=IPQC 3=FQC',
  `qa_category` smallint(6) DEFAULT NULL COMMENT '1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON',
  `guide_std_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_assurance_forms`
--

INSERT INTO `quality_assurance_forms` (`id`, `form_name`, `description`, `qa_type`, `qa_category`, `guide_std_file`, `created_at`, `updated_at`) VALUES
(1, 'IQC', 'IQC Form for Raw Material', 1, 1, 'assets/images/general_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(2, 'IQC', 'IQC Form for Hardware', 1, 2, 'assets/images/general_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(3, 'IQC', 'IQC Form for Polyform', 1, 3, 'assets/images/general_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(4, 'IQC', 'IQC Form for Carton', 1, 4, 'assets/images/general_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(5, 'IPQC', 'IPQC Form', 2, NULL, 'assets/images/general_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(6, 'FQC', 'FQC Form 1', 3, NULL, 'assets/images/special_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27'),
(7, 'FQC', 'FQC Form 2', 3, NULL, 'assets/images/special_level_STD.png', '2021-10-10 09:17:27', '2021-10-10 09:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_form_questions`
--

CREATE TABLE `quality_assurance_form_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qa_form_id` bigint(20) UNSIGNED NOT NULL,
  `defect_category` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_remarks` smallint(6) DEFAULT 0 COMMENT '0=no 1=yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_assurance_form_questions`
--

INSERT INTO `quality_assurance_form_questions` (`id`, `qa_form_id`, `defect_category`, `question`, `is_remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'RAW MATERIAL', 'Product Size Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(2, 1, 'RAW MATERIAL', 'Product Quantity Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(3, 1, 'RAW MATERIAL', 'Product Grade same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(4, 1, 'RAW MATERIAL', 'Product Moisture Content tested by MC meter', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(5, 1, 'RAW MATERIAL', 'Product Surface Check/Crack', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(6, 1, 'RAW MATERIAL', 'Product Veneer Quality', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(7, 2, 'HARDWARE', 'Product Size Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(8, 2, 'HARDWARE', 'Product Quantity Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(9, 2, 'HARDWARE', 'Product Grade same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(10, 2, 'HARDWARE', 'Product Loose tested', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(11, 2, 'HARDWARE', 'Product Rust/Oxide', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(12, 2, 'HARDWARE', 'Product non-operating (folding/extension)', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(13, 3, 'POLYFOAM', 'Product Size Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(14, 3, 'POLYFOAM', 'Product Quantity Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(15, 3, 'POLYFOAM', 'Product Density same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(16, 3, 'POLYFOAM', 'Product Quality; Broken pieces', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(17, 3, 'POLYFOAM', 'Product Cleanliness', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(18, 4, 'CARTON', 'Product Size Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(19, 4, 'CARTON', 'Product Quantity Receive same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(20, 4, 'CARTON', 'Product Density same as PO', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(21, 4, 'CARTON', 'Product Marking & Label; Arrangement', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(22, 4, 'CARTON', 'Product Marking & Label; Spelling/Font/Color', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(23, 4, 'CARTON', 'Product Marking & Label; Barcode', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(24, 5, 'PRODUCT APPEARANCE REJECT', 'Sharp Edges', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(25, 5, 'PRODUCT APPEARANCE REJECT', 'Splinters', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(26, 5, 'PRODUCT APPEARANCE REJECT', 'Rough Surface', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(27, 5, 'PRODUCT APPEARANCE REJECT', 'Sanding Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(28, 5, 'PRODUCT APPEARANCE REJECT', 'Dents', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(29, 5, 'PRODUCT APPEARANCE REJECT', 'Scratches', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(30, 5, 'PRODUCT APPEARANCE REJECT', 'Color Variation', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(31, 5, 'PRODUCT APPEARANCE REJECT', 'Rust/Oxides formed at metal', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(32, 5, 'PRODUCT APPEARANCE REJECT', 'Burn Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(33, 5, 'PRODUCT APPEARANCE REJECT', 'Tool Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(34, 5, 'PRODUCT APPEARANCE REJECT', 'Touch-up Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(35, 5, 'PRODUCT APPEARANCE REJECT', 'Bug holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(36, 5, 'PRODUCT APPEARANCE REJECT', 'Pin holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(37, 5, 'PRODUCT APPEARANCE REJECT', 'Splits', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(38, 5, 'PRODUCT APPEARANCE REJECT', 'Surface check/Crack', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(39, 5, 'PRODUCT APPEARANCE REJECT', 'Component fracture/ Compression failure', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(40, 5, 'PRODUCT CONSTRUCTION REJECT', 'Component missing', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(41, 5, 'PRODUCT CONSTRUCTION REJECT', 'Component malformed', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(42, 5, 'PRODUCT CONSTRUCTION REJECT', 'Loose screw and bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(43, 5, 'PRODUCT CONSTRUCTION REJECT', 'Wrong screw or bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(44, 5, 'PRODUCT CONSTRUCTION REJECT', 'Faulty screw and bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(45, 5, 'PRODUCT CONSTRUCTION REJECT', 'Missing screw holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(46, 5, 'PRODUCT CONSTRUCTION REJECT', 'Screw holes not drilled through', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(47, 5, 'PRODUCT CONSTRUCTION REJECT', 'Loose components as a result of faulty joints', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(48, 5, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating folding mechanism', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(49, 5, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating extension mechanism', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(50, 5, 'PRODUCT CONSTRUCTION REJECT', 'Product wobble/ unstable', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(51, 5, 'PRODUCT CONSTRUCTION REJECT', 'Construction/ shape difference from sample', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(52, 5, 'PRODUCT CONSTRUCTION REJECT', 'Joints misaligned', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(53, 5, 'PRODUCT CONSTRUCTION REJECT', 'Wrong tenon and mortise size', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(54, 5, 'PRODUCT CONSTRUCTION REJECT', 'Faulty joints', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(55, 5, 'PRODUCT CONSTRUCTION REJECT', 'Uneven surface', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(56, 5, 'PRODUCT CONSTRUCTION REJECT', 'Chips', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(57, 6, 'PRODUCT APPEARANCE REJECT', 'Moisture Contents (8-12%)', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(58, 6, 'PRODUCT APPEARANCE REJECT', 'Sharp Edges', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(59, 6, 'PRODUCT APPEARANCE REJECT', 'Rough Surface', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(60, 6, 'PRODUCT APPEARANCE REJECT', 'Sanding Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(61, 6, 'PRODUCT APPEARANCE REJECT', 'Dents', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(62, 6, 'PRODUCT APPEARANCE REJECT', 'Chips', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(63, 6, 'PRODUCT APPEARANCE REJECT', 'Scratches', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(64, 6, 'PRODUCT APPEARANCE REJECT', 'Color Variation', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(65, 6, 'PRODUCT APPEARANCE REJECT', 'Rust/Oxides formed at metal', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(66, 6, 'PRODUCT APPEARANCE REJECT', 'Burn Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(67, 6, 'PRODUCT APPEARANCE REJECT', 'Tool Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(68, 6, 'PRODUCT APPEARANCE REJECT', 'Touch-up Mark', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(69, 6, 'PRODUCT APPEARANCE REJECT', 'Bug holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(70, 6, 'PRODUCT APPEARANCE REJECT', 'Pin holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(71, 6, 'PRODUCT APPEARANCE REJECT', 'Splits', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(72, 6, 'PRODUCT APPEARANCE REJECT', 'Surface check/Crack', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(73, 6, 'PRODUCT APPEARANCE REJECT', 'Component fracture/ Compression failure', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(74, 6, 'PRODUCT CONSTRUCTION REJECT', 'Component missing', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(75, 6, 'PRODUCT CONSTRUCTION REJECT', 'Component malformed', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(76, 6, 'PRODUCT CONSTRUCTION REJECT', 'Loose screw and bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(77, 6, 'PRODUCT CONSTRUCTION REJECT', 'Wrong screw or bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(78, 6, 'PRODUCT CONSTRUCTION REJECT', 'Faulty screw and bolts', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(79, 6, 'PRODUCT CONSTRUCTION REJECT', 'Missing screw holes', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(80, 6, 'PRODUCT CONSTRUCTION REJECT', 'Screw holes not drilled through', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(81, 6, 'PRODUCT CONSTRUCTION REJECT', 'Loose components as a result of faulty joints', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(82, 6, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating folding mechanism', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(83, 6, 'PRODUCT CONSTRUCTION REJECT', 'Non-operating extension mechanism', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(84, 6, 'PRODUCT CONSTRUCTION REJECT', 'Product wobble/ unstable', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(85, 6, 'PRODUCT CONSTRUCTION REJECT', 'Construction/ shape difference from sample', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(86, 6, 'PRODUCT CONSTRUCTION REJECT', 'Joints misaligned', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(87, 6, 'PRODUCT CONSTRUCTION REJECT', 'Wrong tenon and mortise size', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(88, 6, 'PRODUCT CONSTRUCTION REJECT', 'Faulty joints', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(89, 6, 'PRODUCT CONSTRUCTION REJECT', 'Uneven surface', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(90, 6, 'PRODUCT PACKAGING REJECT', 'Missing carton markings as per customers\' specification', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(91, 6, 'PRODUCT PACKAGING REJECT', 'Wrong spelling/ font/ color of the carton', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(92, 6, 'PRODUCT PACKAGING REJECT', 'Wrong carton box', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(93, 6, 'PRODUCT PACKAGING REJECT', 'Missing wrapping protection (If necessary)', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(94, 6, 'PRODUCT PACKAGING REJECT', 'Missing hang tag (if necessary)', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(95, 6, 'PRODUCT PACKAGING REJECT', 'Dirty hangtag/packing accessories', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(96, 6, 'PRODUCT PACKAGING REJECT', 'Non-compliance in packaging', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(97, 6, 'PRODUCT PACKAGING REJECT', 'Non-compliance in accessories', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(98, 6, 'PRODUCT PACKAGING REJECT', 'Accessories are nailed onto products for the wrong buyers', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(99, 6, 'PRODUCT PACKAGING REJECT', 'Missing/Non-compliant instruction sheet(If necessary)', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(100, 6, 'PRODUCT PACKAGING REJECT', 'Moisture/water damaged master carton box', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(101, 6, 'PRODUCT PACKAGING REJECT', 'Damaged carton box', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(102, 6, 'PRODUCT PACKAGING REJECT', 'Damaged/wrinkled color label on carton', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(103, 6, 'PRODUCT PACKAGING REJECT', 'Poor label printing', 0, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(104, 7, NULL, 'Arrival Container Condition', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(105, 7, NULL, 'Booking Number', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(106, 7, NULL, 'Seal Number', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(107, 7, NULL, 'Samples/ Extra 2% Fixings/ Replacement Loaded - Enter Details', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(108, 7, NULL, 'Quantity Products load - As Refer to Loading Lists', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13'),
(109, 7, NULL, 'Container Out Condition - As Per Arrival Condition', 1, '2021-10-10 09:19:13', '2021-10-10 09:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `quality_assurance_pictures`
--

CREATE TABLE `quality_assurance_pictures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quality_assurance_id` bigint(20) UNSIGNED NOT NULL,
  `picture_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '{\"permissions\": {\"users\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"roles\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"multi-site\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"site-locations\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"departments\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"suppliers\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"colors\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"materials\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"machines\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"job-orders\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\",\"manage-bom-list\":\"on\",\"manage-purchase-order\":\"on\",\"manage-receiving-order\":\"on\"},\"inventory\":{\"issue-for-production\":\"on\",\"audit\":\"on\"},\"stock-cards\":{\"view\":\"on\"},\"customers\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"products\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\",\"map-bom-list\":\"on\"},\"product-categories\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"product-units\":{\"add\":\"on\",\"edit\":\"on\",\"delete\":\"on\",\"view\":\"on\"},\"quality-assurance\":{\"perform-QA\":\"on\",\"view\":\"on\",\"reports\":\"on\"},\"daily-production\":{\"edit\":\"on\",\"manage\":\"on\",\"view\":\"on\"},\"shipping\":{\"create-shipment\":\"on\",\"progress-tracking\":\"on\"},\"costing\":{\"daily-production-report\":\"on\"},\"notifications\":{\"view\":\"on\"},\"logs\":{\"view\":\"on\"},\"system-settings\":{\"edit\":\"on\"}}}', '2021-07-25 03:18:28', '2021-11-27 15:10:32');

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `load_to` smallint(6) NOT NULL COMMENT '1=contena 2=lorry',
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `truck_out_date` date DEFAULT NULL,
  `qc_date` date DEFAULT NULL,
  `booking_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seal_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `do_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ended` smallint(6) DEFAULT 0 COMMENT '1=yes 0=no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_items`
--

CREATE TABLE `shipping_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_plan_qty` int(10) UNSIGNED DEFAULT NULL,
  `actual_loaded_qty` int(10) UNSIGNED DEFAULT NULL,
  `is_ended` smallint(5) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_left_overs`
--

CREATE TABLE `shipping_left_overs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_id` bigint(20) UNSIGNED NOT NULL,
  `po_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_progresses`
--

CREATE TABLE `shipping_progresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_item_id` bigint(20) UNSIGNED NOT NULL,
  `timer_type` smallint(6) DEFAULT 1 COMMENT '1=loading 2=break',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `difference_seconds` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_replacement_parts`
--

CREATE TABLE `shipping_replacement_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_stock_cards`
--

CREATE TABLE `shipping_stock_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipping_id` bigint(20) UNSIGNED NOT NULL,
  `stock_card_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sites`
--

INSERT INTO `sites` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Main Office', 'MO1', '2021-07-14 03:19:20', '2021-07-14 03:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `site_locations`
--

CREATE TABLE `site_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_locations`
--

INSERT INTO `site_locations` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(2, 'Loca1', 'LOC1', '2021-08-28 03:45:55', '2021-08-28 03:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Khan Supplies', '2021-11-06 20:59:58', '2021-11-06 21:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `action_on` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, '08:00:00', '17:00:00', '17:01:00', '07:59:00', '2021-10-07 17:37:28', '2021-11-17 22:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `basic_salary` decimal(10,4) DEFAULT NULL,
  `overtime_salary` decimal(10,4) DEFAULT NULL,
  `last_seen_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `site_id`, `basic_salary`, `overtime_salary`, `last_seen_at`) VALUES
(1, 'Admin', 'admin@test.com', '2021-07-25 03:18:41', '$2y$10$q/fspxufKpV1FjANpk9BkeaxOcx7ZOaLuTcZBFIvgfWil3aug4PcC', '51UtIPFvHgnspmvreoZ9NPJu97aAmzNLbeXm8MTw3IjyKTQ35o2oANfclAdz', '2021-07-25 03:18:57', '2021-12-05 16:31:16', 1, 1, '123.2324', '542.3300', '2021-12-05 21:31:16');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_emails`
--
ALTER TABLE `customer_emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_phones`
--
ALTER TABLE `customer_phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_productions`
--
ALTER TABLE `daily_productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_chemicals`
--
ALTER TABLE `daily_production_chemicals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_machines`
--
ALTER TABLE `daily_production_machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_progresses`
--
ALTER TABLE `daily_production_progresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_stock_cards`
--
ALTER TABLE `daily_production_stock_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_production_workers`
--
ALTER TABLE `daily_production_workers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_audits`
--
ALTER TABLE `inventory_audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_orders`
--
ALTER TABLE `job_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_bom_lists`
--
ALTER TABLE `job_order_bom_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_products`
--
ALTER TABLE `job_order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_product_packing_pictures`
--
ALTER TABLE `job_order_product_packing_pictures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_purchase_lists`
--
ALTER TABLE `job_order_purchase_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_receiving_lists`
--
ALTER TABLE `job_order_receiving_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_viewed_by`
--
ALTER TABLE `notification_viewed_by`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_issued_items`
--
ALTER TABLE `production_issued_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `product_bom_mappings`
--
ALTER TABLE `product_bom_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_packings`
--
ALTER TABLE `product_packings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_packing_pictures`
--
ALTER TABLE `product_packing_pictures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_pictures`
--
ALTER TABLE `product_pictures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stock_cards`
--
ALTER TABLE `product_stock_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quality_assurances`
--
ALTER TABLE `quality_assurances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_assurance_answers`
--
ALTER TABLE `quality_assurance_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quality_assurance_forms`
--
ALTER TABLE `quality_assurance_forms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quality_assurance_form_questions`
--
ALTER TABLE `quality_assurance_form_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `quality_assurance_pictures`
--
ALTER TABLE `quality_assurance_pictures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_items`
--
ALTER TABLE `shipping_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_left_overs`
--
ALTER TABLE `shipping_left_overs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_progresses`
--
ALTER TABLE `shipping_progresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_replacement_parts`
--
ALTER TABLE `shipping_replacement_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_stock_cards`
--
ALTER TABLE `shipping_stock_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_locations`
--
ALTER TABLE `site_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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