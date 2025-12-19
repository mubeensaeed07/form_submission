-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 09:06 AM
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
-- Database: `form_submission`
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
-- Table structure for table `facebook_users`
--

CREATE TABLE `facebook_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facebook_url` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `generated_url` varchar(255) DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `created_by_role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_users`
--

INSERT INTO `facebook_users` (`id`, `facebook_url`, `full_name`, `generated_url`, `created_by_id`, `created_by_role`, `created_at`, `updated_at`) VALUES
(1, 'https://www.facebook.com/yaar.mani.791663', 'mani', 'http://127.0.0.1:8000/apply?ref=1&fb=1', 1, 'admin', '2025-12-18 03:43:24', '2025-12-18 03:43:24');

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
-- Table structure for table `form_submissions`
--

CREATE TABLE `form_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `household_size` tinyint(3) UNSIGNED DEFAULT NULL,
  `dependents` tinyint(3) UNSIGNED DEFAULT NULL,
  `family_members` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`family_members`)),
  `employment_status` varchar(255) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `monthly_income` int(10) UNSIGNED DEFAULT NULL,
  `assistance_amount` int(10) UNSIGNED DEFAULT NULL,
  `assistance_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assistance_types`)),
  `assistance_description` text DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `consent` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_by_role` varchar(255) DEFAULT NULL,
  `facebook_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_submissions`
--

INSERT INTO `form_submissions` (`id`, `first_name`, `last_name`, `email`, `phone`, `dob`, `household_size`, `dependents`, `family_members`, `employment_status`, `employer_name`, `monthly_income`, `assistance_amount`, `assistance_types`, `assistance_description`, `ssn`, `street`, `city`, `state`, `zip`, `consent`, `submitted_by_id`, `submitted_by_role`, `facebook_user_id`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Mubeen', 'Qureshi', 'saeedmubeen20@gmail.com', '03327319593', '2025-12-03', 5, 4, '[{\"name\":\"saeed\",\"age\":\"67\",\"relationship\":\"father\"},{\"name\":\"jojo\",\"age\":\"30\",\"relationship\":\"sister\"},{\"name\":\"fygf\",\"age\":\"52\",\"relationship\":\"sister\"}]', 'Employed', 'mubeen', 100, 2000, '[\"Housing Expenses\",\"Utility Bills\",\"Medical Expenses\",\"Food & Essentials\"]', 'paisa bht hai lekin pta ni kahan hai', '123-45-6789', 'House 190 Block D Wapda Colony taru jabba', 'Peshawer', 'kpk', '24310', 1, NULL, NULL, NULL, '2025-12-15 05:32:24', '2025-12-15 05:32:24'),
(2, 'yaar', 'mani', 'mani@gmail.com', '03327319593', '2025-12-12', 2, 1, '[{\"name\":\"jj\",\"age\":\"5\",\"relationship\":\"brother\"},{\"name\":\"kk\",\"age\":\"29\",\"relationship\":\"father\"}]', 'Employed', 'moni', 300, 4900, '[\"Housing Expenses\",\"Utility Bills\",\"Medical Expenses\",\"Food & Essentials\"]', 'bht ghareeb hain', '123-456-789', 'House 190 Block D Wapda Colony taru jabba', 'Peshawer', 'kpk', '24310', 1, 1, 'admin', 1, '2025-12-18 03:46:05', '2025-12-18 03:46:05'),
(3, 'kkkk', 'lll', 'kl@gmail.com', '03149039599', '2025-12-02', 3, 1, '[{\"name\":\"ki\",\"age\":\"55\",\"relationship\":\"mom\"}]', 'Unemployed', 'ol', 200, 5400, '[\"Housing Expenses\",\"Utility Bills\",\"Education Fees\",\"Medical Expenses\",\"Food & Essentials\"]', 'dooooo', '123-456-789', 'jhdhd', 'karachi', 'sindh', NULL, 1, 2, 'agent', NULL, '2025-12-19 00:31:13', '2025-12-19 00:31:13');

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
(4, '2025_12_15_000000_create_form_submissions_table', 1),
(5, '2025_12_15_000001_add_role_and_status_to_users_table', 2),
(6, '2025_12_15_120722_add_submitted_by_to_form_submissions_table', 3),
(7, '2025_12_18_083256_create_facebook_users_table', 4),
(8, '2025_12_18_083305_add_facebook_user_id_to_form_submissions_table', 4);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Ni4x3o79UDoPkJ9cVfYQaCMzHwsuijNoHwTsyQf7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSXQ4Y1ZDT051eFExdFdKYXdBRWc0Y3JtZG4xUjAwVmlaTWhMWG1uWSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1766131314),
('RnoOAlj6Wsm0fz9urUa6wvOydY23rVZGvSoeCefN', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVo4UmhGNkpyMG5sYXI3RUtyN0dHRkNYa3ZQY0xCT0c1eWVoS0V1QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZ2VudC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFnZW50LmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1766131507);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'agent',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'invited',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', 'saeedmubeen20@gmail.com', 'active', NULL, '$2y$12$GKIXuUs1A1TpyfuzuS6mQ.BmCYUfNRqMYvlu4JDL7ugihwJ0zfbCq', NULL, '2025-12-15 05:06:42', '2025-12-15 06:02:47'),
(2, 'agent', 'mani', 'mani@gmail.com', 'active', NULL, '$2y$12$4sst5xUTs0RMN0Pdod6zxepBlQ4lOKugbuqtQ08flEVdmTJ18J/Qm', NULL, '2025-12-15 06:08:18', '2025-12-19 00:22:39'),
(3, 'agent', 'mani', 'lilhexxi7@gmail.com', 'invited', NULL, '$2y$12$o56TMhgoiCHZln/In18XFOKJN4E1Nv7yovTwfcMbuPh7PxkMHPupa', NULL, '2025-12-15 06:09:01', '2025-12-15 06:09:01'),
(4, 'agent', 'kaku', 'kaku@gmail.com', 'active', NULL, '$2y$12$ybX/Q8LXx9YQfYx7LdcdJOvt41lv7eIuwMlXMSu6sMR2BkGDuH42q', NULL, '2025-12-15 06:09:31', '2025-12-19 03:04:50');

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
-- Indexes for table `facebook_users`
--
ALTER TABLE `facebook_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_users_facebook_url_unique` (`facebook_url`),
  ADD KEY `facebook_users_created_by_id_index` (`created_by_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_submissions_facebook_user_id_foreign` (`facebook_user_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for table `facebook_users`
--
ALTER TABLE `facebook_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `form_submissions`
--
ALTER TABLE `form_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD CONSTRAINT `form_submissions_facebook_user_id_foreign` FOREIGN KEY (`facebook_user_id`) REFERENCES `facebook_users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
