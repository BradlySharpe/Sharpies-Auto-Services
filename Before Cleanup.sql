-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2015 at 12:00 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autoservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE IF NOT EXISTS `car` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `make` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `registration` varchar(6) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `owner`, `make`, `model`, `registration`, `created`) VALUES
(1, 1, 'Comm', 'VS V8 Ute 7/97', 'WVS955', '2015-10-13 04:41:00'),
(2, 2, 'Ford', '8/06 Boss 290 ute', 'WFC634', '2015-10-19 00:22:16'),
(3, 3, 'MAZDA', 'E2000H VAN 1/05', 'XEVO22', '2015-10-19 22:20:40'),
(4, 4, 'HOLDEN', 'TS ASTRA', 'JAKER1', '2015-10-20 04:08:45'),
(5, 5, 'MITSUBISHI', 'TRITON GLX R  6/09', '1EH4BT', '2015-10-21 01:11:48'),
(6, 6, 'HONDA', 'JAZZ 11/09', '1AT40D', '2015-10-21 22:19:13'),
(7, 4, 'TOYOTA', 'YARIS 9/11', 'IMINIT', '2015-10-22 02:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(20) NOT NULL,
  `postcode` int(4) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `firstname`, `lastname`, `address`, `city`, `state`, `postcode`, `created`) VALUES
(1, 'camo', '.', '.', 'Warrnambool', 'Victoria', 3280, '2015-10-13 04:40:22'),
(2, 'Justin', 'O"keefe', '49 Koroit ST', 'Warrnambool', 'Victoria', 3280, '2015-10-19 00:19:54'),
(3, 'MACS HOTEL', '.', '199 FAIRY ST', 'Warrnambool', 'Victoria', 3280, '2015-10-19 22:20:12'),
(4, 'SUSAN', 'ALLAN', '12 BEST ST', 'WINSLOW', 'Victoria', 3281, '2015-10-20 04:08:15'),
(5, 'LUKE', 'GURRY', '5 THE HILL CRT', 'WOODFORD', 'Victoria', 3281, '2015-10-21 01:10:00'),
(6, 'CATHY', 'ATRILL', '309 LAVA ST', 'Warrnambool', 'Victoria', 3280, '2015-10-21 22:17:47');

-- --------------------------------------------------------

--
-- Table structure for table `detail`
--

