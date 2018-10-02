-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2018 at 11:28 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mtc`
--

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `Id` int(11) UNSIGNED NOT NULL,
  `ListingId` int(10) NOT NULL,
  `County` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `Town` varchar(100) DEFAULT NULL,
  `PostCode` varchar(15) DEFAULT NULL,
  `Description` text,
  `FullDetailsUrl` varchar(2083) NOT NULL DEFAULT '',
  `DisplayableAddress` varchar(500) NOT NULL DEFAULT '',
  `ImageURL` varchar(2083) NOT NULL,
  `ThumbnailURL` varchar(2083) NOT NULL DEFAULT '',
  `Latitude` decimal(10,8) NOT NULL,
  `Price` int(10) UNSIGNED NOT NULL,
  `Longitude` decimal(11,8) NOT NULL,
  `NumberOfBedrooms` int(3) UNSIGNED NOT NULL DEFAULT '1',
  `NumberOfBathrooms` int(3) UNSIGNED NOT NULL DEFAULT '1',
  `PropertyType` varchar(100) NOT NULL DEFAULT '',
  `SaleOrRent` enum('sale','rent','') NOT NULL DEFAULT '',
  `UpdatedByAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `DeletedByAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
