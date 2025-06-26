/*
SQLyog Enterprise
MySQL - 8.0.30 : Database - pendataan_penyewaan_mobil
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`pendataan_penyewaan_mobil` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `pendataan_penyewaan_mobil`;

-- Tabel Users
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (username: admin, password: admin)
INSERT INTO `users` (`username`, `password`) VALUES ('admin', 'admin');

-- Tabel Penyewa/Pelanggan
CREATE TABLE `penyewa` (
  `id_penyewa` int NOT NULL AUTO_INCREMENT,
  `nama_penyewa` varchar(100) NOT NULL,
  `alamat` text,
  `no_telp` varchar(15),
  `no_ktp` varchar(20),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penyewa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel penyewa
INSERT INTO `penyewa` (`nama_penyewa`, `alamat`, `no_telp`, `no_ktp`) VALUES
('Budi Santoso', 'Jl. Merdeka No. 123, Jakarta', '081234567890', '3271234567890001'),
('Siti Rahayu', 'Jl. Sudirman No. 45, Bandung', '082345678901', '3271234567890002'),
('Ahmad Hidayat', 'Jl. Gatot Subroto No. 67, Surabaya', '083456789012', '3271234567890003'),
('Dewi Lestari', 'Jl. Diponegoro No. 89, Semarang', '084567890123', '3271234567890004'),
('Rudi Hermawan', 'Jl. Ahmad Yani No. 12, Yogyakarta', '085678901234', '3271234567890005');

-- Tabel Driver
CREATE TABLE `driver` (
  `id_driver` int NOT NULL AUTO_INCREMENT,
  `nama_driver` varchar(100) NOT NULL,
  `alamat` text,
  `no_telp` varchar(15),
  `no_sim` varchar(20),
  `harga_perhari` decimal(10,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_driver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel driver
INSERT INTO `driver` (`nama_driver`, `alamat`, `no_telp`, `no_sim`, `harga_perhari`) VALUES
('Joko Widodo', 'Jl. Melati No. 1, Jakarta', '081122334455', 'SIM-123456', 150000),
('Agus Setiawan', 'Jl. Mawar No. 2, Bandung', '082233445566', 'SIM-234567', 150000),
('Dedi Kurniawan', 'Jl. Anggrek No. 3, Surabaya', '083344556677', 'SIM-345678', 150000),
('Eko Prasetyo', 'Jl. Kenanga No. 4, Semarang', '084455667788', 'SIM-456789', 150000),
('Bambang Sutrisno', 'Jl. Dahlia No. 5, Yogyakarta', '085566778899', 'SIM-567890', 150000);

-- Tabel Mobil
CREATE TABLE `mobil` (
  `id_mobil` int NOT NULL AUTO_INCREMENT,
  `nama_mobil` varchar(100) NOT NULL,
  `merk` varchar(50),
  `tahun` year,
  `plat_nomor` varchar(20),
  `warna` varchar(30),
  `harga_sewa_perhari` decimal(10,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mobil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel mobil
INSERT INTO `mobil` (`nama_mobil`, `merk`, `tahun`, `plat_nomor`, `warna`, `harga_sewa_perhari`) VALUES
('Avanza Veloz', 'Toyota', 2022, 'B 1234 KLM', 'Putih', 350000),
('Xpander Ultimate', 'Mitsubishi', 2023, 'B 2345 LMN', 'Hitam', 400000),
('Brio RS', 'Honda', 2022, 'B 3456 MNO', 'Merah', 300000),
('Ertiga Sport', 'Suzuki', 2023, 'B 4567 NOP', 'Silver', 350000),
('Rush TRD', 'Toyota', 2022, 'B 5678 OPQ', 'Putih', 375000);

-- Tabel Biaya Tambahan
CREATE TABLE `tipe_biaya` (
  `id_tipe` int NOT NULL AUTO_INCREMENT,
  `nama_tipe` varchar(50) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel tipe_biaya
INSERT INTO `tipe_biaya` (`nama_tipe`) VALUES
('BBM'),
('Overtime'),
('Tol'),
('Parkir'),
('Penginapan');

-- Tabel Transaksi Penyewaan
CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_penyewa` int NOT NULL,
  `id_mobil` int NOT NULL,
  `id_driver` int,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `total_hari` int NOT NULL,
  `total_biaya_mobil` decimal(10,2) NOT NULL,
  `total_biaya_driver` decimal(10,2) DEFAULT 0,
  `total_biaya_tambahan` decimal(10,2) DEFAULT 0,
  `total_keseluruhan` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('Lunas','DP') NOT NULL,
  `jumlah_dp` decimal(10,2) DEFAULT 0,
  `sisa_pembayaran` decimal(10,2) DEFAULT 0,
  `status_sewa` enum('Berlangsung','Selesai') DEFAULT 'Berlangsung',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa`(`id_penyewa`),
  FOREIGN KEY (`id_mobil`) REFERENCES `mobil`(`id_mobil`),
  FOREIGN KEY (`id_driver`) REFERENCES `driver`(`id_driver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel transaksi
INSERT INTO `transaksi` (`id_penyewa`, `id_mobil`, `id_driver`, `tanggal_mulai`, `tanggal_selesai`, `total_hari`, `total_biaya_mobil`, `total_biaya_driver`, `total_biaya_tambahan`, `total_keseluruhan`, `status_sewa`, `status_pembayaran`, `jumlah_dp`, `sisa_pembayaran`) VALUES
(1, 1, 1, '2024-01-01 10:00:00', '2024-01-03 10:00:00', 2, 700000, 300000, 100000, 1100000, 'Selesai', 'Lunas', 0, 0),
(2, 2, 2, '2024-01-05 09:00:00', '2024-01-07 09:00:00', 2, 800000, 300000, 150000, 1250000, 'Selesai', 'Lunas', 0, 0),
(3, 3, NULL, '2024-01-10 08:00:00', '2024-01-12 08:00:00', 2, 600000, 0, 75000, 675000, 'Selesai', 'Lunas', 0, 0),
(4, 4, 3, '2024-01-15 11:00:00', '2024-01-18 11:00:00', 3, 1050000, 450000, 200000, 1700000, 'Selesai', 'Lunas', 0, 0),
(5, 5, 4, '2024-01-20 13:00:00', '2024-01-22 13:00:00', 2, 750000, 300000, 125000, 1175000, 'Selesai', 'Lunas', 0, 0),
(1, 1, 1, '2025-01-05 10:00:00', '2025-01-06 10:00:00', 1, 350000, 150000, 100000, 600000, 'Selesai', 'Lunas', 0, 0),
(2, 2, 2, '2025-01-10 09:00:00', '2025-01-14 09:00:00', 4, 1600000, 600000, 250000, 2450000, 'Selesai', 'Lunas', 0, 0),
(3, 3, NULL, '2025-01-15 08:00:00', '2025-01-21 08:00:00', 6, 1800000, 0, 300000, 2100000, 'Selesai', 'Lunas', 0, 0),
(4, 4, 3, '2025-01-20 11:00:00', '2025-01-27 11:00:00', 7, 2450000, 1050000, 400000, 3900000, 'Selesai', 'Lunas', 0, 0),
(5, 5, 4, '2025-02-01 13:00:00', '2025-02-08 13:00:00', 7, 2625000, 1050000, 500000, 4175000, 'Selesai', 'Lunas', 0, 0),
(1, 2, NULL, '2025-02-10 10:00:00', '2025-02-24 10:00:00', 14, 5600000, 0, 800000, 6400000, 'Selesai', 'Lunas', 0, 0),
(2, 3, 5, '2025-02-15 09:00:00', '2025-02-28 09:00:00', 13, 3900000, 1950000, 700000, 6550000, 'Selesai', 'Lunas', 0, 0),
(3, 4, 1, '2025-03-01 08:00:00', '2025-03-21 08:00:00', 20, 7000000, 3000000, 1000000, 11000000, 'Selesai', 'Lunas', 0, 0),
(4, 1, 2, '2025-03-05 11:00:00', '2025-03-26 11:00:00', 21, 7350000, 3150000, 1200000, 11700000, 'Berlangsung', 'DP', 5000000, 6700000),
(5, 2, 3, '2025-04-01 13:00:00', '2025-04-30 13:00:00', 29, 11600000, 4350000, 1500000, 17450000, 'Berlangsung', 'DP', 8000000, 9450000),
(1, 3, NULL, '2025-04-05 10:00:00', '2025-04-30 10:00:00', 25, 7500000, 0, 1000000, 8500000, 'Selesai', 'Lunas', 0, 0),
(2, 4, 4, '2025-05-01 09:00:00', '2025-06-15 09:00:00', 45, 15750000, 6750000, 2000000, 24500000, 'Berlangsung', 'DP', 10000000, 14500000),
(3, 5, 5, '2025-05-15 08:00:00', '2025-06-30 08:00:00', 45, 16875000, 6750000, 2200000, 25825000, 'Berlangsung', 'DP', 12000000, 13825000),
(4, 1, 1, '2025-06-01 11:00:00', '2025-08-31 11:00:00', 90, 31500000, 13500000, 3000000, 48000000, 'Berlangsung', 'DP', 20000000, 28000000),
(5, 2, 2, '2025-06-15 13:00:00', '2025-09-14 13:00:00', 90, 36000000, 13500000, 3500000, 53000000, 'Berlangsung', 'DP', 25000000, 28000000);

-- Tabel Detail Biaya Tambahan
CREATE TABLE `detail_biaya` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_tipe` int NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail`),
  FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi`(`id_transaksi`),
  FOREIGN KEY (`id_tipe`) REFERENCES `tipe_biaya`(`id_tipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample data untuk tabel detail_biaya
INSERT INTO `detail_biaya` (`id_transaksi`, `id_tipe`, `jumlah`) VALUES
(1, 1, 50000),
(1, 3, 50000),
(2, 1, 75000),
(2, 3, 75000),
(3, 1, 75000),
(4, 1, 100000),
(4, 3, 50000),
(4, 4, 50000),
(5, 1, 75000),
(5, 3, 50000),
(1, 1, 50000), (1, 3, 50000),
(2, 1, 150000), (2, 3, 100000),
(3, 1, 200000), (3, 3, 100000),
(4, 1, 250000), (4, 3, 150000),
(5, 1, 300000), (5, 3, 200000),
(6, 1, 500000), (6, 3, 300000),
(7, 1, 400000), (7, 3, 300000),
(8, 1, 600000), (8, 3, 400000),
(9, 1, 700000), (9, 3, 500000),
(10, 1, 800000), (10, 3, 700000),
(11, 1, 600000), (11, 3, 400000),
(12, 1, 1000000), (12, 3, 1000000),
(13, 1, 1200000), (13, 3, 1000000),
(14, 1, 1500000), (14, 3, 1500000),
(15, 1, 2000000), (15, 3, 1500000);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
