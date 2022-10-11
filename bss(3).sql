-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 09, 2022 at 05:40 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bss`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Krish S', 'admin@gmail.com', NULL, '$2y$10$g2aQgPv05ZNq9EHDqM2jDexrvnZsBS8hUxBlCQG9NI.Rns2.9V/Ty', NULL, 1, '2021-11-22 09:44:37', '2021-11-22 09:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE IF NOT EXISTS `answers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `correct_answer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answers_question_id_foreign` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `name`, `status`, `created_at`, `updated_at`, `correct_answer`) VALUES
(14, 1, 'ggg', 1, '2022-05-08 05:00:58', '2022-05-08 05:57:24', 0),
(16, 2, 'fgdfgdf', 1, '2022-05-08 05:01:53', '2022-05-08 05:01:53', NULL),
(13, 1, 'nswerrrrr', 1, '2022-05-08 05:00:58', '2022-05-08 05:57:24', 1),
(17, 3, 'dfgdf\r\ndsf', 1, '2022-05-08 06:05:27', '2022-05-08 06:05:27', NULL),
(18, 3, 'fsdfsdf', 1, '2022-05-08 06:05:27', '2022-05-08 06:05:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_identifier` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `duration` int(11) DEFAULT NULL,
  `amount` decimal(15,4) DEFAULT NULL,
  `certification` tinyint(1) NOT NULL DEFAULT '1',
  `other_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `courses_topic_id_foreign` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `topic_id`, `name`, `course_identifier`, `meta_title`, `meta_description`, `duration`, `amount`, `certification`, `other_information`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'IT', 'aaa', NULL, NULL, 60, '200.0000', 1, NULL, 1, '2022-02-05 08:06:08', '2022-05-07 12:14:32'),
(2, 2, 'course 2', '123', NULL, NULL, 3, '150.0000', 1, NULL, 1, '2022-05-07 08:05:48', '2022-05-07 12:50:36'),
(3, 1, 'course 3', 'bbb', NULL, NULL, 11, '20.0000', 1, NULL, 1, '2022-05-07 12:10:09', '2022-05-07 12:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_identity` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_course_id_foreign` (`course_id`),
  KEY `documents_topic_id_foreign` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `topic_id`, `course_id`, `name`, `file_path`, `file_name`, `file_identity`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Pdf One', 'bss/documents/9XHb3bc2z3bgBy1fBCts3rybqu2Xkss5pOrP1u68.pdf', 'Social_Abacus_Report (14).pdf', '0205202213371061fe7d86e3d76', 0, '2022-02-05 08:07:10', '2022-05-08 04:30:28'),
(2, 1, 3, 'ghjhgj', 'bss/documents/pKZn3hUNSJzqrcTTagCqxkK2G7M0MM4co8VzYJNJ.pdf', 'Statement_1634896137872.pdf', '050820220959526277949810aae', 1, '2022-05-08 04:29:52', '2022-05-08 04:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `amount` decimal(15,4) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `expiry_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enrollments_user_id_foreign` (`user_id`),
  KEY `enrollments_course_id_foreign` (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lessons_syllabus_id_foreign` (`syllabus_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `syllabus_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(9, 1, 'Lesson 2', 1, '2022-05-07 09:34:09', '2022-05-07 09:34:09'),
(8, 1, 'Lesson 1', 1, '2022-05-07 09:34:09', '2022-05-07 09:34:09'),
(10, 2, 'sdf', 1, '2022-05-07 09:39:36', '2022-05-07 09:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_02_26_202226_create_permission_tables', 1),
(6, '2021_03_10_103429_create_topics_table', 1),
(7, '2021_03_17_153213_create_admins_table', 1),
(8, '2021_03_23_141201_create_enrollments_table', 1),
(9, '2021_03_26_181153_create_videos_table', 1),
(10, '2021_03_27_213302_alter_users_table', 1),
(11, '2021_03_30_025628_create_lessons_table', 1),
(14, '2021_03_30_103124_create_questions_table', 2),
(13, '2021_04_02_174411_alter_videos_table', 1),
(15, '2022_05_07_144639_alter_syllabus_v1_table', 3),
(16, '2022_05_07_173647_alter_courses_v1_table', 4),
(17, '2022_05_08_083210_alter_documents_v1_table', 5),
(18, '2022_05_08_101349_alter_videos_v1_table', 6),
(19, '2022_05_08_101738_alter_questions_v1_table', 7),
(22, '2022_05_09_073058_create_question_user_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role-list', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(2, 'role-create', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(3, 'role-edit', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(4, 'role-delete', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(5, 'admin-list', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(6, 'admin-create', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(7, 'admin-edit', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(8, 'admin-delete', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(9, 'user-list', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(10, 'user-create', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(11, 'user-edit', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(12, 'user-delete', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(13, 'topic-list', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(14, 'topic-create', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(15, 'topic-edit', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(16, 'topic-delete', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(17, 'course-list', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(18, 'course-create', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(19, 'course-edit', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30'),
(20, 'course-delete', 'admin', '2021-11-22 09:44:30', '2021-11-22 09:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'android', 'ff539671c61a443b4c410b5bede625bbab4b4543867a09a3cfbbcdbe95468a09', '[\"*\"]', NULL, '2022-05-09 06:32:55', '2022-05-09 06:32:55'),
(2, 'App\\Models\\User', 1, 'android', '05e76b90e270b1d108817c98af9d661a002d55df4886fecf6f37a5eaab9bcd31', '[\"*\"]', '2022-05-09 12:20:27', '2022-05-09 06:33:13', '2022-05-09 12:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_course_id_foreign` (`course_id`),
  KEY `questions_topic_id_foreign` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `topic_id`, `course_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'ccccdd', 1, '2022-02-28 12:25:18', '2022-05-08 05:00:58'),
(2, 2, 2, 'ddddcc', 0, '2022-02-28 12:25:18', '2022-05-08 05:01:53'),
(3, 1, 3, 'sdfsdfsdf sdf\r\ndsf', 1, '2022-05-08 06:00:58', '2022-05-08 06:00:58'),
(4, 1, 1, 'fgh', 1, '2022-05-08 06:08:13', '2022-05-08 06:08:13');

-- --------------------------------------------------------

--
-- Table structure for table `question_user`
--

DROP TABLE IF EXISTS `question_user`;
CREATE TABLE IF NOT EXISTS `question_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `date_added` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `question_user_user_id_foreign` (`user_id`),
  KEY `question_user_question_id_foreign` (`question_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_user`
--

INSERT INTO `question_user` (`user_id`, `question_id`, `course_id`, `answer_id`, `enrollment_id`, `date_added`) VALUES
(1, 3, 3, 0, 0, ''),
(1, 1, 3, 2, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-master-admin', 'admin', '2021-11-22 09:44:37', '2021-11-22 09:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `syllabi`
--

DROP TABLE IF EXISTS `syllabi`;
CREATE TABLE IF NOT EXISTS `syllabi` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `syllabi_course_id_foreign` (`course_id`),
  KEY `syllabi_topic_id_foreign` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `syllabi`
--

INSERT INTO `syllabi` (`id`, `topic_id`, `course_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Introduction', 1, '2022-02-05 08:06:47', '2022-05-07 09:34:09'),
(2, 1, 1, 'dfs', 1, '2022-05-07 09:22:44', '2022-05-07 09:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `name`, `meta_title`, `meta_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'computer science', NULL, NULL, 1, '2022-02-05 08:05:43', '2022-02-05 08:05:43'),
(2, 'topic 2', NULL, NULL, 1, '2022-05-07 08:01:59', '2022-05-07 08:01:59');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `syllabus_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `units_syllabus_id_foreign` (`syllabus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_of_passing` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile_number`, `address`, `city`, `postcode`, `degree`, `department`, `year_of_passing`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'cust', 'customer@gmail.com', '123123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$DnXUloZsu6rwYSe2gKxUbe9NRDrckYbXuw9/G2Z9FD6sVYYh2BRdK', NULL, 1, '2022-05-09 06:32:50', '2022-05-09 06:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_identity` varchar(125) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `videos_course_id_foreign` (`course_id`),
  KEY `videos_topic_id_foreign` (`topic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `topic_id`, `course_id`, `name`, `file_path`, `file_name`, `file_identity`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'ddd', 'bss/videos/ki02tpnwo6PdLaKJx2uIdowhl1oEqAVVyI6UCSkG.mp4', 'file_example_MP4_480_1_5MG.mp4', '050820221014386277980e9c7e4', 1, '2022-05-08 04:44:38', '2022-05-08 04:44:38');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
