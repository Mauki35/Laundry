-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2024 at 11:02 AM
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
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pick_up_date` date NOT NULL,
  `pick_up_time` time NOT NULL,
  `pick_up_address` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `mode_of_payment` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `services` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `pick_up_date`, `pick_up_time`, `pick_up_address`, `mobile_number`, `mode_of_payment`, `total_amount`, `services`, `created_at`) VALUES
(47, 1, '2024-08-10', '23:29:00', 'vvv', '99', 'MPESA', 11000.00, 'Service 1: exclusive\\n  Exclusive Item 1: Type - carpets, Size - small, Number of Items - 11\\n', '2024-07-30 22:24:31'),
(52, 2, '2024-08-10', '01:08:00', 'Maisha Hostels , Bomas', '0743273475', 'MPESA', 1250.00, 'Service 1: premium\\n  Amount of Laundry: 10 kgs\\n', '2024-07-31 00:04:22'),
(57, 2, '2024-08-02', '09:36:00', 'Aeiou1', '254717794150', 'MPESA', 750.00, 'Service 1: premium\\n  Amount of Laundry: 1 kgs\\n', '2024-07-31 08:31:53'),
(58, 2, '2024-08-09', '10:10:00', 'Bomas', '254110512784', 'MPESA', 1100.00, 'Service 1: luxury\\n  Amount of Laundry: 6 kgs\\n', '2024-07-31 09:04:42'),
(59, 2, '2024-08-09', '10:18:00', 'Aeious', '90909090', 'MPESA', 1100.00, 'Service 1: luxury\\n  Amount of Laundry: 6 kgs\\n', '2024-07-31 09:14:31'),
(60, 2, '2024-08-09', '11:16:00', 'Kingongo', '254743273475', 'MPESA', 750.00, 'Service 1: premium\\n  Amount of Laundry: 5 kgs\\n', '2024-07-31 10:10:53'),
(61, 2, '2024-07-27', '00:04:00', 'Maisha Hostels, Bomas', '254743273475', 'MPESA', 1250.00, 'Service 1: premium\\n  Amount of Laundry: 6 kgs\\n', '2024-07-31 11:47:54'),
(62, 2, '2024-08-10', '18:57:00', 'Outspan', '254702620764', 'MPESA', 1250.00, 'Service 1: premium\\n  Amount of Laundry: 10 kgs\\n', '2024-07-31 12:36:07'),
(63, 3, '2024-10-05', '13:48:00', 'Umoja, Asian Quarters', '254110512784', 'MPESA', 2250.00, 'Service 1: premium\\n  Amount of Laundry: 17 kgs\\n', '2024-07-31 12:43:43'),
(64, 3, '2024-08-05', '01:18:00', 'Umoja, Asian Quarters', '254721864813', 'MPESA', 1250.00, 'Service 1: premium\\n  Amount of Laundry: 6 kgs\\n', '2024-07-31 15:16:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
