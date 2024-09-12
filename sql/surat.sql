-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 04, 2024 at 05:02 PM
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
-- Database: `surat`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `fungsi` varchar(255) DEFAULT NULL,
  `kode_kegiatan` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `tujuan_kegiatan` varchar(255) DEFAULT NULL,
  `jadwal` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `fungsi`, `kode_kegiatan`, `activity`, `nomor_surat`, `tanggal_surat`, `tujuan_kegiatan`, `jadwal`) VALUES
(1, 'Statistik Distribusi', '123.234', 'Survei Harga Konsumen', 'B-123', '2024-09-05', 'Pencacahan Survei Harga Konsumen', '1-30 September'),
(2, 'Neraca Wilayah', '123.234', 'Survei Harga Produsen', 'B-234', '2024-09-04', 'Survei Bulan Juli', '1-30 September');

-- --------------------------------------------------------

--
-- Table structure for table `activity_dates`
--

CREATE TABLE `activity_dates` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `tujuan_kegiatan` varchar(255) DEFAULT NULL,
  `jadwal` varchar(255) DEFAULT NULL,
  `tanggal_surat` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_dates`
--

INSERT INTO `activity_dates` (`id`, `activity_id`, `date`, `nomor_surat`, `tujuan_kegiatan`, `jadwal`, `tanggal_surat`, `created_by`) VALUES
(1, 1, '2024-01-05', 'B-123', 'Pencacahan Survai Harga Konsumen', '1-30 Sptember', '2024-09-05', '198812345678901234'),
(2, 1, '2024-01-05', 'B-123', 'Pencacahan Survai Harga Konsumen', '1-30 Sptember', '2024-09-05', '198912345678901234'),
(3, 1, '2024-09-06', 'B-123', 'Pencacahan Survai Harga Konsumen', '1-30 Sptember', '2024-09-05', '198912345678901234'),
(4, 1, '2024-01-08', 'B-123', 'Pencacahan Survai Harga Konsumen', '1-30 Sptember', '2024-09-05', '198812345678901234'),
(5, 2, '2024-01-10', 'B-234', 'Survei Bulan Juli', '1-30 Sptember', '2024-09-04', '198812345678901234');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nip_lama` varchar(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `golongan` varchar(10) DEFAULT NULL,
  `pangkat` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `nip_lama`, `nama`, `jabatan`, `golongan`, `pangkat`, `username`, `password`, `level`) VALUES
(1, '198712345678901234', '198712345678900123', 'Andi Wijaya', 'Kepala BPS', 'III/a', 'Penata Muda', 'andi', 'andi', 'Administrator'),
(2, '198812345678901234', '198812345678900124', 'Budi Santoso', 'Staf Pelaksana', 'II/c', 'Pengatur Muda Tingkat I', 'budi', 'budi', 'Pegawai'),
(3, '198912345678901234', '198912345678900125', 'Citra Dewi', 'Kasubbag Umum', 'III/b', 'Penata Muda Tingkat I', 'citra', 'citra', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `ttd` varchar(20) NOT NULL,
  `ppk` varchar(20) NOT NULL,
  `bendahara` varchar(20) NOT NULL,
  `namakabkota` varchar(100) NOT NULL,
  `biaya` decimal(10,0) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `ttd`, `ppk`, `bendahara`, `namakabkota`, `biaya`, `alamat`, `url`) VALUES
(1, 'Rahmad Iswanto', 'Riski Sayuti Rahayu,', 'Atina Khoirunnisa\', ', 'BPS Kabupaten Wonogiri', 150000, 'Jl. Pelem II No. 8 Wonogiri 57612 ', 'Telp (0273) 321055, Faks (0273) 321055, E-Mail : bps3312@bps.go.id');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_dates`
--
ALTER TABLE `activity_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activity_dates`
--
ALTER TABLE `activity_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
