-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2026 at 07:55 PM
-- Server version: 8.0.20
-- PHP Version: 8.3.3

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
  `id` int NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `realname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `zipcode` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `bio` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `salary` int NOT NULL,
  `preference` int NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `likes` int DEFAULT NULL,
  `role` int NOT NULL DEFAULT '1',
  `passhash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `username`, `realname`, `zipcode`, `bio`, `salary`, `preference`, `email`, `likes`, `role`, `passhash`) VALUES
(1, 'leppanee', 'Erik Leppänen', '04250', 'Wow it works. Awesome', 50, 1, 'leppanee@arcada.fi', 4, 4, '$2y$10$qUv8UDpfDY9v6W5skhrKJu.x5N9Q6HH/lQTX2i4hVnCYmAvNsqUsa'),
(2, 'alfred', 'alfred krupp', '2216', 'bowow', 20000, 1, 'alfred@gmail.com', NULL, 1, '$2y$10$nn.bImGShEeIEnC5OuGGP.HvdmDVXyfxOy9zman9T2wEuE./lkHIa'),
(3, 'wawa', 'dw dw', '1234', 'i hate easyphp', 0, 2, 'testmai@gmail.com', NULL, 1, '$2y$10$TvVL.Nzj5Y/MlnY1Tgahq.aEwblyPaTvt5XhXWW3YbQaVGIeo62ru'),
(4, 'new_user', 'awesome suer', '123', 'qwerrttrewerfererert', 19, 2, 'secret@gmail.com', NULL, 1, '$2y$10$2nSFEm3HujM3qwzNj9YHK.F12rYzt2Ot6/FV72ZkMB1PzLpdEqxIC'),
(5, 'sexpest', 'mr awesome', '124231', 'this user sucks', 4, 0, 'theawessomesex@gmail.com', NULL, 1, '$2y$10$WRWu1zAhN9mE4ozksX61sOkXyWHeLi909ZoCrq/9E58BNFAgFdGF6'),
(7, 'admin', 'adam minor', '12345', 'notanadmin..', 999999, 2, 'admin@gmail.com', NULL, 1, '$2y$10$opR7EmcYU71Vy2Sf9PkI8.VRMkYG3ra7tKSY3zPeJ3BJxLIypKl4q'),
(8, 'user1', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(9, 'user2', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal1@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(10, 'user3', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal3@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(11, 'user4', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal4@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(12, 'user5', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal5@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(13, 'user6', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal6@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(14, 'leppanee1', 'Erik Leppänen', '04250', 'Wow it works. Awesome', 50, 1, 'leppanee1@arcada.fi', 4, 4, '$2y$10$qUv8UDpfDY9v6W5skhrKJu.x5N9Q6HH/lQTX2i4hVnCYmAvNsqUsa'),
(15, 'user7', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal7@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im'),
(16, 'user8', 'user2 user3', '4231', 'qwerty', 4932, 1, 'notreal8@gmail.com', NULL, 1, '$2y$10$IFSkaWs1Y0AH2DhCUHG9vOU/5zD0cV53dXns1rI7BMIMbbFnK64Im');

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
