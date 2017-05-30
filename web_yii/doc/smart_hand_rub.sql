-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 18, 2017 at 04:59 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_hand_rub`
--
CREATE DATABASE IF NOT EXISTS `smart_hand_rub` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `smart_hand_rub`;

-- --------------------------------------------------------

--
-- Table structure for table `gateway`
--

DROP TABLE IF EXISTS `gateway`;
CREATE TABLE `gateway` (
  `id` int(10) UNSIGNED NOT NULL,
  `serial` varchar(32) NOT NULL,
  `label` varchar(20) NOT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gateway`
--

INSERT INTO `gateway` (`id`, `serial`, `label`, `remark`, `created_at`, `updated_at`) VALUES
(1, '0000000038a3eddd', 'ECE', 'np', NULL, '2017-04-17 02:42:23');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_summary`
--

DROP TABLE IF EXISTS `gateway_summary`;
CREATE TABLE `gateway_summary` (
  `id` int(10) UNSIGNED NOT NULL,
  `gateway_id` int(10) UNSIGNED NOT NULL,
  `stats_date` date NOT NULL,
  `press_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `replenish_count` int(10) UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gateway_token`
--

DROP TABLE IF EXISTS `gateway_token`;
CREATE TABLE `gateway_token` (
  `id` int(10) UNSIGNED NOT NULL,
  `gateway_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(32) NOT NULL DEFAULT '',
  `label` varchar(20) DEFAULT NULL,
  `mac_address` varchar(32) DEFAULT NULL,
  `expire` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gateway_token`
--

INSERT INTO `gateway_token` (`id`, `gateway_id`, `token`, `label`, `mac_address`, `expire`, `created_at`) VALUES
(5, 1, 'IFovRYg_rHDfMVHA5vd7O7E5zNV1W4WX', 'ACCESS', '::1', '2017-05-18 01:35:58', '2017-04-18 01:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(10) UNSIGNED NOT NULL,
  `gateway_id` int(10) UNSIGNED NOT NULL,
  `serial` varchar(32) NOT NULL COMMENT 'unique identity of the embedded module',
  `label` varchar(20) DEFAULT NULL COMMENT 'human readable label',
  `remark` varchar(100) DEFAULT NULL COMMENT 'Optional extra information about the sanitizer',
  `initial_weight` int(10) UNSIGNED DEFAULT '500',
  `status` int(4) UNSIGNED NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`id`, `gateway_id`, `serial`, `label`, `remark`, `initial_weight`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'abc', '', '', NULL, 10, '2017-04-17 03:08:06', '2017-04-17 06:41:32'),
(3, 1, 'abcd', 'near the main door', 'room 1', 500, 10, '2017-04-17 06:41:02', '2017-04-17 06:41:36');

-- --------------------------------------------------------

--
-- Table structure for table `node_press`
--

DROP TABLE IF EXISTS `node_press`;
CREATE TABLE `node_press` (
  `id` int(10) UNSIGNED NOT NULL,
  `node_id` int(10) UNSIGNED NOT NULL,
  `press` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `node_summary`
--

DROP TABLE IF EXISTS `node_summary`;
CREATE TABLE `node_summary` (
  `id` int(10) UNSIGNED NOT NULL,
  `node_id` int(10) UNSIGNED NOT NULL,
  `stats_date` date NOT NULL,
  `press_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_weight` int(10) UNSIGNED DEFAULT NULL,
  `to_replenish` int(2) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `node_weight`
--

DROP TABLE IF EXISTS `node_weight`;
CREATE TABLE `node_weight` (
  `id` int(10) UNSIGNED NOT NULL,
  `node_id` int(10) UNSIGNED NOT NULL,
  `weight` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(20) NOT NULL,
  `value` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `label`, `value`, `created_at`, `updated_at`) VALUES
(1, 'min_weight', '80', '2017-04-12 01:26:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'FfFTxNJQmNLQMP-2GMvOj-yD8XsRSFau', '$2y$13$oE1GPherU5xRdxC4Mrf8MeIYGkE/tTZpPEq9E7p8giGDUOhRq8hhC', NULL, 'phuonghoatink22@gmail.com', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gateway`
--
ALTER TABLE `gateway`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`serial`);

--
-- Indexes for table `gateway_summary`
--
ALTER TABLE `gateway_summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cluster_id` (`gateway_id`);

--
-- Indexes for table `gateway_token`
--
ALTER TABLE `gateway_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `gatewayId` (`gateway_id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial` (`serial`),
  ADD KEY `gateway_id` (`gateway_id`,`serial`);

--
-- Indexes for table `node_press`
--
ALTER TABLE `node_press`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node_id` (`node_id`) USING BTREE;

--
-- Indexes for table `node_summary`
--
ALTER TABLE `node_summary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node_id` (`node_id`);

--
-- Indexes for table `node_weight`
--
ALTER TABLE `node_weight`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`node_id`),
  ADD KEY `sanitizer_id` (`node_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gateway`
--
ALTER TABLE `gateway`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gateway_summary`
--
ALTER TABLE `gateway_summary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `gateway_token`
--
ALTER TABLE `gateway_token`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `node_press`
--
ALTER TABLE `node_press`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_summary`
--
ALTER TABLE `node_summary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `node_weight`
--
ALTER TABLE `node_weight`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `gateway_summary`
--
ALTER TABLE `gateway_summary`
  ADD CONSTRAINT `gateway_summary_ibfk_1` FOREIGN KEY (`gateway_id`) REFERENCES `gateway` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gateway_token`
--
ALTER TABLE `gateway_token`
  ADD CONSTRAINT `gateway_token_ibfk_1` FOREIGN KEY (`gateway_id`) REFERENCES `gateway` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `node`
--
ALTER TABLE `node`
  ADD CONSTRAINT `node_ibfk_1` FOREIGN KEY (`gateway_id`) REFERENCES `gateway` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `node_press`
--
ALTER TABLE `node_press`
  ADD CONSTRAINT `node_press_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `node_summary`
--
ALTER TABLE `node_summary`
  ADD CONSTRAINT `node_summary_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `node_weight`
--
ALTER TABLE `node_weight`
  ADD CONSTRAINT `node_weight_ibfk_1` FOREIGN KEY (`node_id`) REFERENCES `node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
