-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 08:14 AM
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
-- Database: `business_digital_card_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_business_category`
--

CREATE TABLE `tbl_business_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_business_category`
--

INSERT INTO `tbl_business_category` (`id`, `name`, `status`, `created_at`) VALUES
(1, 'bus stop', 0, '2025-05-14 11:44:40'),
(3, 'dentist', 1, '2025-05-14 11:44:40'),
(4, 'insurance agency', 1, '2025-05-14 11:44:40'),
(5, 'atm', 0, '2025-05-14 11:44:40'),
(6, 'attorney', 0, '2025-05-14 11:44:40'),
(9, 'church', 1, '2025-05-14 11:44:40'),
(10, 'building', 1, '2025-05-14 11:44:40'),
(11, 'restaurant', 1, '2025-05-14 11:44:40'),
(12, 'beauty salon', 1, '2025-05-14 11:44:40'),
(14, 'corporate office', 1, '2025-05-14 11:44:40'),
(15, 'medical clinic', 1, '2025-05-14 11:44:40'),
(16, 'family practice physician', 1, '2025-05-14 11:44:40'),
(17, 'pharmacy', 1, '2025-05-14 11:44:40'),
(18, 'counselor', 1, '2025-05-14 11:44:40'),
(19, 'internist', 1, '2025-05-14 11:44:40'),
(20, 'general contractor', 1, '2025-05-14 11:44:40'),
(21, 'chiropractor', 1, '2025-05-14 11:44:40'),
(22, 'non-profit organization', 1, '2025-05-14 11:44:40'),
(23, 'convenience store', 1, '2025-05-14 11:44:40'),
(24, 'construction company', 1, '2025-05-14 11:44:40'),
(25, 'park', 1, '2025-05-14 11:44:40'),
(26, 'physical therapist', 1, '2025-05-14 11:44:40'),
(27, 'hair salon', 1, '2025-05-14 11:44:40'),
(28, 'Shops', 1, '2025-05-19 12:50:56'),
(29, 'IT Institute ', 1, '2025-05-19 12:51:40'),
(30, 'Computer Services', 1, '2025-05-19 12:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_business_info`
--

CREATE TABLE `tbl_business_info` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `business_category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `contact_no` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `link_token` varchar(255) DEFAULT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0 = Disable,\r\n1 = Enable',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_business_info`
--

INSERT INTO `tbl_business_info` (`id`, `name`, `business_category_id`, `user_id`, `description`, `logo`, `contact_no`, `email`, `link_token`, `address_line_1`, `address_line_2`, `city`, `state`, `zip`, `country`, `status`, `created_at`) VALUES
(35, 'Astro', 27, 80, 'This is one of the best name', 'assets/img/business_logo/logo_685a3fc138c6c4.26984874.jpg', '8320909090', 'astro@gmail.com', '7c7550bd5de5f78b3881b8a1dcf3b6ab', 'Ranuja Temple', '', 'Rajkot', 'Gujarat', '360002', 'India', 1, '2025-06-24 11:33:45'),
(36, 'Codeksha', 29, 84, 'This is one IT Training Institute', 'assets/img/business_logo/logo_685a97bd0da937.80712632.jpg', '8320999999', 'codeksha@gmail.com', '91cbf6d93718ee6b644b362c49c4d9d0', 'Shital Park', '', 'Rajkot', 'Gujarat', '360005', 'India', 1, '2025-06-24 17:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_icons`
--

CREATE TABLE `tbl_icons` (
  `id` int(11) NOT NULL,
  `social_category_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_icons`
--

INSERT INTO `tbl_icons` (`id`, `social_category_id`, `icon`, `created_at`) VALUES
(4, 15, '682ebf69c16385.40054176.png', '2025-05-22 06:08:41'),
(5, 16, '682ebf84a32fb0.28278396.png', '2025-05-22 06:09:08'),
(6, 17, '682ebf8eaf2179.34906833.png', '2025-05-22 06:09:18'),
(7, 18, '682ebfa8b9e772.66333461.png', '2025-05-22 06:09:44'),
(8, 19, '682ebfb6aac6e9.32856499.png', '2025-05-22 06:09:58'),
(9, 20, '682ebfc2971b22.15388934.png', '2025-05-22 06:10:10'),
(10, 21, '682ebfcc839e72.77694140.png', '2025-05-22 06:10:20'),
(11, 22, '682ebfe5071424.85956532.png', '2025-05-22 06:10:45'),
(12, 23, '682ebff8e1cb53.40997346.png', '2025-05-22 06:11:04'),
(13, 24, '6833f7a0735e77.76963926.png', '2025-05-26 05:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_media`
--

CREATE TABLE `tbl_media` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `business_info_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_media`
--

INSERT INTO `tbl_media` (`id`, `image`, `business_info_id`, `created_at`) VALUES
(10, 'other_685a3fc13b6af8.18460516.jpg', 35, '2025-06-24 11:33:45'),
(11, 'other_685a3fc13be714.52074554.jpg', 35, '2025-06-24 11:33:45'),
(12, 'other_685a3fc13c5789.65371129.jpg', 35, '2025-06-24 11:33:45'),
(13, 'other_685a3fc13ce700.74039064.webp', 35, '2025-06-24 11:33:45'),
(14, 'other_685a97bd13bbb6.37275903.jpg', 36, '2025-06-24 17:49:09'),
(15, 'other_685a97bd143ed1.65634664.jpg', 36, '2025-06-24 17:49:09'),
(16, 'other_685a97bd14bd59.20417494.jpg', 36, '2025-06-24 17:49:09'),
(17, 'other_685a97bd155de9.86934373.jpg', 36, '2025-06-24 17:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_links`
--

CREATE TABLE `tbl_other_links` (
  `id` int(11) NOT NULL,
  `business_info_id` int(11) NOT NULL,
  `link_title` varchar(255) NOT NULL,
  `link_sub_title` varchar(255) DEFAULT NULL,
  `link` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_other_links`
--

INSERT INTO `tbl_other_links` (`id`, `business_info_id`, `link_title`, `link_sub_title`, `link`, `created_at`) VALUES
(4, 35, 'T1', 'ST1', 'https://google.com', '2025-06-24 11:33:45'),
(5, 36, 'T1', 'Sub_title_one', 'https://google.com', '2025-06-24 17:49:09'),
(6, 36, 'T2', 'Sub_title_two', 'https://google.com2', '2025-06-24 17:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_category`
--

CREATE TABLE `tbl_social_category` (
  `id` int(11) NOT NULL,
  `platform_name` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1 COMMENT '0 = disable\r\n1 = enable',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_social_category`
--

INSERT INTO `tbl_social_category` (`id`, `platform_name`, `status`, `created_at`) VALUES
(15, 'Whatsapp', 1, '2025-05-22 11:35:47'),
(16, 'YouTube', 1, '2025-05-22 11:35:59'),
(17, 'Email', 1, '2025-05-22 11:36:47'),
(18, 'Linkedin', 1, '2025-05-22 11:37:02'),
(19, 'Instagram', 1, '2025-05-22 11:37:15'),
(20, 'Twitter', 1, '2025-05-22 11:37:40'),
(21, 'Snapchat', 1, '2025-05-22 11:37:56'),
(22, 'Pinterest', 1, '2025-05-22 11:38:07'),
(23, 'Skype', 1, '2025-05-22 11:38:17'),
(24, 'Google Map', 1, '2025-05-26 10:37:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social_links`
--

CREATE TABLE `tbl_social_links` (
  `id` int(11) NOT NULL,
  `social_category_id` int(11) DEFAULT NULL,
  `business_info_id` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_social_links`
--

INSERT INTO `tbl_social_links` (`id`, `social_category_id`, `business_info_id`, `link`, `created_at`) VALUES
(11, 15, 35, 'https://whatsapp.com', '2025-06-24 11:33:45'),
(12, 18, 35, 'https://linkedin.com', '2025-06-24 11:33:45'),
(13, 16, 35, 'https://youtube.com', '2025-06-24 11:33:45'),
(14, 17, 35, 'mailto://astro@gmail.com', '2025-06-24 11:33:45'),
(15, 15, 36, 'https://whatsapp.com', '2025-06-24 17:49:09'),
(16, 16, 36, 'https://youtube.com', '2025-06-24 17:49:09'),
(17, 18, 36, 'https://linkedin.com', '2025-06-24 17:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `role` varchar(10) DEFAULT '2' COMMENT '1 = admin, \r\n2 = user',
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `role`, `first_name`, `middle_name`, `last_name`, `email`, `mobile`, `password`, `status`, `created_at`) VALUES
(80, '2', 'Manthan', '', 'Mistry', 'manthan@gmail.com', '8320909090', '6a15a725a43a60f32190e3929887f513', 1, '2025-05-13 10:11:38'),
(83, '1', 'Manthan', 'K', 'Mistry', 'manthan.jdg@gmail.com', '8320909091', '6a15a725a43a60f32190e3929887f513', 1, '2025-05-13 12:38:20'),
(84, '2', 'Shiv', NULL, 'Zala', 'shiv@gmail.com', '8320909091', '9368f13fc37646984ecd239f2511294e', 1, '2025-06-02 16:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_verification`
--

CREATE TABLE `tbl_verification` (
  `id` int(11) NOT NULL,
  `verification_status` tinyint(4) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `verification_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_verification`
--

INSERT INTO `tbl_verification` (`id`, `verification_status`, `user_id`, `token`, `created_at`, `verification_time`) VALUES
(11, 0, 80, '8e5c7eea6144ff1d067d67fefc4b88', '2025-05-13 10:11:38', '2025-05-13 10:21:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_business_category`
--
ALTER TABLE `tbl_business_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_business_info`
--
ALTER TABLE `tbl_business_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_business_info_ibfk_1` (`business_category_id`),
  ADD KEY `tbl_business_info_ibfk_2` (`user_id`);

--
-- Indexes for table `tbl_icons`
--
ALTER TABLE `tbl_icons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_icons_ibfk_1` (`social_category_id`);

--
-- Indexes for table `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_media_ibfk_1` (`business_info_id`);

--
-- Indexes for table `tbl_other_links`
--
ALTER TABLE `tbl_other_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `business_info_id` (`business_info_id`);

--
-- Indexes for table `tbl_social_category`
--
ALTER TABLE `tbl_social_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_social_links`
--
ALTER TABLE `tbl_social_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_social_links_ibfk_1` (`social_category_id`),
  ADD KEY `tbl_social_links_ibfk_2` (`business_info_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_verification`
--
ALTER TABLE `tbl_verification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_verification_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_business_category`
--
ALTER TABLE `tbl_business_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_business_info`
--
ALTER TABLE `tbl_business_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_icons`
--
ALTER TABLE `tbl_icons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_media`
--
ALTER TABLE `tbl_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_other_links`
--
ALTER TABLE `tbl_other_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_social_category`
--
ALTER TABLE `tbl_social_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_social_links`
--
ALTER TABLE `tbl_social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `tbl_verification`
--
ALTER TABLE `tbl_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_business_info`
--
ALTER TABLE `tbl_business_info`
  ADD CONSTRAINT `tbl_business_info_ibfk_1` FOREIGN KEY (`business_category_id`) REFERENCES `tbl_business_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_business_info_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_icons`
--
ALTER TABLE `tbl_icons`
  ADD CONSTRAINT `tbl_icons_ibfk_1` FOREIGN KEY (`social_category_id`) REFERENCES `tbl_social_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_media`
--
ALTER TABLE `tbl_media`
  ADD CONSTRAINT `tbl_media_ibfk_1` FOREIGN KEY (`business_info_id`) REFERENCES `tbl_business_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_other_links`
--
ALTER TABLE `tbl_other_links`
  ADD CONSTRAINT `tbl_other_links_ibfk_1` FOREIGN KEY (`business_info_id`) REFERENCES `tbl_business_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_social_links`
--
ALTER TABLE `tbl_social_links`
  ADD CONSTRAINT `tbl_social_links_ibfk_1` FOREIGN KEY (`social_category_id`) REFERENCES `tbl_social_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_social_links_ibfk_2` FOREIGN KEY (`business_info_id`) REFERENCES `tbl_business_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_verification`
--
ALTER TABLE `tbl_verification`
  ADD CONSTRAINT `tbl_verification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
