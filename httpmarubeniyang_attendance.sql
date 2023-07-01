-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 01, 2023 at 07:01 AM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `httpmarubeniyang_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `annual_ot_summaries`
--

DROP TABLE IF EXISTS `annual_ot_summaries`;
CREATE TABLE IF NOT EXISTS `annual_ot_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `april` double(8,2) NOT NULL DEFAULT '0.00',
  `may` double(8,2) NOT NULL DEFAULT '0.00',
  `june` double(8,2) NOT NULL DEFAULT '0.00',
  `july` double(8,2) NOT NULL DEFAULT '0.00',
  `august` double(8,2) NOT NULL DEFAULT '0.00',
  `september` double(8,2) NOT NULL DEFAULT '0.00',
  `october` double(8,2) NOT NULL DEFAULT '0.00',
  `november` double(8,2) NOT NULL DEFAULT '0.00',
  `december` double(8,2) NOT NULL DEFAULT '0.00',
  `january` double(8,2) NOT NULL DEFAULT '0.00',
  `february` double(8,2) NOT NULL DEFAULT '0.00',
  `march` double(8,2) NOT NULL DEFAULT '0.00',
  `year` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_serial` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `leave_form_id` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `manual_remark` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corrected_start_time` time NOT NULL,
  `corrected_end_time` time NOT NULL,
  `normal_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `sat_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `sunday_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `public_holiday_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `change_request_date` datetime DEFAULT NULL,
  `change_approve_date` datetime DEFAULT NULL,
  `change_approve_by` int(11) DEFAULT NULL,
  `ot_request_date` datetime DEFAULT NULL,
  `ot_approve_date` datetime DEFAULT NULL,
  `ot_approve_by` int(11) DEFAULT NULL,
  `hotel` tinyint(4) NOT NULL DEFAULT '0',
  `next_day` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `monthly_request` tinyint(4) NOT NULL DEFAULT '0',
  `monthly_request_id` int(11) NOT NULL DEFAULT '0',
  `morning_ot` tinyint(4) NOT NULL DEFAULT '0',
  `evening_ot` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_logs`
--

DROP TABLE IF EXISTS `attendance_logs`;
CREATE TABLE IF NOT EXISTS `attendance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `device` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_serial` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `manual_remark` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corrected_start_time` time NOT NULL,
  `corrected_end_time` time NOT NULL,
  `normal_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `sat_ot_hr` float NOT NULL DEFAULT '0',
  `sunday_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `public_holiday_ot_hr` double(8,2) NOT NULL DEFAULT '0.00',
  `change_request_date` date DEFAULT NULL,
  `change_approve_date` date DEFAULT NULL,
  `change_approve_by` int(11) DEFAULT NULL,
  `ot_request_date` date DEFAULT NULL,
  `ot_approve_date` date DEFAULT NULL,
  `ot_approve_by` int(11) DEFAULT NULL,
  `hotel` tinyint(4) NOT NULL DEFAULT '0',
  `next_day` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `monthly_request` tinyint(4) NOT NULL DEFAULT '0',
  `monthly_request_id` int(11) NOT NULL DEFAULT '0',
  `attendance_created_date` datetime NOT NULL,
  `morning_ot` tinyint(4) NOT NULL DEFAULT '0',
  `evening_ot` tinyint(4) NOT NULL DEFAULT '0',
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_temps`
--

DROP TABLE IF EXISTS `attendance_temps`;
CREATE TABLE IF NOT EXISTS `attendance_temps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `change_request_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `time_in` tinyint(4) NOT NULL DEFAULT '0',
  `time_out` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'AYA', 1, 1, 1, '2022-12-26 01:56:12', '2022-12-26 01:56:12'),
(2, 'KBZ', 1, 1, 1, '2022-12-26 01:56:12', '2022-12-26 01:56:12'),
(3, 'CB', 1, 22, 22, '2023-03-27 18:19:59', '2023-03-27 18:19:59');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_assign_histories`
--

DROP TABLE IF EXISTS `branch_assign_histories`;
CREATE TABLE IF NOT EXISTS `branch_assign_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dept_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `car_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `car_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `model_year` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chasis_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parking` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_user` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_drivers`
--

DROP TABLE IF EXISTS `car_drivers`;
CREATE TABLE IF NOT EXISTS `car_drivers` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `car_fuelings`
--

DROP TABLE IF EXISTS `car_fuelings`;
CREATE TABLE IF NOT EXISTS `car_fuelings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `liter` double(8,2) NOT NULL,
  `rate` int(11) NOT NULL,
  `totalRate` int(11) NOT NULL,
  `current_meter` double(8,2) NOT NULL,
  `mileage_difference` double(8,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `driver` int(11) NOT NULL COMMENT 'user_id',
  `status` enum('pending','reject','accept') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_insurances`
--

DROP TABLE IF EXISTS `car_insurances`;
CREATE TABLE IF NOT EXISTS `car_insurances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `insurance_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `car_id` int(11) NOT NULL,
  `insurance_company` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `premium_amount` double(8,2) NOT NULL,
  `currency` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` set('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_insurance_claim_histories`
--

DROP TABLE IF EXISTS `car_insurance_claim_histories`;
CREATE TABLE IF NOT EXISTS `car_insurance_claim_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` int(11) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  `claim_date` date NOT NULL,
  `claim_detail` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_licenses`
--

DROP TABLE IF EXISTS `car_licenses`;
CREATE TABLE IF NOT EXISTS `car_licenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_license_expire_notis`
--

DROP TABLE IF EXISTS `car_license_expire_notis`;
CREATE TABLE IF NOT EXISTS `car_license_expire_notis` (
  `id` int(11) NOT NULL,
  `first_person_email` varchar(191) DEFAULT NULL,
  `second_person_email` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `car_license_expire_notis`
--

INSERT INTO `car_license_expire_notis` (`id`, `first_person_email`, `second_person_email`, `created_at`, `updated_at`) VALUES
(1, 'kyawkyaw31355@gmail.com', 'naungnaung551110@gmail.com', NULL, '2023-05-30 09:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `car_main_users`
--

DROP TABLE IF EXISTS `car_main_users`;
CREATE TABLE IF NOT EXISTS `car_main_users` (
  `id` bigint(20) NOT NULL,
  `car_id` int(11) NOT NULL,
  `main_user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `car_mileages`
--

DROP TABLE IF EXISTS `car_mileages`;
CREATE TABLE IF NOT EXISTS `car_mileages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `current_km` double(8,2) DEFAULT NULL,
  `km` double(8,2) NOT NULL DEFAULT '0.00',
  `liter` double(8,2) NOT NULL DEFAULT '0.00',
  `actual_km` double(8,2) NOT NULL DEFAULT '0.00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_repair_and_maintanances`
--

DROP TABLE IF EXISTS `car_repair_and_maintanances`;
CREATE TABLE IF NOT EXISTS `car_repair_and_maintanances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` int(11) NOT NULL,
  `kilo_meter` double(8,2) NOT NULL,
  `repair_date` date NOT NULL,
  `amount` double(12,2) NOT NULL,
  `repair_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repair_detail` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `change_requests`
--

DROP TABLE IF EXISTS `change_requests`;
CREATE TABLE IF NOT EXISTS `change_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `actual_date` date NOT NULL,
  `changing_date` date DEFAULT NULL,
  `changing_start_time` time DEFAULT NULL,
  `changing_end_time` time DEFAULT NULL,
  `working_start_time` time DEFAULT NULL,
  `working_end_time` time DEFAULT NULL,
  `requested_date` datetime NOT NULL,
  `status_change_date` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `status_change_by` int(11) DEFAULT NULL,
  `reason_of_correction` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_infos`
--

DROP TABLE IF EXISTS `contact_infos`;
CREATE TABLE IF NOT EXISTS `contact_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_person_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_person_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_person_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_person_hotline` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_person_relationship` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_person_address` text COLLATE utf8mb4_unicode_ci,
  `second_person_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_person_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_person_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_person_hotline` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_person_relationship` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_person_address` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_ot_requests`
--

DROP TABLE IF EXISTS `daily_ot_requests`;
CREATE TABLE IF NOT EXISTS `daily_ot_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `ot_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_date` date NOT NULL,
  `start_from_time` time NOT NULL,
  `start_to_time` time NOT NULL,
  `start_break_hour` int(11) NOT NULL DEFAULT '0',
  `start_break_minute` int(11) NOT NULL DEFAULT '0',
  `start_reason` text COLLATE utf8mb4_unicode_ci,
  `start_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `start_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `end_from_time` time DEFAULT NULL,
  `end_to_time` time DEFAULT NULL,
  `end_break_hour` int(11) NOT NULL DEFAULT '0',
  `end_break_minute` int(11) NOT NULL DEFAULT '0',
  `end_reason` text COLLATE utf8mb4_unicode_ci,
  `end_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `end_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `status_reason` text COLLATE utf8mb4_unicode_ci,
  `monthly_status_reason` text COLLATE utf8mb4_unicode_ci,
  `status_change_date` datetime DEFAULT NULL,
  `status_change_by` int(11) NOT NULL DEFAULT '0',
  `monthly_request` tinyint(4) NOT NULL DEFAULT '0',
  `monthly_request_id` int(11) NOT NULL DEFAULT '0',
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `order_no`, `name`, `short_name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Administration Department', 'ADMI', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(2, 2, 'Accouting Department', 'ACCG', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(3, 3, 'Grain & Food Products Department', 'PRD', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(4, 4, 'Chemical Department', 'CHM', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(5, 5, 'Power Project Department', 'POWR', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(6, 6, 'Plant & Infrastructure Project Department', 'MAC', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(7, 7, 'Metal Department', 'MET', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(8, 8, 'Energy Department', 'PET', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(9, 9, 'Forest Products Department', 'GEM', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(10, 10, 'Business Development Department', 'DEV', 1, 1, 1, '2023-01-11 08:15:57', '2023-01-11 08:15:57'),
(11, 11, 'Life Style Department', 'Tex', 1, 22, 22, '2023-03-27 18:09:56', '2023-03-27 18:09:56'),
(12, 12, 'Nay Pyi Taw Branch', 'NPT', 1, 49, 49, '2023-04-05 03:40:14', '2023-04-05 03:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `driver_licenses`
--

DROP TABLE IF EXISTS `driver_licenses`;
CREATE TABLE IF NOT EXISTS `driver_licenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `license_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('active','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

DROP TABLE IF EXISTS `education`;
CREATE TABLE IF NOT EXISTS `education` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `education_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_graduation` int(11) NOT NULL,
  `major` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_types`
--

DROP TABLE IF EXISTS `employee_types`;
CREATE TABLE IF NOT EXISTS `employee_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_types`
--

