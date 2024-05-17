-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Apr 25, 2024 at 07:41 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `AuthorID` int(8) NOT NULL,
  `BookID` int(8) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`AuthorID`, `BookID`, `Name`) VALUES
(13, 10, 'Aaron Maxwell'),
(15, 12, 'Aaron Maxwell'),
(16, 13, 'Aaron Maxwell'),
(17, 14, 'Dan Bader'),
(19, 16, 'Dan Bader'),
(20, 17, 'Dan Bader'),
(21, 18, 'Cory Althoff'),
(22, 19, 'Cory Althoff'),
(23, 20, 'Cory Althoff'),
(24, 21, 'Cory Althoff'),
(25, 22, 'Mr. Kevin P Hare'),
(26, 22, 'Pindar Van Arman'),
(27, 23, 'Mr. Kevin P Hare'),
(28, 23, 'Pindar Van Arman'),
(29, 24, 'Mr. Kevin P Hare'),
(30, 24, 'Pindar Van Arman'),
(31, 25, 'Mr. Kevin P Hare'),
(32, 25, 'Pindar Van Arman'),
(33, 26, 'Charles Petzold'),
(34, 27, 'Charles Petzold'),
(35, 28, 'Charles Petzold'),
(36, 29, 'Charles Petzold'),
(37, 30, 'Dean Wampler'),
(38, 30, 'Robert C. Marthin'),
(39, 31, 'Dean Wampler'),
(40, 31, 'Robert C. Marthin'),
(41, 32, 'Dean Wampler'),
(42, 32, 'Robert C. Marthin'),
(43, 33, 'Dean Wampler'),
(44, 33, 'Robert C. Marthin'),
(45, 34, 'S. Christian Albright'),
(46, 34, 'Wayne L. Winston'),
(47, 35, 'S. Christian Albright'),
(48, 35, 'Wayne L. Winston'),
(49, 36, 'S. Christian Albright'),
(50, 36, 'Wayne L. Winston'),
(51, 37, 'S. Christian Albright'),
(52, 37, 'Wayne L. Winston'),
(53, 38, 'Foster Provost'),
(54, 38, 'Tom Fawcett'),
(55, 39, 'Foster Provost'),
(56, 39, 'Tom Fawcett'),
(57, 40, 'Foster Provost'),
(58, 40, 'Tom Fawcett'),
(59, 41, 'Foster Provost'),
(60, 41, 'Tom Fawcett'),
(61, 42, 'Harvard Business Review'),
(62, 43, 'Harvard Business Review'),
(63, 44, 'Harvard Business Review'),
(64, 45, 'Harvard Business Review'),
(65, 46, 'Karen Berman'),
(66, 46, 'Joe Knight'),
(67, 46, 'John Case'),
(68, 47, 'Karen Berman'),
(69, 47, 'Joe Knight'),
(70, 47, 'John Case'),
(71, 48, 'Karen Berman'),
(72, 48, 'Joe Knight'),
(73, 48, 'John Case'),
(74, 49, 'Karen Berman'),
(75, 49, 'Joe Knight'),
(76, 49, 'John Case'),
(77, 50, 'Donella H. Meadows'),
(78, 50, 'Diana Wright'),
(79, 51, 'Donella H. Meadows'),
(80, 51, 'Diana Wright'),
(81, 52, 'Donella H. Meadows'),
(82, 52, 'Diana Wright'),
(83, 53, 'Donella H. Meadows'),
(84, 53, 'Diana Wright'),
(85, 54, 'K. F. Riley'),
(86, 54, 'M. P. Hobson'),
(87, 54, 'S. J. Bence'),
(88, 55, 'K. F. Riley'),
(89, 55, 'M. P. Hobson'),
(90, 55, 'S. J. Bence'),
(91, 56, 'K. F. Riley'),
(92, 56, 'M. P. Hobson'),
(93, 56, 'S. J. Bence'),
(94, 57, 'K. F. Riley'),
(95, 57, 'M. P. Hobson'),
(96, 57, 'S. J. Bence'),
(147, 15, 'Dan Bader'),
(152, 11, 'Aaron Maxwell');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `BookID` int(8) NOT NULL,
  `CallNumber` varchar(50) NOT NULL,
  `ISBN` bigint(13) NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Edition` varchar(50) NOT NULL,
  `Price` float NOT NULL,
  `Category` varchar(50) NOT NULL,
  `PublishedYear` year(4) NOT NULL,
  `PublisherName` varchar(100) NOT NULL,
  `Location` varchar(6) NOT NULL,
  `Remark` varchar(100) DEFAULT NULL,
  `Status` enum('Available','Unavailable','Reserved','Archived') NOT NULL,
  `Image` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`BookID`, `CallNumber`, `ISBN`, `Title`, `Edition`, `Price`, `Category`, `PublishedYear`, `PublisherName`, `Location`, `Remark`, `Status`, `Image`) VALUES
(10, 'PR6051.L3352 P68 2017 c.1', 9780692878972, 'Powerful Python: The Most Impactful Patterns, Features, and Development Strategies Modern Python Provides', '2nd', 437.65, 'Computer Science', '2017', 'Powerful Python Press', 'P', '', 'Archived', 'https://m.media-amazon.com/images/I/61hIyatMAmL._SL1360_.jpg'),
(11, 'PR6051.L3352 P68 2017 c.2', 9780692878972, 'Powerful Python: The Most Impactful Patterns, Features, and Development Strategies Modern Python Provides', '2nd', 437.65, 'Computer Science', '2017', 'Powerful Python Press', 'P', '', 'Unavailable', 'https://m.media-amazon.com/images/I/61hIyatMAmL._SL1360_.jpg'),
(12, 'PR6051.L3352 P68 2017 c.3', 9780692878972, 'Powerful Python: The Most Impactful Patterns, Features, and Development Strategies Modern Python Provides', '2nd', 437.65, 'Computer Science', '2017', 'Powerful Python Press', 'P', '', 'Available', 'https://m.media-amazon.com/images/I/61hIyatMAmL._SL1360_.jpg'),
(13, 'PR6051.L3352 P68 2017 c.4', 9780692878972, 'Powerful Python: The Most Impactful Patterns, Features, and Development Strategies Modern Python Provides', '2nd', 437.65, 'Computer Science', '2017', 'Powerful Python Press', 'P', '', 'Available', 'https://m.media-amazon.com/images/I/61hIyatMAmL._SL1360_.jpg'),
(14, 'PTY.J6843 .DB62 2017 C.1', 9781775093305, 'Python Tricks: A Buffet of Awesome Python Features', '1st', 134.52, 'Computer Science', '2017', 'Dan Bader', 'P', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61k7Z74UuZL._SL1500_.jpg'),
(15, 'PTY.J6843 .DB62 2017 C.2', 9781775093305, 'Python Tricks: A Buffet of Awesome Python Features', '1st', 134.52, 'Computer Science', '2017', 'Dan Bader', 'P', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61k7Z74UuZL._SL1500_.jpg'),
(16, 'PTY.J6843 .DB62 2017 C.3', 9781775093305, 'Python Tricks: A Buffet of Awesome Python Features', '1st', 134.52, 'Computer Science', '2017', 'Dan Bader', 'P', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61k7Z74UuZL._SL1500_.jpg'),
(17, 'PTY.J6843 .DB62 2017 C.4', 9781775093305, 'Python Tricks: A Buffet of Awesome Python Features', '1st', 134.52, 'Computer Science', '2017', 'Dan Bader', 'P', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61k7Z74UuZL._SL1500_.jpg'),
(18, 'TST16.E5 CA49 2021 c.1', 9781119724414, 'The Self-Taught Computer Scientist: The Beginner\'s Guide to Data Structures & Algorithms', '1st', 92, 'Computer Science', '2021', 'Wiley', 'T', '', 'Available', 'https://m.media-amazon.com/images/I/51qeLcY29LL._SL1254_.jpg'),
(19, 'TST16.E5 CA49 2021 c.2', 9781119724414, 'The Self-Taught Computer Scientist: The Beginner\'s Guide to Data Structures & Algorithms', '1st', 92, 'Computer Science', '2021', 'Wiley', 'T', '', 'Available', 'https://m.media-amazon.com/images/I/51qeLcY29LL._SL1254_.jpg'),
(20, 'TST16.E5 CA49 2021 c.3', 9781119724414, 'The Self-Taught Computer Scientist: The Beginner\'s Guide to Data Structures & Algorithms', '1st', 92, 'Computer Science', '2021', 'Wiley', 'T', '', 'Available', 'https://m.media-amazon.com/images/I/51qeLcY29LL._SL1254_.jpg'),
(21, 'TST16.E5 CA49 2021 c.4', 9781119724414, 'The Self-Taught Computer Scientist: The Beginner\'s Guide to Data Structures & Algorithms', '1st', 92, 'Computer Science', '2021', 'Wiley', 'T', '', 'Available', 'https://m.media-amazon.com/images/I/51qeLcY29LL._SL1254_.jpg'),
(22, 'CSP22.Y1 KP09 2022 c.1', 9781734554939, 'Computer Science Principles: The Foundational Concepts of Computer Science ', '4th', 70.76, 'Computer Science', '2022', 'Yellow Dart Publishing', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61JmEmSYtIL._SL1500_.jpg'),
(23, 'CSP22.Y1 KP09 2022 c.2', 9781734554939, 'Computer Science Principles: The Foundational Concepts of Computer Science ', '4th', 70.76, 'Computer Science', '2022', 'Yellow Dart Publishing', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61JmEmSYtIL._SL1500_.jpg'),
(24, 'CSP22.Y1 KP09 2022 c.3', 9781734554939, 'Computer Science Principles: The Foundational Concepts of Computer Science ', '4th', 70.76, 'Computer Science', '2022', 'Yellow Dart Publishing', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61JmEmSYtIL._SL1500_.jpg'),
(25, 'CSP22.Y1 KP09 2022 c.4', 9781734554939, 'Computer Science Principles: The Foundational Concepts of Computer Science ', '4th', 70.76, 'Computer Science', '2022', 'Yellow Dart Publishing', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61JmEmSYtIL._SL1500_.jpg'),
(26, 'C48.MP2 CP11 2022 c.1', 9780137909100, 'Code: The Hidden Language of Computer Hardware and Software', '2nd', 122.35, 'Computer Science', '2022', 'Microsoft Press', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/515myo2UtFL._SL1500_.jpg'),
(27, 'C48.MP2 CP11 2022 c.2', 9780137909100, 'Code: The Hidden Language of Computer Hardware and Software', '2nd', 122.35, 'Computer Science', '2022', 'Microsoft Press', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/515myo2UtFL._SL1500_.jpg'),
(28, 'C48.MP2 CP11 2022 c.3', 9780137909100, 'Code: The Hidden Language of Computer Hardware and Software', '2nd', 122.35, 'Computer Science', '2022', 'Microsoft Press', 'C', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/515myo2UtFL._SL1500_.jpg'),
(29, 'C48.MP2 CP11 2022 c.4', 9780137909100, 'Code: The Hidden Language of Computer Hardware and Software', '2nd', 122.35, 'Computer Science', '2022', 'Microsoft Press', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/515myo2UtFL._SL1500_.jpg'),
(30, 'CC84.P1 DW42 2008 c.1', 9780132350884, 'Clean Code: A Handbook of Agile Software Craftsmanship', '1st', 194.14, 'Computer Science', '2008', 'Pearson', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg'),
(31, 'CC84.P1 DW42 2008 c.2', 9780132350884, 'Clean Code: A Handbook of Agile Software Craftsmanship', '1st', 194.14, 'Computer Science', '2008', 'Pearson', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg'),
(32, 'CC84.P1 DW42 2008 c.3', 9780132350884, 'Clean Code: A Handbook of Agile Software Craftsmanship', '1st', 194.14, 'Computer Science', '2008', 'Pearson', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg'),
(33, 'CC84.P1 DW42 2008 c.4', 9780132350884, 'Clean Code: A Handbook of Agile Software Craftsmanship', '1st', 194.14, 'Computer Science', '2008', 'Pearson', 'C', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/51E2055ZGUL._SL1000_.jpg'),
(34, 'BA12.CL7 CA21 2019 c.1', 9780357109953, 'Business Analytics: Data Analysis & Decision Making (MindTap Course List)', '7th', 684.33, 'Business and Analytics', '2019', 'Cengage Learning', 'B', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/81VMlr6cFpL._SL1500_.jpg'),
(35, 'BA12.CL7 CA21 2019 c.2', 9780357109953, 'Business Analytics: Data Analysis & Decision Making (MindTap Course List)', '7th', 684.33, 'Business and Analytics', '2019', 'Cengage Learning', 'B', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/81VMlr6cFpL._SL1500_.jpg'),
(36, 'BA12.CL7 CA21 2019 c.3', 9780357109953, 'Business Analytics: Data Analysis & Decision Making (MindTap Course List)', '7th', 684.33, 'Business and Analytics', '2019', 'Cengage Learning', 'B', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/81VMlr6cFpL._SL1500_.jpg'),
(37, 'BA12.CL7 CA21 2019 c.4', 9780357109953, 'Business Analytics: Data Analysis & Decision Making (MindTap Course List)', '7th', 684.33, 'Business and Analytics', '2019', 'Cengage Learning', 'B', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/81VMlr6cFpL._SL1500_.jpg'),
(38, 'SB63.ORM1 FP61 2013 c.1', 9781449361327, 'Data Science for Business: What You Need to Know about Data Mining and Data-Analytic Thinking', '1st', 161.33, 'Computer Science', '2013', 'O\'Reilly Media', 'D', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/81tyuOUHPNL._SL1500_.jpg'),
(39, 'SB63.ORM1 FP61 2013 c.2', 9781449361327, 'Data Science for Business: What You Need to Know about Data Mining and Data-Analytic Thinking', '1st', 161.33, 'Computer Science', '2013', 'O\'Reilly Media', 'D', 'Paperback', 'Reserved', 'https://m.media-amazon.com/images/I/81tyuOUHPNL._SL1500_.jpg'),
(40, 'SB63.ORM1 FP61 2013 c.3', 9781449361327, 'Data Science for Business: What You Need to Know about Data Mining and Data-Analytic Thinking', '1st', 161.33, 'Computer Science', '2013', 'O\'Reilly Media', 'D', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/81tyuOUHPNL._SL1500_.jpg'),
(41, 'SB63.ORM1 FP61 2013 c.4', 9781449361327, 'Data Science for Business: What You Need to Know about Data Mining and Data-Analytic Thinking', '1st', 161.33, 'Computer Science', '2013', 'O\'Reilly Media', 'D', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/81tyuOUHPNL._SL1500_.jpg'),
(42, 'HBR3.HBR43 HBR11 2018 c.1', 9781633694286, 'HBR Guide to Data Analytics Basics for Managers (HBR Guide Series)', '1st', 59.9, 'Business and Analytics', '2018', 'Harvard Business Review Press', 'H', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/81jy2orAxiL._SL1500_.jpg'),
(43, 'HBR3.HBR43 HBR11 2018 c.2', 9781633694286, 'HBR Guide to Data Analytics Basics for Managers (HBR Guide Series)', '1st', 59.9, 'Business and Analytics', '2018', 'Harvard Business Review Press', 'H', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/81jy2orAxiL._SL1500_.jpg'),
(44, 'HBR3.HBR43 HBR11 2018 c.3', 9781633694286, 'HBR Guide to Data Analytics Basics for Managers (HBR Guide Series)', '1st', 59.9, 'Business and Analytics', '2018', 'Harvard Business Review Press', 'H', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/81jy2orAxiL._SL1500_.jpg'),
(45, 'HBR3.HBR43 HBR11 2018 c.4', 9781633694286, 'HBR Guide to Data Analytics Basics for Managers (HBR Guide Series)', '1st', 59.9, 'Business and Analytics', '2018', 'Harvard Business Review Press', 'H', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/81jy2orAxiL._SL1500_.jpg'),
(46, 'FI09.HBRP8 KB11 2013 c.1', 9781422144114, 'Financial Intelligence, Revised Edition: A Manager\'s Guide to Knowing What the Numbers Really Mean', 'Revised ed.', 94.36, 'Finance and Financial Technology', '2013', 'Harvard Business Review Press', 'F', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/810WDjhKbPL._SL1500_.jpg'),
(47, 'FI09.HBRP8 KB11 2013 c.2', 9781422144114, 'Financial Intelligence, Revised Edition: A Manager\'s Guide to Knowing What the Numbers Really Mean', 'Revised ed.', 94.36, 'Finance and Financial Technology', '2013', 'Harvard Business Review Press', 'F', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/810WDjhKbPL._SL1500_.jpg'),
(48, 'FI09.HBRP8 KB11 2013 c.3', 9781422144114, 'Financial Intelligence, Revised Edition: A Manager\'s Guide to Knowing What the Numbers Really Mean', 'Revised ed.', 94.36, 'Finance and Financial Technology', '2013', 'Harvard Business Review Press', 'F', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/810WDjhKbPL._SL1500_.jpg'),
(49, 'FI09.HBRP8 KB11 2013 c.4', 9781422144114, 'Financial Intelligence, Revised Edition: A Manager\'s Guide to Knowing What the Numbers Really Mean', 'Revised ed.', 94.36, 'Finance and Financial Technology', '2013', 'Harvard Business Review Press', 'F', 'Hardcover', 'Available', 'https://m.media-amazon.com/images/I/810WDjhKbPL._SL1500_.jpg'),
(50, 'TS1.CGP15 DM04 2008 c.1', 9781603580557, 'Thinking in Systems: International Bestseller', '1st', 36.91, 'Business and Analytics', '2008', 'Chelsea Green Publishing', 'T', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/51V4oNS0BSL._SL1000_.jpg'),
(51, 'TS1.CGP15 DM04 2008 c.2', 9781603580557, 'Thinking in Systems: International Bestseller', '1st', 36.91, 'Business and Analytics', '2008', 'Chelsea Green Publishing', 'T', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/51V4oNS0BSL._SL1000_.jpg'),
(52, 'TS1.CGP15 DM04 2008 c.3', 9781603580557, 'Thinking in Systems: International Bestseller', '1st', 36.91, 'Business and Analytics', '2008', 'Chelsea Green Publishing', 'T', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/51V4oNS0BSL._SL1000_.jpg'),
(53, 'TS1.CGP15 DM04 2008 c.4', 9781603580557, 'Thinking in Systems: International Bestseller', '1st', 36.91, 'Business and Analytics', '2008', 'Chelsea Green Publishing', 'T', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/51V4oNS0BSL._SL1000_.jpg'),
(54, 'MM86.CUP94 FK3 2006 c.1', 9780521679718, 'Mathematical Methods for Physics and Engineering: A Comprehensive Guide', '3rd', 325.21, 'Mechanical Engineering', '2006', 'Cambridge University Press', 'M', 'Paperback', 'Unavailable', 'https://m.media-amazon.com/images/I/61llrbAi-DL._SL1500_.jpg'),
(55, 'MM86.CUP94 FK3 2006 c.2', 9780521679718, 'Mathematical Methods for Physics and Engineering: A Comprehensive Guide', '3rd', 325.21, 'Mechanical Engineering', '2006', 'Cambridge University Press', 'M', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61llrbAi-DL._SL1500_.jpg'),
(56, 'MM86.CUP94 FK3 2006 c.3', 9780521679718, 'Mathematical Methods for Physics and Engineering: A Comprehensive Guide', '3rd', 325.21, 'Mechanical Engineering', '2006', 'Cambridge University Press', 'M', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61llrbAi-DL._SL1500_.jpg'),
(57, 'MM86.CUP94 FK3 2006 c.4', 9780521679718, 'Mathematical Methods for Physics and Engineering: A Comprehensive Guide', '3rd', 325.21, 'Mechanical Engineering', '2006', 'Cambridge University Press', 'M', 'Paperback', 'Available', 'https://m.media-amazon.com/images/I/61llrbAi-DL._SL1500_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `book_issued`
--

CREATE TABLE `book_issued` (
  `BookIssuedID` int(8) NOT NULL,
  `UserID` int(8) NOT NULL,
  `BookID` int(8) NOT NULL,
  `DateBorrow` datetime DEFAULT NULL,
  `DateReturn` datetime DEFAULT NULL,
  `DueDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_issued`
--

INSERT INTO `book_issued` (`BookIssuedID`, `UserID`, `BookID`, `DateBorrow`, `DateReturn`, `DueDate`) VALUES
(1, 16, 34, '2024-04-04 05:27:32', '2024-04-04 05:27:36', '2024-04-18 05:27:32'),
(2, 16, 34, '2024-04-04 05:27:42', '2024-04-25 16:58:52', '2024-04-14 05:27:42'),
(3, 16, 26, '2024-04-04 05:27:53', '2024-04-04 15:18:03', '2024-04-18 05:27:53'),
(4, 16, 22, '2024-03-19 05:28:03', '2024-04-04 15:17:53', '2024-04-01 05:28:03'),
(5, 18, 30, '2024-04-04 05:29:32', '0000-00-00 00:00:00', '2024-04-20 05:29:32'),
(6, 18, 38, '2024-04-04 05:29:40', '0000-00-00 00:00:00', '2024-04-18 05:29:40'),
(7, 20, 31, '2024-04-04 05:55:17', '0000-00-00 00:00:00', '2024-04-18 05:55:17'),
(8, 20, 27, '2024-04-04 05:55:21', '0000-00-00 00:00:00', '2024-04-18 05:55:21'),
(9, 1, 32, '2024-04-04 05:56:17', '0000-00-00 00:00:00', '2024-04-18 05:56:17'),
(10, 2, 33, '2024-04-04 05:59:06', '0000-00-00 00:00:00', '2024-04-18 05:59:06'),
(11, 16, 39, '2024-03-20 06:01:58', '2024-04-03 06:02:04', '2024-04-01 06:01:58'),
(12, 16, 50, '2024-04-14 06:10:01', '0000-00-00 00:00:00', '2024-04-21 06:10:01'),
(13, 1, 39, '2024-04-04 06:58:06', '2024-04-04 06:59:51', '2024-04-18 06:58:06'),
(14, 2, 40, '2024-04-04 06:58:22', '0000-00-00 00:00:00', '2024-04-18 06:58:22'),
(15, 20, 41, '2024-04-04 06:59:02', '0000-00-00 00:00:00', '2024-04-18 06:59:02'),
(16, 16, 54, '2024-04-04 15:17:18', '2024-04-04 15:17:24', '2024-05-02 15:17:18'),
(17, 16, 54, '2024-04-04 15:17:32', '2024-04-04 15:17:44', '2024-04-18 15:17:32'),
(18, 16, 38, '2024-04-14 15:18:11', '2024-04-18 08:28:25', '2024-04-28 15:18:11'),
(19, 16, 38, '2024-04-26 15:22:10', '0000-00-00 00:00:00', '2024-06-05 15:22:10'),
(20, 16, 22, '2024-04-25 16:58:01', '2024-04-25 16:58:16', '2024-05-08 16:58:01'),
(21, 31, 11, '2024-04-25 17:02:09', '2024-04-25 17:02:25', '2024-05-17 17:02:09'),
(22, 16, 46, '2024-04-25 18:17:53', '2024-04-25 19:00:10', '2024-06-14 18:17:53'),
(23, 16, 14, '2024-04-25 19:00:20', '2024-04-25 19:02:54', '2024-06-14 19:00:20'),
(24, 16, 12, '2024-04-25 19:02:58', '2024-04-25 19:03:13', '2024-06-14 19:02:58'),
(25, 16, 54, '2024-04-25 19:03:17', '0000-00-00 00:00:00', '2024-06-14 19:03:17'),
(26, 16, 14, '2024-04-25 19:26:33', '2024-04-25 19:26:41', '2024-07-05 19:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `book_reserve`
--

CREATE TABLE `book_reserve` (
  `ReserveID` int(8) NOT NULL,
  `BookID` int(8) NOT NULL,
  `UserID` int(8) NOT NULL,
  `DateReserve` datetime NOT NULL,
  `AllowBorrow` enum('0','1') NOT NULL,
  `Borrowed` tinyint(1) NOT NULL DEFAULT 1,
  `Priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book_reserve`
--

INSERT INTO `book_reserve` (`ReserveID`, `BookID`, `UserID`, `DateReserve`, `AllowBorrow`, `Borrowed`, `Priority`) VALUES
(1, 30, 16, '0000-00-00 00:00:00', '0', 0, 0),
(2, 38, 16, '2024-04-04 06:59:29', '0', 1, 1),
(3, 11, 29, '2024-04-25 17:04:10', '0', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fine`
--

