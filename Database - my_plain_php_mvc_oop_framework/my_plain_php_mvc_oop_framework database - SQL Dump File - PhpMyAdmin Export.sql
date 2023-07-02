-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 27, 2023 at 02:34 PM
-- Server version: 8.0.28
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_plain_php_mvc_oop_framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `title` varchar(512) NOT NULL,
  `description` longtext,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(2048) DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `image_path`, `create_date`) VALUES
(79, 'srgsag', 'sargasrg', 44.00, '', '2022-06-20 19:15:31'),
(80, 'fsdf', 'fsfsa', 4.00, '', '2022-06-21 13:35:20'),
(83, 'srgsr', 'sgsr', 4.00, '', '2022-06-21 20:07:59'),
(88, 'sdafgsadg', 'hyh', 565.00, '', '2022-06-21 22:28:23'),
(89, 'sdafgsadg', 'hyh', 565.00, '', '2022-06-21 22:28:43'),
(95, 'fsfsfsf', 'fsdfsdfsf', 44444.00, '', '2022-06-28 20:48:20'),
(97, 'TOSTY', 'kitty', 333.00, '', '2022-06-28 20:49:07'),
(99, 'dfufyu', 'dfjudyfuj', 55.00, '', '2022-07-30 09:58:37'),
(100, 'fchjfjcf', 'dfyjudfy', 6.00, '', '2022-07-30 09:58:44'),
(104, 'sadfas', 'afs', 33.00, '', '2022-07-30 10:07:07'),
(105, 'sfsa', 'safasf', 44.00, '', '2022-07-30 10:07:17'),
(106, 'sgszgr', 'atgsr', 44.00, '', '2022-07-30 10:07:22'),
(108, 'Refrigerator', 'Refrigerator description', 26500.00, 'uploadedFiles\\YrpN9w2g\\refrigerator.jpg', '2022-07-30 10:07:31'),
(110, 'Vacuum Cleaner', 'Vacuum Cleaner description', 1250.00, 'uploadedFiles\\mcR1bHjh\\vacuum-cleaner.jpg', '2022-07-30 10:08:07'),
(114, 'iPhone 14', 'iPhone description', 55000.00, 'uploadedFiles\\sm0WOP8q\\iphone.jpg', '2022-07-30 10:08:26'),
(117, 'Beautiful Kitty', 'Beautiful kitty description', 630.00, 'uploadedFiles\\2fr9BNz2\\cat.jpg', '2022-07-30 10:08:39'),
(121, 'Espresso Machine', 'Espresso Machine description', 12744.00, 'uploadedFiles\\Y0a63Xb7\\71msBeYfWpL._AC_UF894,1000_QL80_.jpg', '2022-07-30 10:09:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(512) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `status`, `created_at`) VALUES
(8, 'email@example.com', '$2y$10$SV3s/N.p/Ys5zJAKmfguH.GSQ2COSNMVo0Yw8ZpGPzyT3Q6tpnqpe', 'John', 'Doe', 0, '2022-07-02 23:19:08'),
(9, 'safsa@fgsrg.com', '$2y$10$IWRu3rOe/1LBvWq4ON6tMeR1W4A0v/j4kAFMTiAOxtQAhNkcSv09S', 'dasfaef', 'sfsf', 0, '2022-08-04 01:17:57'),
(10, 'sdfgdzsghsz@fsargsg.com', '$2y$10$D69N4XcBvh9y9EK3mfhSbOw92bYZ6wkNji6jFePm4WYa5hqG6jNf.', 'safsgf', 'sgsrgsarg', 0, '2022-08-04 01:20:39'),
(11, 'test@test.com', '$2y$10$XtSVTbM0nn8LZPdJnc74s.ADUTQdecTBdIcHLt13EHAlUgemyhNKy', 'gsrgs', 'Sangavelli', 0, '2023-06-23 09:00:21'),
(12, 'ahmed.yahya@example.com', '$2y$10$XRBCLDOuIPTcNV3OqZB3MOEvvNI8F63fsHBTIc32Qrrx0.B/HuJ6a', 'Ahmed', 'Yahya', 0, '2023-06-26 14:02:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
