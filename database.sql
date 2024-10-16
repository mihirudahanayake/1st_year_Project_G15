-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- දායකයා: 127.0.0.1
-- උත්පාදන වේලාව: ඔක්තෝම්බර් 16, 2024 දින 07:15 PM ට
-- සේවාදායකයේ අනුවාදය: 10.4.32-MariaDB
-- PHP අනුවාදය: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- දත්තගබඩාව: `database`
--

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `bookings`
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
-- වගු සඳහා නික්ෂේප දත්ත `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `start_date`, `end_date`, `booking_status`) VALUES
(6, 7, 12, '2024-08-13', '2024-08-14', 'confirmed'),
(7, 10, 14, '2024-09-28', '2024-09-29', 'confirmed');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`) VALUES
(10, 'Beliatta'),
(11, 'matara'),
(12, 'a'),
(13, 'tan'),
(999, 'New City Name'),
(1000, 'rjt');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `destinations`
--

CREATE TABLE `destinations` (
  `destination_id` int(11) NOT NULL,
  `desti_name` varchar(255) NOT NULL,
  `desti_description` text NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `destinations`
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
(38, 'fs', 'ss\r\nsdf\r\nsd\r\n\r\nd', './uploads/search (2).png', 'matara'),
(40, 'ff', 'gf', '', 'matara'),
(42, 'rjt', 'hi', '', 'rjt');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `destination_images`
--

CREATE TABLE `destination_images` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `destination_images`
--

INSERT INTO `destination_images` (`id`, `destination_id`, `image_url`) VALUES
(1, 40, './uploads/rata_anurata_240826.jpg'),
(2, 40, './uploads/OIP (5).jpeg'),
(4, 17, './uploads/search (2).png'),
(5, 18, './uploads/search (1).png'),
(6, 19, './uploads/search_locate_find_icon-icons.com_67287.png'),
(7, 42, './uploads/0e9e7802690c2c01050fc2c01317ee87.jpg'),
(8, 42, './uploads/3655706b92e2321348b29e2a3e473900.jpg');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `hotels`
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
-- වගු සඳහා නික්ෂේප දත්ත `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `hotel_name`, `total_rooms`, `user_id`, `description`, `location`, `city_id`) VALUES
(16, 'ww', 1, 7, 'ss', 'tan', 11),
(31, 'New Hotel', 5, 11, 'hi', 'matara', 11);

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `hotel_destinations`
--

CREATE TABLE `hotel_destinations` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `hotel_destinations`
--

INSERT INTO `hotel_destinations` (`id`, `hotel_id`, `destination_id`) VALUES
(4, 16, 3),
(5, 16, 38),
(6, 31, 18);

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `hotel_images`
--

CREATE TABLE `hotel_images` (
  `image_id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `hotel_images`
--

INSERT INTO `hotel_images` (`image_id`, `hotel_id`, `image_path`) VALUES
(7, 31, 'uploads/hotel_images/Horizon_Club_Ocean_View_Room-1.jpg');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `notifications`
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
-- වගු සඳහා නික්ෂේප දත්ත `notifications`
--

INSERT INTO `notifications` (`notification_id`, `hotel_id`, `room_id`, `message`, `is_read`, `created_at`) VALUES
(4, 16, 12, 'Room 12 has been booked from 2024-08-13 to 2024-08-14.', 0, '2024-08-12 20:19:09'),
(5, 31, 14, 'Room 14 has been booked from 2024-09-28 to 2024-09-29.', 0, '2024-09-28 09:44:01');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `rooms`
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
-- වගු සඳහා නික්ෂේප දත්ත `rooms`
--

INSERT INTO `rooms` (`room_id`, `hotel_id`, `room_name`, `room_number`, `price_per_night`, `max_adults`, `max_children`, `availability`, `hotel_name`, `facilities`) VALUES
(12, 16, '2e', 5, 7.00, 2, 1, 'available', NULL, '0'),
(14, 31, 'nn', 7, 10.00, 2, 4, 'available', NULL, 'ac'),
(15, 31, 'we', 27, 20.00, 2, 2, 'available', NULL, '0'),
(27, 31, '', 3, 3.00, 2, 1, 'Available', NULL, 'ac'),
(28, 31, '', 45, 4.00, 2, 1, 'Available', NULL, 'no'),
(29, 31, '', 4, 2.00, 1, 0, 'Available', NULL, '2');

--
-- ප්‍රේරක `rooms`
--
DELIMITER $$
CREATE TRIGGER `update_total_rooms_after_delete` AFTER DELETE ON `rooms` FOR EACH ROW BEGIN
    -- Update the total_rooms in hotels table
    UPDATE hotels
    SET total_rooms = total_rooms - 1
    WHERE hotel_id = OLD.hotel_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_rooms_after_insert` AFTER INSERT ON `rooms` FOR EACH ROW BEGIN
    -- Update the total_rooms in hotels table
    UPDATE hotels
    SET total_rooms = total_rooms + 1
    WHERE hotel_id = NEW.hotel_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_total_rooms_after_update` AFTER UPDATE ON `rooms` FOR EACH ROW BEGIN
    -- If the hotel_id is changed, update the total rooms for both hotels
    IF OLD.hotel_id != NEW.hotel_id THEN
        -- Decrement from the old hotel
        UPDATE hotels
        SET total_rooms = total_rooms - 1
        WHERE hotel_id = OLD.hotel_id;
        
        -- Increment for the new hotel
        UPDATE hotels
        SET total_rooms = total_rooms + 1
        WHERE hotel_id = NEW.hotel_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `room_images`
--

CREATE TABLE `room_images` (
  `image_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `room_images`
--

INSERT INTO `room_images` (`image_id`, `room_id`, `image_path`) VALUES
(2, NULL, 'uploads/Screenshot 2024-08-13 152159.png'),
(3, NULL, 'uploads/Screenshot 2024-08-13 152159.png'),
(5, NULL, 'uploads/Screenshot 2024-08-16 220034.png'),
(6, 14, 'uploads/r1iii.jpeg'),
(7, 14, 'uploads/r1.jpg'),
(28, 14, 'uploads/r1i.jpeg'),
(30, 14, 'uploads/r1ii.jpeg'),
(31, 27, 'room_27_66e514d86875f9.91549764.jpg'),
(33, 15, 'uploads/R (2).jpeg'),
(36, 15, 'uploads/room_27_image4.jpeg'),
(37, 15, 'uploads/room_27_image3.jpeg'),
(38, NULL, 'uploads/rooms/room__image1.jpg'),
(39, 29, 'uploads/rooms/room_4_image1.jpg');

-- --------------------------------------------------------

--
-- වගුවක් සඳහා වගු සැකිල්ල `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` varchar(20) NOT NULL,
  `description` varchar(20) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- වගු සඳහා නික්ෂේප දත්ත `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `user_type`, `description`, `hotel_id`, `name`, `telephone`, `profile_picture`) VALUES
(7, 'er', 'erer@d', '$2y$10$qPQFWjDYPAAOpKE.1.665ePJne03zdHARDK8MzOjmlVjjc2tXH1NO', '2024-08-12 07:15:18', 'hotel_admin', '', 16, NULL, NULL, NULL),
(8, 'rr', 'rr@r', '$2y$10$aT6w3', '2024-08-12 07:22:13', 'user', '', 19, NULL, NULL, NULL),
(9, 'Mihiru', 'mihiru09@gmail.com', '$2y$10$zHLJ7', '2024-08-12 08:06:15', 'admin', '', NULL, NULL, NULL, NULL),
(10, 'testuser', 'testuser@gmail.com', '$2y$10$b5JXTAYnKnVOMJN6rstbt.oKb.vqtks4eS1fl38g71Ml5aK68kdcC', '2024-08-13 07:26:15', 'user', '', NULL, 'user', '0701234567', '1.png'),
(11, 'testhadmin', 'testhadmin@gmail.com', '$2y$10$azimQGMjUJrViRdmD/3z0ur8Hp5X0BZKy9hvLfhVZCPCVIIe6U0N2', '2024-08-13 07:27:26', 'hotel_admin', '', 31, NULL, NULL, NULL),
(12, 'testadmin', 'testadmin@gmail.com', '$2y$10$DByNtJngGCUTNKEV9itPaOQaPeMSbzpyFKVZHEbz.98CiDo9PP0R.', '2024-08-13 07:28:12', 'admin', '', NULL, NULL, NULL, NULL),
(14, 'ab', 'ab@a', '$2y$10$NEIrq', '2024-08-13 08:35:19', 'hotel_admin', '', 30, NULL, NULL, NULL),
(15, 'hadmin', 'ff@g', '$2y$10$x77Ls', '2024-10-16 08:15:24', 'user', '', NULL, NULL, NULL, NULL),
(16, 'mm', 'mm@m', '$2y$10$dq.Yh', '2024-10-16 08:16:50', 'user', '', NULL, NULL, NULL, NULL),
(17, 'tt', 'tt@t', '$2y$10$qPQFWjDYPAAOpKE.1.665ePJne03zdHARDK8MzOjmlVjjc2tXH1NO', '2024-10-16 08:19:02', 'user', '', NULL, NULL, NULL, NULL);

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
-- Indexes for table `destination_images`
--
ALTER TABLE `destination_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

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
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `hotel_id` (`hotel_id`);

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
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `destination_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `destination_images`
--
ALTER TABLE `destination_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- නික්ෂේපනය කරන ලද වගු සඳහා සීමා බාධක
--

--
-- වගුව සඳහා සීමා බාධක `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `fk_city` FOREIGN KEY (`city`) REFERENCES `cities` (`city_name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `destination_images`
--
ALTER TABLE `destination_images`
  ADD CONSTRAINT `destination_images_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`destination_id`) ON DELETE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `fk_hotel_city_id` FOREIGN KEY (`city_id`) REFERENCES `cities` (`city_id`),
  ADD CONSTRAINT `fk_location` FOREIGN KEY (`location`) REFERENCES `cities` (`city_name`),
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- වගුව සඳහා සීමා බාධක `hotel_destinations`
--
ALTER TABLE `hotel_destinations`
  ADD CONSTRAINT `hotel_destinations_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_destinations_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`destination_id`) ON DELETE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- වගුව සඳහා සීමා බාධක `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_hotel_name` FOREIGN KEY (`hotel_name`) REFERENCES `hotels` (`hotel_name`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`hotel_id`) ON DELETE CASCADE;

--
-- වගුව සඳහා සීමා බාධක `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
