-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 22 2016 г., 21:50
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `Images`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Attachment`
--

CREATE TABLE IF NOT EXISTS `Attachment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `IdDocument` int(11) NOT NULL,
  `Position` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `IdDocument` (`IdDocument`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=217 ;

--
-- Дамп данных таблицы `Attachment`
--

INSERT INTO `Attachment` (`Id`, `name`, `size`, `thumbnail`, `IdDocument`, `Position`) VALUES
(202, 'Chrysanthemum', 879394, '146921323057926a2edc084.jpg', 72, 0),
(203, 'Desert', 845941, '146921323057926a2edc854.jpg', 72, 2),
(204, 'Lighthouse', 561276, '146921323057926a2edd024.jpg', 72, 3),
(205, 'Penguins', 777835, '146921323057926a2edd40c.jpg', 72, 1),
(206, 'Penguins', 777835, '146921323057926a2eddbdc.jpg', 72, 4),
(207, 'Tulips', 620888, '146921323057926a2ede3ac.jpg', 72, 5),
(209, 'Jellyfish', 775702, '146921325757926a4990b8b.jpg', 73, 0),
(212, 'Tulips', 620888, '146921329257926a6cd6cb5.jpg', 73, 1),
(215, 'Chrysanthemum', 879394, '146921331457926a82de6d1.jpg', 73, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `Document`
--

CREATE TABLE IF NOT EXISTS `Document` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Дамп данных таблицы `Document`
--

INSERT INTO `Document` (`Id`, `Name`, `Description`) VALUES
(72, 'Документ', 'Описание документа'),
(73, 'Еще один документ!!!', 'Еще одно описание');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Attachment`
--
ALTER TABLE `Attachment`
  ADD CONSTRAINT `attachment_ibfk_1` FOREIGN KEY (`IdDocument`) REFERENCES `Document` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
