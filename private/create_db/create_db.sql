-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: 10.209.1.120
-- Generation Time: Apr 11, 2017 at 10:10 AM
-- Server version: 5.5.42
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `193351-cv`
--

-- --------------------------------------------------------

--
-- Table structure for table `SKILLS`
--

CREATE TABLE IF NOT EXISTS `SKILLS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

CREATE TABLE IF NOT EXISTS `USERS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `linkedin` varchar(90) NOT NULL,
  `role` varchar(10) NOT NULL,
  `recover_password` varchar(50) NOT NULL,
  `video` longblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `USERS_SKILLS`
--

CREATE TABLE IF NOT EXISTS `USERS_SKILLS` (
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `skill_level` int(1) NOT NULL,
  PRIMARY KEY (`user_id`,`skill_id`),
  KEY `USER_ID` (`user_id`),
  KEY `SKILL_ID_FK` (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `USERS_SKILLS`
--
ALTER TABLE `USERS_SKILLS`
  ADD CONSTRAINT `SKILL_ID_FK` FOREIGN KEY (`skill_id`) REFERENCES `SKILLS` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `USER_ID_FK` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
