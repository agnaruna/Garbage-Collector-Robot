-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2019 at 01:48 AM
-- Server version: 10.3.13-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id9021035_gacorobot`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`id9021035_root`@`%` PROCEDURE `updatestat` (IN `rid` INT, OUT `path` CHAR(1))  BEGIN

DECLARE rowid,roid int;

UPDATE tasks_TB SET RB_status='complete' WHERE RB_status='pending' AND robotID=rid;

SELECT roundid,robotID,path_name INTO rowid,roid,path FROM tasks_TB WHERE RB_status='pending' AND TIMESTAMPDIFF(HOUR,Date_Time,NOW())>=1 LIMIT 1;


 UPDATE robotTB SET stat='broken' WHERE robotid=roid;
 UPDATE tasks_TB set robotID=rid WHERE roundid=rowid;


END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rclogin`
--

CREATE TABLE `rclogin` (
  `user` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rclogin`
--

INSERT INTO `rclogin` (`user`, `password`) VALUES
('janith@net.com', '123'),
('samitha@gmail.com', 'samitha123');

-- --------------------------------------------------------

--
-- Table structure for table `robotTB`
--

CREATE TABLE `robotTB` (
  `robotid` int(3) NOT NULL,
  `stat` varchar(10) NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `robotTB`
--

INSERT INTO `robotTB` (`robotid`, `stat`) VALUES
(10, 'available'),
(15, 'broken'),
(20, 'available'),
(25, 'available'),
(30, 'available'),
(35, 'available'),
(40, 'available'),
(550, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_TB`
--

CREATE TABLE `tasks_TB` (
  `roundid` int(11) NOT NULL,
  `path_name` char(1) NOT NULL,
  `Date_Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `RB_status` varchar(8) DEFAULT 'not yet',
  `robotID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tasks_TB`
--

INSERT INTO `tasks_TB` (`roundid`, `path_name`, `Date_Time`, `RB_status`, `robotID`) VALUES
(127, 'A', '2019-03-23 09:16:03', 'complete', 25),
(128, 'A', '2019-03-23 09:18:09', 'complete', 25),
(129, 'A', '2019-03-23 15:32:48', 'complete', 25),
(130, 'A', '2019-03-23 15:32:48', 'complete', 25),
(131, 'A', '2019-03-23 15:32:48', 'complete', 25),
(132, 'A', '2019-03-23 16:01:10', 'complete', 25),
(133, 'A', '2019-03-23 16:31:41', 'complete', 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rclogin`
--
ALTER TABLE `rclogin`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `robotTB`
--
ALTER TABLE `robotTB`
  ADD PRIMARY KEY (`robotid`);

--
-- Indexes for table `tasks_TB`
--
ALTER TABLE `tasks_TB`
  ADD PRIMARY KEY (`roundid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks_TB`
--
ALTER TABLE `tasks_TB`
  MODIFY `roundid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
