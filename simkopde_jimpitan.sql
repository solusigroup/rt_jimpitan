-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2026 at 07:24 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simkopde_jimpitan`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_master`
--

CREATE TABLE `jadwal_master` (
  `id` int(11) NOT NULL,
  `warga_id` int(11) NOT NULL,
  `hari` varchar(20) NOT NULL,
  `pasaran` varchar(20) NOT NULL,
  `nominal_jimpitan` int(11) NOT NULL DEFAULT 0,
  `status_tugas` enum('Belum Selesai','Sudah Selesai') NOT NULL DEFAULT 'Belum Selesai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_master`
--

INSERT INTO `jadwal_master` (`id`, `warga_id`, `hari`, `pasaran`, `nominal_jimpitan`, `status_tugas`) VALUES
(1, 3, 'Senin', 'Pahing', 0, 'Belum Selesai'),
(2, 11, 'Senin', 'Pon', 0, 'Belum Selesai'),
(3, 1, 'Senin', 'Wage', 0, 'Belum Selesai'),
(4, 9, 'Senin', 'Kliwon', 0, 'Belum Selesai'),
(5, 2, 'Senin', 'Legi', 0, 'Belum Selesai'),
(6, 15, 'Selasa', 'Pahing', 0, 'Belum Selesai'),
(7, 12, 'Selasa', 'Pon', 0, 'Belum Selesai'),
(8, 4, 'Selasa', 'Wage', 0, 'Belum Selesai'),
(9, 8, 'Selasa', 'Kliwon', 0, 'Belum Selesai'),
(10, 6, 'Minggu', 'Pon', 0, 'Belum Selesai'),
(11, 5, 'Rabu', 'Pahing', 0, 'Belum Selesai'),
(12, 13, 'Rabu', 'Pon', 0, 'Belum Selesai'),
(13, 7, 'Rabu', 'Wage', 0, 'Belum Selesai'),
(14, 14, 'Rabu', 'Kliwon', 0, 'Belum Selesai'),
(15, 10, 'Rabu', 'Legi', 0, 'Belum Selesai'),
(16, 16, 'Kamis', 'Pahing', 0, 'Belum Selesai'),
(17, 17, 'Kamis', 'Pon', 0, 'Belum Selesai'),
(18, 34, 'Kamis', 'Wage', 0, 'Belum Selesai'),
(19, 26, 'Kamis', 'Kliwon', 0, 'Belum Selesai'),
(20, 18, 'Kamis', 'Legi', 0, 'Belum Selesai'),
(21, 35, 'Jumat', 'Pahing', 0, 'Belum Selesai'),
(22, 32, 'Jumat', 'Pon', 0, 'Belum Selesai'),
(23, 20, 'Jumat', 'Wage', 0, 'Belum Selesai'),
(24, 25, 'Jumat', 'Kliwon', 0, 'Belum Selesai'),
(25, 28, 'Jumat', 'Legi', 0, 'Belum Selesai'),
(26, 23, 'Sabtu', 'Pahing', 0, 'Belum Selesai'),
(27, 27, 'Sabtu', 'Pon', 0, 'Belum Selesai'),
(28, 21, 'Sabtu', 'Wage', 0, 'Belum Selesai'),
(29, 22, 'Sabtu', 'Kliwon', 0, 'Belum Selesai'),
(30, 31, 'Sabtu', 'Legi', 0, 'Belum Selesai'),
(31, 33, 'Minggu', 'Pahing', 0, 'Belum Selesai'),
(32, 19, 'Selasa', 'Legi', 0, 'Belum Selesai'),
(33, 24, 'Minggu', 'Wage', 0, 'Belum Selesai'),
(34, 30, 'Minggu', 'Kliwon', 0, 'Belum Selesai'),
(35, 29, 'Minggu', 'Legi', 0, 'Belum Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `jimpitan_harian`
--

CREATE TABLE `jimpitan_harian` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `warga_id` int(11) NOT NULL,
  `status` enum('Belum Dikerjakan','Sudah Dikerjakan') DEFAULT 'Belum Dikerjakan',
  `waktu_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nominal` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jimpitan_harian`
--

INSERT INTO `jimpitan_harian` (`id`, `tanggal`, `warga_id`, `status`, `waktu_update`, `nominal`) VALUES
(1, '2026-05-31', 19, 'Sudah Dikerjakan', '2026-05-31 13:17:14', 0),
(7, '2026-06-01', 1, 'Belum Dikerjakan', '2026-06-01 14:23:39', 0),
(8, '2026-06-02', 8, 'Sudah Dikerjakan', '2026-06-07 04:53:31', 17400),
(9, '2026-06-04', 16, 'Belum Dikerjakan', '2026-06-04 11:17:14', 0),
(10, '2026-06-03', 10, 'Sudah Dikerjakan', '2026-06-07 04:53:47', 13000),
(11, '2026-06-05', 32, 'Belum Dikerjakan', '2026-06-06 02:17:38', 0),
(33, '2026-06-06', 21, 'Sudah Dikerjakan', '2026-06-07 04:54:36', 0),
(48, '2026-06-07', 30, 'Belum Dikerjakan', '2026-06-07 04:48:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kas_pengeluaran`
--

CREATE TABLE `kas_pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `nominal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_setting`
--

CREATE TABLE `kas_setting` (
  `id` int(11) NOT NULL,
  `saldo_awal` int(11) NOT NULL DEFAULT 0,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kas_setting`
--

INSERT INTO `kas_setting` (`id`, `saldo_awal`, `keterangan`) VALUES
(1, 3077300, 'Saldo awal kas jimpitan pertama kali');

-- --------------------------------------------------------

--
-- Table structure for table `warga`
--

CREATE TABLE `warga` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_rumah` varchar(50) NOT NULL,
  `no_wa` varchar(50) DEFAULT '',
  `status_aktif` tinyint(1) DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warga`
--

INSERT INTO `warga` (`id`, `nama`, `no_rumah`, `no_wa`, `status_aktif`, `foto`) VALUES
(1, 'ARDITO', 'SMB V-1', '628881707888', 1, 'warga_1780711504_8301.jpg'),
(2, 'EDI PANCA', 'SMB V-2', '6282131120143', 1, 'warga_1780711489_2338.jpg'),
(3, 'AGUS M', 'SMB V-3', '628563495830', 1, 'warga_1780711473_8914.jpg'),
(4, 'JAMAL', 'SMB V-4', '6285608641760', 1, 'warga_1780711463_3936.jpg'),
(5, 'PURWO', 'SMB V-5', '62 85649481334', 1, 'warga_1780711453_4879.jpg'),
(6, 'NUR KHOLIK', 'SMB V-6', '6282331323131', 1, 'warga_1780712431_3509.jpg'),
(7, 'RIZKY', 'SMB V-7', '6285142522506', 1, NULL),
(8, 'JOSE JOHANES', 'SMB V-8', '6285731857730', 1, 'warga_1780711443_1644.jpg'),
(9, 'DEMI', 'SMB V-9', '628979012789', 1, 'warga_1780711434_1558.jpg'),
(10, 'SUYONO', 'SMB V-10', '6281216049260', 1, 'warga_1780711422_4983.jpg'),
(11, 'ANOM', 'SMB V-11', '628123504718', 1, 'warga_1780712517_2261.jpg'),
(12, 'HERU S', 'SMB V-12', '6285732273882', 1, 'warga_1780711400_7403.jpg'),
(13, 'RIZAL (B Yeti)', 'SMB V-13', '6281249665451', 1, NULL),
(14, 'SURYANTO', 'SMB V-14', '6282142147336', 1, 'warga_1780711390_8036.jpg'),
(15, 'HENDRA', 'SMB V-15', '6282231706072', 1, 'warga_1780712464_4773.jpg'),
(16, 'TEGUH', 'SMB V-16', '6285733333981', 1, 'warga_1780711377_2360.jpg'),
(17, 'ZAINAL', 'SMB V-17', '6285732047117', 1, 'warga_1780711365_3813.jpg'),
(18, 'KASIYADI', 'SMB VI-1', '6285732232684', 1, 'warga_1780711354_5247.jpg'),
(19, 'TAUFIK', 'SMB VI-2', '6287852245882', 1, 'warga_1780710999_6144.jpg'),
(20, 'NUR SHOLEH', 'SMB VI-3', '6285646530555', 1, 'warga_1780712414_7269.jpg'),
(21, 'RONY', 'SMB VI-4', '', 1, 'warga_1780711568_6334.jpg'),
(22, 'SURATNO', 'SMB VI-5', '6285812607052', 1, 'warga_1780710987_4307.jpg'),
(23, 'FARID', 'SMB VI-6', '6282244746464', 1, NULL),
(24, 'TOMI', 'SMB VI-7', '6285730048049', 1, 'warga_1780710974_9707.jpg'),
(25, 'RAHMAD', 'SMB VI-8', '6285649443429', 1, 'warga_1780710962_3860.jpg'),
(26, 'KANDEG', 'SMB VI-9', '6285646528384', 1, 'warga_1780710948_6366.jpg'),
(27, 'SAJURI', 'SMB VI-10', '', 1, 'warga_1780710936_9055.jpg'),
(28, 'RIO', 'SMB VI-11', '6281938114680', 1, 'warga_1780712391_7631.jpg'),
(29, 'HILMI', 'SMB VI-12', '', 1, NULL),
(30, 'YULIANTO', 'SMB VI-13', '6285733575101', 1, 'warga_1780710914_3213.jpg'),
(31, 'IQBAL', 'SMB VI-14', '62881036131326', 1, NULL),
(32, 'NANANG', 'SMB VI-15', '6281331429861', 1, 'warga_1780710926_8303.jpg'),
(33, 'SUWARNO', 'SMB VI-19', '', 1, 'warga_1780710887_4825.jpg'),
(34, 'BUDI', 'SMB VI-18', '6281357620426', 1, 'warga_1780712381_7205.jpg'),
(35, 'KURNIAWAN', 'SMB VI-20', '6282141643495', 1, 'warga_1780710666_8613.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal_master`
--
ALTER TABLE `jadwal_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `jimpitan_harian`
--
ALTER TABLE `jimpitan_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_tanggal_warga` (`tanggal`,`warga_id`),
  ADD KEY `warga_id` (`warga_id`);

--
-- Indexes for table `kas_pengeluaran`
--
ALTER TABLE `kas_pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kas_setting`
--
ALTER TABLE `kas_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal_master`
--
ALTER TABLE `jadwal_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `jimpitan_harian`
--
ALTER TABLE `jimpitan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `kas_pengeluaran`
--
ALTER TABLE `kas_pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_setting`
--
ALTER TABLE `kas_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warga`
--
ALTER TABLE `warga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_master`
--
ALTER TABLE `jadwal_master`
  ADD CONSTRAINT `jadwal_master_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `warga` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jimpitan_harian`
--
ALTER TABLE `jimpitan_harian`
  ADD CONSTRAINT `jimpitan_harian_ibfk_1` FOREIGN KEY (`warga_id`) REFERENCES `warga` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