CREATE TABLE `fine` (
  `FineID` int(8) NOT NULL,
  `UserID` int(8) NOT NULL,
  `BookIssuedID` int(8) NOT NULL,
  `FineType` enum('Overdue','Damaged','Lost') NOT NULL,
  `FineAmount` float DEFAULT 0,
  `IsPaid` enum('0','1') NOT NULL,
  `DateFined` datetime NOT NULL,
  `DateCompleteFine` datetime DEFAULT NULL,
  `PaymentType` enum('Cash','Credit Card','QR Code','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `fine`
--

INSERT INTO `fine` (`FineID`, `UserID`, `BookIssuedID`, `FineType`, `FineAmount`, `IsPaid`, `DateFined`, `DateCompleteFine`, `PaymentType`) VALUES
(67, 16, 4, 'Overdue', 3, '1', '2024-04-02 05:28:03', '2024-04-25 22:59:26', 'QR Code'),
(68, 16, 11, 'Overdue', 2, '1', '2024-04-02 06:01:58', '2024-04-25 22:59:26', 'QR Code'),
(72, 16, 2, 'Overdue', 11, '1', '2024-04-15 05:27:42', '2024-04-25 22:59:26', 'QR Code'),
(73, 16, 2, 'Damaged', 684.33, '1', '2024-04-25 23:04:40', '2024-04-25 23:43:06', 'QR Code'),
(74, 16, 17, 'Damaged', 325.21, '1', '2024-04-26 01:13:42', '2024-04-26 01:24:55', 'QR Code');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `ReturnDate` int(200) NOT NULL,
  `ExtendDate` int(200) NOT NULL,
  `BorrowLimit` int(50) NOT NULL,
  `OverdueFine` float NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `ExtendLimit` int(50) NOT NULL,
  `PhoneNo` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `QrCodePay` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`ReturnDate`, `ExtendDate`, `BorrowLimit`, `OverdueFine`, `StartTime`, `EndTime`, `ExtendLimit`, `PhoneNo`, `Email`, `QrCodePay`) VALUES
(14, 14, 4, 1, '09:00:00', '17:00:00', 2, '07-5602561', 'uosmlibrary@soton.ac.uk', 'https://pbs.twimg.com/media/GL5cZbCbMAAm0Hm?format=png&name=360x360');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(8) NOT NULL,
  `UserTypeID` int(8) DEFAULT NULL,
  `ID` int(8) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `Password` varchar(128) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `TotalFine` float DEFAULT NULL,
  `RegisteredDate` datetime NOT NULL,
  `IsVerified` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `UserTypeID`, `ID`, `Email`, `LastName`, `FirstName`, `Password`, `PhoneNumber`, `TotalFine`, `RegisteredDate`, `IsVerified`) VALUES
