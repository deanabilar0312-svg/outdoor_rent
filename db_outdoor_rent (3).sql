-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2026 at 07:37 AM
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
-- Database: `db_outdoor_rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alat`
--

INSERT INTO `alat` (`id`, `nama_alat`, `harga`, `stok`, `gambar`) VALUES
(3, 'Sepatu Gunung Eiger', 30000, 5, 'default.jpg'),
(4, 'Sleeping Bag Polar', 15000, 10, '1782537995-503_669629038395901697.jpg'),
(6, 'Kompor Portabel dan Gas', 35000, 10, '1782537788-763_Camping cookware.jpg'),
(7, 'Matras Karet', 10000, 8, 'default.jpg'),
(8, 'Flysheet 3X4 Meter', 10000, 6, 'default.jpg'),
(18, 'Tas Osprey 60 Liter', 35000, 10, '1782536765_249_Img.tasosprey60L.jpg.jpeg'),
(19, 'Tenda Dome 4 Orang', 25000, 4, '1782536807_238_Img.tendadome4.jpg.jpeg'),
(21, 'Cooking Set', 20000, 10, '1782537172_603_Camping cookware.jpg'),
(22, 'Kursi Portable', 5000, 15, '1782537257_123_Outdoor Camping Chair Portable Folding Beach Chairs Patio Furniture.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `alat_outdoor`
--

CREATE TABLE `alat_outdoor` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'zaki', 'zaki123', 'user'),
(2, 'dea', 'dea123', 'user'),
(3, 'deva', 'deva123', 'user'),
(4, 'admin_rental', 'admin123', 'admin'),
(5, 'zack_customer', 'user123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alat_outdoor`
--
ALTER TABLE `alat_outdoor`
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
-- AUTO_INCREMENT for table `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `alat_outdoor`
--
ALTER TABLE `alat_outdoor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
