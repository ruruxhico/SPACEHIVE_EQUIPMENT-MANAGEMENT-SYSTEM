-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2025 at 10:13 AM
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
-- Database: `spacehive`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment_assets`
--

CREATE TABLE `equipment_assets` (
  `property_tag` varchar(30) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `status` enum('Available','Borrowed','Maintenance','Unusable') DEFAULT 'Available',
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_assets`
--

INSERT INTO `equipment_assets` (`property_tag`, `type_id`, `status`, `remarks`) VALUES
('ITSO-LAP-001', 1, 'Available', ''),
('ITSO-LAP-002', 1, 'Available', ''),
('ITSO-LAP-003', 1, 'Available', ''),
('ITSO-LAP-004', 1, 'Available', ''),
('ITSO-LAP-005', 1, 'Available', ''),
('ITSO-LAP-006', 1, 'Unusable', '');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_types`
--

CREATE TABLE `equipment_types` (
  `type_id` int(11) NOT NULL,
  `type_code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `total_quantity` int(11) DEFAULT 0,
  `available_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_types`
--

INSERT INTO `equipment_types` (`type_id`, `type_code`, `name`, `description`, `total_quantity`, `available_quantity`, `image`) VALUES
(1, 'LAP', 'Laptop', 'Standard School Laptop', 6, 6, '1764344697_0a18220177467faf43bb.jpg'),
(2, 'DLP', 'DLP Projector', 'Standard Projector', 0, 0, 'default.png'),
(3, 'MKB', 'Mac Keyboard', 'Apple Magic Keyboard', 0, 0, 'default.png'),
(4, 'MMS', 'Mac Mouse', 'Apple Magic Mouse', 0, 0, 'default.png'),
(5, 'WCM', 'Wacom Tablet', 'Drawing Tablet for Design', 0, 0, 'default.png'),
(6, 'CBL', 'HDMI-VGA Cable', 'Video Adapter Cable', 0, 0, 'default.png'),
(7, 'SPK', 'Speaker Set', 'External Speakers', 0, 0, 'default.png'),
(8, 'CAM', 'Webcam', 'External USB Webcam', 0, 0, 'default.png'),
(9, 'CRP', 'Crimping Tool', 'Network Cable Tool', 0, 0, 'default.png'),
(10, 'TST', 'Cable Tester', 'LAN Cable Tester', 0, 0, 'default.png'),
(11, 'KEY', 'Lab Room Key', 'Physical Key for Labs', 0, 0, 'default.png');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `borrower_id` varchar(20) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `scheduled_date` date NOT NULL,
  `status` enum('Pending','Approved','Cancelled','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `borrower_id` varchar(20) NOT NULL,
  `item_tag` varchar(30) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `borrowed_at` datetime DEFAULT current_timestamp(),
  `expected_return` datetime NOT NULL,
  `returned_at` datetime DEFAULT NULL,
  `status` enum('Ongoing','Returned','Overdue') DEFAULT 'Ongoing',
  `issued_by` varchar(20) DEFAULT NULL,
  `received_by` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_accessories`
--

CREATE TABLE `type_accessories` (
  `accessory_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `accessory_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_accessories`
--

INSERT INTO `type_accessories` (`accessory_id`, `type_id`, `accessory_name`) VALUES
(9, 1, 'Laptop Charger'),
(10, 2, 'DLP Extension Cord'),
(11, 2, 'DLP VGA/HDMI Cable'),
(12, 2, 'DLP Power Cable'),
(13, 2, 'DLP Remote Control'),
(14, 3, 'Lightning Cable'),
(15, 4, 'Lightning Cable'),
(16, 5, 'Wacom Pen');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `school_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('ITSO','Associate','Student') NOT NULL,
  `associate_key` varchar(20) DEFAULT NULL,
  `student_number` int(9) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verifytoken` varchar(255) NOT NULL,
  `isverified` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`school_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `role`, `associate_key`, `student_number`, `status`, `created_at`, `verifytoken`, `isverified`) VALUES
('12345678', 'DANIELLE', '', 'MERCADO', 'dnmercado@fit.edu.ph', '$2y$10$b2alBg8GEbhe.t3k3gpSY.gvlewDQXoXF7/DCD9uVDeTMegizfBl2', 'ITSO', NULL, NULL, 'Active', '2025-11-29 00:06:17', '63d7192bbdf1de395f668a6c69dcd3d3', 0),
('202312294', 'SOPHIA', 'ISABELLE', 'PAYPON', 'sbpaypon@fit.edu.ph', '$2y$10$qxdMuTuUa2f5JdL213DGreK0usAbZu7DJ5LAwY1OZ1rId50eY7Jna', 'Student', NULL, NULL, 'Active', '2025-11-28 22:18:11', '88a1e32790bf66041b36f1d3865400c9', 0),
('87654321', 'BJEA ', 'MARELLE', 'DANTIC', 'badantic@fit.edu.ph', '$2y$10$EqZ9255C2jEXy/ucWmxiBuYXJzjyhRyfALsMTkKZbQq2sr2WOayb6', 'Associate', NULL, NULL, 'Active', '2025-11-29 00:20:04', '889780ee5d105615a3c584c4337a0a2c', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment_assets`
--
ALTER TABLE `equipment_assets`
  ADD PRIMARY KEY (`property_tag`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `equipment_types`
--
ALTER TABLE `equipment_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `item_tag` (`item_tag`),
  ADD KEY `issued_by` (`issued_by`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `type_accessories`
--
ALTER TABLE `type_accessories`
  ADD PRIMARY KEY (`accessory_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`school_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment_types`
--
ALTER TABLE `equipment_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_accessories`
--
ALTER TABLE `type_accessories`
  MODIFY `accessory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `equipment_assets`
--
ALTER TABLE `equipment_assets`
  ADD CONSTRAINT `equipment_assets_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `equipment_types` (`type_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`school_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `equipment_types` (`type_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`school_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`item_tag`) REFERENCES `equipment_assets` (`property_tag`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`issued_by`) REFERENCES `users` (`school_id`),
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`received_by`) REFERENCES `users` (`school_id`);

--
-- Constraints for table `type_accessories`
--
ALTER TABLE `type_accessories`
  ADD CONSTRAINT `type_accessories_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `equipment_types` (`type_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
