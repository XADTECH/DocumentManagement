-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 28, 2025 at 07:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `documentmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint UNSIGNED NOT NULL,
  `document_id` int UNSIGNED NOT NULL,
  `approved_by` int UNSIGNED NOT NULL,
  `status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Account Department', '2024-12-12 04:16:06', '2024-12-12 04:32:22'),
(4, 'HR Department', '2024-12-12 04:32:09', '2024-12-12 04:32:09'),
(5, 'Project Management', '2024-12-30 13:36:09', '2024-12-30 13:36:09'),
(6, 'Fleet Management', '2024-12-30 13:39:29', '2024-12-30 13:39:29'),
(7, 'Training and Development', '2024-12-30 13:42:24', '2024-12-30 13:42:24'),
(8, 'Finance & Accounts', '2024-12-30 13:45:13', '2024-12-30 13:45:13'),
(9, 'Cash & LPO', '2024-12-30 13:51:30', '2024-12-30 13:51:30'),
(10, 'Contract Department', '2024-12-30 13:57:28', '2024-12-30 13:57:28'),
(11, 'Admin Department', '2024-12-30 14:01:56', '2024-12-30 14:01:56'),
(12, 'CEO', '2024-12-30 14:04:29', '2024-12-30 14:04:29'),
(13, 'Secretary', '2024-12-31 04:15:34', '2024-12-31 04:15:34');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_paths` json NOT NULL,
  `department_id` int UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED DEFAULT NULL,
  `document_type_id` int UNSIGNED NOT NULL,
  `uploaded_by` int UNSIGNED NOT NULL,
  `ceo_approval` tinyint(1) NOT NULL DEFAULT '0',
  `approval_status` enum('Pending','Approved','Rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `name`, `unique_id`, `remarks`, `file_paths`, `department_id`, `subcategory_id`, `document_type_id`, `uploaded_by`, `ceo_approval`, `approval_status`, `created_at`, `updated_at`) VALUES
(27, 'Invoice for Splash Co LTD', 'DOC97450', 'document is rejected by CEO', '\"[\\\"public\\\\/documents\\\\/Account Department\\\\/Cashier\\\\/Invoice\\\\/HR10-ECF-2024 ( Employee clearance form) (1).pdf\\\"]\"', 2, 1, 1, 44, 0, 'Rejected', '2025-01-15 10:37:07', '2025-01-15 11:02:59'),
(29, 'secretarial approval', 'DOC67914', NULL, '\"[\\\"public\\\\/documents\\\\/Secretary\\\\/General Documents\\\\/Documents\\\\/invoice.jpg\\\"]\"', 13, 35, 13, 43, 1, 'Pending', '2025-01-15 11:10:12', '2025-01-15 11:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `name`, `department_id`, `subcategory_id`, `created_at`, `updated_at`) VALUES
(1, 'Invoice', 2, 1, '2024-12-30 08:25:17', '2024-12-30 08:25:17'),
(2, 'Receipts', 2, 1, '2024-12-30 08:31:30', '2024-12-30 08:31:30'),
(3, 'Vouchers', 2, 1, '2024-12-30 08:33:26', '2024-12-30 08:33:26'),
(6, 'Notes', 2, 1, '2024-12-30 13:31:40', '2024-12-30 13:32:48'),
(9, 'Documents', 2, 34, '2025-01-14 16:19:06', '2025-01-14 16:19:06'),
(11, 'Documents', 4, 20, '2025-01-14 16:23:36', '2025-01-14 16:23:36'),
(12, 'Documents', 5, 29, '2025-01-14 16:24:29', '2025-01-14 16:24:29'),
(13, 'Documents', 13, 35, '2025-01-15 08:31:03', '2025-01-15 08:31:03');

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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_12_02_103640_create_departments_table', 1),
(7, '2024_12_02_114523_create_approvals_table', 1),
(8, '2024_12_02_114603_create_documents_table', 1),
(9, '2024_12_02_103641_create_departments_table', 2),
(10, '2024_12_02_114524_create_approvals_table', 2),
(11, '2024_12_02_114607_create_documents_table', 2),
(12, '2024_12_05_082508_create_categories_table', 2),
(13, '2024_12_05_090546_create_subcategories_table', 2),
(14, '2024_12_04_090546_create_subcategories_table', 3),
(15, '2024_12_04_090548_create_subcategories_table', 4),
(16, '2024_12_30_113525_create_document_types_table', 5),
(17, '2024_12_30_113526_create_document_types_table', 6),
(18, '2024_12_02_114608_create_documents_table', 7),
(19, '2024_12_02_114609_create_documents_table', 8),
(20, '2025_01_14_202241_remove_unique_constraint_from_document_types_name', 9),
(21, '2025_01_15_142323_add_unique_id_to_documents_table', 10);

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
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'Cashier', 2, '2024-12-12 04:59:30', '2024-12-12 04:59:30'),
(4, 'Taxation', 2, '2025-01-06 02:51:18', '2025-01-06 02:51:18'),
(5, 'Audit', 2, '2025-01-06 02:52:25', '2025-01-06 02:52:25'),
(6, 'Payroll', 2, '2025-01-06 02:52:35', '2025-01-06 02:52:35'),
(7, 'Budget', 2, '2025-01-06 02:53:07', '2025-01-06 02:53:07'),
(8, 'Credit Controll', 2, '2025-01-06 02:53:19', '2025-01-06 02:53:19'),
(9, 'Assets', 2, '2025-01-06 02:53:50', '2025-01-06 02:53:50'),
(10, 'Compliance', 2, '2025-01-06 02:54:00', '2025-01-06 02:54:00'),
(11, 'Procurement', 2, '2025-01-06 02:54:45', '2025-01-06 02:54:45'),
(12, 'Recruitment', 4, '2025-01-08 14:38:05', '2025-01-08 14:38:05'),
(13, 'Talent Acquisition', 4, '2025-01-08 14:38:19', '2025-01-08 14:38:19'),
(14, 'Learning And Development', 4, '2025-01-08 14:38:46', '2025-01-08 14:38:46'),
(15, 'Compensation And Benefits', 4, '2025-01-08 14:39:10', '2025-01-08 14:39:10'),
(16, 'Talent Management', 4, '2025-01-08 14:39:27', '2025-01-08 14:39:27'),
(17, 'Performance Management', 4, '2025-01-08 14:39:46', '2025-01-08 14:39:46'),
(18, 'Health And Safety', 4, '2025-01-08 14:40:15', '2025-01-08 14:40:15'),
(19, 'Policy Management', 4, '2025-01-08 14:40:35', '2025-01-08 14:40:35'),
(20, 'General Documents', 4, '2025-01-08 14:40:57', '2025-01-08 14:40:57'),
(21, 'Project Planning And Scheduling', 5, '2025-01-08 14:41:46', '2025-01-08 14:41:46'),
(22, 'Risk Management', 5, '2025-01-08 14:42:04', '2025-01-08 14:42:04'),
(23, 'Cost Management', 5, '2025-01-08 14:42:34', '2025-01-08 14:42:34'),
(24, 'Quality Management', 5, '2025-01-08 14:42:49', '2025-01-08 14:42:49'),
(25, 'Stakeholder Management', 5, '2025-01-08 14:43:06', '2025-01-08 14:43:06'),
(26, 'Resource Management', 5, '2025-01-08 14:43:19', '2025-01-08 14:43:19'),
(27, 'Procurement Management', 5, '2025-01-08 14:43:53', '2025-01-08 14:43:53'),
(28, 'Health And Safety', 5, '2025-01-08 14:50:12', '2025-01-08 14:50:12'),
(29, 'General Documents', 5, '2025-01-08 14:50:44', '2025-01-14 16:27:49'),
(30, 'Vehicle Maintenance and Repairs', 6, '2025-01-08 14:52:35', '2025-01-08 14:52:35'),
(31, 'Fleet Operation', 6, '2025-01-08 14:52:55', '2025-01-08 14:52:55'),
(32, 'Fuel Management', 6, '2025-01-08 14:53:17', '2025-01-08 14:53:17'),
(33, 'Driver Management', 6, '2025-01-08 14:53:34', '2025-01-08 14:53:34'),
(34, 'General Documents', 2, '2025-01-14 16:05:41', '2025-01-15 08:29:41'),
(35, 'General Documents', 13, '2025-01-15 08:30:45', '2025-01-15 08:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `xad_id` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `profile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `xad_id`, `first_name`, `last_name`, `email`, `nationality`, `organization_unit`, `phone_number`, `password`, `role`, `permissions`, `profile_image`, `created_at`, `updated_at`) VALUES
(33, NULL, 'xad', 'tech', 'admin@xadtech.com', 'pak', 'admin', '0521077862', '$2y$12$WHWpYX5rpA3oSZYQYx.T6emR.1A.C2XfICThPWxCEvzfSistqapBW', 'Admin', '\"[\\\"Project Management\\\",\\\"Cash Flow Management\\\",\\\"Bank Management\\\",\\\"User Management\\\"]\"', '172499948757.jfif', '2024-08-30 02:31:28', '2024-08-30 02:31:28'),
(43, 'PT-001', 'sahar', 'sahar', 'secretary@xadtech.com', 'PK', 'Secretary', '123456789', '$2y$10$J2ig7lAYuTNLe0mhuldtdOTCt9ie06P8JSKedMz2d3ijNjcg4tLx6', 'Secretary', NULL, '173563596893.jpg', '2024-12-31 05:06:08', '2025-01-15 10:30:40'),
(44, 'XAD-456', 'nabeel', 'javed', 'nabeel@xadtech.com', 'PK', 'Account Department', '0521077862', '$2y$10$Q6U1N5LDQjyQLUqOzgR/GujP67Gp.p/9TAeozjWDULdWnghVyQ/mq', 'Account Department', NULL, '', '2025-01-04 04:05:58', '2025-01-12 19:47:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_unique_id_unique` (`unique_id`);

--
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_types_department_id_foreign` (`department_id`),
  ADD KEY `document_types_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_types`
--
ALTER TABLE `document_types`
  ADD CONSTRAINT `document_types_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_types_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
