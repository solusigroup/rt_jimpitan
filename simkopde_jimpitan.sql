-- Jimpitan RT Digital Database Export
-- Generated via PHP CLI Export Script

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `warga`;
CREATE TABLE `warga` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `no_rumah` VARCHAR(50) NOT NULL,
  `no_wa` VARCHAR(50) DEFAULT '',
  `foto` VARCHAR(255) DEFAULT NULL,
  `status_aktif` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `warga` (`id`, `nama`, `no_rumah`, `no_wa`, `status_aktif`) VALUES
(1, 'ARDITO', 'SMB V-1', '628881707888', 1),
(2, 'EDI', 'SMB V-2', '', 1),
(3, 'AGUS M', 'SMB V-3', '628563495830', 1),
(4, 'JAMAL', 'SMB V-4', '6285608641760', 1),
(5, 'PURWO', 'SMB V-5', '', 1),
(6, 'NUR KHOLIK', 'SMB V-6', '', 1),
(7, 'RIZKY', 'SMB V-7', '', 1),
(8, 'JOSE JOHANES', 'SMB V-8', '6285731857730', 1),
(9, 'DEMI', 'SMB V-9', '', 1),
(10, 'SUYONO', 'SMB V-10', '', 1),
(11, 'ANOM', 'SMB V-11', '628123504718', 1),
(12, 'HERU S', 'SMB V-12', '6285732273882', 1),
(13, 'RIZAL (B Yeti)', 'SMB V-13', '', 1),
(14, 'SURYANTO', 'SMB V-14', '', 1),
(15, 'HENDRA', 'SMB V-15', '', 1),
(16, 'TEGUH', 'SMB V-16', '', 1),
(17, 'ZAINAL', 'SMB V-17', '', 1),
(18, 'KASIYADI', 'SMB VI-1', '6285732232684', 1),
(19, 'TAUFIK', 'SMB VI-2', '6287852245882', 1),
(20, 'NUR SHOLEH', 'SMB VI-3', '', 1),
(21, 'RONY', 'SMB VI-4', '', 1),
(22, 'SURATNO', 'SMB VI-5', '', 1),
(23, 'FARID', 'SMB VI-6', '6282244746464', 1),
(24, 'TOMI', 'SMB VI-7', '', 1),
(25, 'RAHMAD', 'SMB VI-8', '', 1),
(26, 'KANDEG', 'SMB VI-9', '', 1),
(27, 'SAJURI', 'SMB VI-10', '', 1),
(28, 'RIO', 'SMB VI-11', '', 1),
(29, 'HILMI', 'SMB VI-12', '', 1),
(30, 'YULIANTO', 'SMB VI-13', '', 1),
(31, 'IQBAL', 'SMB VI-14', '62881036131326', 1),
(32, 'NANANG', 'SMB VI-15', '', 1),
(33, 'SUWARNO', 'SMB VI-16', '', 1),
(34, 'BUDI', 'SMB VI-17', '6281357620426', 1),
(35, 'KURNIAWAN', 'SMB VI-18', '6282141643495', 1);

DROP TABLE IF EXISTS `jadwal_master`;
CREATE TABLE `jadwal_master` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `warga_id` INT NOT NULL,
  `hari` VARCHAR(20) NOT NULL,
  `pasaran` VARCHAR(20) NOT NULL,
  FOREIGN KEY (`warga_id`) REFERENCES `warga` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jadwal_master` (`id`, `warga_id`, `hari`, `pasaran`) VALUES
(1, 3, 'Senin', 'Pahing'),
(2, 11, 'Senin', 'Pon'),
(3, 1, 'Senin', 'Wage'),
(4, 9, 'Senin', 'Kliwon'),
(5, 2, 'Senin', 'Legi'),
(6, 15, 'Selasa', 'Pahing'),
(7, 12, 'Selasa', 'Pon'),
(8, 4, 'Selasa', 'Wage'),
(9, 8, 'Selasa', 'Kliwon'),
(10, 6, 'Selasa', 'Legi'),
(11, 5, 'Rabu', 'Pahing'),
(12, 13, 'Rabu', 'Pon'),
(13, 7, 'Rabu', 'Wage'),
(14, 14, 'Rabu', 'Kliwon'),
(15, 10, 'Rabu', 'Legi'),
(16, 16, 'Kamis', 'Pahing'),
(17, 17, 'Kamis', 'Pon'),
(18, 34, 'Kamis', 'Wage'),
(19, 26, 'Kamis', 'Kliwon'),
(20, 18, 'Kamis', 'Legi'),
(21, 35, 'Jumat', 'Pahing'),
(22, 32, 'Jumat', 'Pon'),
(23, 20, 'Jumat', 'Wage'),
(24, 25, 'Jumat', 'Kliwon'),
(25, 28, 'Jumat', 'Legi'),
(26, 23, 'Sabtu', 'Pahing'),
(27, 27, 'Sabtu', 'Pon'),
(28, 21, 'Sabtu', 'Wage'),
(29, 22, 'Sabtu', 'Kliwon'),
(30, 31, 'Sabtu', 'Legi'),
(31, 33, 'Minggu', 'Pahing'),
(32, 19, 'Minggu', 'Pon'),
(33, 24, 'Minggu', 'Wage'),
(34, 30, 'Minggu', 'Kliwon'),
(35, 29, 'Minggu', 'Legi');

SET FOREIGN_KEY_CHECKS=1;
