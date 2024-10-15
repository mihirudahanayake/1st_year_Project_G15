-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: Oct 15, 2024 at 01:44 AM
-- Server version: 10.11.9-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37114215_mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `booking_status` enum('confirmed','pending','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `start_date`, `end_date`, `booking_status`) VALUES
(6, 7, 12, '2024-08-13', '2024-08-14', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`) VALUES
(10, 'Beliatta'),
(11, 'matara'),
(12, 'a'),
(13, 'tan'),
(999, 'New City Name');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `destination_id` int(11) NOT NULL,
  `desti_name` varchar(255) NOT NULL,
  `desti_description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`destination_id`, `desti_name`, `desti_description`, `image_url`, `city`) VALUES
(3, 'be', 'er\r\nsdcjcgc\r\n\r\nere3e ', './uploads/4092564-about-mobile-ui-profile-ui-user-website_114033.png', 'Beliatta'),
(17, 'eee', 'eee', './uploads/search (2).png', 'tan'),
(18, 'sda', 'sd\r\n\r\nsd\r\n\r\nefdef fe\r\n', './uploads/search (1).png', 'tan'),
(19, 'we', 'ew\r\nwe\r\n ew  \r\n we\r\n\r\nwe', './uploads/search_locate_find_icon-icons.com_67287.png', 'tan'),
(29, 'we', 'we', './uploads/search.png', 'tan'),
(34, 'ryi', 'fyu', './uploads/search_9545869.png', 'Beliatta'),
(36, 'asd', 'ds', './uploads/search.png', 'Beliatta'),
(37, 'sdac', 'dsa', './uploads/search (1).png', 'Beliatta'),
(38, 'fs', 'ss\r\nsdf\r\nsd\r\n\r\nd', './uploads/search (2).png', 'matara');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(11) NOT NULL,
  `hotel_name` varchar(100) NOT NULL,
  `total_rooms` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(20) NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `hotel_name`, `total_rooms`, `user_id`, `description`, `location`, `city_id`) VALUES
(16, 'ww', 3, 7, 'ss', 'matara', 11),
(30, 'abab', 2, 14, 'ab', 'a', 12),
(31, 'New Hotel', 2, 11, 'hi', 'matara', 11);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_destinations`
--

CREATE TABLE `hotel_destinations` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_destinations`
--

INSERT INTO `hotel_destinations` (`id`, `hotel_id`, `destination_id`) VALUES
(4, 16, 3),
(5, 16, 38),
(6, 31, 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `hotel_id`, `room_id`, `message`, `is_read`, `created_at`) VALUES
(4, 16, 12, 'Room 12 has been booked from 2024-08-13 to 2024-08-14.', 0, '2024-08-12 20:19:09'),
(5, NULL, NULL, 'Room  has been booked from  to .', 0, '2024-08-26 07:26:39');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_number` int(255) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `max_adults` int(11) NOT NULL,
  `max_children` int(11) NOT NULL,
  `availability` varchar(255) NOT NULL,
  `hotel_name` varchar(255) DEFAULT NULL,
  `facilities` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_name`, `room_number`, `price_per_night`, `max_adults`, `max_children`, `availability`, `hotel_name`, `facilities`) VALUES
(12, 16, '2e', 5, '7.00', 2, 1, 'available', NULL, '0'),
(14, 31, 'nn', 7, '10.00', 2, 4, 'available', NULL, 'ac'),
(15, 31, 'we', 27, '20.00', 2, 2, 'available', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `image_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`image_id`, `room_id`, `image_path`) VALUES
(2, NULL, 'uploads/Screenshot 2024-08-13 152159.png'),
(3, NULL, 'uploads/Screenshot 2024-08-13 152159.png'),
(4, NULL, 'uploads/Screenshot 2024-08-13 152159.png'),
(5, NULL, 'uploads/Screenshot 2024-08-16 220034.png'),
(6, 14, 'uploads/r1iii.jpeg'),
(7, 14, 'uploads/r1.jpg'),
(28, 14, 'uploads/r1i.jpeg'),
(30, 14, 'uploads/r1ii.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` varchar(20) NOT NULL,
  `description` varchar(20) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `user_type`, `description`, `hotel_id`) VALUES