(1, 1, 31486959, 'st6g19@soton.ac.uk', 'Thmn', 'Samuel', '594f803b380a41396ed63dca39503542', '0128372321', 0, '2024-01-01 01:32:00', '1'),
(2, 1, 32492774, 'zzy1a21@soton.ac.uk', 'Zong ', 'Yee', 'a21075a36eeddd084e17611a238c7101', '0134858593', 0, '2024-01-02 01:32:00', '1'),
(3, 1, 32493282, 'rxs1n21@soton.ac.uk', 'Roy', 'Sze', '67c762276bced09ee4df0ed537d164ea', '0191849385', 0, '2024-01-03 01:32:00', '0'),
(4, 1, 32493339, 'azj1n21@soton.ac.uk', 'Ainsley', 'Jong', '50f84daf3a6dfd6a9f20c9f8ef428942', '0104838483', 0, '2024-01-04 01:32:00', '0'),
(5, 1, 33042993, 'psbs1a21@soton.ac.uk', 'Prabhjot ', 'Bhupinder Singh', '86871b9b1ab33b0834d455c540d82e89', '0194758295', 0, '2024-01-05 01:32:00', '0'),
(6, 1, 33043132, 'ap1a21@soton.ac.uk ', 'Arun ', 'Prakash', 'a98f6f64e6cdfac22ab2ffd15a7241e3', '0129471948', 0, '2024-01-06 01:32:00', '0'),
(7, 1, 33245398, 'jyi1n21@soton.ac.uk', 'Jie ', 'In', '9a0fe27c8bcc9aad51eda55e1b735eb5', '0184838573', 0, '2024-01-07 01:32:00', '0'),
(8, 1, 33245932, 'sak2n21@soton.ac.uk', 'Shao', 'Kok', '594f803b380a41396ed63dca39503542', '0194938767', 0, '2024-01-08 01:32:00', '0'),
(9, 1, 33296049, 'jl6u21@soton.ac.uk', 'Jeremiah', 'Lee', 'a21075a36eeddd084e17611a238c7101', '0149581047', 0, '2024-01-09 01:32:00', '0'),
(10, 1, 33296278, 'hso1n21@soton.ac.uk', 'Hui ', 'Ong', '67c762276bced09ee4df0ed537d164ea', '0129227466', 0, '2024-01-10 01:32:00', '0'),
(11, 1, 33296839, 'wql1n21@soton.ac.uk', 'Wei ', 'Lee', '50f84daf3a6dfd6a9f20c9f8ef428942', '0111948373', 0, '2024-01-11 01:32:00', '0'),
(12, 1, 33296855, 'xwl2n21@soton.ac.uk', 'Xin ', 'Lim', '86871b9b1ab33b0834d455c540d82e89', '0199484837', 0, '2024-01-12 01:32:00', '1'),
(13, 1, 33296987, 'wl9n21@soton.ac.uk', 'Wei ', 'Lim', 'a98f6f64e6cdfac22ab2ffd15a7241e3', '0193846253', 0, '2024-01-13 01:32:00', '0'),
(14, 1, 33297177, 'rs11g21@soton.ac.uk', 'Shaai ', 'Shaai', '9a0fe27c8bcc9aad51eda55e1b735eb5', '0149588293', 0, '2024-01-14 01:32:00', '0'),
(15, 1, 33297223, 'jhc1u21@soton.ac.uk', 'Jun ', 'Choo', '594f803b380a41396ed63dca39503542', '0183747263', 0, '2024-01-15 01:32:00', '0'),
(16, 8, 33297517, 'kxt1g21@soton.ac.uk', 'KeXin ', 'Tong', 'a21075a36eeddd084e17611a238c7101', '0177130883', 0, '2024-01-16 01:32:00', '1'),
(17, 1, 33352984, 'meme1g21@soton.ac.uk', 'Elham ', 'Mohamad Eznillah', '67c762276bced09ee4df0ed537d164ea', '0129484628', 0, '2024-01-17 01:32:00', '0'),
(18, 8, 33353018, 'hph1g21@soton.ac.uk', 'Htet ', 'Hein', '50f84daf3a6dfd6a9f20c9f8ef428942', '0132252577', 0, '2024-01-18 01:32:00', '1'),
(19, 1, 33353026, 'hqo1e21@soton.ac.uk', 'Hui ', 'Ooi', '86871b9b1ab33b0834d455c540d82e89', '0193848294', 0, '2024-01-19 01:32:00', '0'),
(20, 8, 33354596, 'zhc1e22@soton.ac.uk', 'Zi Han', 'Chee', '594f803b380a41396ed63dca39503542', '0127730678', 0, '2024-01-20 01:32:00', '1'),
(21, 1, 33354707, 'ykl1e22@soton.ac.uk', 'Yi ', 'Lau', '9a0fe27c8bcc9aad51eda55e1b735eb5', '0137482748', 0, '2024-01-21 01:32:00', '0'),
(22, 1, 33354723, 'sab1e22@soton.ac.uk', 'Shu', 'Beh', '594f803b380a41396ed63dca39503542', '0184837473', 0, '2024-01-22 01:32:00', '0'),
(23, 1, 33399948, 'jhh1e22@soton.ac.uk', 'Jian ', 'Hin', 'a21075a36eeddd084e17611a238c7101', '0193746274', 0, '2024-01-23 01:32:00', '0'),
(24, 1, 33400717, 'sx1e22@soton.ac.uk', 'Sizhi ', 'Xu', '67c762276bced09ee4df0ed537d164ea', '0184299823', 0, '2024-01-24 01:32:00', '0'),
(25, 1, 34344381, 'ho1e22@soton.ac.uk', 'Hana ', 'Oh', '50f84daf3a6dfd6a9f20c9f8ef428942', '0158308098', 0, '2024-01-25 01:32:00', '1'),
(26, 1, 34396918, 'mt1e22@soton.ac.uk', 'Muhammad', 'Taha', '86871b9b1ab33b0834d455c540d82e89', '0158294494', 0, '2024-01-26 01:32:00', '0'),
(27, 1, 34402926, 'cht1c22@soton.ac.uk', 'Tee ', 'Hong', 'a98f6f64e6cdfac22ab2ffd15a7241e3', '0178481849', 0, '2024-01-27 01:32:00', '0'),
(28, 6, 22130953, 'a1m43@soton.ac.uk', 'Adam', 'Lim', '9a0fe27c8bcc9aad51eda55e1b735eb5', '0193748274', 0, '2024-01-28 01:32:00', '1'),
(29, 6, 22874399, 'md2n42@soton.ac.uk', 'Madni', 'Ya', '594f803b380a41396ed63dca39503542', '0193726274', 0, '2024-01-29 01:32:00', '1'),
(30, 7, 22093384, 'jt8l42@soton.ac.uk', 'Jessica', 'Thomsa', 'a21075a36eeddd084e17611a238c7101', '0119393938', 0, '2024-01-30 01:32:00', '0'),
(31, 3, 22841039, 'aa8r24@soton.ac.uk', 'Alex', 'Ander', '67c762276bced09ee4df0ed537d164ea', '0193937373', 0, '2024-01-31 01:32:00', '0'),
(32, 4, 22909490, 'xh4o32@soton.ac.uk', 'Xin ', 'Han', '50f84daf3a6dfd6a9f20c9f8ef428942', '0194725424', 0, '2024-02-01 01:32:00', '0'),
(33, 5, 35957375, 'xhl4x26@soton.ac.uk', 'Xue', 'Loi', '86871b9b1ab33b0834d455c540d82e89', '0164724242', 0, '2024-02-02 01:32:00', '0'),
(34, 6, 66666666, 'librarian@soton.ac.uk', 'librarian', 'librarian', 'e10adc3949ba59abbe56e057f20f883e', '0123456789', 0, '2024-03-13 12:15:55', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_otp_login`
--

CREATE TABLE `user_otp_login` (
  `OTPID` int(11) NOT NULL,
  `UserID` int(8) NOT NULL,
  `otp` int(6) NOT NULL,
  `is_expired` enum('0','1') DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `expiry_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_otp_login`
--

INSERT INTO `user_otp_login` (`OTPID`, `UserID`, `otp`, `is_expired`, `create_at`, `expiry_time`) VALUES
(24, 16, 971881, '0', '2024-03-19 01:01:42', '2024-03-19 01:06:42'),
(25, 18, 962095, '0', '2024-03-19 01:02:23', '2024-03-19 01:07:23'),
(26, 16, 177956, '0', '2024-03-19 17:06:45', '2024-03-19 17:11:45'),
(27, 16, 750685, '0', '2024-03-19 17:06:52', '2024-03-19 17:11:52'),
(28, 16, 233455, '0', '2024-03-19 17:07:47', '2024-03-19 17:12:47'),
(29, 16, 766422, '0', '2024-03-19 17:07:58', '2024-03-19 17:12:58'),
(30, 16, 931459, '0', '2024-03-19 17:15:15', '2024-03-19 17:20:15'),
(31, 16, 798077, '0', '2024-03-19 17:15:26', '2024-03-19 17:20:26'),
(32, 16, 974612, '0', '2024-03-19 17:15:36', '2024-03-19 17:20:36'),
(33, 16, 224239, '0', '2024-03-19 17:15:51', '2024-03-19 17:20:51'),
(34, 16, 108612, '0', '2024-03-19 17:16:05', '2024-03-19 17:21:05'),
(35, 16, 383818, '0', '2024-03-19 17:20:07', '2024-03-19 17:25:07'),
(36, 16, 748020, '0', '2024-03-19 17:21:26', '2024-03-19 17:26:26'),
(37, 16, 225510, '0', '2024-03-19 17:21:37', '2024-03-19 17:26:37'),
(38, 16, 888483, '0', '2024-03-19 17:21:41', '2024-03-19 17:26:41'),
(39, 16, 429988, '0', '2024-03-19 17:23:29', '2024-03-19 17:28:29'),
(40, 16, 461920, '0', '2024-03-19 17:23:34', '2024-03-19 17:28:34'),
(41, 16, 829472, '0', '2024-03-19 17:24:13', '2024-03-19 17:29:13'),
(42, 16, 533766, '0', '2024-03-19 17:24:48', '2024-03-19 17:29:48'),
(43, 16, 409116, '0', '2024-03-19 17:24:51', '2024-03-19 17:29:51'),
(44, 16, 174918, '0', '2024-03-19 17:24:56', '2024-03-19 17:29:56'),
(45, 16, 890378, '0', '2024-03-19 17:26:12', '2024-03-19 17:31:12'),
(46, 16, 650899, '0', '2024-03-19 17:26:15', '2024-03-19 17:31:15'),
(47, 16, 689104, '0', '2024-03-19 17:26:22', '2024-03-19 17:31:22'),
(48, 16, 584404, '0', '2024-03-19 17:27:12', '2024-03-19 17:32:12'),
(49, 16, 958948, '0', '2024-03-19 17:27:33', '2024-03-19 17:32:33'),
(50, 16, 924383, '0', '2024-03-19 17:27:36', '2024-03-19 17:32:36'),
(51, 16, 975482, '0', '2024-03-19 17:27:41', '2024-03-19 17:32:41'),
(52, 16, 350254, '0', '2024-03-19 17:29:08', '2024-03-19 17:34:08'),
(53, 16, 881736, '0', '2024-03-19 17:29:12', '2024-03-19 17:34:12'),
(54, 16, 673809, '0', '2024-03-19 17:29:37', '2024-03-19 17:34:37'),
(55, 16, 916434, '0', '2024-03-19 17:29:43', '2024-03-19 17:34:43'),
(56, 16, 345364, '0', '2024-03-19 17:36:47', '2024-03-19 17:41:47'),
(57, 16, 469585, '0', '2024-03-19 17:39:18', '2024-03-19 17:44:18'),
(58, 16, 824193, '1', '2024-03-19 17:39:39', '2024-03-19 17:44:39'),
(59, 16, 783359, '1', '2024-03-19 17:40:51', '2024-03-19 17:45:51'),
(60, 20, 323608, '1', '2024-03-25 10:09:51', '2024-03-25 10:14:51'),
(61, 1, 370184, '0', '2024-04-01 10:11:48', '2024-04-01 10:16:48'),
(62, 18, 611044, '0', '2024-04-01 22:54:21', '2024-04-01 22:59:21'),
(63, 16, 700299, '1', '2024-04-02 12:10:35', '2024-04-02 12:15:35'),
(64, 16, 694965, '1', '2024-04-02 12:11:17', '2024-04-02 12:16:17'),
(65, 16, 445923, '1', '2024-04-02 12:13:01', '2024-04-02 12:18:01'),
(67, 16, 725181, '1', '2024-04-02 12:53:36', '2024-04-02 12:58:36'),
(68, 16, 598644, '1', '2024-04-02 12:55:14', '2024-04-02 13:00:14'),
(69, 16, 811138, '1', '2024-04-03 00:12:09', '2024-04-03 00:17:09'),
(70, 16, 566375, '1', '2024-04-03 23:53:37', '2024-04-03 23:58:37'),
(71, 16, 916650, '1', '2024-04-04 10:27:32', '2024-04-04 10:32:32'),
(72, 34, 962275, '0', '2024-04-04 11:41:48', '2024-04-04 11:46:48'),
(73, 34, 544519, '0', '2024-04-04 11:42:44', '2024-04-04 11:47:44'),
(74, 34, 111972, '1', '2024-04-04 11:43:12', '2024-04-04 11:48:12'),
(75, 34, 977453, '1', '2024-04-04 11:44:36', '2024-04-04 11:49:36'),
(76, 34, 225329, '1', '2024-04-04 11:46:56', '2024-04-04 11:51:56'),
(77, 2, 410838, '0', '2024-04-04 11:56:39', '2024-04-04 12:01:39'),
(78, 16, 242149, '0', '2024-04-04 15:37:39', '2024-04-04 15:42:39'),
(79, 16, 552170, '0', '2024-04-04 15:37:50', '2024-04-04 15:42:50'),
(80, 16, 943028, '0', '2024-04-04 21:22:58', '2024-04-04 21:27:58'),
(81, 34, 884980, '0', '2024-04-04 21:27:11', '2024-04-04 21:32:11'),
(82, 16, 616552, '1', '2024-04-16 23:24:39', '2024-04-16 23:29:39'),
(83, 16, 260886, '1', '2024-04-25 22:56:33', '2024-04-25 23:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `UserTypeID` int(8) NOT NULL,
  `Type` enum('Student','Lecturer','Alumni','Transferred','Withdrew','Librarian','Admin') NOT NULL,
  `Course` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`UserTypeID`, `Type`, `Course`) VALUES
(1, 'Student', 'Computer Science Part 2'),
(2, 'Student', 'Foundation of Computer Science'),
(3, 'Lecturer', 'Computer Science'),
(4, 'Alumni', 'Engineering'),
(5, 'Withdrew', 'Business'),
(6, 'Librarian', NULL),
(7, 'Transferred', 'Mechanical Engineering'),
(8, 'Admin', NULL),
(14, 'Student', 'Computer Science Part 1'),
(15, 'Student', 'Engineering Part 1'),
(16, 'Student', 'Engineering Part 2'),
(17, 'Student', 'Business Part 1'),
(18, 'Student', 'Business Part 2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`AuthorID`),
  ADD KEY `BookID` (`BookID`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookID`),
  ADD UNIQUE KEY `CallNumber` (`CallNumber`,`Location`);

