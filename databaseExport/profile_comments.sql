-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 14, 2026 at 05:11 PM
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
-- Database: `kjellmac`
--

-- --------------------------------------------------------

--
-- Table structure for table `profile_comments`
--

CREATE TABLE `profile_comments` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL COMMENT 'Author of the comment',
  `profile_owner_id` bigint(20) NOT NULL COMMENT 'User whose profile is being commented on',
  `parent_comment_id` bigint(20) DEFAULT NULL COMMENT 'NULL for top-level comments, otherwise ID of parent',
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores comments on user profiles with support for nested replies';

--
-- Dumping data for table `profile_comments`
--

INSERT INTO `profile_comments` (`id`, `user_id`, `profile_owner_id`, `parent_comment_id`, `content`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 2, 1, NULL, 'oosd', '2026-03-14 16:45:21', '2026-03-14 16:45:21', 0),
(2, 2, 1, 1, 'it works?', '2026-03-14 16:45:36', '2026-03-14 16:45:36', 0),
(3, 2, 1, 1, 'oh my it works', '2026-03-14 16:45:49', '2026-03-14 16:45:49', 0),
(4, 2, 2, NULL, 'i have no friend so i write comments on my own profile', '2026-03-14 16:46:50', '2026-03-14 16:46:50', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `profile_comments`
--
ALTER TABLE `profile_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_profile_owner` (`profile_owner_id`),
  ADD KEY `idx_parent` (`parent_comment_id`),
  ADD KEY `fk_profile_comments_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profile_comments`
--
ALTER TABLE `profile_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profile_comments`
--
ALTER TABLE `profile_comments`
  ADD CONSTRAINT `fk_profile_comments_owner` FOREIGN KEY (`profile_owner_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profile_comments_parent` FOREIGN KEY (`parent_comment_id`) REFERENCES `profile_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profile_comments_user` FOREIGN KEY (`user_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
