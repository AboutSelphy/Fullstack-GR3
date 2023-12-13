-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2023 at 09:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `animalshelter`
--
CREATE DATABASE IF NOT EXISTS `animalshelter` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `animalshelter`;

-- --------------------------------------------------------

--
-- Table structure for table `adoptions`
--

CREATE TABLE `adoptions` (
  `id` int(11) NOT NULL,
  `status` enum('Available','Adopted','Pending') NOT NULL DEFAULT 'Available',
  `date` date NOT NULL,
  `fk_user` int(11) NOT NULL,
  `fk_animal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `species` varchar(50) NOT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `fk_shelter` int(11) NOT NULL,
  `vaccination` enum('no','yes') DEFAULT 'no',
  `image` varchar(255) DEFAULT 'default.jpg',
  `status` enum('available','adopted','pending') DEFAULT 'available',
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `name`, `species`, `gender`, `age`, `fk_shelter`, `vaccination`, `image`, `status`, `description`) VALUES
(3, 'Bobby', 'Fox', 'Male', 3, 1, 'no', 'fox.jpg', 'available', 'Meet Bobby, a charming little fox with a heart full of curiosity and mischief.');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `loginID` int(11) NOT NULL,
  `userID` int(100) NOT NULL,
  `time` datetime DEFAULT current_timestamp(),
  `sessionID` varchar(75) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`loginID`, `userID`, `time`, `sessionID`) VALUES
(65, 1, '2023-12-12 09:36:28', 'p4fou28ec72uuv5rhp1ue7ah9s');

-- --------------------------------------------------------

--
-- Table structure for table `shelters`
--

CREATE TABLE `shelters` (
  `id` int(11) NOT NULL,
  `shelter_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `fk_zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelters`
--

INSERT INTO `shelters` (`id`, `shelter_name`, `capacity`, `description`, `fk_zip`) VALUES
(1, 'Waldviertler Wildlife', 30, 'Welcome to Waldviertler Wildlife, a haven nestled in the heart of Lower Austria, dedicated to the preservation and rehabilitation of native wildlife. Our center serves as a refuge for injured and orphaned animals, providing expert care and a chance for them to return to their natural habitats. Supported by passionate conservationists, Waldviertler Wildlife is not just a sanctuary; it\'s a commitment to safeguarding biodiversity. Join us in our mission to nurture, protect, and release these precious creatures back into the wild. Together, let\'s create a future where humans and wildlife thrive harmoniously.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('user','admin','shelter') NOT NULL DEFAULT 'user',
  `address` varchar(255) NOT NULL,
  `fk_zip` int(11) NOT NULL,
  `fk_shelter` int(11) DEFAULT NULL,
  `profile` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `status`, `address`, `fk_zip`, `fk_shelter`, `profile`) VALUES
(1, 'Svenja', 'Paws', 'svenja@paws.at', '123', 'admin', 'Paw Street 1', 1, 1, 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `zip`
--

CREATE TABLE `zip` (
  `id` int(11) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zip`
--

INSERT INTO `zip` (`id`, `zip`, `city`, `country`) VALUES
(1, 3912, 'Grafenschlag', 'Austria');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_animal` (`fk_animal`),
  ADD KEY `fk_user` (`fk_user`);

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animals_ibfk_1` (`fk_shelter`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`loginID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `shelters`
--
ALTER TABLE `shelters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zip` (`fk_zip`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_shelter` (`fk_shelter`),
  ADD KEY `fk_zip` (`fk_zip`);

--
-- Indexes for table `zip`
--
ALTER TABLE `zip`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoptions`
--
ALTER TABLE `adoptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `shelters`
--
ALTER TABLE `shelters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zip`
--
ALTER TABLE `zip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD CONSTRAINT `fk_animal` FOREIGN KEY (`fk_animal`) REFERENCES `animals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`fk_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `animals_ibfk_1` FOREIGN KEY (`fk_shelter`) REFERENCES `shelters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints for table `shelters`
--
ALTER TABLE `shelters`
  ADD CONSTRAINT `fk_zip` FOREIGN KEY (`fk_zip`) REFERENCES `zip` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_shelter` FOREIGN KEY (`fk_shelter`) REFERENCES `shelters` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`fk_zip`) REFERENCES `zip` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