--
-- Indexes for table `book_issued`
--
ALTER TABLE `book_issued`
  ADD PRIMARY KEY (`BookIssuedID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BookID` (`BookID`);

--
-- Indexes for table `book_reserve`
--
ALTER TABLE `book_reserve`
  ADD PRIMARY KEY (`ReserveID`),
  ADD KEY `BookID` (`BookID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `fine`
--
ALTER TABLE `fine`
  ADD PRIMARY KEY (`FineID`),
  ADD KEY `fk_user_id` (`UserID`),
  ADD KEY `fk_book_issued_id` (`BookIssuedID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `ID` (`ID`,`Email`),
  ADD KEY `UserTypeID` (`UserTypeID`);

--
-- Indexes for table `user_otp_login`
--
ALTER TABLE `user_otp_login`
  ADD PRIMARY KEY (`OTPID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`UserTypeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `AuthorID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `BookID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `book_issued`
--
ALTER TABLE `book_issued`
  MODIFY `BookIssuedID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `book_reserve`
--
ALTER TABLE `book_reserve`
  MODIFY `ReserveID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fine`
--
ALTER TABLE `fine`
  MODIFY `FineID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `user_otp_login`
--
ALTER TABLE `user_otp_login`
  MODIFY `OTPID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `UserTypeID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book_issued`
--
ALTER TABLE `book_issued`
  ADD CONSTRAINT `book_issued_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_issued_ibfk_2` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book_reserve`
--
ALTER TABLE `book_reserve`
  ADD CONSTRAINT `book_reserve_ibfk_1` FOREIGN KEY (`BookID`) REFERENCES `book` (`BookID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_reserve_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fine`
--
ALTER TABLE `fine`
  ADD CONSTRAINT `fk_book_issued_id` FOREIGN KEY (`BookIssuedID`) REFERENCES `book_issued` (`BookIssuedID`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`UserTypeID`) REFERENCES `user_type` (`UserTypeID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_otp_login`
--
ALTER TABLE `user_otp_login`
  ADD CONSTRAINT `user_otp_login_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
