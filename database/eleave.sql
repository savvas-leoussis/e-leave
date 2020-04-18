-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Apr 18, 2020 at 01:11 PM
-- Server version: 8.0.19
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eleave`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `vacation_start` date NOT NULL,
  `vacation_end` date NOT NULL,
  `reason` text,
  `date_submitted` date NOT NULL,
  `days_requested` int NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `employee_id`, `vacation_start`, `vacation_end`, `reason`, `date_submitted`, `days_requested`, `status`) VALUES
(1, 3, '2020-04-12', '2020-04-15', 'This is a reason.', '2020-04-12', 3, 'accepted'),
(2, 3, '2020-04-16', '2020-04-17', 'This is a reason.', '2020-04-09', 2, 'accepted'),
(3, 3, '2020-04-16', '2020-04-17', 'This is a reason.', '2020-04-01', 2, 'accepted'),
(10, 3, '2020-01-01', '2020-02-02', 'test', '2020-04-12', 21, 'pending'),
(12, 3, '2020-02-02', '2020-03-03', 'resr', '2020-04-15', 22, 'pending'),
(22, 3, '2020-02-01', '2022-02-02', 'ewr', '2020-04-15', 511, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('employee','supervisor') NOT NULL,
  `password` varchar(255) NOT NULL,
  `supervisor_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `type`, `password`, `supervisor_id`) VALUES
(1, 'Jason', 'Watkins', 'admin@company.com', 'supervisor', '$2y$10$kj2e2.p3T7zPUVQpTLqVKezNlE04A4.nKO4YJsOeSDEdAZxdjyYFu', 1),
(3, 'John', 'Doe', 'employee@company.com', 'employee', '$2y$10$Jx9OKtdlKDh2v39F7IjhYe5.2tjMWYhzofQ58NUkzkyDeyYsRdOLe', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
