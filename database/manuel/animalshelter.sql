-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Dez 2023 um 14:54
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `animalshelter`
--
CREATE DATABASE IF NOT EXISTS `animalshelter` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `animalshelter`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `adoptions`
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
-- Tabellenstruktur für Tabelle `animals`
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
-- Daten für Tabelle `animals`
--

INSERT INTO `animals` (`id`, `name`, `species`, `gender`, `age`, `fk_shelter`, `vaccination`, `image`, `status`, `description`) VALUES
(3, 'Bobby', 'Fox', 'Male', 3, 1, 'no', 'fox.jpg', 'available', 'Meet Bobby, a charming little fox with a heart full of curiosity and mischief.');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `login`
--

CREATE TABLE `login` (
  `loginID` int(11) NOT NULL,
  `userID` int(100) NOT NULL,
  `time` datetime DEFAULT current_timestamp(),
  `sessionID` varchar(75) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shelters`
--

CREATE TABLE `shelters` (
  `id` int(11) NOT NULL,
  `shelter_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `description` varchar(1000) DEFAULT NULL,
  `fk_zip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `shelters`
--

INSERT INTO `shelters` (`id`, `shelter_name`, `capacity`, `image`, `description`, `fk_zip`) VALUES
(1, 'Waldviertler Wildlife', 30, 'default.jpg', 'Welcome to Waldviertler Wildlife, a haven nestled in the heart of Lower Austria, dedicated to the preservation and rehabilitation of native wildlife. Our center serves as a refuge for injured and orphaned animals, providing expert care and a chance for them to return to their natural habitats. Supported by passionate conservationists, Waldviertler Wildlife is not just a sanctuary; it\'s a commitment to safeguarding biodiversity. Join us in our mission to nurture, protect, and release these precious creatures back into the wild. Together, let\'s create a future where humans and wildlife thrive harmoniously.', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
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
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `status`, `address`, `fk_zip`, `fk_shelter`, `profile`) VALUES
(1, 'Svenja', 'Paws', 'svenja@paws.at', '5a6d7484275344e47ce0f5e9b2d458c176dc396c8842518ad782ef824631c6bb', 'user', 'Paw Street 1', 1, 1, 'default.jpg'),
(11, 'svenja', 'mad queen', 'mad@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'user', 'mad housing', 1, 1, 'default.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zip`
--

CREATE TABLE `zip` (
  `id` int(11) NOT NULL,
  `zip` int(11) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `zip`
--

INSERT INTO `zip` (`id`, `zip`, `city`, `country`) VALUES
(1, 3912, 'Grafenschlag', 'Austria');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `adoptions`
--
ALTER TABLE `adoptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_animal` (`fk_animal`),
  ADD KEY `fk_user` (`fk_user`);

--
-- Indizes für die Tabelle `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animals_ibfk_1` (`fk_shelter`);

--
-- Indizes für die Tabelle `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`loginID`),
  ADD KEY `userID` (`userID`);

--
-- Indizes für die Tabelle `shelters`
--
ALTER TABLE `shelters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_zip` (`fk_zip`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_shelter` (`fk_shelter`),
  ADD KEY `fk_zip` (`fk_zip`);

--
-- Indizes für die Tabelle `zip`
--
ALTER TABLE `zip`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `adoptions`
--
ALTER TABLE `adoptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `login`
--
ALTER TABLE `login`
  MODIFY `loginID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT für Tabelle `shelters`
--
ALTER TABLE `shelters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT für Tabelle `zip`
--
ALTER TABLE `zip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `adoptions`
--
ALTER TABLE `adoptions`
  ADD CONSTRAINT `fk_animal` FOREIGN KEY (`fk_animal`) REFERENCES `animals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`fk_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `animals_ibfk_1` FOREIGN KEY (`fk_shelter`) REFERENCES `shelters` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_new_constraint` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `shelters`
--
ALTER TABLE `shelters`
  ADD CONSTRAINT `fk_zip` FOREIGN KEY (`fk_zip`) REFERENCES `zip` (`id`);

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_shelter` FOREIGN KEY (`fk_shelter`) REFERENCES `shelters` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`fk_zip`) REFERENCES `zip` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