(7, 'er', 'erer@d', '$2y$10$ffv51s/rQSdKf3S3Q9IOVOsHIvbORo0Bho7qbL3zN4HnX3DrAlUSC', '2024-08-12 07:15:18', 'hotel_admin', '', 16),
(8, 'rr', 'rr@r', '$2y$10$aT6w38RUqaZMaIUCMI/me.JDpiJhjMs0zBf0XFptTHXAhNlimvAHy', '2024-08-12 07:22:13', 'user', '', 19),
(9, 'Mihiru', 'mihiru09@gmail.com', '$2y$10$zHLJ7gUmy/YtJw87uxzTB.tEQMAc0J8MXGpD0rtWPwTKHqiVn9EwS', '2024-08-12 08:06:15', 'admin', '', NULL),
(10, 'testuser', 'testuser@gmail.com', '$2y$10$W4EiVnim6hLPdTR6wE.Q2OLEK2ryF3c8uC1gmyb5A/UNiXcQGGA2e', '2024-08-13 07:26:15', 'user', '', NULL),
(11, 'testhadmin', 'testhadmin@gmail.com', '$2y$10$G8wtC7bMPxKIIlkb4NXLGOXSFhGoXJGw7Mu.aaw/alYOnh3s9JC0W', '2024-08-13 07:27:26', 'hotel_admin', '', 31),
(12, 'testadmin', 'testadmin@gmail.com', '$2y$10$MVdZIAk0vPDbTmgrsJFW/O1kX9sjgPB8.Brl6oiiiJM/TmxpOcjAy', '2024-08-13 07:28:12', 'admin', '', NULL),
(14, 'ab', 'ab@a', '$2y$10$NEIrqCP20erFUxuh8yQdD.TEQZzi5wUwxq3/BA05l7S4Sxs6s1jXC', '2024-08-13 08:35:19', 'hotel_admin', '', 30),
(15, 'Chamara', 'gshsjksgshzh@gmail.com', '$2y$10$GRAaWdL9YfVUUGnnkY9DNeNCQM0GctRqxUkR5lwgI5eRWoJl2Vfqe', '2024-08-17 08:10:31', 'user', '', NULL),
(16, 'thari', 'heraththaridu68@gmail.com', '$2y$10$mDLKVNrpAqYxK6nMrqxxkOBXP4eRoyD97A0Mbh94M9Mts/l4xFLpa', '2024-08-18 04:30:38', 'user', '', NULL),
(17, 'thanushi', 'thanushidhananjalee21@gmail.com', '$2y$10$nLhJU7zIZcDQp2bzxo7ScubjoWPTDEnp4qDdc09lB6wCn8L1SO3L.', '2024-08-21 10:03:57', 'user', '', NULL),
(18, 'Bhagya', 'bhagyaklhari9@gmail.com', '$2y$10$2OcjGiyNDERMcqcR6jHPteOIzNfQRyVEnhZFRfWrCTf/.4zeVHABm', '2024-10-15 04:29:40', 'user', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_name`),
  ADD UNIQUE KEY `city_name` (`city_name`),
  ADD UNIQUE KEY `city_id` (`city_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`destination_id`) USING BTREE,
  ADD KEY `fk_city` (`city`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`),
  ADD UNIQUE KEY `hotel_name` (`hotel_name`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `fk_location` (`location`),
  ADD KEY `fk_hotel_city_id` (`city_id`);

--
-- Indexes for table `hotel_destinations`
--
ALTER TABLE `hotel_destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `fk_hotel_name` (`hotel_name`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `destination_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hotel_destinations`
--
ALTER TABLE `hotel_destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`city`) REFERENCES `cities` (`city_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `fk_hotel_city_id` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`),
  ADD CONSTRAINT `fk_location` FOREIGN KEY (`location`) REFERENCES `cities` (`city_name`),
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `hotel_destinations`
--
ALTER TABLE `hotel_destinations`
  ADD CONSTRAINT `hotel_destinations_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_destinations_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`destination_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_hotel_name` FOREIGN KEY (`hotel_name`) REFERENCES `hotels` (`hotel_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
