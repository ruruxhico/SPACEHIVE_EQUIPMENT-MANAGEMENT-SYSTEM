-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 04:03 PM
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
  `available_quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `school_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('ITSO','Associate','Student') NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `accessory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
