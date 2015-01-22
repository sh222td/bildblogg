-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 19 jan 2015 kl 01:18
-- Serverversion: 5.6.15-log
-- PHP-version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `bildblogg`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `fileID` int(11) NOT NULL,
  `comment` varchar(300) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`commentID`),
  KEY `filename` (`fileID`),
  KEY `fileID` (`fileID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=35 ;

--
-- Dumpning av Data i tabell `comments`
--

INSERT INTO `comments` (`commentID`, `fileID`, `comment`) VALUES
(1, 80, 'En tÃ¤mligen eminent artist!'),
(2, 80, 'Han har mer smink pÃ¥ sig Ã¤n Cher. Lol.'),
(4, 72, 'Det Ã¤r ankorna som kommer styra allt i framtiden... BEWARE.'),
(25, 72, 'Ã…h vilka coola ankor!'),
(26, 64, 'Kitty katt'),
(34, 113, 'StudentfÃ¶reningen Prima IngenjÃ¶rer I Kalmar.');

-- --------------------------------------------------------

--
-- Tabellstruktur `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `fileID` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) COLLATE utf8_bin NOT NULL,
  `filepath` varchar(200) COLLATE utf8_bin NOT NULL,
  `description` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`fileID`),
  KEY `filename` (`filename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=116 ;

--
-- Dumpning av Data i tabell `file`
--

INSERT INTO `file` (`fileID`, `filename`, `filepath`, `description`, `category`) VALUES
(63, 'supersuaveWP.jpg', 'http://sh222td.nu/images/supersuaveWP.jpg', 'SkÃ¤mtnivÃ¥n var pÃ¥ hÃ¶g nivÃ¥ under vÃ¥rat fÃ¶rsta Ã¥r i WP! \r\n\r\nGoda tider!', 1),
(64, 'b9d6z.jpg', 'http://sh222td.nu/images/b9d6z.jpg', 'Grumpy cat looking over his hills of grumpyness!', 1),
(72, 'awyiss.png', 'http://sh222td.nu/images/awyiss.png', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAW yiss!', 2),
(80, 'ALICE_COOPER_MARIA_JOHANSSON_004.jpg', 'http://sh222td.nu/images/ALICE_COOPER_MARIA_JOHANSSON_004.jpg', 'Sweden Rock 2014', 4),
(86, 'cat.jpg', 'http://sh222td.nu/images/cat.jpg', 'This is a cat.', 2),
(98, 'AwmqGq8.jpg', 'http://sh222td.nu/images/AwmqGq8.jpg', 'Skyrim Schyrim', 1),
(111, 'cookieclicker.jpg', 'http://sh222td.nu/images/cookieclicker.jpg', 'THY SHALL ALL PERSISH!', 4),
(112, 'qPiI6.jpg', 'http://sh222td.nu/images/qPiI6.jpg', 'McGonnagal.', 4),
(113, 'SPIIK-logga-v2.png', 'http://sh222td.nu/images/SPIIK-logga-v2.png', 'BÃ¤sta studentfÃ¶reningen', 4),
(115, 'bach.jpg', 'http://sh222td.nu/images/bach.jpg', 'Kycklingen pÃ¥ gÃ¥rden sÃ¤ger...', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `password` varchar(20) COLLATE utf8_bin NOT NULL,
  `usertype` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `usertype`) VALUES
(1, 'Admin', 'Password', 1);

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`fileID`) REFERENCES `file` (`fileID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
