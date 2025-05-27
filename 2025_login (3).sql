-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 10:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2025_login`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `prod_id` int(11) NOT NULL,
  `prod_title` varchar(255) NOT NULL,
  `prod_cat_fk` int(11) NOT NULL,
  `prod_cond_fk` int(11) NOT NULL,
  `prod_shelf_fk` int(11) NOT NULL,
  `prod_price` int(11) NOT NULL,
  `prod_info` varchar(255) NOT NULL,
  `prod_code` int(11) NOT NULL,
  `prod_year` int(11) NOT NULL,
  `prod_status` tinyint(2) NOT NULL,
  `Img_name` varchar(255) NOT NULL DEFAULT 'placeholder.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`prod_id`, `prod_title`, `prod_cat_fk`, `prod_cond_fk`, `prod_shelf_fk`, `prod_price`, `prod_info`, `prod_code`, `prod_year`, `prod_status`, `Img_name`) VALUES
(21, 'Jordan', 3, 2, 6, 200, 'en bok om middleastr', 0, 1998, 1, 'placeholder');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `r_id` int(11) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`r_id`, `r_name`, `r_level`) VALUES
(1, 'user', 100),
(2, 'editor', 200),
(3, 'admin', 300),
(4, 'Giga-admin', 9999);

-- --------------------------------------------------------

--
-- Table structure for table `tab-prod-genre`
--

CREATE TABLE `tab-prod-genre` (
  `T-P-G` int(11) NOT NULL,
  `genre_fk` int(11) NOT NULL,
  `prod_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab-prod-genre`
--

INSERT INTO `tab-prod-genre` (`T-P-G`, `genre_fk`, `prod_fk`) VALUES
(31, 1, 21),
(30, 2, 21);

-- --------------------------------------------------------

--
-- Table structure for table `table-prod-author`
--

CREATE TABLE `table-prod-author` (
  `prod-auth-id` int(11) NOT NULL,
  `auth_fk` int(11) NOT NULL,
  `prod_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table-prod-author`
--

INSERT INTO `table-prod-author` (`prod-auth-id`, `auth_fk`, `prod_fk`) VALUES
(29, 18, 21),
(30, 19, 21);

-- --------------------------------------------------------

--
-- Table structure for table `tab_athor`
--

CREATE TABLE `tab_athor` (
  `auth_id` int(11) NOT NULL,
  `auth_fname` varchar(255) NOT NULL,
  `auth_lnmae` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab_athor`
--

INSERT INTO `tab_athor` (`auth_id`, `auth_fname`, `auth_lnmae`) VALUES
(1, 'Ivan ', 'Konev'),
(2, 'Alllah', 'akbar'),
(3, 'Wictor', ''),
(4, 'Josstaffen', ''),
(5, 'test', ''),
(6, 'arggard', ''),
(7, 'J', 'J'),
(8, 'fgbdfbgdfg', ''),
(9, 'Sturmbannfuhrer', ''),
(10, 'Axel', 'S'),
(11, 'VH', ''),
(12, 'dfvbbfgdxgfdn', ''),
(13, 'drsdtrhstdbh', ''),
(14, 'objögstö', ''),
(15, 'bdffdgb', ''),
(16, 'dfgbrgdfdbsdfb', ''),
(17, 'trbgfbgd', ''),
(18, 'Slamm', ''),
(19, 'Johan', ''),
(20, 'rgftdfbs', ''),
(21, 'drfgbrdgdrg', ''),
(22, 'hggngh', ''),
(23, 'dsvdfvfdv', '');

-- --------------------------------------------------------

--
-- Table structure for table `tab_cat`
--

