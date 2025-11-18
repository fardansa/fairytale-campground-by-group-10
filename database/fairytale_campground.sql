-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 18, 2025 at 05:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fairytale_campground`
--

-- --------------------------------------------------------

--
-- Table structure for table `camp`
--

CREATE TABLE `camp` (
  `camp_id` int(11) UNSIGNED NOT NULL,
  `paket_id` int(11) UNSIGNED NOT NULL,
  `nomor_camp` varchar(10) NOT NULL,
  `nomor_loker` varchar(10) NOT NULL,
  `status` enum('tersedia','tidak tersedia') NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `camp`
--

INSERT INTO `camp` (`camp_id`, `paket_id`, `nomor_camp`, `nomor_loker`, `status`) VALUES
(1, 1, 'S01', '1', 'tersedia'),
(2, 1, 'S02', '2', 'tersedia'),
(3, 1, 'S03', '3', 'tersedia'),
(4, 1, 'S04', '4', 'tersedia'),
(5, 1, 'S05', '5', 'tersedia'),
(6, 1, 'S06', '6', 'tersedia'),
(7, 1, 'S07', '7', 'tersedia'),
(8, 1, 'S08', '8', 'tersedia'),
(9, 1, 'S09', '9', 'tersedia'),
(10, 1, 'S10', '10', 'tersedia'),
(11, 2, 'D01', '11', 'tersedia'),
(12, 2, 'D02', '12', 'tersedia'),
(13, 2, 'D03', '13', 'tersedia'),
(14, 2, 'D04', '14', 'tersedia'),
(15, 2, 'D05', '15', 'tersedia'),
(16, 2, 'D06', '16', 'tersedia'),
(17, 2, 'D07', '17', 'tersedia'),
(18, 2, 'D08', '18', 'tersedia'),
(19, 2, 'D09', '19', 'tersedia'),
(20, 2, 'D10', '20', 'tersedia'),
(21, 3, 'F01', '21', 'tersedia'),
(22, 3, 'F02', '22', 'tersedia'),
(23, 3, 'F03', '23', 'tersedia'),
(24, 3, 'F04', '24', 'tersedia'),
(25, 3, 'F05', '25', 'tersedia'),
(26, 3, 'F06', '26', 'tersedia'),
(27, 3, 'F07', '27', 'tersedia'),
(28, 3, 'F08', '28', 'tersedia'),
(29, 3, 'F09', '29', 'tersedia'),
(30, 3, 'F10', '30', 'tersedia'),
(31, 3, 'F11', '31', 'tersedia'),
(32, 3, 'F12', '32', 'tersedia'),
(33, 3, 'F13', '33', 'tersedia'),
(34, 3, 'F14', '34', 'tersedia'),
(35, 3, 'F15', '35', 'tersedia'),
(36, 3, 'F16', '36', 'tersedia'),
(37, 3, 'F17', '37', 'tersedia'),
(38, 3, 'F18', '38', 'tersedia'),
(39, 3, 'F19', '39', 'tersedia'),
(40, 3, 'F20', '40', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `detail_id` int(10) UNSIGNED NOT NULL,
  `pemesanan_id` int(10) UNSIGNED NOT NULL,
  `camp_id` int(11) UNSIGNED NOT NULL,
  `harga_per_malam` int(10) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `paket_id` int(11) UNSIGNED NOT NULL,
  `nama_paket` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `fasilitas` text NOT NULL,
  `kapasitas` int(10) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`paket_id`, `nama_paket`, `deskripsi`, `fasilitas`, `kapasitas`, `harga`) VALUES
(1, 'single', 'jomblo ya?', 'kasur, bantal, dan guling anime', 1, 200),
(2, 'double', 'berduaan', 'coming soon', 2, 300),
(3, 'family', 'untuk keluarga sah', 'coming soon', 5, 600);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int(10) UNSIGNED NOT NULL,
  `pemesanan_id` int(10) UNSIGNED NOT NULL,
  `total_pembayaran` int(11) NOT NULL,
  `status_pembayaran` enum('menunggu_verifikasi','diterima','ditolak') NOT NULL DEFAULT 'menunggu_verifikasi',
  `tanggal_pembayaran` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan_master`
--

CREATE TABLE `pemesanan_master` (
  `pemesanan_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status_pemesanan` enum('menunggu_pembayaran','menunggu_konfirmasi','telah_dibayar','expired','dibatalkan') NOT NULL DEFAULT 'menunggu_pembayaran',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expired_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin1', 'blmtau@gmail.com', 'apahayo123', 'admin', '2025-11-18 07:10:00'),
(2, 'tukang tes', 'testing@gmail.com', 'test123', 'user', '2025-11-18 07:10:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `camp`
--
ALTER TABLE `camp`
  ADD PRIMARY KEY (`camp_id`),
  ADD KEY `fk_paket_for_camp` (`paket_id`);

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `detail_for_booking` (`pemesanan_id`),
  ADD KEY `camp_for_booking` (`camp_id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`paket_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `booking_for_payment` (`pemesanan_id`);

--
-- Indexes for table `pemesanan_master`
--
ALTER TABLE `pemesanan_master`
  ADD PRIMARY KEY (`pemesanan_id`),
  ADD KEY `user_for_booking` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `camp`
--
ALTER TABLE `camp`
  MODIFY `camp_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `detail_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `paket_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pemesanan_master`
--
ALTER TABLE `pemesanan_master`
  MODIFY `pemesanan_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `camp`
--
ALTER TABLE `camp`
  ADD CONSTRAINT `fk_paket_for_camp` FOREIGN KEY (`paket_id`) REFERENCES `camp` (`camp_id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `camp_for_booking` FOREIGN KEY (`camp_id`) REFERENCES `detail_pemesanan` (`detail_id`),
  ADD CONSTRAINT `detail_for_booking` FOREIGN KEY (`pemesanan_id`) REFERENCES `detail_pemesanan` (`detail_id`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `booking_for_payment` FOREIGN KEY (`pemesanan_id`) REFERENCES `pembayaran` (`pembayaran_id`);

--
-- Constraints for table `pemesanan_master`
--
ALTER TABLE `pemesanan_master`
  ADD CONSTRAINT `user_for_booking` FOREIGN KEY (`user_id`) REFERENCES `pemesanan_master` (`pemesanan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
