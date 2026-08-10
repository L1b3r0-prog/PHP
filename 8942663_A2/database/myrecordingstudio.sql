-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myrecordingstudio`
--
CREATE DATABASE IF NOT EXISTS `myrecordingstudio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `myrecordingstudio`;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `duration_hours` int(11) NOT NULL CHECK (`duration_hours` between 1 and 12),
  `end_time` time NOT NULL,
  `total_cost` decimal(8,2) NOT NULL,
  `status` enum('active','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `studio_id`, `client_id`, `booking_date`, `start_time`, `duration_hours`, `end_time`, `total_cost`, `status`, `created_at`) VALUES
(1, 4, 2, '2026-08-03', '10:00:00', 1, '11:00:00', 40.00, 'active', '2026-08-03 01:40:44'),
(2, 4, 2, '2026-08-03', '11:00:00', 1, '12:00:00', 40.00, 'active', '2026-08-03 01:40:55'),
(3, 5, 2, '2026-08-03', '10:00:00', 1, '11:00:00', 40.00, 'active', '2026-08-03 02:10:53'),
(4, 4, 2, '2026-08-03', '12:00:00', 1, '13:00:00', 40.00, 'active', '2026-08-03 02:23:23'),
(5, 4, 2, '2026-08-06', '10:00:00', 1, '11:00:00', 40.00, 'active', '2026-08-06 06:42:18'),
(6, 4, 2, '2026-08-06', '15:00:00', 1, '16:00:00', 40.00, 'active', '2026-08-06 06:42:48'),
(7, 4, 2, '2026-08-06', '18:00:00', 1, '19:00:00', 40.00, 'cancelled', '2026-08-06 07:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `num_studios` int(11) NOT NULL CHECK (`num_studios` > 0),
  `cost_per_hour` decimal(8,2) NOT NULL CHECK (`cost_per_hour` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `description`, `num_studios`, `cost_per_hour`) VALUES
(1, 'Bedok Studio', 4, 50.00),
(2, 'Clementi Records', 2, 40.00),
(3, 'Punggol Vids', 4, 35.00);

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `studio_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `studio_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`studio_id`, `location_id`, `studio_number`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 2),
(6, 3, 1),
(7, 3, 2),
(8, 3, 3),
(9, 3, 4),
(10, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('admin','client') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `phone`, `email`, `password`, `type`, `created_at`) VALUES
(1, 'System Admin', 61234567, 'admin@myrecordingstudio.com', '$2b$10$9NYTE7mJJ2gROox2cLHguuU4/piFA1Hn9AP4iyq8pkc22OL6Dd3GG', 'admin', '2026-08-03 01:39:15'),
(2, 'Benjamin', 12345678, 'email@gmail.com', '$2y$10$ccT2KwRDMtp1XYDKQlYJFOhbmEaq8PesQzqS3OBso.OoUtYHNDxlu', 'client', '2026-08-03 01:40:20'),
(3, 'Jack', 81234567, 'testing@hotmail.com', '$2y$10$bVs5HVGisfZxYu1y79zzSe8JuMnJTTWgu/i0.MFiWV0ZSxrAN9hJm', 'client', '2026-08-08 00:49:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `studio_id` (`studio_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`studio_id`),
  ADD UNIQUE KEY `location_id` (`location_id`,`studio_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `studio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `studios`
--
ALTER TABLE `studios`
  ADD CONSTRAINT `studios_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
