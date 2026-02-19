-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 19, 2026 at 01:12 PM
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
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `bio` varchar(255) NOT NULL,
  `salary` int(10) NOT NULL,
  `preference` int(1) NOT NULL,
  `email` varchar(40) NOT NULL,
  `likes` int(4) DEFAULT NULL,
  `role` int(1) NOT NULL DEFAULT 1,
  `passhash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `username`, `realname`, `zipcode`, `bio`, `salary`, `preference`, `email`, `likes`, `role`, `passhash`) VALUES
(1, 'leppanee', 'Erik Leppänen', '04250', 'Wow it works. Awesome', 50, 1, 'leppanee@arcada.fi', 4, 4, '$2y$10$qUv8UDpfDY9v6W5skhrKJu.x5N9Q6HH/lQTX2i4hVnCYmAvNsqUsa'),
(2, 'alfred', 'alfred krupp', '2216', 'bowow', 20000, 1, 'alfred@gmail.com', NULL, 1, '$2y$10$nn.bImGShEeIEnC5OuGGP.HvdmDVXyfxOy9zman9T2wEuE./lkHIa');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