CREATE TABLE IF NOT EXISTS `detail` (
  `id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `description` text NOT NULL,
  `comment` text,
  `cost` float NOT NULL,
  `quantity` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail`
--

INSERT INTO `detail` (`id`, `invoice`, `description`, `comment`, `cost`, `quantity`) VALUES
(1, 1000, 'Hychill Refrigerant ', '', 60, 0.8),
(2, 1000, 'O ring OR9032', '', 7, 2),
(3, 1000, 'Refrigerant oil', '', 80, 0.12),
(4, 1000, 'VS V8 A/C compressor front bearing (35BG05S16G-2DL)', '', 32, 1),
(5, 1000, 'VS V8 A/C discharge hose', '', 125.4, 1),
(6, 1000, 'VS V8 A/C drive belt 13A0850', '', 19.5, 1),
(7, 1000, 'Labour', 'R+R Front bumper. Fit new condenser, receiver dryer, electric fan and pipes. Fit new front bearing to compressor. Fit new compressor drive belt. Vac system to check for leaks, add refrigerant oil, re-gas system and test.', 70, 5),
(8, 1001, 'NULON 6LT 10W/40 FULL SYNTHETIC ENGINE OIL', '', 75.5, 1),
(9, 1001, 'RYCO PREMIUM OIL FILTER', '', 17.95, 1),
(10, 1001, 'Labour', 'CARRIED OUT 117000 KM SERVICE AND SAFETY CHECK', 70, 1.5),
(11, 1002, '15W/40 MOTOR OIL', '', 6.5, 4),
(12, 1002, 'RYCO PREMIUM OIL FILTER Z436', '', 12.5, 1),
(13, 1002, 'Labour', 'CARRIED OUT 256000KM SERVICE AND SAFETY CHECK', 70, 2),
(14, 1003, 'Labour', 'ENGINE STALLING AND WONT START,ENGINE LIGHT STAYING ON.  CHECK CODES AND WIRING CONNECTORS, CODE P0001 ENGINE STALLING (BAD EARTH TO ENGINE) MAKE UP AND FIT NEW EARTH WIRE FROM BODY TO ENGINE. CLEAR CODES AND ROAD TEST                                                                                                   ', 70, 2.5),
(15, 1004, '5W/30 SYNTHETIC C3 MOTOR OIL', '', 5.7, 8),
(16, 1004, 'NIPPON MAX OIL FILTER WZ372NM', '', 44.95, 1),
(17, 1004, 'RYCO PREMIUM AIR FILTER A1512', '', 56.5, 1),
(18, 1004, 'WESTFIL FUEL FILTER WCF103', '', 42.95, 1),
(19, 1004, 'BENDIX 4WD DISC PADS DB1774-4WD', '', 135.5, 1),
(20, 1004, 'Labour', 'CARRIED OUT 92000KM SERVICE AND SAFETY CHECK,REPLACE FUEL AND AIR FILTERS,CHECK ALL DRIVELINE OIL LEVELS,CHECK REAR BRAKES AND REPLACE FRONT BRAKE PADS,ROTATE WHEELS,CHECK TROUBLE CODES (MAP SENSOR FAULT) CLEAR CODES AND ROAD TEST(CHECK LIGHT REMAINED OFF) MAYBE INTERMITTENT FAULT', 70, 3),
(21, 1005, '10W/30 MOTOR OIL', '', 3.5, 4),
(22, 1005, 'RYCO PREMIUM OIL FILTER Z547', '', 16.95, 1),
(23, 1006, '10W/30 MOTOR OIL', '', 3.5, 4),
(24, 1006, 'RYCO PREMIUM OIL FILTER Z547', '', 16.95, 1),
(25, 1006, 'Labour', 'CARRIED OUT 59496KM SERVICE AND SAFETY CHECK,ROTATE WHEELS,ROAD TEST', 70, 1.2),
(26, 1007, '10W/30 MOTOR OIL', '', 3.5, 4),
(27, 1007, 'RYCO PREMIUM OIL FILTER Z386', '', 11.5, 1),
(28, 1007, 'SPARK PLUGS BKR5EYA-11', '', 5.95, 4),
(29, 1007, 'Labour', 'CARRIED OUT 86000KM SERVICE AND SAFETY CHECK,REPLACED SPARK PLUGS,ROTATE WHEELS,CHECK A/C OPERATION,ROAD TEST.  (NEEDS IN TANK FUEL FILTER NEXT SERVICE)', 70, 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1008 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `service`, `created`) VALUES
(1000, 1000, '2015-10-13 04:51:22'),
(1001, 1001, '2015-10-19 03:18:46'),
(1002, 1002, '2015-10-20 01:03:47'),
(1003, 1003, '2015-10-20 04:30:18'),
(1004, 1004, '2015-10-21 03:35:12'),
(1005, 1005, '2015-10-21 23:55:48'),
(1006, 1006, '2015-10-22 00:00:40'),
(1007, 1007, '2015-10-22 03:59:47');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `defaultCost` float DEFAULT NULL,
  `defaultQuantity` float DEFAULT NULL,
  `comment` tinyint(1) DEFAULT '1',
  `active` tinyint(1) DEFAULT '1',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `description`, `defaultCost`, `defaultQuantity`, `comment`, `active`, `created`) VALUES
