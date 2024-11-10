-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2024 at 02:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simplestats-web-leaderboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `ruststats`
--

CREATE TABLE `ruststats` (
  `id` int(10) UNSIGNED NOT NULL,
  `server_id` smallint(6) NOT NULL,
  `steamid` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `playtime` int(11) NOT NULL COMMENT 'Playtime in seconds',
  `kills` mediumint(9) NOT NULL,
  `deaths` mediumint(9) NOT NULL,
  `kdr` float NOT NULL,
  `avatar` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruststats`
--

INSERT INTO `ruststats` (`id`, `server_id`, `steamid`, `name`, `playtime`, `kills`, `deaths`, `kdr`, `avatar`) VALUES
(1, 1, '1', 'test', 12, 4, 6, 54, ''),
(2, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(3, 1, '1', 'test', 12, 4, 6, 54, ''),
(4, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(5, 1, '1', 'test', 12, 4, 6, 54, ''),
(6, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(7, 1, '1', 'test', 12, 4, 6, 54, ''),
(8, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(9, 1, '1', 'test', 12, 4, 6, 54, ''),
(10, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(11, 1, '1', 'test', 12, 4, 6, 54, ''),
(12, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(13, 1, '1', 'test', 12, 4, 6, 54, ''),
(14, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(15, 1, '1', 'test', 12, 4, 6, 54, ''),
(16, 1, '1', 'test 2', 120, 46, 66, 5.4, ''),
(17, 2, '2', 'test', 12, 4, 6, 54, ''),
(18, 2, '2', 'test 2', 120, 46, 66, 5.4, ''),
(19, 2, '2', 'server 2 name 3', 12, 4, 6, 54, ''),
(20, 2, '3', 'server 2 name 4', 120, 46, 66, 5.4, ''),
(21, 3, '4', 'server 3 name 1', 12, 4, 6, 54, ''),
(22, 3, '3', 'server 3 name 2', 120, 46, 66, 5.4, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ruststats`
--
ALTER TABLE `ruststats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `steamid_index` (`steamid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ruststats`
--
ALTER TABLE `ruststats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
