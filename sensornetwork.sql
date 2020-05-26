-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Maj 2020, 15:31
-- Wersja serwera: 5.7.17
-- Wersja PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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

--
-- Zrzut danych tabeli `czujniki`
--

INSERT INTO `czujniki` (`id`, `programowy_nr`, `bateria`, `miejsce`) VALUES
(1, 3, 50, 'piwnica'),
(2, 6, 20, 'strych'),
(3, 4, 70, 'klatka'),
(4, 1, 40, 'spizarka');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pomiar`
--

CREATE TABLE `pomiar` (
  `id` int(11) NOT NULL,
  `nr_czujnika` int(11) NOT NULL,
  `data` date NOT NULL,
  `wilgotnosc` float NOT NULL,
  `temperatura` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `pomiar`
--

INSERT INTO `pomiar` (`id`, `nr_czujnika`, `data`, `wilgotnosc`, `temperatura`) VALUES
(1, 1, '2020-05-12', 2, 23),
(2, 2, '2020-05-14', 3, 25),
(3, 3, '2020-05-16', 8, 27),
(4, 4, '2020-05-17', 10, 28);

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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `czujniki`
--
ALTER TABLE `czujniki`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pomiar`
--
ALTER TABLE `pomiar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nr_czujnika` (`nr_czujnika`);

--
-- Indexes for table `users`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  ADD CONSTRAINT `pomiar_ibfk_1` FOREIGN KEY (`nr_czujnika`) REFERENCES `czujniki` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