CREATE TABLE `tab_cat` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab_cat`
--

INSERT INTO `tab_cat` (`cat_id`, `cat_name`) VALUES
(3, 'Sjöfarts böcker'),
(19, 'kategori 2'),
(20, 'kategorrgrdg');

-- --------------------------------------------------------

--
-- Table structure for table `tab_genger`
--

CREATE TABLE `tab_genger` (
  `gen_id` int(11) NOT NULL,
  `gen_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab_genger`
--

INSERT INTO `tab_genger` (`gen_id`, `gen_name`) VALUES
(1, 'Historia'),
(2, 'Fakta'),
(3, 'Fiktion'),
(7, 'Erotisk');

-- --------------------------------------------------------

--
-- Table structure for table `tab_kond`
--

CREATE TABLE `tab_kond` (
  `cond_id` int(11) NOT NULL,
  `cond_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab_kond`
--

INSERT INTO `tab_kond` (`cond_id`, `cond_class`) VALUES
(1, 'Good'),
(2, 'Used'),
(3, 'Damaged'),
(4, 'New'),
(5, 'Fair');

-- --------------------------------------------------------

--
-- Table structure for table `tab_shelf`
--

CREATE TABLE `tab_shelf` (
  `shelf_id` int(11) NOT NULL,
  `shelf_nr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tab_shelf`
--

INSERT INTO `tab_shelf` (`shelf_id`, `shelf_nr`) VALUES
(1, 23),
(2, 1),
(3, 99),
(4, 69),
(5, 12),
(6, 11),
(7, 34343),
(8, 0),
(9, 54),
(10, 9),
(11, 243),
(12, 232332),
(13, 3443),
(14, 22),
(15, 223),
(16, 1212);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `u_fname` varchar(255) NOT NULL,
  `u_lname` varchar(255) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_lastlogin` datetime NOT NULL,
  `u_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `u_isactive` tinyint(1) NOT NULL,
  `u_role_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_fname`, `u_lname`, `u_email`, `u_password`, `u_lastlogin`, `u_created`, `u_isactive`, `u_role_fk`) VALUES
(10, 'Jostari', 'safea', 'dafds', 'shb@hb23a.sf', '$2y$10$lXf24Nsgj.6bYB3KglXopulkbI5DcBlwWuBo/x3AehZMy9TeeZ9Hu', '0000-00-00 00:00:00', '2025-04-04 06:45:09', 1, 4),
(17, 'Admin', 'Admin', 'Admin', 'Admin@amd.fi', '$2y$10$4.tO2Dy1cXRyV9z2uCw62uvRM.pC50i1eQd0BtXk3XFX9AOC4A85m', '0000-00-00 00:00:00', '2025-04-14 08:20:00', 1, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `prod_shelf_fk` (`prod_shelf_fk`),
  ADD KEY `prod_cat_fk` (`prod_cat_fk`,`prod_cond_fk`),
  ADD KEY `prod_cond_fk` (`prod_cond_fk`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `tab-prod-genre`
--
ALTER TABLE `tab-prod-genre`
  ADD PRIMARY KEY (`T-P-G`),
  ADD KEY `genre_fk` (`genre_fk`,`prod_fk`),
  ADD KEY `prod_fk` (`prod_fk`);

--
-- Indexes for table `table-prod-author`
--
ALTER TABLE `table-prod-author`
  ADD PRIMARY KEY (`prod-auth-id`),
  ADD KEY `auth_fk` (`auth_fk`,`prod_fk`),
  ADD KEY `prod_fk` (`prod_fk`);

--
-- Indexes for table `tab_athor`
--
ALTER TABLE `tab_athor`
  ADD PRIMARY KEY (`auth_id`);

--
-- Indexes for table `tab_cat`
--
ALTER TABLE `tab_cat`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `tab_genger`
--
ALTER TABLE `tab_genger`
  ADD PRIMARY KEY (`gen_id`);

--
-- Indexes for table `tab_kond`
--
ALTER TABLE `tab_kond`
  ADD PRIMARY KEY (`cond_id`);

--
-- Indexes for table `tab_shelf`
--
ALTER TABLE `tab_shelf`
  ADD PRIMARY KEY (`shelf_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `u_role_fk` (`u_role_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tab-prod-genre`
--
ALTER TABLE `tab-prod-genre`
  MODIFY `T-P-G` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `table-prod-author`
--
ALTER TABLE `table-prod-author`
  MODIFY `prod-auth-id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tab_athor`
--
ALTER TABLE `tab_athor`
  MODIFY `auth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tab_cat`
--
ALTER TABLE `tab_cat`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tab_genger`
--
ALTER TABLE `tab_genger`
  MODIFY `gen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tab_kond`
--
ALTER TABLE `tab_kond`
  MODIFY `cond_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tab_shelf`
--
ALTER TABLE `tab_shelf`
  MODIFY `shelf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`prod_shelf_fk`) REFERENCES `tab_shelf` (`shelf_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`prod_cat_fk`) REFERENCES `tab_cat` (`cat_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`prod_cond_fk`) REFERENCES `tab_kond` (`cond_id`);

--
-- Constraints for table `tab-prod-genre`
--
ALTER TABLE `tab-prod-genre`
  ADD CONSTRAINT `tab-prod-genre_ibfk_1` FOREIGN KEY (`prod_fk`) REFERENCES `products` (`prod_id`),
  ADD CONSTRAINT `tab-prod-genre_ibfk_2` FOREIGN KEY (`genre_fk`) REFERENCES `tab_genger` (`gen_id`);

--
-- Constraints for table `table-prod-author`
--
ALTER TABLE `table-prod-author`
  ADD CONSTRAINT `table-prod-author_ibfk_1` FOREIGN KEY (`prod_fk`) REFERENCES `products` (`prod_id`),
  ADD CONSTRAINT `table-prod-author_ibfk_2` FOREIGN KEY (`auth_fk`) REFERENCES `tab_athor` (`auth_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`u_role_fk`) REFERENCES `roles` (`r_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