INSERT INTO `employee_types` (`id`, `type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'YGN-Staff', 1, 1, NULL, '2022-12-26 01:47:15', '2022-12-26 01:47:15'),
(2, 'YGN-Driver', 1, 1, NULL, '2022-12-26 01:47:15', '2022-12-26 01:47:15'),
(3, 'YGN-Contract Driver', 1, 1, NULL, '2023-02-02 05:15:10', '2023-02-02 05:15:10'),
(4, 'YGN-Rental Driver', 1, 1, NULL, '2023-02-02 05:15:10', '2023-02-02 05:15:10'),
(5, 'NPT-Staff', 1, 1, NULL, '2023-02-02 05:16:26', '2023-02-02 05:16:26'),
(6, 'NPT-Driver', 1, 1, NULL, '2023-02-02 05:16:26', '2023-02-02 05:16:26'),
(7, 'NPT-Contract Driver', 1, 1, NULL, '2023-02-02 05:17:02', '2023-02-02 05:17:02'),
(8, 'Qc_Staff', 1, 1, 1, '2023-03-01 04:53:48', '2023-03-01 04:53:51'),
(9, 'Receptionist', 1, 1, 1, '2023-03-27 09:58:52', '2023-03-27 09:58:52'),
(10, 'YGN-Assistant', 1, 1, 1, '2023-03-27 09:58:52', '2023-03-27 09:58:52'),
(11, 'NPT-Assistant', 1, 1, 1, '2023-05-22 08:05:09', '2023-05-22 08:05:09');

-- --------------------------------------------------------

--
-- Table structure for table `employment_records`
--

DROP TABLE IF EXISTS `employment_records`;
CREATE TABLE IF NOT EXISTS `employment_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `english_skills`
--

DROP TABLE IF EXISTS `english_skills`;
CREATE TABLE IF NOT EXISTS `english_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mark` int(11) DEFAULT NULL,
  `level` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_acquition` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `competency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `performance` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_pay` double(8,2) DEFAULT NULL,
  `basic_salary` double(8,2) DEFAULT NULL,
  `allowance` double(8,2) DEFAULT NULL,
  `ot_rate` double(8,2) DEFAULT NULL,
  `water_festival_bonus` double(8,2) DEFAULT NULL,
  `thadingyut_bonus` double(8,2) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `dollar` double(8,2) NOT NULL,
  `yen` double(8,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

DROP TABLE IF EXISTS `families`;
CREATE TABLE IF NOT EXISTS `families` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `relationship` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_dob` date DEFAULT NULL,
  `work` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowance_fee` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fingerprintdevice`
--

DROP TABLE IF EXISTS `fingerprintdevice`;
CREATE TABLE IF NOT EXISTS `fingerprintdevice` (
  `fig_ID` int(11) NOT NULL,
  `fig_Name` varchar(100) NOT NULL,
  `fig_IP` varchar(20) NOT NULL,
  `fig_SerialNo` varchar(20) DEFAULT NULL,
  `fig_PortNo` int(11) NOT NULL,
  `fig_SiteNo` varchar(20) DEFAULT NULL,
  `fig_PCName` varchar(20) NOT NULL,
  `fig_TimerMinutes` int(11) NOT NULL,
  `fig_AttMaxID` int(11) NOT NULL,
  `fig_AttDownloadDate` datetime NOT NULL,
  `fig_AttDeleteDayBefore` int(11) NOT NULL,
  `fig_AttDeletedDate` datetime NOT NULL,
  `fig_AttLinePerPage` int(11) NOT NULL,
  `fig_ProfileDownload` tinyint(1) NOT NULL,
  `fig_ProfileRequestDate` datetime NOT NULL,
  `fig_ProfileDownloadDate` datetime NOT NULL,
  `fig_ProfileMaxID` int(11) NOT NULL,
  `fig_ProfileLinePerPage` int(11) NOT NULL,
  `fig_ProfileUploadDevice` varchar(30) NOT NULL,
  `fig_ProfileUploadDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fig_Delete` int(11) NOT NULL DEFAULT '0',
  `fig_Creator` varchar(20) NOT NULL,
  `fig_CreateDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

DROP TABLE IF EXISTS `holidays`;
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` int(11) NOT NULL DEFAULT '0',
  `holiday_type_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_types`
--

DROP TABLE IF EXISTS `holiday_types`;
CREATE TABLE IF NOT EXISTS `holiday_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_skill` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lates`
--

DROP TABLE IF EXISTS `lates`;
CREATE TABLE IF NOT EXISTS `lates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `april` int(11) NOT NULL DEFAULT '0',
  `may` int(11) NOT NULL DEFAULT '0',
  `june` int(11) NOT NULL DEFAULT '0',
  `july` int(11) NOT NULL DEFAULT '0',
  `august` int(11) NOT NULL DEFAULT '0',
  `september` int(11) NOT NULL DEFAULT '0',
  `october` int(11) NOT NULL DEFAULT '0',
  `november` int(11) NOT NULL DEFAULT '0',
  `december` int(11) NOT NULL DEFAULT '0',
  `january` int(11) NOT NULL DEFAULT '0',
  `february` int(11) NOT NULL DEFAULT '0',
  `march` int(11) NOT NULL DEFAULT '0',
  `year` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_forms`
--

DROP TABLE IF EXISTS `leave_forms`;
CREATE TABLE IF NOT EXISTS `leave_forms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `day_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `total_days` float NOT NULL,
  `remaining_days` float NOT NULL,
  `approve_by_dep_manager` enum('pending','reject','accept') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approve_reason_by_dep_manager` text COLLATE utf8mb4_unicode_ci,
  `approve_by_GM` enum('pending','reject','accept') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approve_by_RS_GM` enum('pending','accept','reject') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approve_reason_by_GM` text COLLATE utf8mb4_unicode_ci,
  `approve_reason_by_RS_GM` text COLLATE utf8mb4_unicode_ci,
  `request_leave_cancel` int(4) DEFAULT '0',
  `cancel_leave_approve_by_dep_manager` enum('pending','reject','accept') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_leave_approve_reason_by_dep_manager` text COLLATE utf8mb4_unicode_ci,
  `cancel_leave_approve_by_admi_manager` enum('pending','reject','accept') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_leave_approve_reason_by_admi_manager` text COLLATE utf8mb4_unicode_ci,
  `cancel_leave_approve_by_RS_GM` enum('pending','accept','reject') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_leave_approve_reason_by_RS_GM` text COLLATE utf8mb4_unicode_ci,
  `leave_cancel_reason` text COLLATE utf8mb4_unicode_ci,
  `certificate` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE IF NOT EXISTS `leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_type_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_day` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `leave_type_name`, `leave_day`, `type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Casual Leave', 6, 'paid', 1, 1, 1, '2023-01-04 15:15:15', '2023-01-04 15:15:15'),
(2, 'Earned Leave', 10, 'paid', 1, 1, 1, '2023-01-04 15:15:15', '2023-01-04 15:15:15'),
(3, 'Medical Leave', 30, 'paid', 1, 1, 1, NULL, NULL),
(4, 'Maternity Leave', 98, 'paid', 1, 1, 1, NULL, NULL),
(5, 'Paternity Leave', 15, 'paid', 1, 1, 1, NULL, NULL),
(6, 'Long Term Sick Leave', 150, 'paid', 1, 1, 1, NULL, NULL),
(7, 'Congratulatory Leave', 10, 'paid', 1, 1, 1, NULL, NULL),
(8, 'Condolence Leave', 7, 'paid', 1, 1, 1, NULL, NULL),
(9, 'Unpaid Leave', 365, 'unpaid', 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `life_assurances`
--

DROP TABLE IF EXISTS `life_assurances`;
CREATE TABLE IF NOT EXISTS `life_assurances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `premium_amount` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_histories`
--

DROP TABLE IF EXISTS `login_histories`;
CREATE TABLE IF NOT EXISTS `login_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(201, '2014_10_12_000000_create_users_table', 1),
(202, '2014_10_12_100000_create_password_resets_table', 1),
(203, '2019_08_19_000000_create_failed_jobs_table', 1),
(204, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(205, '2022_12_06_025100_create_permission_tables', 1),
(206, '2022_12_06_192651_create_ns_employees_table', 1),
(207, '2022_12_06_195651_create_rs_employees_table', 1),
(208, '2022_12_07_085745_create_branch_assign_histories_table', 1),
(209, '2022_12_07_091852_create_position_assign_histories_table', 1),
(210, '2022_12_07_092744_create_contact_infos_table', 1),
(211, '2022_12_07_104207_create_families_table', 1),
(212, '2022_12_07_105008_create_education_table', 1),
(213, '2022_12_07_111504_create_qualifications_table', 1),
(214, '2022_12_07_113141_create_languages_table', 1),
(215, '2022_12_07_113532_create_english_skills_table', 1),
(216, '2022_12_07_114815_create_pc_skills_table', 1),
(217, '2022_12_07_115153_create_employment_records_table', 1),
(218, '2022_12_07_115740_create_oversea_records_table', 1),
(219, '2022_12_07_120447_create_warnings_table', 1),
(220, '2022_12_07_120823_create_retirements_table', 1),
(221, '2022_12_07_121206_create_others_table', 1),
(222, '2022_12_07_121652_create_evaluations_table', 1),
(223, '2022_12_07_122835_create_branches_table', 1),
(224, '2022_12_07_123424_create_departments_table', 1),
(225, '2022_12_07_123601_create_holiday_types_table', 1),
(226, '2022_12_07_123724_create_holidays_table', 1),
(227, '2022_12_07_124035_create_employee_types_table', 1),
(228, '2022_12_07_124133_create_banks_table', 1),
(229, '2022_12_07_124520_create_attendances_table', 1),
(230, '2022_12_07_173341_create_attendance_logs_table', 1),
(231, '2022_12_07_173836_create_change_requests_table', 1),
(232, '2022_12_07_181845_create_lates_table', 1),
(233, '2022_12_07_183350_create_daily_ot_requests_table', 1),
(234, '2022_12_08_114046_create_monthly_ot_requests_table', 1),
(235, '2022_12_08_115105_create_monthly_ot_request_details_table', 1),
(236, '2022_12_09_172814_create_leave_types_table', 1),
(237, '2022_12_09_173502_create_leave_forms_table', 1),
(238, '2022_12_09_182558_create_cars_table', 1),
(239, '2022_12_09_183657_create_car_insurances_table', 1),
(240, '2022_12_09_184311_create_car_insurance_claim_histories_table', 1),
(241, '2022_12_09_184810_create_car_fuelings_table', 1),
(242, '2022_12_09_185135_create_car_repair_and_maintanances_table', 1),
(243, '2022_12_09_185918_create_car_licenses_table', 1),
(244, '2022_12_09_230321_create_car_mileages_table', 1),
(245, '2022_12_09_230926_create_driver_licenses_table', 1),
(246, '2022_12_09_233003_create_exchange_rates_table', 1),
(247, '2022_12_09_233338_create_ns_salaries_table', 1),
(248, '2022_12_09_234609_create_rs_salaries_table', 1),
(249, '2022_12_12_103548_create_salaries_table', 1),
(250, '2022_12_12_113133_create_salary_exchange_details_table', 1),
(251, '2022_12_12_113528_create_other_allowances_table', 1),
(252, '2022_12_12_113819_create_other_deductions_table', 1),
(253, '2022_12_18_225430_create_reset_passwords_table', 2),
(254, '2022_12_19_121339_create_login_histories_table', 2),
(255, '2023_01_02_115525_create_raw_att_logs_table', 3),
(256, '2023_01_31_184953_create_annual_ot_summaries_table', 4),
(257, '2023_03_07_002415_create_sscs_table', 5),
(258, '2023_03_07_151040_create_ns_income_taxes_table', 5),
(259, '2023_03_07_153919_create_rs_income_taxes_table', 5),
(260, '2023_03_07_160356_create_rs_income_tax_jpy_details_table', 5),
(261, '2023_03_07_161655_create_rs_income_tax_mm_details_table', 5),
(262, '2023_03_15_115225_create_other_adjustments_table', 6),
(263, '2023_03_17_230716_create_ns_income_tax_details_table', 6),
(264, '2023_03_17_231250_create_tax_ranges_table', 6),
(265, '2023_03_27_142955_create_monthly_driver_ot_requests_table', 7),
(266, '2023_03_27_143027_create_monthly_driver_ot_request_details_table', 7),
(267, '2023_03_28_192603_create_ns_actual_taxes_table', 8),
(268, '2023_03_28_192647_create_rs_actual_taxes_table', 8),
(269, '2023_04_04_152340_create_attendance_temps_table', 9),
(270, '2023_05_26_163449_create_payment_exchange_rates_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1),
(12, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(15, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 1),
(20, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 1),
(22, 'App\\Models\\User', 1),
(23, 'App\\Models\\User', 1),
(24, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 1),
(29, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 1),
(31, 'App\\Models\\User', 1),
(32, 'App\\Models\\User', 1),
(35, 'App\\Models\\User', 1),
(36, 'App\\Models\\User', 1),
(37, 'App\\Models\\User', 1),
(38, 'App\\Models\\User', 1),
(39, 'App\\Models\\User', 1),
(40, 'App\\Models\\User', 1),
(41, 'App\\Models\\User', 1),
(42, 'App\\Models\\User', 1),
(43, 'App\\Models\\User', 1),
(44, 'App\\Models\\User', 1),
(45, 'App\\Models\\User', 1),
(46, 'App\\Models\\User', 1),
(47, 'App\\Models\\User', 1),
(48, 'App\\Models\\User', 1),
(49, 'App\\Models\\User', 1),
(54, 'App\\Models\\User', 1),
(55, 'App\\Models\\User', 1),
(56, 'App\\Models\\User', 1),
(57, 'App\\Models\\User', 1),
(58, 'App\\Models\\User', 1),
(59, 'App\\Models\\User', 1),
(60, 'App\\Models\\User', 1),
(61, 'App\\Models\\User', 1),
(62, 'App\\Models\\User', 1),
(63, 'App\\Models\\User', 1),
(64, 'App\\Models\\User', 1),
(65, 'App\\Models\\User', 1),
(66, 'App\\Models\\User', 1),
(67, 'App\\Models\\User', 1),
(68, 'App\\Models\\User', 1),
(69, 'App\\Models\\User', 1),
(70, 'App\\Models\\User', 1),
(71, 'App\\Models\\User', 1),
(72, 'App\\Models\\User', 1),
(73, 'App\\Models\\User', 1),
(74, 'App\\Models\\User', 1),
(75, 'App\\Models\\User', 1),
(76, 'App\\Models\\User', 1),
(77, 'App\\Models\\User', 1),
(78, 'App\\Models\\User', 1),
(79, 'App\\Models\\User', 1),
(80, 'App\\Models\\User', 1),
(81, 'App\\Models\\User', 1),
(82, 'App\\Models\\User', 1),
(83, 'App\\Models\\User', 1),
(84, 'App\\Models\\User', 1),
(85, 'App\\Models\\User', 1),
(86, 'App\\Models\\User', 1),
(87, 'App\\Models\\User', 1),
(90, 'App\\Models\\User', 1),
(91, 'App\\Models\\User', 1),
(92, 'App\\Models\\User', 1),
(93, 'App\\Models\\User', 1),
(94, 'App\\Models\\User', 1),
(95, 'App\\Models\\User', 1),
(131, 'App\\Models\\User', 1),
(132, 'App\\Models\\User', 1),
(133, 'App\\Models\\User', 1),
(134, 'App\\Models\\User', 1),
(135, 'App\\Models\\User', 1),
(141, 'App\\Models\\User', 1),
(155, 'App\\Models\\User', 1),
(158, 'App\\Models\\User', 1),
(159, 'App\\Models\\User', 1),
(160, 'App\\Models\\User', 1),
(161, 'App\\Models\\User', 1),
(162, 'App\\Models\\User', 1),
(163, 'App\\Models\\User', 1),
(164, 'App\\Models\\User', 1),
(165, 'App\\Models\\User', 1),
(166, 'App\\Models\\User', 1),
(167, 'App\\Models\\User', 1),
(168, 'App\\Models\\User', 1),
(169, 'App\\Models\\User', 1),
(170, 'App\\Models\\User', 1),
(171, 'App\\Models\\User', 1),
(172, 'App\\Models\\User', 1),
(173, 'App\\Models\\User', 1),
(174, 'App\\Models\\User', 1),
(175, 'App\\Models\\User', 1),
(176, 'App\\Models\\User', 1),
(177, 'App\\Models\\User', 1),
(178, 'App\\Models\\User', 1),
(179, 'App\\Models\\User', 1),
(180, 'App\\Models\\User', 1),
(181, 'App\\Models\\User', 1),
(183, 'App\\Models\\User', 1),
(186, 'App\\Models\\User', 1),
(188, 'App\\Models\\User', 1),
(189, 'App\\Models\\User', 1),
(190, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 49),
(4, 'App\\Models\\User', 49),
(5, 'App\\Models\\User', 49),
(6, 'App\\Models\\User', 49),
(7, 'App\\Models\\User', 49),
(8, 'App\\Models\\User', 49),
(9, 'App\\Models\\User', 49),
(10, 'App\\Models\\User', 49),
(11, 'App\\Models\\User', 49),
(12, 'App\\Models\\User', 49),
(13, 'App\\Models\\User', 49),
(14, 'App\\Models\\User', 49),
(15, 'App\\Models\\User', 49),
(16, 'App\\Models\\User', 49),
(17, 'App\\Models\\User', 49),
(20, 'App\\Models\\User', 49),
(21, 'App\\Models\\User', 49),
(22, 'App\\Models\\User', 49),
(23, 'App\\Models\\User', 49),
(24, 'App\\Models\\User', 49),
(25, 'App\\Models\\User', 49),
(26, 'App\\Models\\User', 49),
(27, 'App\\Models\\User', 49),
(28, 'App\\Models\\User', 49),
(29, 'App\\Models\\User', 49),
(30, 'App\\Models\\User', 49),
(31, 'App\\Models\\User', 49),
(32, 'App\\Models\\User', 49),
(35, 'App\\Models\\User', 49),
(37, 'App\\Models\\User', 49),
(38, 'App\\Models\\User', 49),
(39, 'App\\Models\\User', 49),
(41, 'App\\Models\\User', 49),
(42, 'App\\Models\\User', 49),
(43, 'App\\Models\\User', 49),
(44, 'App\\Models\\User', 49),
(45, 'App\\Models\\User', 49),
(46, 'App\\Models\\User', 49),
(47, 'App\\Models\\User', 49),
(48, 'App\\Models\\User', 49),
(49, 'App\\Models\\User', 49),
(52, 'App\\Models\\User', 49),
(54, 'App\\Models\\User', 49),
(55, 'App\\Models\\User', 49),
(56, 'App\\Models\\User', 49),
(57, 'App\\Models\\User', 49),
(58, 'App\\Models\\User', 49),
(59, 'App\\Models\\User', 49),
(60, 'App\\Models\\User', 49),
(61, 'App\\Models\\User', 49),
(62, 'App\\Models\\User', 49),
(63, 'App\\Models\\User', 49),
(64, 'App\\Models\\User', 49),
(65, 'App\\Models\\User', 49),
(66, 'App\\Models\\User', 49),
(67, 'App\\Models\\User', 49),
(68, 'App\\Models\\User', 49),
(69, 'App\\Models\\User', 49),
(70, 'App\\Models\\User', 49),
(71, 'App\\Models\\User', 49),
(72, 'App\\Models\\User', 49),
(73, 'App\\Models\\User', 49),
(74, 'App\\Models\\User', 49),
(75, 'App\\Models\\User', 49),
(76, 'App\\Models\\User', 49),
(77, 'App\\Models\\User', 49),
(78, 'App\\Models\\User', 49),
(79, 'App\\Models\\User', 49),
(80, 'App\\Models\\User', 49),
(81, 'App\\Models\\User', 49),
(82, 'App\\Models\\User', 49),
(83, 'App\\Models\\User', 49),
(84, 'App\\Models\\User', 49),
(85, 'App\\Models\\User', 49),
(86, 'App\\Models\\User', 49),
(87, 'App\\Models\\User', 49),
(90, 'App\\Models\\User', 49),
(91, 'App\\Models\\User', 49),
(92, 'App\\Models\\User', 49),
(93, 'App\\Models\\User', 49),
(95, 'App\\Models\\User', 49),
(131, 'App\\Models\\User', 49),
(132, 'App\\Models\\User', 49),
(133, 'App\\Models\\User', 49),
(134, 'App\\Models\\User', 49),
(135, 'App\\Models\\User', 49),
(136, 'App\\Models\\User', 49),
(137, 'App\\Models\\User', 49),
(138, 'App\\Models\\User', 49),
(139, 'App\\Models\\User', 49),
(140, 'App\\Models\\User', 49),
(141, 'App\\Models\\User', 49),
(142, 'App\\Models\\User', 49),
(143, 'App\\Models\\User', 49),
(144, 'App\\Models\\User', 49),
(145, 'App\\Models\\User', 49),
(146, 'App\\Models\\User', 49),
(147, 'App\\Models\\User', 49),
(148, 'App\\Models\\User', 49),
(149, 'App\\Models\\User', 49),
(150, 'App\\Models\\User', 49),
(151, 'App\\Models\\User', 49),
(152, 'App\\Models\\User', 49),
(153, 'App\\Models\\User', 49),
(154, 'App\\Models\\User', 49),
(155, 'App\\Models\\User', 49),
(158, 'App\\Models\\User', 49),
(161, 'App\\Models\\User', 49),
(162, 'App\\Models\\User', 49),
(166, 'App\\Models\\User', 49),
(167, 'App\\Models\\User', 49),
(168, 'App\\Models\\User', 49),
(169, 'App\\Models\\User', 49),
(170, 'App\\Models\\User', 49),
(171, 'App\\Models\\User', 49),
(172, 'App\\Models\\User', 49),
(173, 'App\\Models\\User', 49),
(174, 'App\\Models\\User', 49),
(175, 'App\\Models\\User', 49),
(176, 'App\\Models\\User', 49),
(177, 'App\\Models\\User', 49),
(178, 'App\\Models\\User', 49),
(179, 'App\\Models\\User', 49),
(180, 'App\\Models\\User', 49),
(181, 'App\\Models\\User', 49),
(183, 'App\\Models\\User', 49),
(188, 'App\\Models\\User', 49),
(1, 'App\\Models\\User', 50),
(4, 'App\\Models\\User', 50),
(5, 'App\\Models\\User', 50),
(6, 'App\\Models\\User', 50),
(7, 'App\\Models\\User', 50),
(8, 'App\\Models\\User', 50),
(9, 'App\\Models\\User', 50),
(10, 'App\\Models\\User', 50),
(11, 'App\\Models\\User', 50),
(12, 'App\\Models\\User', 50),
(13, 'App\\Models\\User', 50),
(14, 'App\\Models\\User', 50),
(15, 'App\\Models\\User', 50),
(16, 'App\\Models\\User', 50),
(17, 'App\\Models\\User', 50),
(20, 'App\\Models\\User', 50),
(21, 'App\\Models\\User', 50),
(22, 'App\\Models\\User', 50),
(23, 'App\\Models\\User', 50),
(24, 'App\\Models\\User', 50),
(25, 'App\\Models\\User', 50),
(26, 'App\\Models\\User', 50),
(27, 'App\\Models\\User', 50),
(28, 'App\\Models\\User', 50),
(29, 'App\\Models\\User', 50),
(30, 'App\\Models\\User', 50),
(31, 'App\\Models\\User', 50),
(34, 'App\\Models\\User', 50),
(51, 'App\\Models\\User', 50),
(54, 'App\\Models\\User', 50),
(55, 'App\\Models\\User', 50),
(56, 'App\\Models\\User', 50),
(57, 'App\\Models\\User', 50),
(58, 'App\\Models\\User', 50),
(59, 'App\\Models\\User', 50),
(60, 'App\\Models\\User', 50),
(61, 'App\\Models\\User', 50),
(62, 'App\\Models\\User', 50),
(63, 'App\\Models\\User', 50),
(64, 'App\\Models\\User', 50),
(65, 'App\\Models\\User', 50),
(66, 'App\\Models\\User', 50),
(67, 'App\\Models\\User', 50),
(68, 'App\\Models\\User', 50),
(69, 'App\\Models\\User', 50),
(70, 'App\\Models\\User', 50),
(71, 'App\\Models\\User', 50),
(72, 'App\\Models\\User', 50),
(73, 'App\\Models\\User', 50),
(74, 'App\\Models\\User', 50),
(75, 'App\\Models\\User', 50),
(76, 'App\\Models\\User', 50),
(77, 'App\\Models\\User', 50),
(78, 'App\\Models\\User', 50),
(79, 'App\\Models\\User', 50),
(80, 'App\\Models\\User', 50),
(81, 'App\\Models\\User', 50),
(82, 'App\\Models\\User', 50),
(83, 'App\\Models\\User', 50),
(84, 'App\\Models\\User', 50),
(85, 'App\\Models\\User', 50),
(87, 'App\\Models\\User', 50),
(90, 'App\\Models\\User', 50),
(91, 'App\\Models\\User', 50),
(92, 'App\\Models\\User', 50),
(93, 'App\\Models\\User', 50),
(131, 'App\\Models\\User', 50),
(132, 'App\\Models\\User', 50),
(133, 'App\\Models\\User', 50),
(134, 'App\\Models\\User', 50),
(135, 'App\\Models\\User', 50),
(136, 'App\\Models\\User', 50),
(137, 'App\\Models\\User', 50),
(138, 'App\\Models\\User', 50),
(139, 'App\\Models\\User', 50),
(140, 'App\\Models\\User', 50),
(141, 'App\\Models\\User', 50),
(142, 'App\\Models\\User', 50),
(143, 'App\\Models\\User', 50),
(144, 'App\\Models\\User', 50),
(145, 'App\\Models\\User', 50),
(146, 'App\\Models\\User', 50),
(147, 'App\\Models\\User', 50),
(148, 'App\\Models\\User', 50),
(149, 'App\\Models\\User', 50),
(150, 'App\\Models\\User', 50),
(151, 'App\\Models\\User', 50),
(152, 'App\\Models\\User', 50),
(153, 'App\\Models\\User', 50),
(154, 'App\\Models\\User', 50),
(155, 'App\\Models\\User', 50),
(183, 'App\\Models\\User', 50),
(187, 'App\\Models\\User', 50),
(188, 'App\\Models\\User', 50),
(1, 'App\\Models\\User', 52),
(4, 'App\\Models\\User', 52),
(5, 'App\\Models\\User', 52),
(6, 'App\\Models\\User', 52),
(8, 'App\\Models\\User', 52),
(9, 'App\\Models\\User', 52),
(10, 'App\\Models\\User', 52),
(11, 'App\\Models\\User', 52),
(12, 'App\\Models\\User', 52),
(13, 'App\\Models\\User', 52),
(14, 'App\\Models\\User', 52),
(15, 'App\\Models\\User', 52),
(17, 'App\\Models\\User', 52),
(20, 'App\\Models\\User', 52),
(21, 'App\\Models\\User', 52),
(22, 'App\\Models\\User', 52),
(23, 'App\\Models\\User', 52),
(26, 'App\\Models\\User', 52),
(27, 'App\\Models\\User', 52),
(31, 'App\\Models\\User', 52),
(51, 'App\\Models\\User', 52),
(54, 'App\\Models\\User', 52),
(55, 'App\\Models\\User', 52),
(56, 'App\\Models\\User', 52),
(57, 'App\\Models\\User', 52),
(58, 'App\\Models\\User', 52),
(59, 'App\\Models\\User', 52),
(60, 'App\\Models\\User', 52),
(61, 'App\\Models\\User', 52),
(62, 'App\\Models\\User', 52),
(63, 'App\\Models\\User', 52),
(64, 'App\\Models\\User', 52),
(65, 'App\\Models\\User', 52),
(66, 'App\\Models\\User', 52),
(67, 'App\\Models\\User', 52),
(68, 'App\\Models\\User', 52),
(69, 'App\\Models\\User', 52),
(70, 'App\\Models\\User', 52),
(71, 'App\\Models\\User', 52),
(72, 'App\\Models\\User', 52),
(73, 'App\\Models\\User', 52),
(74, 'App\\Models\\User', 52),
(75, 'App\\Models\\User', 52),
(76, 'App\\Models\\User', 52),
(77, 'App\\Models\\User', 52),
(78, 'App\\Models\\User', 52),
(79, 'App\\Models\\User', 52),
(80, 'App\\Models\\User', 52),
(81, 'App\\Models\\User', 52),
(87, 'App\\Models\\User', 52),
(90, 'App\\Models\\User', 52),
(141, 'App\\Models\\User', 52),
(142, 'App\\Models\\User', 52),
(143, 'App\\Models\\User', 52),
(144, 'App\\Models\\User', 52),
(183, 'App\\Models\\User', 52),
(186, 'App\\Models\\User', 52),
(188, 'App\\Models\\User', 52),
(1, 'App\\Models\\User', 53),
(4, 'App\\Models\\User', 53),
(5, 'App\\Models\\User', 53),
(6, 'App\\Models\\User', 53),
(7, 'App\\Models\\User', 53),
(8, 'App\\Models\\User', 53),
(9, 'App\\Models\\User', 53),
(10, 'App\\Models\\User', 53),
(11, 'App\\Models\\User', 53),
(12, 'App\\Models\\User', 53),
(13, 'App\\Models\\User', 53),
(14, 'App\\Models\\User', 53),
(15, 'App\\Models\\User', 53),
(17, 'App\\Models\\User', 53),
(20, 'App\\Models\\User', 53),
(21, 'App\\Models\\User', 53),
(26, 'App\\Models\\User', 53),
(27, 'App\\Models\\User', 53),
(31, 'App\\Models\\User', 53),
(34, 'App\\Models\\User', 53),
(51, 'App\\Models\\User', 53),
(54, 'App\\Models\\User', 53),
(55, 'App\\Models\\User', 53),
(56, 'App\\Models\\User', 53),
(63, 'App\\Models\\User', 53),
(64, 'App\\Models\\User', 53),
(65, 'App\\Models\\User', 53),
(66, 'App\\Models\\User', 53),
(67, 'App\\Models\\User', 53),
(68, 'App\\Models\\User', 53),
(69, 'App\\Models\\User', 53),
(70, 'App\\Models\\User', 53),
(71, 'App\\Models\\User', 53),
(72, 'App\\Models\\User', 53),
(73, 'App\\Models\\User', 53),
(74, 'App\\Models\\User', 53),
(76, 'App\\Models\\User', 53),
(77, 'App\\Models\\User', 53),
(78, 'App\\Models\\User', 53),
(79, 'App\\Models\\User', 53),
(80, 'App\\Models\\User', 53),
(81, 'App\\Models\\User', 53),
(82, 'App\\Models\\User', 53),
(83, 'App\\Models\\User', 53),
(84, 'App\\Models\\User', 53),
(86, 'App\\Models\\User', 53),
(89, 'App\\Models\\User', 53),
(90, 'App\\Models\\User', 53),
(91, 'App\\Models\\User', 53),
(92, 'App\\Models\\User', 53),
(93, 'App\\Models\\User', 53),
(140, 'App\\Models\\User', 53),
(141, 'App\\Models\\User', 53),
(142, 'App\\Models\\User', 53),
(143, 'App\\Models\\User', 53),
(144, 'App\\Models\\User', 53),
(183, 'App\\Models\\User', 53),
(188, 'App\\Models\\User', 53),
(2, 'App\\Models\\User', 54),
(4, 'App\\Models\\User', 54),
(5, 'App\\Models\\User', 54),
(11, 'App\\Models\\User', 54),
(15, 'App\\Models\\User', 54),
(16, 'App\\Models\\User', 54),
(17, 'App\\Models\\User', 54),
(20, 'App\\Models\\User', 54),
(21, 'App\\Models\\User', 54),
(22, 'App\\Models\\User', 54),
(26, 'App\\Models\\User', 54),
(27, 'App\\Models\\User', 54),
(28, 'App\\Models\\User', 54),
(29, 'App\\Models\\User', 54),
(32, 'App\\Models\\User', 54),
(35, 'App\\Models\\User', 54),
(36, 'App\\Models\\User', 54),
(37, 'App\\Models\\User', 54),
(38, 'App\\Models\\User', 54),
(39, 'App\\Models\\User', 54),
(40, 'App\\Models\\User', 54),
(41, 'App\\Models\\User', 54),
(42, 'App\\Models\\User', 54),
(43, 'App\\Models\\User', 54),
(44, 'App\\Models\\User', 54),
(45, 'App\\Models\\User', 54),
(46, 'App\\Models\\User', 54),
(47, 'App\\Models\\User', 54),
(48, 'App\\Models\\User', 54),
(49, 'App\\Models\\User', 54),
(82, 'App\\Models\\User', 54),
(83, 'App\\Models\\User', 54),
(84, 'App\\Models\\User', 54),
(86, 'App\\Models\\User', 54),
(89, 'App\\Models\\User', 54),
(90, 'App\\Models\\User', 54),
(146, 'App\\Models\\User', 54),
(147, 'App\\Models\\User', 54),
(148, 'App\\Models\\User', 54),
(149, 'App\\Models\\User', 54),
(155, 'App\\Models\\User', 54),
(158, 'App\\Models\\User', 54),
(159, 'App\\Models\\User', 54),
(160, 'App\\Models\\User', 54),
(161, 'App\\Models\\User', 54),
(162, 'App\\Models\\User', 54),
(163, 'App\\Models\\User', 54),
(164, 'App\\Models\\User', 54),
(165, 'App\\Models\\User', 54),
(166, 'App\\Models\\User', 54),
(167, 'App\\Models\\User', 54),
(168, 'App\\Models\\User', 54),
(169, 'App\\Models\\User', 54),
(170, 'App\\Models\\User', 54),
(171, 'App\\Models\\User', 54),
(172, 'App\\Models\\User', 54),
(173, 'App\\Models\\User', 54),
(174, 'App\\Models\\User', 54),
(175, 'App\\Models\\User', 54),
(176, 'App\\Models\\User', 54),
(177, 'App\\Models\\User', 54),
(178, 'App\\Models\\User', 54),
(179, 'App\\Models\\User', 54),
(180, 'App\\Models\\User', 54),
(181, 'App\\Models\\User', 54),
(1, 'App\\Models\\User', 55),
(4, 'App\\Models\\User', 55),
(5, 'App\\Models\\User', 55),
(11, 'App\\Models\\User', 55),
(12, 'App\\Models\\User', 55),
(13, 'App\\Models\\User', 55),
(15, 'App\\Models\\User', 55),
(17, 'App\\Models\\User', 55),
(20, 'App\\Models\\User', 55),
(21, 'App\\Models\\User', 55),
(22, 'App\\Models\\User', 55),
(23, 'App\\Models\\User', 55),
(24, 'App\\Models\\User', 55),
(25, 'App\\Models\\User', 55),
(26, 'App\\Models\\User', 55),
(27, 'App\\Models\\User', 55),
(29, 'App\\Models\\User', 55),
(32, 'App\\Models\\User', 55),
(35, 'App\\Models\\User', 55),
(36, 'App\\Models\\User', 55),
(37, 'App\\Models\\User', 55),
(38, 'App\\Models\\User', 55),
(39, 'App\\Models\\User', 55),
(40, 'App\\Models\\User', 55),
(41, 'App\\Models\\User', 55),
(42, 'App\\Models\\User', 55),
(43, 'App\\Models\\User', 55),
(44, 'App\\Models\\User', 55),
(45, 'App\\Models\\User', 55),
(46, 'App\\Models\\User', 55),
(47, 'App\\Models\\User', 55),
(48, 'App\\Models\\User', 55),
(49, 'App\\Models\\User', 55),
(82, 'App\\Models\\User', 55),
(86, 'App\\Models\\User', 55),
(89, 'App\\Models\\User', 55),
(90, 'App\\Models\\User', 55),
(146, 'App\\Models\\User', 55),
(147, 'App\\Models\\User', 55),
(148, 'App\\Models\\User', 55),
(149, 'App\\Models\\User', 55),
(155, 'App\\Models\\User', 55),
(158, 'App\\Models\\User', 55),
(159, 'App\\Models\\User', 55),
(160, 'App\\Models\\User', 55),
(161, 'App\\Models\\User', 55),
(162, 'App\\Models\\User', 55),
(163, 'App\\Models\\User', 55),
(164, 'App\\Models\\User', 55),
(165, 'App\\Models\\User', 55),
(166, 'App\\Models\\User', 55),
(167, 'App\\Models\\User', 55),
(168, 'App\\Models\\User', 55),
(169, 'App\\Models\\User', 55),
(170, 'App\\Models\\User', 55),
(171, 'App\\Models\\User', 55),
(172, 'App\\Models\\User', 55),
(173, 'App\\Models\\User', 55),
(174, 'App\\Models\\User', 55),
(175, 'App\\Models\\User', 55),
(176, 'App\\Models\\User', 55),
(177, 'App\\Models\\User', 55),
(178, 'App\\Models\\User', 55),
(179, 'App\\Models\\User', 55),
(180, 'App\\Models\\User', 55),
(181, 'App\\Models\\User', 55),
(183, 'App\\Models\\User', 55),
(3, 'App\\Models\\User', 56),
(4, 'App\\Models\\User', 56),
(5, 'App\\Models\\User', 56),
(11, 'App\\Models\\User', 56),
(15, 'App\\Models\\User', 56),
(19, 'App\\Models\\User', 56),
(20, 'App\\Models\\User', 56),
(21, 'App\\Models\\User', 56),
(26, 'App\\Models\\User', 56),
(27, 'App\\Models\\User', 56),
(86, 'App\\Models\\User', 56),
(89, 'App\\Models\\User', 56),
(90, 'App\\Models\\User', 56),
(185, 'App\\Models\\User', 56),
(3, 'App\\Models\\User', 57),
(4, 'App\\Models\\User', 57),
(5, 'App\\Models\\User', 57),
(11, 'App\\Models\\User', 57),
(15, 'App\\Models\\User', 57),
(19, 'App\\Models\\User', 57),
(20, 'App\\Models\\User', 57),
(21, 'App\\Models\\User', 57),
(26, 'App\\Models\\User', 57),
(27, 'App\\Models\\User', 57),
(86, 'App\\Models\\User', 57),
(89, 'App\\Models\\User', 57),
(90, 'App\\Models\\User', 57),
(185, 'App\\Models\\User', 57),
(3, 'App\\Models\\User', 58),
(4, 'App\\Models\\User', 58),
(5, 'App\\Models\\User', 58),
(11, 'App\\Models\\User', 58),
(15, 'App\\Models\\User', 58),
(19, 'App\\Models\\User', 58),
(20, 'App\\Models\\User', 58),
(21, 'App\\Models\\User', 58),
(26, 'App\\Models\\User', 58),
(27, 'App\\Models\\User', 58),
(86, 'App\\Models\\User', 58),
(89, 'App\\Models\\User', 58),
(90, 'App\\Models\\User', 58),
(3, 'App\\Models\\User', 59),
(4, 'App\\Models\\User', 59),
(5, 'App\\Models\\User', 59),
(11, 'App\\Models\\User', 59),
(15, 'App\\Models\\User', 59),
(19, 'App\\Models\\User', 59),
(20, 'App\\Models\\User', 59),
(21, 'App\\Models\\User', 59),
(26, 'App\\Models\\User', 59),
(27, 'App\\Models\\User', 59),
(86, 'App\\Models\\User', 59),
(89, 'App\\Models\\User', 59),
(90, 'App\\Models\\User', 59),
(3, 'App\\Models\\User', 60),
(4, 'App\\Models\\User', 60),
(5, 'App\\Models\\User', 60),
(11, 'App\\Models\\User', 60),
(15, 'App\\Models\\User', 60),
(19, 'App\\Models\\User', 60),
(20, 'App\\Models\\User', 60),
(21, 'App\\Models\\User', 60),
(26, 'App\\Models\\User', 60),
(27, 'App\\Models\\User', 60),
(89, 'App\\Models\\User', 60),
(90, 'App\\Models\\User', 60),
(185, 'App\\Models\\User', 60),
(2, 'App\\Models\\User', 61),
(4, 'App\\Models\\User', 61),
(5, 'App\\Models\\User', 61),
(11, 'App\\Models\\User', 61),
(15, 'App\\Models\\User', 61),
(16, 'App\\Models\\User', 61),
(18, 'App\\Models\\User', 61),
(20, 'App\\Models\\User', 61),
(21, 'App\\Models\\User', 61),
(26, 'App\\Models\\User', 61),
(27, 'App\\Models\\User', 61),
(28, 'App\\Models\\User', 61),
(66, 'App\\Models\\User', 61),
(84, 'App\\Models\\User', 61),
(89, 'App\\Models\\User', 61),
(90, 'App\\Models\\User', 61),
(184, 'App\\Models\\User', 61),
(3, 'App\\Models\\User', 62),
(4, 'App\\Models\\User', 62),
(5, 'App\\Models\\User', 62),
(11, 'App\\Models\\User', 62),
(15, 'App\\Models\\User', 62),
(19, 'App\\Models\\User', 62),
(20, 'App\\Models\\User', 62),
(21, 'App\\Models\\User', 62),
(26, 'App\\Models\\User', 62),
(27, 'App\\Models\\User', 62),
(86, 'App\\Models\\User', 62),
(89, 'App\\Models\\User', 62),
(90, 'App\\Models\\User', 62),
(185, 'App\\Models\\User', 62),
(3, 'App\\Models\\User', 63),
(4, 'App\\Models\\User', 63),
(5, 'App\\Models\\User', 63),
(11, 'App\\Models\\User', 63),
(15, 'App\\Models\\User', 63),
(19, 'App\\Models\\User', 63),
(20, 'App\\Models\\User', 63),
(21, 'App\\Models\\User', 63),
(26, 'App\\Models\\User', 63),
(27, 'App\\Models\\User', 63),
(86, 'App\\Models\\User', 63),
(89, 'App\\Models\\User', 63),
(90, 'App\\Models\\User', 63),
(1, 'App\\Models\\User', 64),
(4, 'App\\Models\\User', 64),
(5, 'App\\Models\\User', 64),
(6, 'App\\Models\\User', 64),
(7, 'App\\Models\\User', 64),
(8, 'App\\Models\\User', 64),
(9, 'App\\Models\\User', 64),
(10, 'App\\Models\\User', 64),
(11, 'App\\Models\\User', 64),
(12, 'App\\Models\\User', 64),
(13, 'App\\Models\\User', 64),
(14, 'App\\Models\\User', 64),
(15, 'App\\Models\\User', 64),
(17, 'App\\Models\\User', 64),
(20, 'App\\Models\\User', 64),
(21, 'App\\Models\\User', 64),
(22, 'App\\Models\\User', 64),
(23, 'App\\Models\\User', 64),
(24, 'App\\Models\\User', 64),
(25, 'App\\Models\\User', 64),
(26, 'App\\Models\\User', 64),
(27, 'App\\Models\\User', 64),
(29, 'App\\Models\\User', 64),
(32, 'App\\Models\\User', 64),
(35, 'App\\Models\\User', 64),
(36, 'App\\Models\\User', 64),
(37, 'App\\Models\\User', 64),
(38, 'App\\Models\\User', 64),
(39, 'App\\Models\\User', 64),
(40, 'App\\Models\\User', 64),
(41, 'App\\Models\\User', 64),
(42, 'App\\Models\\User', 64),
(43, 'App\\Models\\User', 64),
(44, 'App\\Models\\User', 64),
(45, 'App\\Models\\User', 64),
(46, 'App\\Models\\User', 64),
(47, 'App\\Models\\User', 64),
(48, 'App\\Models\\User', 64),
(49, 'App\\Models\\User', 64),
(82, 'App\\Models\\User', 64),
(83, 'App\\Models\\User', 64),
(86, 'App\\Models\\User', 64),
(89, 'App\\Models\\User', 64),
(90, 'App\\Models\\User', 64),
(146, 'App\\Models\\User', 64),
(147, 'App\\Models\\User', 64),
(148, 'App\\Models\\User', 64),
(149, 'App\\Models\\User', 64),
(155, 'App\\Models\\User', 64),
(158, 'App\\Models\\User', 64),
(159, 'App\\Models\\User', 64),
(160, 'App\\Models\\User', 64),
(161, 'App\\Models\\User', 64),
(162, 'App\\Models\\User', 64),
(163, 'App\\Models\\User', 64),
(164, 'App\\Models\\User', 64),
(165, 'App\\Models\\User', 64),
(166, 'App\\Models\\User', 64),
(167, 'App\\Models\\User', 64),
(168, 'App\\Models\\User', 64),
(169, 'App\\Models\\User', 64),
(170, 'App\\Models\\User', 64),
(171, 'App\\Models\\User', 64),
(172, 'App\\Models\\User', 64),
(173, 'App\\Models\\User', 64),
(174, 'App\\Models\\User', 64),
(175, 'App\\Models\\User', 64),
(176, 'App\\Models\\User', 64),
(177, 'App\\Models\\User', 64),
(178, 'App\\Models\\User', 64),
(179, 'App\\Models\\User', 64),
(180, 'App\\Models\\User', 64),
(181, 'App\\Models\\User', 64),
(183, 'App\\Models\\User', 64),
(3, 'App\\Models\\User', 65),
(4, 'App\\Models\\User', 65),
(5, 'App\\Models\\User', 65),
(11, 'App\\Models\\User', 65),
(15, 'App\\Models\\User', 65),
(19, 'App\\Models\\User', 65),
(20, 'App\\Models\\User', 65),
(21, 'App\\Models\\User', 65),
(26, 'App\\Models\\User', 65),
(27, 'App\\Models\\User', 65),
(86, 'App\\Models\\User', 65),
(89, 'App\\Models\\User', 65),
(90, 'App\\Models\\User', 65),
(3, 'App\\Models\\User', 66),
(4, 'App\\Models\\User', 66),
(5, 'App\\Models\\User', 66),
(11, 'App\\Models\\User', 66),
(15, 'App\\Models\\User', 66),
(19, 'App\\Models\\User', 66),
(20, 'App\\Models\\User', 66),
(21, 'App\\Models\\User', 66),
(26, 'App\\Models\\User', 66),
(27, 'App\\Models\\User', 66),
(86, 'App\\Models\\User', 66),
(89, 'App\\Models\\User', 66),
(90, 'App\\Models\\User', 66),
(185, 'App\\Models\\User', 66),
(3, 'App\\Models\\User', 67),
(4, 'App\\Models\\User', 67),
(5, 'App\\Models\\User', 67),
(11, 'App\\Models\\User', 67),
(15, 'App\\Models\\User', 67),
(19, 'App\\Models\\User', 67),
(20, 'App\\Models\\User', 67),
(21, 'App\\Models\\User', 67),
(26, 'App\\Models\\User', 67),
(27, 'App\\Models\\User', 67),
(86, 'App\\Models\\User', 67),
(89, 'App\\Models\\User', 67),
(90, 'App\\Models\\User', 67),
(2, 'App\\Models\\User', 68),
(4, 'App\\Models\\User', 68),
(5, 'App\\Models\\User', 68),
(11, 'App\\Models\\User', 68),
(15, 'App\\Models\\User', 68),
(18, 'App\\Models\\User', 68),
(20, 'App\\Models\\User', 68),
(21, 'App\\Models\\User', 68),
(26, 'App\\Models\\User', 68),
(27, 'App\\Models\\User', 68),
(89, 'App\\Models\\User', 68),
(90, 'App\\Models\\User', 68),
(184, 'App\\Models\\User', 68),
(2, 'App\\Models\\User', 69),
(4, 'App\\Models\\User', 69),
(5, 'App\\Models\\User', 69),
(11, 'App\\Models\\User', 69),
(15, 'App\\Models\\User', 69),
(16, 'App\\Models\\User', 69),
(18, 'App\\Models\\User', 69),
(20, 'App\\Models\\User', 69),
(21, 'App\\Models\\User', 69),
(22, 'App\\Models\\User', 69),
(26, 'App\\Models\\User', 69),
(27, 'App\\Models\\User', 69),
(28, 'App\\Models\\User', 69),
(84, 'App\\Models\\User', 69),
(86, 'App\\Models\\User', 69),
(89, 'App\\Models\\User', 69),
(90, 'App\\Models\\User', 69),
(3, 'App\\Models\\User', 70),
(4, 'App\\Models\\User', 70),
(5, 'App\\Models\\User', 70),
(11, 'App\\Models\\User', 70),
(15, 'App\\Models\\User', 70),
(20, 'App\\Models\\User', 70),
(21, 'App\\Models\\User', 70),
(26, 'App\\Models\\User', 70),
(27, 'App\\Models\\User', 70),
(86, 'App\\Models\\User', 70),
(89, 'App\\Models\\User', 70),
(90, 'App\\Models\\User', 70),
(2, 'App\\Models\\User', 71),
(5, 'App\\Models\\User', 71),
(6, 'App\\Models\\User', 71),
(11, 'App\\Models\\User', 71),
(14, 'App\\Models\\User', 71),
(16, 'App\\Models\\User', 71),
(17, 'App\\Models\\User', 71),
(20, 'App\\Models\\User', 71),
(21, 'App\\Models\\User', 71),
(22, 'App\\Models\\User', 71),
(24, 'App\\Models\\User', 71),
(28, 'App\\Models\\User', 71),
(30, 'App\\Models\\User', 71),
(32, 'App\\Models\\User', 71),
(35, 'App\\Models\\User', 71),
(37, 'App\\Models\\User', 71),
(42, 'App\\Models\\User', 71),
(51, 'App\\Models\\User', 71),
(82, 'App\\Models\\User', 71),
(84, 'App\\Models\\User', 71),
(85, 'App\\Models\\User', 71),
(86, 'App\\Models\\User', 71),
(87, 'App\\Models\\User', 71),
(91, 'App\\Models\\User', 71),
(95, 'App\\Models\\User', 71),
(155, 'App\\Models\\User', 71),
(3, 'App\\Models\\User', 72),
(4, 'App\\Models\\User', 72),
(11, 'App\\Models\\User', 72),
(15, 'App\\Models\\User', 72),
(20, 'App\\Models\\User', 72),
(21, 'App\\Models\\User', 72),
(26, 'App\\Models\\User', 72),
(27, 'App\\Models\\User', 72),
(86, 'App\\Models\\User', 72),
(89, 'App\\Models\\User', 72),
(90, 'App\\Models\\User', 72),
(1, 'App\\Models\\User', 73),
(4, 'App\\Models\\User', 73),
(5, 'App\\Models\\User', 73),
(6, 'App\\Models\\User', 73),
(7, 'App\\Models\\User', 73),
(8, 'App\\Models\\User', 73),
(9, 'App\\Models\\User', 73),
(10, 'App\\Models\\User', 73),
(11, 'App\\Models\\User', 73),
(12, 'App\\Models\\User', 73),
(13, 'App\\Models\\User', 73),
(14, 'App\\Models\\User', 73),
(15, 'App\\Models\\User', 73),
(18, 'App\\Models\\User', 73),
(20, 'App\\Models\\User', 73),
(21, 'App\\Models\\User', 73),
(22, 'App\\Models\\User', 73),
(23, 'App\\Models\\User', 73),
(24, 'App\\Models\\User', 73),
(25, 'App\\Models\\User', 73),
(26, 'App\\Models\\User', 73),
(27, 'App\\Models\\User', 73),
(28, 'App\\Models\\User', 73),
(31, 'App\\Models\\User', 73),
(51, 'App\\Models\\User', 73),
(54, 'App\\Models\\User', 73),
(55, 'App\\Models\\User', 73),
(56, 'App\\Models\\User', 73),
(57, 'App\\Models\\User', 73),
(58, 'App\\Models\\User', 73),
(59, 'App\\Models\\User', 73),
(60, 'App\\Models\\User', 73),
(61, 'App\\Models\\User', 73),
(62, 'App\\Models\\User', 73),
(63, 'App\\Models\\User', 73),
(64, 'App\\Models\\User', 73),
(65, 'App\\Models\\User', 73),
(66, 'App\\Models\\User', 73),
(67, 'App\\Models\\User', 73),
(68, 'App\\Models\\User', 73),
(69, 'App\\Models\\User', 73),
(70, 'App\\Models\\User', 73),
(71, 'App\\Models\\User', 73),
(72, 'App\\Models\\User', 73),
(73, 'App\\Models\\User', 73),
(74, 'App\\Models\\User', 73),
(75, 'App\\Models\\User', 73),
(76, 'App\\Models\\User', 73),
(77, 'App\\Models\\User', 73),
(78, 'App\\Models\\User', 73),
(79, 'App\\Models\\User', 73),
(80, 'App\\Models\\User', 73),
(81, 'App\\Models\\User', 73),
(82, 'App\\Models\\User', 73),
(83, 'App\\Models\\User', 73),
(84, 'App\\Models\\User', 73),
(86, 'App\\Models\\User', 73),
(87, 'App\\Models\\User', 73),
(90, 'App\\Models\\User', 73),
(91, 'App\\Models\\User', 73),
(184, 'App\\Models\\User', 73),
(2, 'App\\Models\\User', 74),
(4, 'App\\Models\\User', 74),
(5, 'App\\Models\\User', 74),
(6, 'App\\Models\\User', 74),
(7, 'App\\Models\\User', 74),
(9, 'App\\Models\\User', 74),
(11, 'App\\Models\\User', 74),
(12, 'App\\Models\\User', 74),
(13, 'App\\Models\\User', 74),
(14, 'App\\Models\\User', 74),
(15, 'App\\Models\\User', 74),
(18, 'App\\Models\\User', 74),
(20, 'App\\Models\\User', 74),
(21, 'App\\Models\\User', 74),
(22, 'App\\Models\\User', 74),
(23, 'App\\Models\\User', 74),
(26, 'App\\Models\\User', 74),
(27, 'App\\Models\\User', 74),
(31, 'App\\Models\\User', 74),
(51, 'App\\Models\\User', 74),
(54, 'App\\Models\\User', 74),
(63, 'App\\Models\\User', 74),
(64, 'App\\Models\\User', 74),
(66, 'App\\Models\\User', 74),
(67, 'App\\Models\\User', 74),
(69, 'App\\Models\\User', 74),
(72, 'App\\Models\\User', 74),
(73, 'App\\Models\\User', 74),
(74, 'App\\Models\\User', 74),
(76, 'App\\Models\\User', 74),
(78, 'App\\Models\\User', 74),
(79, 'App\\Models\\User', 74),
(81, 'App\\Models\\User', 74),
(86, 'App\\Models\\User', 74),
(89, 'App\\Models\\User', 74),
(90, 'App\\Models\\User', 74),
(185, 'App\\Models\\User', 74),
(3, 'App\\Models\\User', 75),
(4, 'App\\Models\\User', 75),
(5, 'App\\Models\\User', 75),
(11, 'App\\Models\\User', 75),
(15, 'App\\Models\\User', 75),
(19, 'App\\Models\\User', 75),
(20, 'App\\Models\\User', 75),
(21, 'App\\Models\\User', 75),
(26, 'App\\Models\\User', 75),
(27, 'App\\Models\\User', 75),
(86, 'App\\Models\\User', 75),
(89, 'App\\Models\\User', 75),
(90, 'App\\Models\\User', 75),
(3, 'App\\Models\\User', 76),
(4, 'App\\Models\\User', 76),
(5, 'App\\Models\\User', 76),
(11, 'App\\Models\\User', 76),
(15, 'App\\Models\\User', 76),
(19, 'App\\Models\\User', 76),
(20, 'App\\Models\\User', 76),
(21, 'App\\Models\\User', 76),
(26, 'App\\Models\\User', 76),
(27, 'App\\Models\\User', 76),
(89, 'App\\Models\\User', 76),
(3, 'App\\Models\\User', 77),
(4, 'App\\Models\\User', 77),
(5, 'App\\Models\\User', 77),
(11, 'App\\Models\\User', 77),
(15, 'App\\Models\\User', 77),
(19, 'App\\Models\\User', 77),
(20, 'App\\Models\\User', 77),
(27, 'App\\Models\\User', 77),
(52, 'App\\Models\\User', 77),
(86, 'App\\Models\\User', 77),
(3, 'App\\Models\\User', 78),
(4, 'App\\Models\\User', 78),
(11, 'App\\Models\\User', 78),
(15, 'App\\Models\\User', 78),
(19, 'App\\Models\\User', 78),
(26, 'App\\Models\\User', 78),
(27, 'App\\Models\\User', 78),
(86, 'App\\Models\\User', 78),
(89, 'App\\Models\\User', 78),
(90, 'App\\Models\\User', 78),
(3, 'App\\Models\\User', 79),
(4, 'App\\Models\\User', 79),
(11, 'App\\Models\\User', 79),
(15, 'App\\Models\\User', 79),
(19, 'App\\Models\\User', 79),
(27, 'App\\Models\\User', 79),
(53, 'App\\Models\\User', 79),
(66, 'App\\Models\\User', 79),
(86, 'App\\Models\\User', 79),
(89, 'App\\Models\\User', 79),
(90, 'App\\Models\\User', 79),
(3, 'App\\Models\\User', 80),
(19, 'App\\Models\\User', 80),
(21, 'App\\Models\\User', 80),
(27, 'App\\Models\\User', 80),
(53, 'App\\Models\\User', 80),
(66, 'App\\Models\\User', 80),
(67, 'App\\Models\\User', 80),
(89, 'App\\Models\\User', 80),
(90, 'App\\Models\\User', 80),
(3, 'App\\Models\\User', 81),
(4, 'App\\Models\\User', 81),
(11, 'App\\Models\\User', 81),
(19, 'App\\Models\\User', 81),
(21, 'App\\Models\\User', 81),
(27, 'App\\Models\\User', 81),
(53, 'App\\Models\\User', 81),
(66, 'App\\Models\\User', 81),
(89, 'App\\Models\\User', 81),
(90, 'App\\Models\\User', 81),
(3, 'App\\Models\\User', 82),
(4, 'App\\Models\\User', 82),
(5, 'App\\Models\\User', 82),
(11, 'App\\Models\\User', 82),
(19, 'App\\Models\\User', 82),
(27, 'App\\Models\\User', 82),
(53, 'App\\Models\\User', 82),
(66, 'App\\Models\\User', 82),
(67, 'App\\Models\\User', 82),
(89, 'App\\Models\\User', 82),
(90, 'App\\Models\\User', 82),
(3, 'App\\Models\\User', 83),
(4, 'App\\Models\\User', 83),
(11, 'App\\Models\\User', 83),
(19, 'App\\Models\\User', 83),
(20, 'App\\Models\\User', 83),
(21, 'App\\Models\\User', 83),
(26, 'App\\Models\\User', 83),
(27, 'App\\Models\\User', 83),
(53, 'App\\Models\\User', 83),
(66, 'App\\Models\\User', 83),
(67, 'App\\Models\\User', 83),
(89, 'App\\Models\\User', 83),
(90, 'App\\Models\\User', 83),
(3, 'App\\Models\\User', 84),
(4, 'App\\Models\\User', 84),
(11, 'App\\Models\\User', 84),
(19, 'App\\Models\\User', 84),
(21, 'App\\Models\\User', 84),
(27, 'App\\Models\\User', 84),
(53, 'App\\Models\\User', 84),
(66, 'App\\Models\\User', 84),
(67, 'App\\Models\\User', 84),
(89, 'App\\Models\\User', 84),
(90, 'App\\Models\\User', 84),
(3, 'App\\Models\\User', 85),
(4, 'App\\Models\\User', 85),
(11, 'App\\Models\\User', 85),
(21, 'App\\Models\\User', 85),
(27, 'App\\Models\\User', 85),
(53, 'App\\Models\\User', 85),
(66, 'App\\Models\\User', 85),
(67, 'App\\Models\\User', 85),
(89, 'App\\Models\\User', 85),
(90, 'App\\Models\\User', 85),
(3, 'App\\Models\\User', 86),
(4, 'App\\Models\\User', 86),
(11, 'App\\Models\\User', 86),
(21, 'App\\Models\\User', 86),
(27, 'App\\Models\\User', 86),
(53, 'App\\Models\\User', 86),
(66, 'App\\Models\\User', 86),
(67, 'App\\Models\\User', 86),
(89, 'App\\Models\\User', 86),
(90, 'App\\Models\\User', 86),
(3, 'App\\Models\\User', 87),
(4, 'App\\Models\\User', 87),
(11, 'App\\Models\\User', 87),
(19, 'App\\Models\\User', 87),
(21, 'App\\Models\\User', 87),
(27, 'App\\Models\\User', 87),
(53, 'App\\Models\\User', 87),
(66, 'App\\Models\\User', 87),
(67, 'App\\Models\\User', 87),
(89, 'App\\Models\\User', 87),
(90, 'App\\Models\\User', 87),
(3, 'App\\Models\\User', 88),
(4, 'App\\Models\\User', 88),
(11, 'App\\Models\\User', 88),
(19, 'App\\Models\\User', 88),
(21, 'App\\Models\\User', 88),
(27, 'App\\Models\\User', 88),
(53, 'App\\Models\\User', 88),
(66, 'App\\Models\\User', 88),
(67, 'App\\Models\\User', 88),
(89, 'App\\Models\\User', 88),
(90, 'App\\Models\\User', 88),
(3, 'App\\Models\\User', 89),
(4, 'App\\Models\\User', 89),
(11, 'App\\Models\\User', 89),
(19, 'App\\Models\\User', 89),
(21, 'App\\Models\\User', 89),
(27, 'App\\Models\\User', 89),
(53, 'App\\Models\\User', 89),
(66, 'App\\Models\\User', 89),
(67, 'App\\Models\\User', 89),
(89, 'App\\Models\\User', 89),
(90, 'App\\Models\\User', 89),
(3, 'App\\Models\\User', 90),
(4, 'App\\Models\\User', 90),
(11, 'App\\Models\\User', 90),
(19, 'App\\Models\\User', 90),
(21, 'App\\Models\\User', 90),
(27, 'App\\Models\\User', 90),
(53, 'App\\Models\\User', 90),
(66, 'App\\Models\\User', 90),
(67, 'App\\Models\\User', 90),
(89, 'App\\Models\\User', 90),
(90, 'App\\Models\\User', 90),
(3, 'App\\Models\\User', 91),
(4, 'App\\Models\\User', 91),
(11, 'App\\Models\\User', 91),
(19, 'App\\Models\\User', 91),
(21, 'App\\Models\\User', 91),
(27, 'App\\Models\\User', 91),
(53, 'App\\Models\\User', 91),
(66, 'App\\Models\\User', 91),
(67, 'App\\Models\\User', 91),
(89, 'App\\Models\\User', 91),
(90, 'App\\Models\\User', 91),
(3, 'App\\Models\\User', 92),
(4, 'App\\Models\\User', 92),
(11, 'App\\Models\\User', 92),
(19, 'App\\Models\\User', 92),
(21, 'App\\Models\\User', 92),
(27, 'App\\Models\\User', 92),
(89, 'App\\Models\\User', 92),
(90, 'App\\Models\\User', 92),
(3, 'App\\Models\\User', 93),
(4, 'App\\Models\\User', 93),
(11, 'App\\Models\\User', 93),
(19, 'App\\Models\\User', 93),
(21, 'App\\Models\\User', 93),
(27, 'App\\Models\\User', 93),
(89, 'App\\Models\\User', 93),
(90, 'App\\Models\\User', 93),
(3, 'App\\Models\\User', 94),
(4, 'App\\Models\\User', 94),
(11, 'App\\Models\\User', 94),
(19, 'App\\Models\\User', 94),
(21, 'App\\Models\\User', 94),
(27, 'App\\Models\\User', 94),
(53, 'App\\Models\\User', 94),
(66, 'App\\Models\\User', 94),
(67, 'App\\Models\\User', 94),
(89, 'App\\Models\\User', 94),
(90, 'App\\Models\\User', 94),
(3, 'App\\Models\\User', 95),
(4, 'App\\Models\\User', 95),
(11, 'App\\Models\\User', 95),
(19, 'App\\Models\\User', 95),
(21, 'App\\Models\\User', 95),
(27, 'App\\Models\\User', 95),
(53, 'App\\Models\\User', 95),
(66, 'App\\Models\\User', 95),
(67, 'App\\Models\\User', 95),
(89, 'App\\Models\\User', 95),
(90, 'App\\Models\\User', 95),
(3, 'App\\Models\\User', 96),
(19, 'App\\Models\\User', 96),
(21, 'App\\Models\\User', 96),
(22, 'App\\Models\\User', 96),
(89, 'App\\Models\\User', 96),
(90, 'App\\Models\\User', 96),
(3, 'App\\Models\\User', 99),
(4, 'App\\Models\\User', 99),
(5, 'App\\Models\\User', 99),
(11, 'App\\Models\\User', 99),
(15, 'App\\Models\\User', 99),
(18, 'App\\Models\\User', 99),
(20, 'App\\Models\\User', 99),
(21, 'App\\Models\\User', 99),
(26, 'App\\Models\\User', 99),
(27, 'App\\Models\\User', 99),
(89, 'App\\Models\\User', 99),
(90, 'App\\Models\\User', 99),
(184, 'App\\Models\\User', 99),
(2, 'App\\Models\\User', 100),
(18, 'App\\Models\\User', 100),
(20, 'App\\Models\\User', 100),
(21, 'App\\Models\\User', 100),
(26, 'App\\Models\\User', 100),
(27, 'App\\Models\\User', 100),
(28, 'App\\Models\\User', 100),
(84, 'App\\Models\\User', 100),
(89, 'App\\Models\\User', 100),
(2, 'App\\Models\\User', 101),
(5, 'App\\Models\\User', 101),
(16, 'App\\Models\\User', 101),
(18, 'App\\Models\\User', 101),
(20, 'App\\Models\\User', 101),
(21, 'App\\Models\\User', 101),
(26, 'App\\Models\\User', 101),
(27, 'App\\Models\\User', 101),
(28, 'App\\Models\\User', 101),
(84, 'App\\Models\\User', 101),
(89, 'App\\Models\\User', 101),
(91, 'App\\Models\\User', 101),
(3, 'App\\Models\\User', 102),
(4, 'App\\Models\\User', 102),
(11, 'App\\Models\\User', 102),
(89, 'App\\Models\\User', 102),
(90, 'App\\Models\\User', 102),
(185, 'App\\Models\\User', 102),
(3, 'App\\Models\\User', 103),
(4, 'App\\Models\\User', 103),
(11, 'App\\Models\\User', 103),
(89, 'App\\Models\\User', 103),
(90, 'App\\Models\\User', 103),
(185, 'App\\Models\\User', 103),
(1, 'App\\Models\\User', 104),
(4, 'App\\Models\\User', 104),
(5, 'App\\Models\\User', 104),
(6, 'App\\Models\\User', 104),
(7, 'App\\Models\\User', 104),
(8, 'App\\Models\\User', 104),
(9, 'App\\Models\\User', 104),
(10, 'App\\Models\\User', 104),
(11, 'App\\Models\\User', 104),
(12, 'App\\Models\\User', 104),
(13, 'App\\Models\\User', 104),
(14, 'App\\Models\\User', 104),
(15, 'App\\Models\\User', 104),
(17, 'App\\Models\\User', 104),
(20, 'App\\Models\\User', 104),
(21, 'App\\Models\\User', 104),
(22, 'App\\Models\\User', 104),
(23, 'App\\Models\\User', 104),
(24, 'App\\Models\\User', 104),
(25, 'App\\Models\\User', 104),
(26, 'App\\Models\\User', 104),
(27, 'App\\Models\\User', 104),
(31, 'App\\Models\\User', 104),
(34, 'App\\Models\\User', 104),
(51, 'App\\Models\\User', 104),
(54, 'App\\Models\\User', 104),
(55, 'App\\Models\\User', 104),
(57, 'App\\Models\\User', 104),
(58, 'App\\Models\\User', 104),
(59, 'App\\Models\\User', 104),
(60, 'App\\Models\\User', 104),
(61, 'App\\Models\\User', 104),
(62, 'App\\Models\\User', 104),
(63, 'App\\Models\\User', 104),
(64, 'App\\Models\\User', 104),
(65, 'App\\Models\\User', 104),
(66, 'App\\Models\\User', 104),
(67, 'App\\Models\\User', 104),
(68, 'App\\Models\\User', 104),
(69, 'App\\Models\\User', 104),
(70, 'App\\Models\\User', 104),
(71, 'App\\Models\\User', 104),
(72, 'App\\Models\\User', 104),
(73, 'App\\Models\\User', 104),
(74, 'App\\Models\\User', 104),
(75, 'App\\Models\\User', 104),
(76, 'App\\Models\\User', 104),
(77, 'App\\Models\\User', 104),
(78, 'App\\Models\\User', 104),
(79, 'App\\Models\\User', 104),
(80, 'App\\Models\\User', 104),
(81, 'App\\Models\\User', 104),
(82, 'App\\Models\\User', 104),
(83, 'App\\Models\\User', 104),
(86, 'App\\Models\\User', 104),
(87, 'App\\Models\\User', 104),
(90, 'App\\Models\\User', 104),
(131, 'App\\Models\\User', 104),
(132, 'App\\Models\\User', 104),
(133, 'App\\Models\\User', 104),
(134, 'App\\Models\\User', 104),
(135, 'App\\Models\\User', 104),
(136, 'App\\Models\\User', 104),
(137, 'App\\Models\\User', 104),
(138, 'App\\Models\\User', 104),
(139, 'App\\Models\\User', 104),
(140, 'App\\Models\\User', 104),
(141, 'App\\Models\\User', 104),
(142, 'App\\Models\\User', 104),
(143, 'App\\Models\\User', 104),
(144, 'App\\Models\\User', 104),
(145, 'App\\Models\\User', 104),
(146, 'App\\Models\\User', 104),
(150, 'App\\Models\\User', 104),
(151, 'App\\Models\\User', 104),
(152, 'App\\Models\\User', 104),
(153, 'App\\Models\\User', 104),
(183, 'App\\Models\\User', 104),
(188, 'App\\Models\\User', 104),
(189, 'App\\Models\\User', 104),
(190, 'App\\Models\\User', 104),
(3, 'App\\Models\\User', 109),
(5, 'App\\Models\\User', 109),
(19, 'App\\Models\\User', 109),
(20, 'App\\Models\\User', 109),
(26, 'App\\Models\\User', 109),
(27, 'App\\Models\\User', 109),
(89, 'App\\Models\\User', 109),
(3, 'App\\Models\\User', 110),
(5, 'App\\Models\\User', 110),
(19, 'App\\Models\\User', 110),
(20, 'App\\Models\\User', 110),
(26, 'App\\Models\\User', 110),
(27, 'App\\Models\\User', 110),
(89, 'App\\Models\\User', 110),
(3, 'App\\Models\\User', 111),
(4, 'App\\Models\\User', 111),
(5, 'App\\Models\\User', 111),
(11, 'App\\Models\\User', 111),
(15, 'App\\Models\\User', 111),
(19, 'App\\Models\\User', 111),
(20, 'App\\Models\\User', 111),
(21, 'App\\Models\\User', 111),
(26, 'App\\Models\\User', 111),
(27, 'App\\Models\\User', 111),
(89, 'App\\Models\\User', 111),
(90, 'App\\Models\\User', 111),
(185, 'App\\Models\\User', 111),
(3, 'App\\Models\\User', 112),
(5, 'App\\Models\\User', 112),
(15, 'App\\Models\\User', 112),
(19, 'App\\Models\\User', 112),
(20, 'App\\Models\\User', 112),
(26, 'App\\Models\\User', 112),
(27, 'App\\Models\\User', 112),
(3, 'App\\Models\\User', 113),
(4, 'App\\Models\\User', 113),
(5, 'App\\Models\\User', 113),
(11, 'App\\Models\\User', 113),
(15, 'App\\Models\\User', 113),
(19, 'App\\Models\\User', 113),
(20, 'App\\Models\\User', 113),
(21, 'App\\Models\\User', 113),
(26, 'App\\Models\\User', 113),
(27, 'App\\Models\\User', 113),
(89, 'App\\Models\\User', 113),
(3, 'App\\Models\\User', 114),
(5, 'App\\Models\\User', 114),
(15, 'App\\Models\\User', 114),
(19, 'App\\Models\\User', 114),
(20, 'App\\Models\\User', 114),
(21, 'App\\Models\\User', 114),
(26, 'App\\Models\\User', 114),
(27, 'App\\Models\\User', 114),
(89, 'App\\Models\\User', 114),
(90, 'App\\Models\\User', 114),
(185, 'App\\Models\\User', 114),
(3, 'App\\Models\\User', 115),
(4, 'App\\Models\\User', 115),
(5, 'App\\Models\\User', 115),
(11, 'App\\Models\\User', 115),
(15, 'App\\Models\\User', 115),
(19, 'App\\Models\\User', 115),
(20, 'App\\Models\\User', 115),
(21, 'App\\Models\\User', 115),
(26, 'App\\Models\\User', 115),
(27, 'App\\Models\\User', 115),
(34, 'App\\Models\\User', 115),
(89, 'App\\Models\\User', 115),
(90, 'App\\Models\\User', 115),
(185, 'App\\Models\\User', 115),
(3, 'App\\Models\\User', 116),
(4, 'App\\Models\\User', 116),
(5, 'App\\Models\\User', 116),
(11, 'App\\Models\\User', 116),
(15, 'App\\Models\\User', 116),
(19, 'App\\Models\\User', 116),
(20, 'App\\Models\\User', 116),
(21, 'App\\Models\\User', 116),
(26, 'App\\Models\\User', 116),
(27, 'App\\Models\\User', 116),
(89, 'App\\Models\\User', 116),
(90, 'App\\Models\\User', 116),
(185, 'App\\Models\\User', 116),
(3, 'App\\Models\\User', 117),
(4, 'App\\Models\\User', 117),
(5, 'App\\Models\\User', 117),
(11, 'App\\Models\\User', 117),
(15, 'App\\Models\\User', 117),
(19, 'App\\Models\\User', 117),
(20, 'App\\Models\\User', 117),
(21, 'App\\Models\\User', 117),
(26, 'App\\Models\\User', 117),
(27, 'App\\Models\\User', 117),
(89, 'App\\Models\\User', 117),
(90, 'App\\Models\\User', 117),
(185, 'App\\Models\\User', 117),
(2, 'App\\Models\\User', 119),
(5, 'App\\Models\\User', 119),
(15, 'App\\Models\\User', 119),
(16, 'App\\Models\\User', 119),
(18, 'App\\Models\\User', 119),
(20, 'App\\Models\\User', 119),
(21, 'App\\Models\\User', 119),
(26, 'App\\Models\\User', 119),
(27, 'App\\Models\\User', 119),
(28, 'App\\Models\\User', 119),
(52, 'App\\Models\\User', 119),
(66, 'App\\Models\\User', 119),
(84, 'App\\Models\\User', 119),
(86, 'App\\Models\\User', 119),
(89, 'App\\Models\\User', 119),
(91, 'App\\Models\\User', 119),
(184, 'App\\Models\\User', 119),
(52, 'App\\Models\\User', 120),
(66, 'App\\Models\\User', 120),
(89, 'App\\Models\\User', 120),
(91, 'App\\Models\\User', 120),
(182, 'App\\Models\\User', 120),
(184, 'App\\Models\\User', 120),
(2, 'App\\Models\\User', 121),
(5, 'App\\Models\\User', 121),
(16, 'App\\Models\\User', 121),
(18, 'App\\Models\\User', 121),
(20, 'App\\Models\\User', 121),
(21, 'App\\Models\\User', 121),
(28, 'App\\Models\\User', 121),
(52, 'App\\Models\\User', 121),
(66, 'App\\Models\\User', 121),
(84, 'App\\Models\\User', 121),
(89, 'App\\Models\\User', 121),
(91, 'App\\Models\\User', 121),
(184, 'App\\Models\\User', 121),
(4, 'App\\Models\\User', 22),
(5, 'App\\Models\\User', 22),
(6, 'App\\Models\\User', 22),
(7, 'App\\Models\\User', 22),
(8, 'App\\Models\\User', 22),
(9, 'App\\Models\\User', 22),
(10, 'App\\Models\\User', 22),
(11, 'App\\Models\\User', 22),
(12, 'App\\Models\\User', 22),
(13, 'App\\Models\\User', 22),
(14, 'App\\Models\\User', 22),
(15, 'App\\Models\\User', 22),
(16, 'App\\Models\\User', 22),
(186, 'App\\Models\\User', 22),
(188, 'App\\Models\\User', 22),
(54, 'App\\Models\\User', 22),
(55, 'App\\Models\\User', 22),
(56, 'App\\Models\\User', 22),
(57, 'App\\Models\\User', 22),
(58, 'App\\Models\\User', 22),
(59, 'App\\Models\\User', 22),
(60, 'App\\Models\\User', 22),
(61, 'App\\Models\\User', 22),
(62, 'App\\Models\\User', 22),
(63, 'App\\Models\\User', 22),
(64, 'App\\Models\\User', 22),
(65, 'App\\Models\\User', 22),
(66, 'App\\Models\\User', 22),
(67, 'App\\Models\\User', 22),
(68, 'App\\Models\\User', 22),
(69, 'App\\Models\\User', 22),
(70, 'App\\Models\\User', 22),
(71, 'App\\Models\\User', 22),
(72, 'App\\Models\\User', 22),
(73, 'App\\Models\\User', 22),
(74, 'App\\Models\\User', 22),
(75, 'App\\Models\\User', 22),
(76, 'App\\Models\\User', 22),
(77, 'App\\Models\\User', 22),
(78, 'App\\Models\\User', 22),
(79, 'App\\Models\\User', 22),
(80, 'App\\Models\\User', 22),
(81, 'App\\Models\\User', 22),
(90, 'App\\Models\\User', 22),
(91, 'App\\Models\\User', 22),
(92, 'App\\Models\\User', 22),
(93, 'App\\Models\\User', 22),
(94, 'App\\Models\\User', 22),
(95, 'App\\Models\\User', 22),
(189, 'App\\Models\\User', 22),
(190, 'App\\Models\\User', 22),
(82, 'App\\Models\\User', 22),
(83, 'App\\Models\\User', 22),
(84, 'App\\Models\\User', 22),
(85, 'App\\Models\\User', 22),
(86, 'App\\Models\\User', 22),
(182, 'App\\Models\\User', 22),
(131, 'App\\Models\\User', 22),
(132, 'App\\Models\\User', 22),
(133, 'App\\Models\\User', 22),
(134, 'App\\Models\\User', 22),
(135, 'App\\Models\\User', 22),
(136, 'App\\Models\\User', 22),
(137, 'App\\Models\\User', 22),
(138, 'App\\Models\\User', 22),
(139, 'App\\Models\\User', 22),
(140, 'App\\Models\\User', 22),
(141, 'App\\Models\\User', 22),
(142, 'App\\Models\\User', 22),
(143, 'App\\Models\\User', 22),
(144, 'App\\Models\\User', 22),
(145, 'App\\Models\\User', 22),
(146, 'App\\Models\\User', 22),
(147, 'App\\Models\\User', 22),
(148, 'App\\Models\\User', 22),
(149, 'App\\Models\\User', 22),
(150, 'App\\Models\\User', 22),
(151, 'App\\Models\\User', 22),
(152, 'App\\Models\\User', 22),
(153, 'App\\Models\\User', 22),
(154, 'App\\Models\\User', 22),
(187, 'App\\Models\\User', 22),
(20, 'App\\Models\\User', 22),
(21, 'App\\Models\\User', 22),
(22, 'App\\Models\\User', 22),
(23, 'App\\Models\\User', 22),
(24, 'App\\Models\\User', 22),
(25, 'App\\Models\\User', 22),
(26, 'App\\Models\\User', 22),
(27, 'App\\Models\\User', 22),
(28, 'App\\Models\\User', 22),
(29, 'App\\Models\\User', 22),
(30, 'App\\Models\\User', 22),
(31, 'App\\Models\\User', 22),
(35, 'App\\Models\\User', 22),
(36, 'App\\Models\\User', 22),
(37, 'App\\Models\\User', 22),
(38, 'App\\Models\\User', 22),
(39, 'App\\Models\\User', 22),
(40, 'App\\Models\\User', 22),
(41, 'App\\Models\\User', 22),
(42, 'App\\Models\\User', 22),
(43, 'App\\Models\\User', 22),
(44, 'App\\Models\\User', 22),
(45, 'App\\Models\\User', 22),
(46, 'App\\Models\\User', 22),
(47, 'App\\Models\\User', 22),
(48, 'App\\Models\\User', 22),
(49, 'App\\Models\\User', 22),
(158, 'App\\Models\\User', 22),
(159, 'App\\Models\\User', 22),
(160, 'App\\Models\\User', 22),
(161, 'App\\Models\\User', 22),
(162, 'App\\Models\\User', 22),
(163, 'App\\Models\\User', 22),
(164, 'App\\Models\\User', 22),
(165, 'App\\Models\\User', 22),
(166, 'App\\Models\\User', 22),
(167, 'App\\Models\\User', 22),
(168, 'App\\Models\\User', 22),
(169, 'App\\Models\\User', 22),
(170, 'App\\Models\\User', 22),
(171, 'App\\Models\\User', 22),
(172, 'App\\Models\\User', 22),
(173, 'App\\Models\\User', 22),
(174, 'App\\Models\\User', 22),
(175, 'App\\Models\\User', 22),
(176, 'App\\Models\\User', 22),
(177, 'App\\Models\\User', 22),
(178, 'App\\Models\\User', 22),
(179, 'App\\Models\\User', 22),
(180, 'App\\Models\\User', 22),
(181, 'App\\Models\\User', 22),
(1, 'App\\Models\\User', 22),
(17, 'App\\Models\\User', 22),
(32, 'App\\Models\\User', 22),
(155, 'App\\Models\\User', 22),
(183, 'App\\Models\\User', 22),
(51, 'App\\Models\\User', 22),
(87, 'App\\Models\\User', 22);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(15, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 49),
(6, 'App\\Models\\User', 50),
(8, 'App\\Models\\User', 52),
(1, 'App\\Models\\User', 53),
(1, 'App\\Models\\User', 54),
(1, 'App\\Models\\User', 55),
(1, 'App\\Models\\User', 56),
(1, 'App\\Models\\User', 57),
(1, 'App\\Models\\User', 58),
(1, 'App\\Models\\User', 59),
(1, 'App\\Models\\User', 60),
(1, 'App\\Models\\User', 61),
(1, 'App\\Models\\User', 62),
(1, 'App\\Models\\User', 63),
(1, 'App\\Models\\User', 64),
(1, 'App\\Models\\User', 65),
(1, 'App\\Models\\User', 66),
(1, 'App\\Models\\User', 67),
(1, 'App\\Models\\User', 68),
(1, 'App\\Models\\User', 69),
(1, 'App\\Models\\User', 70),
(12, 'App\\Models\\User', 71),
(2, 'App\\Models\\User', 72),
(2, 'App\\Models\\User', 73),
(2, 'App\\Models\\User', 74),
(2, 'App\\Models\\User', 75),
(2, 'App\\Models\\User', 76),
(1, 'App\\Models\\User', 77),
(9, 'App\\Models\\User', 78),
(3, 'App\\Models\\User', 79),
(3, 'App\\Models\\User', 80),
(3, 'App\\Models\\User', 81),
(3, 'App\\Models\\User', 82),
(3, 'App\\Models\\User', 83),
(4, 'App\\Models\\User', 84),
(4, 'App\\Models\\User', 85),
(4, 'App\\Models\\User', 86),
(5, 'App\\Models\\User', 87),
(5, 'App\\Models\\User', 88),
(5, 'App\\Models\\User', 89),
(5, 'App\\Models\\User', 90),
(5, 'App\\Models\\User', 91),
(6, 'App\\Models\\User', 92),
(7, 'App\\Models\\User', 93),
(3, 'App\\Models\\User', 94),
(3, 'App\\Models\\User', 95),
(6, 'App\\Models\\User', 96),
(1, 'App\\Models\\User', 97),
(1, 'App\\Models\\User', 98),
(1, 'App\\Models\\User', 99),
(12, 'App\\Models\\User', 100),
(12, 'App\\Models\\User', 101),
(6, 'App\\Models\\User', 102),
(3, 'App\\Models\\User', 103),
(1, 'App\\Models\\User', 104),
(1, 'App\\Models\\User', 105),
(15, 'App\\Models\\User', 111),
(15, 'App\\Models\\User', 113),
(15, 'App\\Models\\User', 114),
(15, 'App\\Models\\User', 115),
(15, 'App\\Models\\User', 116),
(15, 'App\\Models\\User', 117),
(12, 'App\\Models\\User', 120),
(12, 'App\\Models\\User', 121);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_driver_ot_requests`
--

DROP TABLE IF EXISTS `monthly_driver_ot_requests`;
CREATE TABLE IF NOT EXISTS `monthly_driver_ot_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `date` date NOT NULL,
  `manager_main_status` int(11) NOT NULL DEFAULT '0',
  `account_main_status` int(11) NOT NULL DEFAULT '0',
  `gm_main_status` int(11) NOT NULL DEFAULT '0',
  `manager_reason` text COLLATE utf8mb4_unicode_ci,
  `account_reason` text COLLATE utf8mb4_unicode_ci,
  `gm_reason` text COLLATE utf8mb4_unicode_ci,
  `manager_change_date` datetime DEFAULT NULL,
  `account_change_date` datetime DEFAULT NULL,
  `gm_change_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_driver_ot_request_details`
--

DROP TABLE IF EXISTS `monthly_driver_ot_request_details`;
CREATE TABLE IF NOT EXISTS `monthly_driver_ot_request_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `monthly_ot_request_id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `ot_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_date` date NOT NULL,
  `start_from_time` time NOT NULL,
  `start_to_time` time NOT NULL,
  `start_break_hour` int(11) NOT NULL DEFAULT '0',
  `start_break_minute` int(11) NOT NULL DEFAULT '0',
  `start_reason` text COLLATE utf8mb4_unicode_ci,
  `start_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `start_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `end_from_time` time DEFAULT NULL,
  `end_to_time` time DEFAULT NULL,
  `end_break_hour` int(11) NOT NULL DEFAULT '0',
  `end_break_minute` int(11) NOT NULL DEFAULT '0',
  `end_reason` text COLLATE utf8mb4_unicode_ci,
  `end_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `end_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `morning_taxi_time` tinyint(4) NOT NULL DEFAULT '0',
  `evening_taxi_time` tinyint(4) NOT NULL DEFAULT '0',
  `manager_status` tinyint(4) NOT NULL DEFAULT '0',
  `account_status` tinyint(4) NOT NULL DEFAULT '0',
  `gm_status` tinyint(4) NOT NULL DEFAULT '0',
  `manager_status_reason` text COLLATE utf8mb4_unicode_ci,
  `account_status_reason` text COLLATE utf8mb4_unicode_ci,
  `gm_status_reason` text COLLATE utf8mb4_unicode_ci,
  `manager_change_date` datetime DEFAULT NULL,
  `manager_change_by` int(11) NOT NULL DEFAULT '0',
  `account_change_date` datetime DEFAULT NULL,
  `account_change_by` int(11) NOT NULL DEFAULT '0',
  `gm_change_date` datetime DEFAULT NULL,
  `gm_change_by` int(11) NOT NULL DEFAULT '0',
  `attendance` tinyint(4) NOT NULL DEFAULT '0',
  `day_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_ot_requests`
--

DROP TABLE IF EXISTS `monthly_ot_requests`;
CREATE TABLE IF NOT EXISTS `monthly_ot_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `date` date NOT NULL,
  `manager_main_status` int(11) NOT NULL DEFAULT '0',
  `account_main_status` int(11) NOT NULL DEFAULT '0',
  `gm_main_status` int(11) NOT NULL DEFAULT '0',
  `manager_reason` text COLLATE utf8mb4_unicode_ci,
  `account_reason` text COLLATE utf8mb4_unicode_ci,
  `gm_reason` text COLLATE utf8mb4_unicode_ci,
  `manager_change_date` datetime DEFAULT NULL,
  `account_change_date` datetime DEFAULT NULL,
  `gm_change_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_ot_request_details`
--

DROP TABLE IF EXISTS `monthly_ot_request_details`;
CREATE TABLE IF NOT EXISTS `monthly_ot_request_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `monthly_ot_request_id` int(11) NOT NULL,
  `daily_ot_request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `ot_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_date` date NOT NULL,
  `start_from_time` time NOT NULL,
  `start_to_time` time NOT NULL,
  `start_break_hour` int(11) NOT NULL DEFAULT '0',
  `start_break_minute` int(11) NOT NULL DEFAULT '0',
  `start_reason` text COLLATE utf8mb4_unicode_ci,
  `start_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `start_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `end_from_time` time DEFAULT NULL,
  `end_to_time` time DEFAULT NULL,
  `end_break_hour` int(11) NOT NULL DEFAULT '0',
  `end_break_minute` int(11) NOT NULL DEFAULT '0',
  `end_reason` text COLLATE utf8mb4_unicode_ci,
  `end_hotel` tinyint(4) NOT NULL DEFAULT '0',
  `end_next_day` tinyint(4) NOT NULL DEFAULT '0',
  `manager_status` tinyint(4) NOT NULL DEFAULT '0',
  `account_status` tinyint(4) NOT NULL DEFAULT '0',
  `gm_status` tinyint(4) NOT NULL DEFAULT '0',
  `manager_status_reason` text COLLATE utf8mb4_unicode_ci,
  `account_status_reason` text COLLATE utf8mb4_unicode_ci,
  `gm_status_reason` text COLLATE utf8mb4_unicode_ci,
  `manager_change_date` datetime DEFAULT NULL,
  `manager_change_by` int(11) NOT NULL DEFAULT '0',
  `account_change_date` datetime DEFAULT NULL,
  `account_change_by` int(11) NOT NULL DEFAULT '0',
  `gm_change_date` datetime DEFAULT NULL,
  `gm_change_by` int(11) NOT NULL DEFAULT '0',
  `daily_request` tinyint(4) NOT NULL DEFAULT '0',
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ns_actual_taxes`
--

DROP TABLE IF EXISTS `ns_actual_taxes`;
CREATE TABLE IF NOT EXISTS `ns_actual_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `tax_for` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_period` date DEFAULT NULL,
  `tax_amount_mmk` double(8,2) NOT NULL,
  `exchange_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `tax_amount_usd` double(8,2) NOT NULL,
  `pay_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ns_employees`
--

DROP TABLE IF EXISTS `ns_employees`;
CREATE TABLE IF NOT EXISTS `ns_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `hourly_rate` double(10,2) DEFAULT NULL,
  `ot_rate` double(10,2) DEFAULT NULL,
  `nrc_no` text COLLATE utf8mb4_unicode_ci,
  `religion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `microsoft_word` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `microsoft_excel` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `microsoft_powerpoint` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_address` text COLLATE utf8mb4_unicode_ci,
  `new_address` text COLLATE utf8mb4_unicode_ci,
  `new_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `others_address` text COLLATE utf8mb4_unicode_ci,
  `others_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employment_contract_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ns_employees`
--

INSERT INTO `ns_employees` (`id`, `user_id`, `grade`, `hourly_rate`, `ot_rate`, `nrc_no`, `religion`, `race`, `microsoft_word`, `microsoft_excel`, `microsoft_powerpoint`, `current_address`, `new_address`, `new_phone`, `others_address`, `others_phone`, `employment_contract_no`, `created_at`, `updated_at`) VALUES
(2, 50, NULL, NULL, NULL, '12/OuKaTa(N)203067', NULL, NULL, 'myanmar,english', 'basic', 'basic', 'No.1115,yathawaddy 17th Street,South Oakkala Tsp.', NULL, NULL, NULL, NULL, '00057', '2023-03-28 04:56:47', '2023-06-08 02:08:43'),
(3, 52, NULL, 1.92, NULL, '12/DAGAYA(N)018202', 'BUDDHISM', 'BURMA', 'myanmar,english', 'basic', 'basic', '250/A, BOMYINTAUNG STREET, 12 WARD, EAST DAGON T/S, YANGON.', NULL, NULL, NULL, NULL, NULL, '2023-03-29 08:56:17', '2023-06-28 06:48:54'),
(4, 22, NULL, NULL, NULL, '321', NULL, NULL, NULL, NULL, NULL, '321', NULL, NULL, NULL, NULL, NULL, '2023-04-04 18:07:56', '2023-06-21 16:24:42'),
(5, 73, NULL, NULL, NULL, '12/MaGaDa(N)128905', NULL, NULL, NULL, NULL, NULL, 'Room No (02), Building No (6214), Thukkha Theddi Ward, Zabu Thiri Township, Nay Pyi Taw', NULL, NULL, NULL, NULL, NULL, '2023-04-25 03:03:23', '2023-05-09 07:41:32'),
(6, 78, NULL, 1.41, NULL, '12/KAMAYA(N)070652', NULL, NULL, 'english', 'basic', 'basic', 'No.1,room-61,Beside of AA medical Pacific center , Kamayut township.', NULL, NULL, NULL, NULL, NULL, '2023-05-08 06:16:00', '2023-05-08 07:01:26'),
(7, 104, NULL, NULL, NULL, '7/LaPaTa(N)127236', NULL, NULL, 'myanmar,english,japanese', 'intermediate', 'intermediate', 'No. 372, Htanee Street, Insein Township, Yangon', NULL, NULL, NULL, NULL, NULL, '2023-05-09 07:28:47', '2023-05-29 03:27:39'),
(8, 53, NULL, NULL, NULL, '12/UkaTa(N)185445', NULL, NULL, NULL, NULL, NULL, 'No.222(B), Barani Street, 12 Block, South Okkalapa Township', NULL, NULL, NULL, NULL, NULL, '2023-05-10 01:55:15', '2023-05-10 01:55:15'),
(9, 49, NULL, NULL, NULL, 'TZ1143392', NULL, 'Japan', NULL, NULL, NULL, 'TOSTA PLAZA, Laydaungkan Rd. Thingankyunt Township, Yangon, Myanmar', NULL, '09882417373', '8147 Ohba Fujisawa-shi, Kanagawa, Japan', '81-466-81-4810', '00001', '2023-05-11 05:27:46', '2023-06-08 02:07:03'),
(10, 74, NULL, NULL, NULL, '8/PaKhaKa(N)213710', NULL, NULL, NULL, NULL, NULL, 'No. 144, Pauk Taw Village, Pobbathiri Township, Naypyitaw', NULL, NULL, NULL, NULL, NULL, '2023-05-19 02:16:23', '2023-05-29 03:22:23'),
(11, 90, NULL, NULL, 2.00, '12/DaGaYa(N)018501', NULL, NULL, NULL, NULL, NULL, 'No.878,10 south Quarter,Thakata township,Yangon', NULL, NULL, NULL, NULL, NULL, '2023-05-23 08:01:42', '2023-05-23 08:01:42'),
(12, 88, NULL, NULL, 2.00, '7/PaTaNa(N)092638', NULL, NULL, NULL, NULL, NULL, 'No.992.Bo Myint Aung St.7 ward.east dagon Tsp.Yangon', NULL, NULL, NULL, NULL, NULL, '2023-05-23 08:07:33', '2023-05-23 08:07:33'),
(13, 91, NULL, NULL, NULL, '12/oukata(N)195928', NULL, NULL, NULL, NULL, NULL, 'No.55 ,South Okkalapa. Township Yangon', NULL, NULL, NULL, NULL, NULL, '2023-05-23 08:07:39', '2023-06-07 02:00:37'),
(14, 89, NULL, NULL, 2.00, '12/DAGATA(N)090880', NULL, NULL, NULL, NULL, NULL, 'No.856 / Yadanabom (3) street . (9)quarter.', NULL, NULL, NULL, NULL, NULL, '2023-05-23 08:30:50', '2023-05-23 08:30:50'),
(15, 87, NULL, NULL, NULL, '12/OuKaTa(N)126724', NULL, NULL, NULL, NULL, NULL, 'No.55.Shwe Pyi Thar St. 32 Ward.North Dagon Tsp.Yangon', NULL, NULL, NULL, NULL, NULL, '2023-05-23 08:51:05', '2023-06-08 02:07:51'),
(16, 68, NULL, NULL, NULL, '12/Yakana (N) 076919', 'Myanmar', 'Buddha', 'myanmar,english', 'intermediate', 'advanced', 'No. SH1, Room 402, Thitsar Garden Housing, Thitsar Road, 8 Ward, South Okkalapa Township', 'No. SH1, Room 402, Thitsar Garden Housing, Thitsar Road, 8 Ward, South Okkalapa Township', NULL, 'No. C-3, Thitsar Road, 8 ward, South Okkalapa Township, Yangon, Myanmar', '0943056494', NULL, '2023-05-25 02:16:21', '2023-06-05 03:11:51'),
(17, 61, NULL, NULL, NULL, '12/TaMaNa(N)000678', 'Myanmar', 'Buddhist', 'myanmar,english', 'intermediate', 'basic', 'Bldg No(3), Rm#002, 154th Street, Tamwe Township, Yangon. Myanmar.', NULL, NULL, NULL, NULL, NULL, '2023-06-01 04:25:16', '2023-06-07 04:34:24'),
(18, 67, NULL, NULL, NULL, '12/OuKaTa(N)194213', 'Buddha', 'Burmese', 'myanmar,english,japanese', 'intermediate', 'intermediate', '226 (A) Dhamayarzar 28 st, 10 block, S/okkalapa tsp, Yangon, Myanmar', NULL, NULL, NULL, '09796609206', '12/KaMaYa/Marubeni Corporation/00014', '2023-06-01 06:45:08', '2023-06-02 02:24:58'),
(19, 54, NULL, NULL, NULL, '10/ThaHtaNa(N)159110', 'Christian', 'Myanmar', NULL, NULL, NULL, 'No.18 (7B) , Zaburit Street , Sanchaung Township , Yangon.', NULL, '+95-9-5404958', NULL, '+95-9-250647364', NULL, '2023-06-01 07:18:10', '2023-06-01 07:18:11'),
(20, 60, NULL, NULL, NULL, '12/MaYaKa(N)129975', 'Buddhist', 'Burma', NULL, NULL, NULL, 'N0.979/B, Real Home Residence, Thaw Ka Road, Quarter (1), North Okkalapa Township', NULL, NULL, NULL, NULL, NULL, '2023-06-01 09:40:15', '2023-06-01 09:40:28'),
(21, 63, NULL, NULL, NULL, '8/PKK(n)249503', 'Buddish', 'Myanmar', 'myanmar,english', 'intermediate', 'intermediate', 'No.502, Tower A, SMK CONDO Aung Zeya Rd, Yankin', NULL, NULL, NULL, NULL, '12/KaMaYa/Marubeni Corporation/00020', '2023-06-05 04:37:37', '2023-06-05 04:52:04'),
(22, 58, NULL, NULL, NULL, '12/ဗဟန (နိုင်) ၁၀၃၁၅၁', 'Buddhist', NULL, NULL, NULL, NULL, 'No.868, Thirizayyar 20(A) St, 7 Ward, South Okkalapa Tsp. Yangon.', 'No.868, Thirizayyar 20(A) St, 7 Ward, South Okkalapa Tsp. Yangon.', NULL, 'No.139, 39(A) Thitsa St, North Dagon Tsp, Yangon', NULL, NULL, '2023-06-06 09:23:34', '2023-06-07 01:42:57'),
(23, 86, NULL, NULL, NULL, '12/DAGATA(N)090877', 'Buddhist', 'Myammar', NULL, NULL, NULL, 'No(No.535) Ka Sabal Myaing 1 Street 17Quarter SouthDagon Township.', NULL, NULL, NULL, NULL, NULL, '2023-06-07 02:07:47', '2023-06-07 02:07:47'),
(24, 81, NULL, NULL, NULL, '12/AhSaNa (N) 069686', NULL, NULL, NULL, NULL, NULL, 'No,1012, Seminary Lane, East Gyogone,Insein', NULL, NULL, NULL, NULL, NULL, '2023-06-07 02:11:06', '2023-06-07 03:17:42'),
(25, 79, NULL, NULL, NULL, '12/LaMaNa(N)102277', NULL, NULL, NULL, NULL, NULL, 'No.18(B)thri myaing 5street 13Ouarter Hlaing', NULL, '09250237094', NULL, NULL, NULL, '2023-06-07 02:18:15', '2023-06-07 02:18:15'),
(26, 82, NULL, NULL, NULL, '12/Oukama(N)255262', NULL, NULL, NULL, NULL, NULL, 'No.378.Sakawar Street .L quarter.Northokkalapa', NULL, NULL, NULL, NULL, NULL, '2023-06-07 02:48:28', '2023-06-07 02:48:28'),
(27, 83, NULL, NULL, NULL, '12/LaMaNa(N)125431', NULL, NULL, NULL, NULL, NULL, 'No.(7).Pantitaryarma Street.(13)Ward.Hlaing.Yangon', NULL, NULL, NULL, NULL, NULL, '2023-06-07 02:49:12', '2023-06-07 02:51:13'),
(28, 92, NULL, NULL, NULL, '12/UKaMa(N)079563', NULL, NULL, NULL, NULL, NULL, 'No/25,AdiPaDi St 9/Ware Kamaryut Tsp', NULL, NULL, NULL, NULL, NULL, '2023-06-07 04:06:09', '2023-06-07 04:06:09'),
(0, 1, NULL, NULL, NULL, '111', NULL, NULL, NULL, NULL, NULL, '111', NULL, NULL, NULL, NULL, NULL, '2023-06-08 08:08:02', '2023-06-08 08:08:25'),
(0, 118, NULL, NULL, NULL, 'rwer34ewr', NULL, NULL, NULL, NULL, NULL, 'werwe3324324', NULL, NULL, NULL, NULL, NULL, '2023-06-20 06:40:09', '2023-06-20 06:40:09');

-- --------------------------------------------------------

--
-- Table structure for table `ns_income_taxes`
--

DROP TABLE IF EXISTS `ns_income_taxes`;
CREATE TABLE IF NOT EXISTS `ns_income_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `salary_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary_usd` double(10,2) NOT NULL,
  `ot_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `bonus_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `leave_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `adjustment_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `total_income_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `estimated_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_percent` double(8,2) NOT NULL DEFAULT '0.00',
  `estimated_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `estimated_income_tax` double(10,2) NOT NULL DEFAULT '0.00',
  `estimated_income_tax_round` double(10,2) NOT NULL DEFAULT '0.00',
  `exchange_rate` double(10,2) NOT NULL DEFAULT '0.00',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `basic_allowance_percent` int(11) NOT NULL DEFAULT '0',
  `basic_max_allowance` double(15,2) NOT NULL DEFAULT '0.00',
  `parent_allowance` double(15,2) NOT NULL DEFAULT '0.00',
  `spouse_allowance` double(15,2) NOT NULL DEFAULT '0.00',
  `children_allowance` double(15,2) NOT NULL DEFAULT '0.00',
  `life_assured` double(15,2) NOT NULL DEFAULT '0.00',
  `one_year_tax` double(15,2) NOT NULL DEFAULT '0.00',
  `one_month_tax` double(15,2) NOT NULL DEFAULT '0.00',
  `deducted_tax_rate` double(15,2) NOT NULL DEFAULT '0.00',
  `actual_tax_mmk` float(12,2) NOT NULL DEFAULT '0.00',
  `actual_exchange_rate` float(12,2) NOT NULL DEFAULT '0.00',
  `actual_tax_usd` float(10,2) NOT NULL DEFAULT '0.00',
  `actual_rate` float(10,2) NOT NULL DEFAULT '0.00',
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ns_income_taxes`
--

INSERT INTO `ns_income_taxes` (`id`, `date`, `salary_id`, `user_id`, `salary_usd`, `ot_usd`, `bonus_usd`, `leave_usd`, `adjustment_usd`, `total_income_usd`, `estimated_type`, `estimated_percent`, `estimated_usd`, `estimated_income_tax`, `estimated_income_tax_round`, `exchange_rate`, `remark`, `basic_allowance_percent`, `basic_max_allowance`, `parent_allowance`, `spouse_allowance`, `children_allowance`, `life_assured`, `one_year_tax`, `one_month_tax`, `deducted_tax_rate`, `actual_tax_mmk`, `actual_exchange_rate`, `actual_tax_usd`, `actual_rate`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2023-05-31', 1, 64, 927.00, 227.00, 0.00, 0.00, 0.00, 1154.00, 'percent', 8.00, 0.00, 92.32, 92.00, 2905.00, NULL, 20, 4766580.00, 0.00, 0.00, 0.00, 0.00, 1306632.00, 108886.00, 7.00, 0.00, 0.00, 0.00, 0.00, NULL, '2023-06-05 05:29:18', '2023-06-05 05:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `ns_income_tax_details`
--

DROP TABLE IF EXISTS `ns_income_tax_details`;
CREATE TABLE IF NOT EXISTS `ns_income_tax_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ns_income_tax_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `ot_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `ssc_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `bonus_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `total_salary_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `exchange_rate` double(15,2) NOT NULL DEFAULT '0.00',
  `total_salary_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ns_salaries`
--

DROP TABLE IF EXISTS `ns_salaries`;
CREATE TABLE IF NOT EXISTS `ns_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `april` double(15,2) NOT NULL,
  `may` double(15,2) NOT NULL,
  `june` double(15,2) NOT NULL,
  `july` double(15,2) NOT NULL,
  `august` double(15,2) NOT NULL,
  `september` double(15,2) NOT NULL,
  `october` double(15,2) NOT NULL,
  `november` double(15,2) NOT NULL,
  `december` double(15,2) NOT NULL,
  `january` double(15,2) NOT NULL,
  `february` double(15,2) NOT NULL,
  `march` double(15,2) NOT NULL,
  `year` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `others`
--

DROP TABLE IF EXISTS `others`;
CREATE TABLE IF NOT EXISTS `others` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `interest` text COLLATE utf8mb4_unicode_ci,
  `strong_point` text COLLATE utf8mb4_unicode_ci,
  `weak_point` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_adjustments`
--

DROP TABLE IF EXISTS `other_adjustments`;
CREATE TABLE IF NOT EXISTS `other_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` double(15,2) NOT NULL,
  `usd_amount` double(15,2) NOT NULL,
  `mmk_amount` double(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_allowances`
--

DROP TABLE IF EXISTS `other_allowances`;
CREATE TABLE IF NOT EXISTS `other_allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(15,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_deductions`
--

DROP TABLE IF EXISTS `other_deductions`;
CREATE TABLE IF NOT EXISTS `other_deductions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(15,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oversea_records`
--

DROP TABLE IF EXISTS `oversea_records`;
CREATE TABLE IF NOT EXISTS `oversea_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `country_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_exchange_rates`
--

DROP TABLE IF EXISTS `payment_exchange_rates`;
CREATE TABLE IF NOT EXISTS `payment_exchange_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `usd` float DEFAULT '0',
  `yen` double(8,2) NOT NULL DEFAULT '0.00',
  `ot_exchange_rate` float NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_exchange_rates`
--

INSERT INTO `payment_exchange_rates` (`id`, `date`, `usd`, `yen`, `ot_exchange_rate`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2023-05-01', 2905, 0.00, 2100, 64, 1, '2023-05-29 03:06:53', '2023-06-06 06:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `pc_skills`
--

DROP TABLE IF EXISTS `pc_skills`;
CREATE TABLE IF NOT EXISTS `pc_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `type`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'attendance-read-all', 'Read All', 'The login user can see the record list of all staff in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(2, 'attendance-read-group', 'Read Group', 'The login user can see the record list of all staff with the same department in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(3, 'attendance-read-one', 'Read One', 'The login user can only see their own record list in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(4, 'raw-attendance-list', 'Raw Attendance List', 'The login user can see the \"Raw Attendance List\" menu in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(5, 'attendance-change-request', 'Attendance Change Request List', 'The login user can see the \"Attendance Change Request\" menu in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(6, 'late-record-list', 'Late Record List', 'The login user can see the \"Late Record List\" menu in attendance management', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(7, 'export-late-record-list', 'Export Late Record List', 'The login user can export the Late Record List as excel format', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(8, 'create-attendance', 'Create Manual Attendance', 'The login user can create the attendance record by manually', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(9, 'edit-attendance', 'Edit Raw Attendance', 'The login user can edit the raw attendance record', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(10, 'delete-attendance', 'Delete Raw Attendance', 'The login user can delete the raw attendance record', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(11, 'attendance-detail-list', 'Attendance Detail View', 'The login user can see the detail attendance record', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(12, 'print-attendance-detail-list', 'Print Attendance Detail View', 'The login user can print the detail attendance record', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(13, 'export-attendance-detail-list', 'Export Attendance Detail View', 'The login user can export the detail attendance record as excel format', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(14, 'update-attendance-detail-list', 'Update Attendance Detail View', 'The login user can update the detail attendance information', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(15, 'create-change-request', 'Create Change Request', 'The login user can request to change the attendance time in and time out', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(16, 'attendance-change-status', 'Approved', 'The login user can change the status (Accept and Reject) of Change Request', 'Attendance Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(17, 'ot-read-all', 'Read All', 'The login user can see the record list of all staff in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(18, 'ot-read-group', 'Read Group', 'The login user can see the record list of staff with the same department in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(19, 'ot-read-one', 'Read One', 'The login user can only see their own record list in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(20, 'daily-ot-request-list', 'Daily OT Request List', 'The login user can see the \"Daily OT Request List\" menu in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(21, 'monthly-ot-request-list', 'Monthly OT Request List', 'The login user can see the \"Monthly OT Request List\" menu in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(22, 'monthly-ot-summary-list', 'Monthly OT Summary List', 'The login user can see the \"Monthly OT Summary List\" menu in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(23, 'export-monthly-ot-summary-list', 'Export Monthly OT Summary List', 'The login user can export the monthly summary list as excel format', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(24, 'annual-ot-summary-list', 'Annual OT Summary List', 'The login user can see the \"Annual OT Summary List\" menu in ot management', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(25, 'export-annual-ot-summary-list', 'Export Annual OT Summary List', 'The login user can export the annual summary list as excel format', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(26, 'create-ot', 'Create OT Request', 'The login user can request for overtime', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(27, 'send-monthly-ot-request', 'Send Monthly OT Request', 'The login user can send monthly overtime record to Manager', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(28, 'change-ot-manager-status', 'Approved by Dept GM', 'The login user can change the department GM status(Accept and Reject) for overtime', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(29, 'change-ot-account-status', 'Approved by Account', 'The login user can change the account status(Accept and Reject) for overtime', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(30, 'change-ot-gm-status', 'Approved by Admin GM', 'The login user can change the admin GM status(Accept and Reject) for overtime', 'OT Management', 'web', '2023-03-09 14:52:10', '2023-03-09 14:52:10'),
(31, 'change-ot-admin-status', 'Approved by Admin', 'The login user can change the Admin status(Accept and Reject) for overtime', 'OT Management', 'web', '2023-03-25 15:55:12', '2023-03-25 15:55:12'),
(32, 'salary-read-all', 'Read All', 'The login user can see the record list of all staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(33, 'salary-read-group', 'Read Group', 'The login user can see the record list of all staff with the same department in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(34, 'salary-read-one', 'Read One', 'The login user can only see their own record list in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(35, 'rs-salary-list', 'RS Salary List', 'The login user can see the RS Salary List in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(36, 'edit-rs-salary', 'Edit RS Salary', 'The login user can edit the RS Salary List in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(37, 'ns-salary-list', 'NS Salary List', 'The login user can see the NS Salary List in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(38, 'edit-ns-salary', 'Edit NS Salary', 'The login user can edit the NS Salary List in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(39, 'calculate-salary', 'Salary Calculation', 'The login user can make the salary calculation for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(40, 'edit-salary-calculation', 'Edit Salary Calculation', 'The login user can edit the salary calculation for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(41, 'payslip-detail', 'Pay Slip Detail', 'The login user can see the Pay Slip Detail for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(42, 'monthly-salary-list', 'Monthly Salary List', 'The login user can see the Monthly Salary List for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(43, 'monthly-salary-list-download', 'Export Monthly Salary List', 'The login user can download as excel format the Monthly Salary List for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(44, 'pay-list-bank', 'Pay List (Bank)', 'The login user can see the Pay List (Bank) for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(45, 'pay-list-bank-download', 'Export Pay List (Bank)', 'The login user can download as excel format the Pay List (Bank) for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(46, 'pay-list-ns', 'Pay List (NS) Internal', 'The login user can see the Pay List (NS) Internal for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(47, 'pay-list-ns-download', 'Export Pay List (NS) Internal', 'The login user can download as excel format the Pay List (NS) Internal for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(48, 'pay-list-jpn', 'Pay List (JPN) Internal', 'The login user can see the Pay List (JPN) Internal for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(49, 'pay-list-jpn-download', 'Export Pay List (JPN) Internal', 'The login user can download as excel format the Pay List (JPN) Internal for the Staff in salary management', 'Salary Management', 'web', '2023-03-27 05:25:05', '2023-03-27 05:25:05'),
(51, 'car-read-all', 'Read All', 'The login user can see the record list of all car data in car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(52, 'car-read-group', 'Read Group', 'The login user can see the record list of car with the same department in car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(53, 'car-read-one', 'Read One', 'The login user can only see their own record list in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(54, 'car-registration', 'Car Registration', 'The login user can only see their car registration list in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(55, 'car-registration-edit', 'Car Registration Edit', 'The login user can Edit Car Registration   in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(56, 'car-registration-delete', 'Car Registration Delete', 'The login user can Delete  Car Registration  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(57, 'car-insurance-list', 'Car Insurance List', 'The login user can only see their car insurance list in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(58, 'car-insurance-edit', 'Car Insurance Edit', 'The login user can Edit  Car Insurance    in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(59, 'car-insurance-delete', 'Car Insurance Delete', 'The login user can  Delete Car Insurance    in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(60, 'car-insurance-claim-list', 'Car Insurance Claim List', 'The login user can only see their car insurance claim list in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(61, 'car-insurance-claim-edit', 'Car Insurance Claim Edit', 'The login user can  Edit Insurance Claim  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(62, 'car-insurance-claim-delete', 'Car Insurance Claim Delete', 'The login user can  Delete Insurance Claim  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(63, 'car-maintanance-repair-list', 'Maintanance & Repair', 'The login user can only see their  maintanance  & repair  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(64, 'car-maintanance-repair-edit', 'maintanance & Repair Edit', 'The login user can Edit Car maintanance  & repair    in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(65, 'car-maintanance-repair-delete', 'maintanance & Repair Delete', 'The login user can Delete Car maintanance  & repair    in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(66, 'car-fueling-list', 'Car Fueling List', 'The login user can  see Car Fueling List  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(67, 'car-fueling-edit', 'Car Fueling Edit', 'The login user can Edit Car Fueling   in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(68, 'car-fueling-delete', 'Car Fueling Delete', 'The login user can Delete Car Fueling   in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(69, 'car-license-list', 'Car License List', 'The login user can see Car License List  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(70, 'car-license-edit', 'Car License Edit', 'The login user can Edit Car License   in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(71, 'car-license-delete', 'Car License Delete', 'The login user can Delete Car License   in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(72, 'car-mileage-list', 'Car Mileage List', 'The login user can see Car Mileage List  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(73, 'car-mileage-edit', 'Car Mileage Edit', 'The login user can see Car Mileage Edit  in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(74, 'car-mileage-delete', 'Car Mileage Delete', 'The login user can see Car Mileage Delete in Car management', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(75, 'export-car-insurance', 'Export Car Insurance', 'The login user can export Car Insurance', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(76, 'export-kilo-for-maintanance', 'Export Kilo For Maintenance', 'The login user can export Kilo For Maintenance', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(77, 'export-repair-record-for-by-car', 'Export Repair Record (By Car)', 'The login user can export repair record by car', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(78, 'export-repair-record', 'Export Repair Record ', 'The login user can export repair record', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(79, 'fueling-export', 'Fueling Export', 'The login user can export fueling', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(80, 'export-license', 'Export License', 'The login user can export license', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(81, 'export-car-mileages', 'Export Car Mileage', 'The login user can export car mileages', 'Car Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(82, 'list-of-yearly-leave', 'List Of Yearly Leave', 'The login user can search and see List of yearly leave of users', 'Leave Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(83, 'export-list-of-yearly-leave', 'List Of Yearly Leave Export', 'The login user can search and export List of yearly leave of users', 'Leave Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(84, 'leave-approve-by-dep-manager', 'Leave Approve By Dep Manager', 'The login user can change the account status(Accept and Reject) for leave', 'Leave Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(85, 'leave-approve-by-admi-gm', 'Leave Approve By Admin GM', 'The login user can change the account status(Accept and Reject) for leave', 'Leave Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(86, 'alert-email-for-unpaid-leave', 'Receive Account Email For Unpaid Leave', 'The login user can receive mail if employee take unpaid leave', 'Leave Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(87, 'employee-read-all', 'Read All', 'The login user can see the  list of all NS and RS in employee management', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(88, 'employee-read-group', 'Read Group', 'The login user can see  the  list of  NS and RS with the same department in employee management', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(89, 'employee-read-one', 'Read One', 'The login user can only see their own record list in employee management', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(90, 'edit-ns-record', 'Edit NS Employee', 'The login user can edit the NS Employee record', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(91, 'edit-rs-record', 'Edit RS Employee', 'The login user can edit the RS Employee record', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(92, 'delete-ns-record', 'Delete NS Employee', 'The login user can delete the NS Employee record', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(93, 'delete-rs-record', 'Delete RS Employee', 'The login user can delete the RS Employee record', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(94, 'dep-gm-for-performance-evaluation', 'Performance Evaluation Permission For Dep Manager', 'The login user can see performance evaluation of staff', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(95, 'admin-gm-for-performance-evaluation', 'Performance Evaluation Edit Permission For Admin GM', 'The login user can edit performance evaluation of Admin Gm', 'Employee Management', 'web', '2023-03-27 13:57:05', '2023-03-27 13:57:05'),
(131, 'user-create', 'Create User', 'The login user can create user in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(132, 'user-list', 'User List', 'The login user can see all user in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(133, 'user-edit', 'Edit User', 'The login user can edit all user in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(134, 'user-delete', 'Delete User', 'The login user can delete all user in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(135, 'user-permission', 'Give Permission To User', 'The login user can give permission all user in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(136, 'branch-list', 'Branch List', 'The login user can see Branch List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(137, 'department-list', 'Department List', 'The login user can see Department List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(138, 'department-create', 'create Department', 'The login user can create Department  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(139, 'department-edit', 'Edit Department', 'The login user can edit Department  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(140, 'holiday-type-list', 'Holiday Type List', 'The login user can see Holiday Type  List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(141, 'holiday-list', 'Holiday  List', 'The login user can see Holiday  List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(142, 'holiday-create', 'Create Holiday', 'The login user can create Holiday in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(143, 'holiday-edit', 'Edit Holiday', 'The login user can Edit Holiday in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(144, 'holiday-delete', 'Delete Holiday', 'The login user can Delete Holiday in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(145, 'employee-type-list', 'Employee Type List', 'The login user can see Employee Type List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(146, 'bank-list', 'Bank List', 'The login user can see Bank List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(147, 'bank-create', 'Create Bank', 'The login user can create Bank  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(148, 'bank-edit', 'Edit Bank', 'The login user can edit Bank  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(149, 'bank-delete', 'Delete Bank', 'The login user can Delete Bank  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(150, 'leave-type-list', 'Leave Type List', 'The login user can see Leave Type  List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(151, 'role-list', 'Role List', 'The login user can see Bank List in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(152, 'role-create', 'Create Role', 'The login user can create Role  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(153, 'role-edit', 'Edit Role', 'The login user can edit Role  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(154, 'role-delete', 'Delete Role', 'The login user can Delete Role  in  Master management', 'Master Management', 'web', '2023-03-31 09:35:13', '2023-03-31 09:35:13'),
(155, 'tax-read-all', 'Read All', 'The login user can see the record list of all staff in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(156, 'tax-read-group', 'Read Group', 'The login user can see the record list of all staff with the same department in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(157, 'tax-read-one', 'Read One', 'The login user can only see their own record list in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(158, 'ns-actual-tax-list', 'NS Actual Income Tax List', 'The login user can see the NS Actual Income Tax List in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(159, 'create-ns-actual-tax', 'Add NS Actual Income Tax', 'The login user can add new record for NS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(160, 'edit-ns-actual-tax', 'Edit NS Actual Income Tax', 'The login user can edit the NS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(161, 'delete-ns-actual-tax', 'Delete NS Actual Income Tax', 'The login user can delete the NS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(162, 'rs-actual-tax-list', 'RS Actual Income Tax List', 'The login user can see the RS Actual Income Tax List in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(163, 'create-rs-actual-tax', 'Add RS Actual Income Tax', 'The login user can add new record for RS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(164, 'edit-rs-actual-tax', 'Edit RS Actual Income Tax', 'The login user can edit the RS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(165, 'delete-rs-actual-tax', 'Delete RS Actual Income Tax', 'The login user can delete the RS Actual Income Tax in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(166, 'ssc-report', 'SSC Report', 'The login user can see the SSC Report in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(167, 'ssc-report-download', 'Export SSC Report', 'The login user can download as excel format the SSC Report in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(168, 'monthly-tax-statement', 'Monthly Tax Statement', 'The login user can see the Monthly Tax Statement in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(169, 'monthly-tax-statement-download', 'Export Monthly Tax Statement', 'The login user can download as excel format the Monthly Tax Statement in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(170, 'monthly-paye', 'Monthly PAYE', 'The login user can see the Monthly PAYE in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(171, 'monthly-paye-download', 'Export Monthly PAYE', 'The login user can download as excel format the Monthly PAYE in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(172, 'ns-actual-tax', 'Actual Tax Payment (NS)', 'The login user can see the Actual Tax Payment (NS) in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(173, 'ns-actual-tax-download', 'Export Actual Tax Payment (NS)', 'The login user can download as excel format the Actual Tax Payment (NS) in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(174, 'rs-actual-tax', 'Actual Tax Payment (JPN)', 'The login user can see the Actual Tax Payment (JPN) in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(175, 'rs-actual-tax-download', 'Export Actual Tax Payment (JPN)', 'The login user can download as excel format the Actual Tax Payment (JPN) in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(176, 'annual-paid-personal', 'Annual Deducted & Paid Personal', 'The login user can see the Annual Deducted & Paid Personal in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(177, 'annual-paid-personal-download', 'Export Annual Deducted & Paid Personal', 'The login user can download as excel format the Annual Deducted & Paid Personal in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(178, 'tax-office-submission', 'Annaul Tax Office Submission', 'The login user can see the Annaul Tax Office Submission in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(179, 'tax-office-submission-download', 'Export Annaul Tax Office Submission', 'The login user can download as excel format the Annaul Tax Office Submission in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(180, 'exchange-rate', 'Exchange Rate Report', 'The login user can see the Exchange Rate Report in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(181, 'exchange-rate-download', 'Export Exchange Rate Report', 'The login user can download as excel format the Exchange Rate Report in Tax Management', 'Tax Management', 'web', '2023-03-31 20:18:44', '2023-03-31 20:18:44'),
(182, 'leave-approve-by-gm', 'approve by GM', 'The login user can approve RS Leave in  Leave management', 'Leave Management', 'web', '2023-04-25 04:24:10', '2023-04-25 04:24:10'),
(183, 'leave-read-all', 'Read All', 'The login user can see the record list of all staff in attendance management', 'Leave Management', 'web', NULL, NULL),
(184, 'leave-read-group', 'Read Group', 'The login user can see the same department of leave list of all staff in leave management', 'Leave Management', 'web', NULL, NULL),
(185, 'leave-read-one', 'Read One', 'The login user can only see their own record list in leave management', 'Leave Management', 'web', NULL, NULL),
(186, 'delete-attendance-detail-list', 'Delete Attendance Detail View', 'The login user can delete the detail attendance information', 'Attendance Management', 'web', '2023-05-17 11:55:54', '2023-05-17 11:55:54'),
(187, 'alert-car-license-expire', 'Alert For Car License Expire', 'The login user can update alert mail information for car license expire', 'Master Management', 'web', '2023-05-17 11:55:54', '2023-05-17 11:55:54'),
(188, 'update-ot-request', 'Update OT Request', 'The login user can edit the OT for driver and assistant that don\'t check for OT in attendance ', 'Attendance Management', 'web', '2023-06-05 14:28:34', '2023-06-05 14:28:34'),
(189, 'ns-export', 'Export NS Employee', 'The login user can export ns employee', 'Employee Management', 'web', '2023-06-05 14:28:34', '2023-06-05 14:28:34'),
(190, 'rs-export', 'Export RS Employee', 'The login user can export rs employee', 'Employee Management', 'web', '2023-06-05 14:28:34', '2023-06-05 14:28:34');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position_assign_histories`
--

DROP TABLE IF EXISTS `position_assign_histories`;
CREATE TABLE IF NOT EXISTS `position_assign_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `position_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `premium_amount_updates`
--

DROP TABLE IF EXISTS `premium_amount_updates`;
CREATE TABLE IF NOT EXISTS `premium_amount_updates` (
  `id` int(11) NOT NULL,
  `car_insurance_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `premium_amount` double(8,2) NOT NULL,
  `currency` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
CREATE TABLE IF NOT EXISTS `qualifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_acquition` int(11) NOT NULL,
  `certificate` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_att`
--

DROP TABLE IF EXISTS `raw_att`;
CREATE TABLE IF NOT EXISTS `raw_att` (
  `att_id` bigint(20) UNSIGNED NOT NULL,
  `att_UserID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `att_ip` varchar(20) NOT NULL,
  `att_serial` varchar(20) NOT NULL,
  `att_Date` datetime NOT NULL,
  `branch` int(11) NOT NULL DEFAULT '0',
  `reason` text,
  `att_UpdateTime` datetime NOT NULL,
  `att_CreateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `raw_att_logs`
--

DROP TABLE IF EXISTS `raw_att_logs`;
CREATE TABLE IF NOT EXISTS `raw_att_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `att_id` int(11) NOT NULL,
  `att_UserID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `att_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `att_serial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `att_Date` datetime NOT NULL,
  `branch` int(11) NOT NULL DEFAULT '0',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_profile`
--

DROP TABLE IF EXISTS `raw_profile`;
CREATE TABLE IF NOT EXISTS `raw_profile` (
  `pro_id` bigint(20) NOT NULL,
  `pro_UserID` int(11) NOT NULL,
  `pro_UserName` varchar(50) NOT NULL,
  `pro_Ip` varchar(20) NOT NULL,
  `pro_Serial` varchar(20) NOT NULL,
  `pro_Download` tinyint(4) NOT NULL DEFAULT '0',
  `pro_UploadedTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reset_passwords`
--

DROP TABLE IF EXISTS `reset_passwords`;
CREATE TABLE IF NOT EXISTS `reset_passwords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `retirements`
--

DROP TABLE IF EXISTS `retirements`;
CREATE TABLE IF NOT EXISTS `retirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `short_name`, `status`, `created_by`, `updated_by`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Office Staff 1 (YGN)', NULL, '1', 1, 1, 'web', '2023-03-27 06:33:26', '2023-03-27 06:33:26'),
(2, 'Office Staff  2(NPT)', NULL, '1', 1, 1, 'web', '2023-03-27 06:33:26', '2023-03-27 06:33:26'),
(3, 'Driver(Office)', NULL, '1', 1, 1, 'web', '2023-03-27 06:41:26', '2023-03-27 06:41:23'),
(4, 'Driver(Contract)', NULL, '1', 1, 1, 'web', '2023-03-27 06:41:29', '2023-03-27 06:41:21'),
(5, 'Driver(Rental)', NULL, '1', 1, 1, 'web', '2023-03-27 06:41:33', '2023-03-27 06:41:18'),
(6, 'Assistant(YGN)', NULL, '1', 1, 1, 'web', '2023-03-27 06:41:36', '2023-03-27 06:41:14'),
(7, 'Assistant (NPT)', NULL, '1', 1, 1, 'web', '2023-03-27 06:41:09', '2023-03-27 06:41:06'),
(8, 'Receptionist (A)', NULL, '1', 1, 1, 'web', '2023-03-27 06:40:59', '2023-03-27 06:41:03'),
(9, 'Receptionist (B)', NULL, '1', 1, 1, 'web', '2023-03-27 06:40:54', '2023-03-27 06:40:56'),
(10, 'GM', NULL, '1', 1, 1, 'web', '2023-03-27 06:40:46', '2023-03-27 06:40:49'),
(11, 'DM', NULL, '1', 1, 1, 'web', '2023-03-27 06:40:40', '2023-03-27 06:40:43'),
(12, 'R-Staff', '1', '1', 1, NULL, 'web', '2023-03-27 06:40:33', '2023-03-27 06:40:36'),
(13, 'Non-Marubeni', NULL, '1', 1, 1, 'web', '2023-03-27 06:39:08', '2023-03-27 06:39:08'),
(14, 'SYSADMI', NULL, '1', 1, 1, 'web', '2023-03-27 06:39:08', '2023-03-27 06:39:08'),
(15, 'QC Staff', NULL, '1', 1, 1, 'web', '2023-04-20 07:38:26', '2023-04-20 07:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_actual_taxes`
--

DROP TABLE IF EXISTS `rs_actual_taxes`;
CREATE TABLE IF NOT EXISTS `rs_actual_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `tax_for` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_period` date DEFAULT NULL,
  `tax_amount_mmk` double(8,2) NOT NULL,
  `exchange_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `tax_amount_usd` double(8,2) NOT NULL,
  `pay_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_employees`
--

DROP TABLE IF EXISTS `rs_employees`;
CREATE TABLE IF NOT EXISTS `rs_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `second_bank_name_mmk` int(11) DEFAULT NULL,
  `second_bank_account_mmk` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_education` text COLLATE utf8mb4_unicode_ci,
  `residant_place` text COLLATE utf8mb4_unicode_ci,
  `form_c` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frc_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduation_name_of_university` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `major` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mjsrv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mjsrv_expire_date` date DEFAULT NULL,
  `stay_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stay_permit_expire_date` date DEFAULT NULL,
  `aboard_date` date DEFAULT NULL,
  `japan_hot_line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `japan_address` text COLLATE utf8mb4_unicode_ci,
  `japan_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `myanmar_address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rs_employees`
--

INSERT INTO `rs_employees` (`id`, `user_id`, `second_bank_name_mmk`, `second_bank_account_mmk`, `final_education`, `residant_place`, `form_c`, `frc_no`, `graduation_name_of_university`, `major`, `mjsrv`, `mjsrv_expire_date`, `stay_permit`, `stay_permit_expire_date`, `aboard_date`, `japan_hot_line`, `japan_address`, `japan_phone`, `myanmar_address`, `created_at`, `updated_at`) VALUES
(3, 65, 1, '10001', 'Oxford University', 'Kabaraye villa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-04 18:21:50', '2023-04-04 18:21:50'),
(4, 22, NULL, NULL, 'Tun Tun', 'no321', NULL, NULL, NULL, NULL, NULL, '2023-07-10', NULL, '2023-07-10', NULL, NULL, NULL, NULL, NULL, '2023-04-05 09:16:17', '2023-05-10 02:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `rs_income_taxes`
--

DROP TABLE IF EXISTS `rs_income_taxes`;
CREATE TABLE IF NOT EXISTS `rs_income_taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `salary_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary` double(10,2) NOT NULL DEFAULT '0.00',
  `income_tax_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `exchange_rate_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `exchange_rate_yen` double(10,2) NOT NULL DEFAULT '0.00',
  `income_tax_mmk` double(10,2) NOT NULL DEFAULT '0.00',
  `ssc` double(10,2) NOT NULL DEFAULT '0.00',
  `percent_allowance` double(10,2) NOT NULL DEFAULT '0.00',
  `max_allowance` double(10,2) NOT NULL DEFAULT '0.00',
  `parent_allowance` double(10,2) NOT NULL DEFAULT '0.00',
  `spouse_allowance` double(10,2) NOT NULL DEFAULT '0.00',
  `children_allowance` double(10,2) NOT NULL DEFAULT '0.00',
  `tax_calculation_percent` double(10,2) NOT NULL DEFAULT '0.00',
  `one_year_tax` double(10,2) NOT NULL DEFAULT '0.00',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `actual_tax_mmk` float(12,2) NOT NULL,
  `actual_exchange_rate` float(12,2) NOT NULL,
  `actual_tax_usd` float(10,2) NOT NULL,
  `actual_rate` float(10,2) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_income_tax_jpy_details`
--

DROP TABLE IF EXISTS `rs_income_tax_jpy_details`;
CREATE TABLE IF NOT EXISTS `rs_income_tax_jpy_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rs_income_tax_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `transfer_from_mm_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `adjustment_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `income_tax_jpy_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `bonus_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `oversea_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `dc_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `total_salary_yen` double(15,2) NOT NULL DEFAULT '0.00',
  `exchange_rate` double(15,2) NOT NULL DEFAULT '0.00',
  `total_salary_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_income_tax_mm_details`
--

DROP TABLE IF EXISTS `rs_income_tax_mm_details`;
CREATE TABLE IF NOT EXISTS `rs_income_tax_mm_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rs_income_tax_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `transfer_to_jp_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `bonus_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `oversea_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `dc_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `total_salary_usd` double(10,2) NOT NULL DEFAULT '0.00',
  `exchange_rate` double(10,2) NOT NULL DEFAULT '0.00',
  `total_salary_mmk` double(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_leave_data`
--

DROP TABLE IF EXISTS `rs_leave_data`;
CREATE TABLE IF NOT EXISTS `rs_leave_data` (
  `id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `earned_leaves` float NOT NULL,
  `refresh_leaves` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rs_refresh_leaves`
--

DROP TABLE IF EXISTS `rs_refresh_leaves`;
CREATE TABLE IF NOT EXISTS `rs_refresh_leaves` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `refresh_leaves` float NOT NULL,
  `earned_leaves` float DEFAULT NULL,
  `other` float DEFAULT NULL,
  `airfare` varchar(191) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rs_salaries`
--

DROP TABLE IF EXISTS `rs_salaries`;
CREATE TABLE IF NOT EXISTS `rs_salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `april` double(15,2) NOT NULL,
  `may` double(15,2) NOT NULL,
  `june` double(15,2) NOT NULL,
  `july` double(15,2) NOT NULL,
  `august` double(15,2) NOT NULL,
  `september` double(15,2) NOT NULL,
  `october` double(15,2) NOT NULL,
  `november` double(15,2) NOT NULL,
  `december` double(15,2) NOT NULL,
  `january` double(15,2) NOT NULL,
  `february` double(15,2) NOT NULL,
  `march` double(15,2) NOT NULL,
  `year` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

DROP TABLE IF EXISTS `salaries`;
CREATE TABLE IF NOT EXISTS `salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `pay_for` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `leave_from_date` date DEFAULT NULL,
  `leave_to_date` date DEFAULT NULL,
  `employee_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate_usd` double(8,2) NOT NULL,
  `exchange_rate_yen` double(8,2) NOT NULL,
  `previous_normal_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `previous_sat_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `previous_sunday_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `previous_public_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `current_normal_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `current_sat_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `current_sunday_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `current_public_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `total_ot_hr` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `total_working_hour` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `hourly_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `salary_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `salary_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `kbz_opening_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `kbz_opening_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_to_japan_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_to_japan_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_from_japan_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_from_japan_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `electricity_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `electricity_mmk` double(8,2) NOT NULL DEFAULT '0.00',
  `car_usd` double NOT NULL DEFAULT '0',
  `car_mmk` double NOT NULL DEFAULT '0',
  `ssc_exchange` double(10,2) NOT NULL DEFAULT '0.00',
  `mmk_ssc` double(15,2) NOT NULL DEFAULT '0.00',
  `usd_ssc` double(10,2) NOT NULL DEFAULT '0.00',
  `ssc_mmk` double(8,2) NOT NULL DEFAULT '0.00',
  `ssc_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `ot_rate_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `previous_normal_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `previous_sat_ot_payment_usd` float NOT NULL DEFAULT '0',
  `previous_sunday_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `previous_public_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `previous_taxi_charge_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `current_normal_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `current_sat_ot_payment_usd` float NOT NULL DEFAULT '0',
  `current_sunday_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `current_public_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `current_taxi_charge_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `total_ot_payment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `total_ot_payment_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `unpaid_leave_day` double(8,2) NOT NULL DEFAULT '0.00',
  `daily_rate_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `leave_amount_usd` double(8,2) NOT NULL DEFAULT '0.00',
  `leave_amount_mmk` double(8,2) NOT NULL DEFAULT '0.00',
  `other_adjustment_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `other_adjustment_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `estimated_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_percent` double(8,2) NOT NULL DEFAULT '0.00',
  `estimated_tax_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `estimated_tax_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `actual_percent` double(8,2) NOT NULL DEFAULT '0.00',
  `actual_tax_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `actual_tax_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `usd_allowance_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `usd_allowance_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `mmk_allowance_usd` float(15,2) NOT NULL DEFAULT '0.00',
  `mmk_allowance_mmk` float(15,2) NOT NULL DEFAULT '0.00',
  `usd_deduction_usd` double(15,2) NOT NULL DEFAULT '0.00',
  `usd_deduction_mmk` double(15,2) NOT NULL DEFAULT '0.00',
  `mmk_deduction_usd` float(15,2) NOT NULL DEFAULT '0.00',
  `mmk_deduction_mmk` float(15,2) NOT NULL DEFAULT '0.00',
  `bonus_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `bonus_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `is_retire` tinyint(4) NOT NULL DEFAULT '0',
  `retire_fee` double(15,2) NOT NULL DEFAULT '0.00',
  `net_salary_usd` double(20,2) NOT NULL DEFAULT '0.00',
  `net_salary_mmk` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_mmk_acc` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_usd_acc` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_mmk_cash` double(20,2) NOT NULL DEFAULT '0.00',
  `transfer_usd_cash` double(20,2) NOT NULL DEFAULT '0.00',
  `pay_slip_remark` text COLLATE utf8mb4_unicode_ci,
  `ssc_remark` text COLLATE utf8mb4_unicode_ci,
  `monthly_paye_remark` text COLLATE utf8mb4_unicode_ci,
  `pay_date` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `no_ssc` tinyint(4) NOT NULL DEFAULT '0',
  `other_bonus` text COLLATE utf8mb4_unicode_ci,
  `payment_exchange_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `user_id`, `pay_for`, `year`, `month`, `leave_from_date`, `leave_to_date`, `employee_type`, `exchange_rate_usd`, `exchange_rate_yen`, `previous_normal_ot_hr`, `previous_sat_ot_hr`, `previous_sunday_ot_hr`, `previous_public_ot_hr`, `current_normal_ot_hr`, `current_sat_ot_hr`, `current_sunday_ot_hr`, `current_public_ot_hr`, `total_ot_hr`, `total_working_hour`, `hourly_rate`, `salary_usd`, `salary_mmk`, `kbz_opening_usd`, `kbz_opening_mmk`, `transfer_to_japan_usd`, `transfer_to_japan_mmk`, `transfer_from_japan_usd`, `transfer_from_japan_mmk`, `electricity_usd`, `electricity_mmk`, `car_usd`, `car_mmk`, `ssc_exchange`, `mmk_ssc`, `usd_ssc`, `ssc_mmk`, `ssc_usd`, `ot_rate_usd`, `previous_normal_ot_payment_usd`, `previous_sat_ot_payment_usd`, `previous_sunday_ot_payment_usd`, `previous_public_ot_payment_usd`, `previous_taxi_charge_usd`, `current_normal_ot_payment_usd`, `current_sat_ot_payment_usd`, `current_sunday_ot_payment_usd`, `current_public_ot_payment_usd`, `current_taxi_charge_usd`, `total_ot_payment_usd`, `total_ot_payment_mmk`, `unpaid_leave_day`, `daily_rate_usd`, `leave_amount_usd`, `leave_amount_mmk`, `other_adjustment_usd`, `other_adjustment_mmk`, `estimated_type`, `estimated_percent`, `estimated_tax_usd`, `estimated_tax_mmk`, `actual_percent`, `actual_tax_usd`, `actual_tax_mmk`, `usd_allowance_usd`, `usd_allowance_mmk`, `mmk_allowance_usd`, `mmk_allowance_mmk`, `usd_deduction_usd`, `usd_deduction_mmk`, `mmk_deduction_usd`, `mmk_deduction_mmk`, `bonus_name`, `bonus_usd`, `bonus_mmk`, `is_retire`, `retire_fee`, `net_salary_usd`, `net_salary_mmk`, `transfer_mmk_acc`, `transfer_usd_acc`, `transfer_mmk_cash`, `transfer_usd_cash`, `pay_slip_remark`, `ssc_remark`, `monthly_paye_remark`, `pay_date`, `status`, `no_ssc`, `other_bonus`, `payment_exchange_rate`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 64, 'May, 2023', 2023, 5, '2023-04-20', '2023-05-20', 'ns', 2100.00, 1513.30, '19:50', '00:00', '00:00', '00:00', '00:00', '00:00', '00:00', '00:00', '19:50', '0', 0.00, 927.00, 2692935.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 2905.00, 0.00, 0.00, 0.00, 0.00, 11.41, 226.29, 0, 0.00, 0.00, 0.00, 0.00, 0, 0.00, 0.00, 0.00, 227.00, 476700.00, 0.00, 29.90, 0.00, 0.00, 0.00, 0.00, 'percent', 8.00, 92.00, 267260.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'NULL', 0.00, 0.00, 0, 0.00, 1062.00, 2902375.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, '2023-06-05', 1, 1, NULL, 2905.00, 64, NULL, '2023-06-05 05:29:18', '2023-06-05 05:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `salary_exchange_details`
--

DROP TABLE IF EXISTS `salary_exchange_details`;
CREATE TABLE IF NOT EXISTS `salary_exchange_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_to` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` double(15,2) NOT NULL,
  `usd_amount` double(15,2) NOT NULL,
  `mmk_amount` double(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sscs`
--

DROP TABLE IF EXISTS `sscs`;
CREATE TABLE IF NOT EXISTS `sscs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `salary_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `salary_usd` double(10,2) NOT NULL,
  `salary_mmk` double(10,2) NOT NULL,
  `employer_first_percent` int(11) NOT NULL,
  `employee_percent` int(11) NOT NULL,
  `employer_second_percent` int(11) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sscs`
--

INSERT INTO `sscs` (`id`, `date`, `salary_id`, `user_id`, `salary_usd`, `salary_mmk`, `employer_first_percent`, `employee_percent`, `employer_second_percent`, `remark`, `created_at`, `updated_at`) VALUES
(1, '2023-05-31', 1, 64, 927.00, 300000.00, 2, 2, 2, NULL, '2023-06-05 05:29:18', '2023-06-05 05:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `tax_ranges`
--

DROP TABLE IF EXISTS `tax_ranges`;
CREATE TABLE IF NOT EXISTS `tax_ranges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_kyat` double(20,2) NOT NULL DEFAULT '0.00',
  `to_kyat` double(20,2) NOT NULL DEFAULT '0.00',
  `percent` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tax_ranges`
--

INSERT INTO `tax_ranges` (`id`, `from_kyat`, `to_kyat`, `percent`, `created_at`, `updated_at`) VALUES
(1, 1.00, 2000000.00, 0, '2023-03-17 18:01:20', '2023-03-17 18:01:20'),
(2, 2000001.00, 10000000.00, 5, '2023-03-17 18:01:20', '2023-03-17 18:01:20'),
(3, 10000001.00, 30000000.00, 10, '2023-03-17 18:09:45', '2023-03-17 18:09:45'),
(4, 30000001.00, 50000000.00, 15, '2023-03-17 18:09:45', '2023-03-17 18:09:45'),
(5, 50000001.00, 70000000.00, 20, '2023-03-17 18:11:10', '2023-03-17 18:11:10'),
(6, 70000001.00, 100000000000000000.00, 25, '2023-03-17 18:11:10', '2023-03-17 18:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noti_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google2fa_secret` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_name_jp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `entranced_date` date DEFAULT NULL,
  `working_start_time` time DEFAULT NULL,
  `working_end_time` time DEFAULT NULL,
  `personal_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_type_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `department_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssc_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name_usd` int(11) DEFAULT NULL,
  `bank_account_usd` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name_mmk` int(11) DEFAULT NULL,
  `bank_account_mmk` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `passport_number` text COLLATE utf8mb4_unicode_ci,
  `date_of_issue` date DEFAULT NULL,
  `date_of_expire` date DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `retire` tinyint(4) NOT NULL DEFAULT '0',
  `photo_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign_photo_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_changing_condition` text COLLATE utf8mb4_unicode_ci,
  `check_ns_rs` tinyint(4) NOT NULL DEFAULT '0',
  `password_change` tinyint(4) NOT NULL DEFAULT '0',
  `profile_add` tinyint(4) NOT NULL DEFAULT '0',
  `working_day_per_week` float NOT NULL DEFAULT '0',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_day_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_accounts`
--

DROP TABLE IF EXISTS `user_bank_accounts`;
CREATE TABLE IF NOT EXISTS `user_bank_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `bank_account` int(30) NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

DROP TABLE IF EXISTS `warnings`;
CREATE TABLE IF NOT EXISTS `warnings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