(1, 'O ring OR9032', 7, 1, 0, 1, '2015-10-13 04:42:38'),
(2, 'Labour', 70, 1, 0, 1, '2015-10-13 04:42:48'),
(3, 'Hychill Refrigerant ', 60, 1, 0, 1, '2015-10-13 04:43:21'),
(4, 'Refrigerant oil', 80, 1, 0, 1, '2015-10-13 04:43:39'),
(5, 'VS V8 A/C compressor front bearing (35BG05S16G-2DL)', 32, 1, 0, 1, '2015-10-13 04:44:33'),
(6, 'VS V8 A/C discharge hose', 125.4, 1, 0, 1, '2015-10-13 04:45:07'),
(7, 'VS V8 A/C drive belt 13A0850', 19.5, 1, 0, 1, '2015-10-13 04:45:44'),
(8, 'NULON 6LT 10W/40 FULL SYNTHETIC ENGINE OIL', 75.5, 1, 0, 1, '2015-10-19 03:14:07'),
(9, 'RYCO PREMIUM OIL FILTER', 17.95, 1, 0, 1, '2015-10-19 03:15:12'),
(10, '15W/40 MOTOR OIL', 6.5, 1, 0, 1, '2015-10-20 00:57:47'),
(11, 'RYCO PREMIUM OIL FILTER Z436', 12.5, 1, 0, 1, '2015-10-20 00:58:41'),
(12, 'RYCO PREMIUM AIR FILTER A1512', 56.5, 1, 0, 1, '2015-10-21 03:20:15'),
(13, 'NIPPON MAX OIL FILTER WZ372NM', 44.95, 1, 0, 1, '2015-10-21 03:21:08'),
(14, 'WESTFIL FUEL FILTER WCF103', 42.95, 1, 0, 1, '2015-10-21 03:21:58'),
(15, '5W/30 SYNTHETIC C3 MOTOR OIL', 5.7, 1, 0, 1, '2015-10-21 03:23:42'),
(16, 'BENDIX 4WD DISC PADS DB1774-4WD', 135.5, 1, 0, 1, '2015-10-21 03:24:36'),
(17, 'RYCO PREMIUM OIL FILTER Z547', 16.95, 1, 0, 1, '2015-10-21 23:52:54'),
(18, '10W/30 MOTOR OIL', 3.5, 1, 0, 1, '2015-10-21 23:54:20'),
(19, 'RYCO PREMIUM OIL FILTER Z386', 11.5, 1, 0, 1, '2015-10-22 03:51:55'),
(20, 'RYCO PREMIUM OIL FILTER Z386', 11.5, 1, 0, 1, '2015-10-22 03:51:55'),
(21, 'SPARK PLUGS BKR5EYA-11', 5.95, 1, 0, 1, '2015-10-22 03:53:10');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `amount` float NOT NULL,
  `comment` text,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `safetycheck`
--

CREATE TABLE IF NOT EXISTS `safetycheck` (
  `id` int(11) NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `safetycheck`
--

INSERT INTO `safetycheck` (`id`, `completed`, `created`) VALUES
(1000, 0, '2015-10-13 04:41:10'),
(1001, 0, '2015-10-19 00:22:26'),
(1002, 0, '2015-10-19 22:20:50'),
(1003, 0, '2015-10-20 04:08:54'),
(1004, 0, '2015-10-20 09:01:40'),
(1005, 0, '2015-10-21 01:11:58'),
(1006, 0, '2015-10-21 22:19:18'),
(1007, 0, '2015-10-21 23:56:51'),
(1008, 0, '2015-10-22 02:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `car` int(11) NOT NULL,
  `odo` int(6) NOT NULL,
  `safetyCheck` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=1008 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `owner`, `car`, `odo`, `safetyCheck`, `created`) VALUES
(1000, 1, 1, 254714, 1000, '2015-10-13 04:41:17'),
(1001, 2, 2, 117833, 1001, '2015-10-19 00:22:42'),
(1002, 3, 3, 256140, 1002, '2015-10-19 22:21:27'),
(1003, 4, 4, 161271, 1003, '2015-10-20 04:10:42'),
(1004, 5, 5, 92558, 1005, '2015-10-21 01:13:45'),
(1005, 6, 6, 59496, 1006, '2015-10-21 22:20:00'),
(1006, 6, 6, 59496, 1007, '2015-10-21 23:57:18'),
(1007, 4, 7, 86696, 1008, '2015-10-22 02:48:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration` (`registration`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail`
--
ALTER TABLE `detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice` (`invoice`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service` (`service`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safetycheck`
--
ALTER TABLE `safetycheck`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`owner`),
  ADD KEY `car` (`car`),
  ADD KEY `safetyCheck` (`safetyCheck`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `detail`
--
ALTER TABLE `detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1008;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `safetycheck`
--
ALTER TABLE `safetycheck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1009;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1008;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `fk_car_ownerId` FOREIGN KEY (`owner`) REFERENCES `customer` (`id`);

--
-- Constraints for table `detail`
--
ALTER TABLE `detail`
  ADD CONSTRAINT `fk_detail_invoiceId` FOREIGN KEY (`invoice`) REFERENCES `invoice` (`id`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_serviceId` FOREIGN KEY (`service`) REFERENCES `service` (`id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `fk_service_carId` FOREIGN KEY (`car`) REFERENCES `car` (`id`),
  ADD CONSTRAINT `fk_service_ownerId` FOREIGN KEY (`owner`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `fk_service_safetyCheckId` FOREIGN KEY (`safetyCheck`) REFERENCES `safetycheck` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
