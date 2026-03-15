-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 15, 2026 at 02:57 PM
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
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `salary` int(11) NOT NULL,
  `preference` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `likes` int(11) DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `passhash` varchar(255) NOT NULL,
  `is_softbanned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `username`, `realname`, `zipcode`, `bio`, `salary`, `preference`, `email`, `likes`, `role`, `passhash`, `is_softbanned`) VALUES
(17, 'admin', 'adam min', '1', 'wowzer', 2, 0, 'thelord@gmail.com', NULL, 4, '$2y$10$fK4sUnXSWUTTmhxilMQzveEdKndmFFdnkE0wnVPtpc3/0GOEcezY.', 0);

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
-- Indexes for dumped tables
--

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `profile_comments`
--
ALTER TABLE `profile_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
