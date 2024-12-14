-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 14, 2024 at 11:46 AM
-- Server version: 10.6.19-MariaDB-cll-lve
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ezrdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `phonenum` varchar(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `userpass` varchar(255) DEFAULT NULL,
  `verifytoken` varchar(32) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `phonenum`, `email`, `userpass`, `verifytoken`, `verified`) VALUES
(25, 'Jourzie', 'Villanueva', '09493074231', 'jourzie.villanueva-21@cpu.edu.ph', '$2y$10$X.eaVPTPuh/DsJ2WKDF0.uUPr78FLA91dIicqQqAq9VyUcXnctvtS', 'b970047a0479a5d285b40a308411041c', 1),
(28, 'Jybeth Jhan', 'Ombina', '09271249790', 'jybethjhan.ombina-21@cpu.edu.ph', '$2y$10$wteNJrfCre7cwLSeuMKYROJyAJYiujfSkcbi1drkfFtnb89sGskRW', 'e70917176bc0818e449f46f323164135', 0),
(29, 'Karl', 'Villanueva', '09469899177', 'villanuevakarl727@gmail.com', '$2y$10$hLJFhtDbm/SUVH6O1rLm8OWFqSiX0FSfeNHYtkYlHm6A1f0lkaGS2', '9a6fe8c7ca089a523e5175ce26e27263', 0),
(30, 'test', 'test', '123123421', 'test123@gmail.com', '$2y$10$EnsnzNxheFvjKWoHtRIejehbj5V9Bn00.SEGro3eFnWxVgCXBIuBq', '629688d4b0f596be03cf1ad5d4dcc9d3', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
