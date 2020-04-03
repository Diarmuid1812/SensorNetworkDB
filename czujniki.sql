-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Kwi 2020, 01:19
-- Wersja serwera: 5.7.17
-- Wersja PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `czujniki`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `czujnik`
--

CREATE TABLE `czujnik` (
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
  `data` int(11) NOT NULL,
  `wilgotnosc` int(11) NOT NULL,
  `temperatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `raport`
--

CREATE TABLE `raport` (
  `id` int(11) NOT NULL,
  `nr_czujnika` int(11) NOT NULL,
  `miejsce` text COLLATE utf8_polish_ci NOT NULL,
  `data` datetime NOT NULL,
  `wilgotnosc` int(11) NOT NULL,
  `temperatura` int(11) NOT NULL
) ENGINE=CSV DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `czujnik`
--
ALTER TABLE `czujnik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pomiar`
--
ALTER TABLE `pomiar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nr_czujnika` (`nr_czujnika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `czujnik`
--
ALTER TABLE `czujnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `pomiar`
--
ALTER TABLE `pomiar`
  ADD CONSTRAINT `pomiar_ibfk_1` FOREIGN KEY (`nr_czujnika`) REFERENCES `czujnik` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
