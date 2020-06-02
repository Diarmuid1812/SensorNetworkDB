-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Cze 2020, 12:17
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `sensornetwork`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `czujniki`
--

CREATE TABLE `czujniki` (
  `id` int(11) NOT NULL,
  `programowy_nr` int(11) NOT NULL,
  `bateria` int(11) NOT NULL,
  `miejsce` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pomiar`
--

CREATE TABLE `pomiar` (
  `id` int(11) NOT NULL,
  `nr_czujnika` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `wilgotnosc` float NOT NULL,
  `temperatura` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `passw_changed` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `admin`, `passw_changed`, `created_at`) VALUES
(10, 'test', '---', '$2y$10$5HJSetkQDRrn8QMUOTrwgO8Gl58XBN7olR322eYxGhDDXimP85Pa.', 1, 1, '2020-05-27 22:22:57');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `czujniki`
--
ALTER TABLE `czujniki`
  ADD PRIMARY KEY (`id`,`programowy_nr`) USING BTREE,
  ADD UNIQUE KEY `programowy_nr` (`programowy_nr`);

--
-- Indeksy dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nr_czujnika` (`nr_czujnika`) USING BTREE;

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `czujniki`
--
ALTER TABLE `czujniki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  ADD CONSTRAINT `pomiar_ibfk_1` FOREIGN KEY (`nr_czujnika`) REFERENCES `czujniki` (`programowy_nr`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
