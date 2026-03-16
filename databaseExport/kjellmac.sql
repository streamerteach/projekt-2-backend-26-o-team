-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 05:10 PM
-- Server version: 8.0.20
-- PHP Version: 8.2.14

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
  `id` bigint NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `realname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `zipcode` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `salary` int NOT NULL,
  `preference` int NOT NULL,
  `gender` int NOT NULL DEFAULT '2' COMMENT '0=male, 1=female, 2=other',
  `email` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `likes` int DEFAULT NULL,
  `role` int NOT NULL DEFAULT '1',
  `passhash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_softbanned` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `username`, `realname`, `zipcode`, `bio`, `salary`, `preference`, `gender`, `email`, `likes`, `role`, `passhash`, `is_softbanned`) VALUES
(1, 'leppanee', 'Erik Leppänen', '04250', 'Wow it works. Awesome', 50, 1, 2, 'leppanee@arcada.fi', 4, 4, '$2y$10$qUv8UDpfDY9v6W5skhrKJu.x5N9Q6HH/lQTX2i4hVnCYmAvNsqUsa', 0),
(2, 'alfred', 'alfred krupp', '2216', 'bowow', 20000, 1, 2, 'alfred@gmail.com', NULL, 1, '$2y$10$nn.bImGShEeIEnC5OuGGP.HvdmDVXyfxOy9zman9T2wEuE./lkHIa', 0),
(3, 'wawa', 'dw dw', '1234', 'i hate easyphp', 0, 2, 2, 'testmai@gmail.com', NULL, 1, '$2y$10$TvVL.Nzj5Y/MlnY1Tgahq.aEwblyPaTvt5XhXWW3YbQaVGIeo62ru', 0),
(4, 'new_user', 'awesome suer', '123', 'qwerrttrewerfererert', 19, 2, 2, 'secret@gmail.com', NULL, 1, '$2y$10$2nSFEm3HujM3qwzNj9YHK.F12rYzt2Ot6/FV72ZkMB1PzLpdEqxIC', 0),
(5, 'sexpest', 'mr awesome', '124231', 'this user sucks', 4, 0, 2, 'theawessomesex@gmail.com', NULL, 1, '$2y$10$WRWu1zAhN9mE4ozksX61sOkXyWHeLi909ZoCrq/9E58BNFAgFdGF6', 0),
(7, 'admin', 'adam minor', '12345', 'notanadmin..', 999999, 2, 1, 'admin@gmail.com', NULL, 1, '$2y$10$opR7EmcYU71Vy2Sf9PkI8.VRMkYG3ra7tKSY3zPeJ3BJxLIypKl4q', 0),
(8, 'user1', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(9, 'user2', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal1@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(10, 'user3', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal3@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(11, 'user4', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal4@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(12, 'user5', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal5@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(13, 'user6', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal6@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(14, 'leppanee1', 'Erik Leppänen', '04250', 'Wow it works. Awesome', 50, 1, 2, 'leppanee1@arcada.fi', 4, 4, '$2y$10$qUv8UDpfDY9v6W5skhrKJu.x5N9Q6HH/lQTX2i4hVnCYmAvNsqUsa', 0),
(15, 'user7', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal7@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0),
(16, 'user8', 'user2 user3', '4231', 'qwerty', 4932, 1, 2, 'notreal8@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im', 0);

-- --------------------------------------------------------

--
-- Table structure for table `profile_comments`
--

CREATE TABLE `profile_comments` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL COMMENT 'Author of the comment',
  `profile_owner_id` bigint NOT NULL COMMENT 'User whose profile is being commented on',
  `parent_comment_id` bigint DEFAULT NULL COMMENT 'NULL for top-level comments, otherwise ID of parent',
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stores comments on user profiles with support for nested replies';

--
-- Dumping data for table `profile_comments`
--

INSERT INTO `profile_comments` (`id`, `user_id`, `profile_owner_id`, `parent_comment_id`, `content`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 2, 1, NULL, 'oosd', '2026-03-14 16:45:21', '2026-03-14 16:45:21', 0),
(2, 2, 1, 1, 'it works?', '2026-03-14 16:45:36', '2026-03-14 16:45:36', 0),
(3, 2, 1, 1, 'oh my it works', '2026-03-14 16:45:49', '2026-03-14 16:45:49', 0),
(4, 2, 2, NULL, 'i have no friend so i write comments on my own profile', '2026-03-14 16:46:50', '2026-03-14 16:46:50', 0),
(5, 8, 7, NULL, 'testting', '2026-03-14 21:15:25', '2026-03-14 21:15:25', 0),
(6, 8, 7, 5, '4895023', '2026-03-14 21:15:39', '2026-03-14 21:15:39', 0);

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `profile_comments`
--
ALTER TABLE `profile_comments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
