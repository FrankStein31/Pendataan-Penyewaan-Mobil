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

/*Table structure for table `detail_biaya` */

DROP TABLE IF EXISTS `detail_biaya`;

CREATE TABLE `detail_biaya` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_tipe` int NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail`),
  KEY `id_transaksi` (`id_transaksi`),
  KEY `id_tipe` (`id_tipe`),
  CONSTRAINT `detail_biaya_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  CONSTRAINT `detail_biaya_ibfk_2` FOREIGN KEY (`id_tipe`) REFERENCES `tipe_biaya` (`id_tipe`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `detail_biaya` */

insert  into `detail_biaya`(`id_detail`,`id_transaksi`,`id_tipe`,`jumlah`,`created_at`) values 
(1,1,1,50000.00,'2025-06-26 23:04:18'),
(2,1,3,50000.00,'2025-06-26 23:04:18'),
(3,2,1,75000.00,'2025-06-26 23:04:18'),
(4,2,3,75000.00,'2025-06-26 23:04:18'),
(5,3,1,75000.00,'2025-06-26 23:04:18'),
(6,4,1,100000.00,'2025-06-26 23:04:18'),
(7,4,3,50000.00,'2025-06-26 23:04:18'),
(8,4,4,50000.00,'2025-06-26 23:04:18'),
(9,5,1,75000.00,'2025-06-26 23:04:18'),
(10,5,3,50000.00,'2025-06-26 23:04:18'),
(11,1,1,50000.00,'2025-06-26 23:04:18'),
(12,1,3,50000.00,'2025-06-26 23:04:18'),
(13,2,1,150000.00,'2025-06-26 23:04:18'),
(14,2,3,100000.00,'2025-06-26 23:04:18'),
(15,3,1,200000.00,'2025-06-26 23:04:18'),
(16,3,3,100000.00,'2025-06-26 23:04:18'),
(17,4,1,250000.00,'2025-06-26 23:04:18'),
(18,4,3,150000.00,'2025-06-26 23:04:18'),
(19,5,1,300000.00,'2025-06-26 23:04:18'),
(20,5,3,200000.00,'2025-06-26 23:04:18'),
(21,6,1,500000.00,'2025-06-26 23:04:18'),
(22,6,3,300000.00,'2025-06-26 23:04:18'),
(23,7,1,400000.00,'2025-06-26 23:04:18'),
(24,7,3,300000.00,'2025-06-26 23:04:18'),
(25,8,1,600000.00,'2025-06-26 23:04:18'),
(26,8,3,400000.00,'2025-06-26 23:04:18'),
(27,9,1,700000.00,'2025-06-26 23:04:18'),
(28,9,3,500000.00,'2025-06-26 23:04:18'),
(29,10,1,800000.00,'2025-06-26 23:04:18'),
(30,10,3,700000.00,'2025-06-26 23:04:18'),
(31,11,1,600000.00,'2025-06-26 23:04:18'),
(32,11,3,400000.00,'2025-06-26 23:04:18'),
(33,12,1,1000000.00,'2025-06-26 23:04:18'),
(34,12,3,1000000.00,'2025-06-26 23:04:18'),
(35,13,1,1200000.00,'2025-06-26 23:04:18'),
(36,13,3,1000000.00,'2025-06-26 23:04:18'),
(37,14,1,1500000.00,'2025-06-26 23:04:18'),
(38,14,3,1500000.00,'2025-06-26 23:04:18'),
(39,15,1,2000000.00,'2025-06-26 23:04:18'),
(40,15,3,1500000.00,'2025-06-26 23:04:18'),
(49,24,1,50000.00,'2025-06-27 02:21:09'),
(50,24,4,2000.00,'2025-06-27 02:21:09'),
(51,25,3,400000.00,'2025-06-27 02:23:29');

/*Table structure for table `driver` */

DROP TABLE IF EXISTS `driver`;

CREATE TABLE `driver` (
  `id_driver` int NOT NULL AUTO_INCREMENT,
  `nama_driver` varchar(100) NOT NULL,
  `alamat` text,
  `no_telp` varchar(15) DEFAULT NULL,
  `no_sim` varchar(20) DEFAULT NULL,
  `harga_perhari` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_driver`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `driver` */

insert  into `driver`(`id_driver`,`nama_driver`,`alamat`,`no_telp`,`no_sim`,`harga_perhari`,`created_at`) values 
(1,'Joko Widodo','Jl. Melati No. 1, Jakarta','081122334455','SIM-123456',150000.00,'2025-06-26 23:04:17'),
(2,'Agus Setiawan','Jl. Mawar No. 2, Bandung','082233445566','SIM-234567',150000.00,'2025-06-26 23:04:17'),
(3,'Dedi Kurniawan','Jl. Anggrek No. 3, Surabaya','083344556677','SIM-345678',150000.00,'2025-06-26 23:04:17'),
(4,'Eko Prasetyo','Jl. Kenanga No. 4, Semarang','084455667788','SIM-456789',150000.00,'2025-06-26 23:04:17'),
(5,'Bambang Sutrisno','Jl. Dahlia No. 5, Yogyakarta','085566778899','SIM-567890',150000.00,'2025-06-26 23:04:17'),
(6,'Agung','Medan','088837119212','123123',100000.00,'2025-06-27 00:19:02');

/*Table structure for table `mobil` */

DROP TABLE IF EXISTS `mobil`;

CREATE TABLE `mobil` (
  `id_mobil` int NOT NULL AUTO_INCREMENT,
  `nama_mobil` varchar(100) NOT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `tahun` year DEFAULT NULL,
  `plat_nomor` varchar(20) DEFAULT NULL,
  `warna` varchar(30) DEFAULT NULL,
  `harga_sewa_perhari` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mobil`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `mobil` */

insert  into `mobil`(`id_mobil`,`nama_mobil`,`merk`,`tahun`,`plat_nomor`,`warna`,`harga_sewa_perhari`,`created_at`) values 
(1,'Avanza Veloz','Toyota',2022,'B 1234 KLM','Putih',350000.00,'2025-06-26 23:04:17'),
(2,'Xpander Ultimate','Mitsubishi',2023,'B 2345 LMN','Hitam',400000.00,'2025-06-26 23:04:17'),
(3,'Brio RS','Honda',2022,'B 3456 MNO','Merah',300000.00,'2025-06-26 23:04:17'),
(4,'Ertiga Sport','Suzuki',2023,'B 4567 NOP','Silver',350000.00,'2025-06-26 23:04:17'),
(5,'Rush TRD','Toyota',2022,'B 5678 OPQ','Putih',375000.00,'2025-06-26 23:04:17'),
(6,'Civic','Honda',2021,'BK 1234 AG','Merah',200000.00,'2025-06-27 00:18:37');

/*Table structure for table `penyewa` */

DROP TABLE IF EXISTS `penyewa`;

CREATE TABLE `penyewa` (
  `id_penyewa` int NOT NULL AUTO_INCREMENT,
  `nama_penyewa` varchar(100) NOT NULL,
  `alamat` text,
  `no_telp` varchar(15) DEFAULT NULL,
  `no_ktp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_penyewa`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `penyewa` */

insert  into `penyewa`(`id_penyewa`,`nama_penyewa`,`alamat`,`no_telp`,`no_ktp`,`created_at`) values 
(1,'Budi Santoso','Jl. Merdeka No. 123, Jakarta','081234567890','3271234567890001','2025-06-26 23:04:17'),
(2,'Siti Rahayu','Jl. Sudirman No. 45, Bandung','082345678901','3271234567890002','2025-06-26 23:04:17'),
(3,'Ahmad Hidayat','Jl. Gatot Subroto No. 67, Surabaya','083456789012','3271234567890003','2025-06-26 23:04:17'),
(4,'Dewi Lestari','Jl. Diponegoro No. 89, Semarang','084567890123','3271234567890004','2025-06-26 23:04:17'),
(5,'Rudi Hermawan','Jl. Ahmad Yani No. 12, Yogyakarta','085678901234','3271234567890005','2025-06-26 23:04:17'),
(6,'Frankie','Medan','08883866931','123123123','2025-06-27 00:17:18');

/*Table structure for table `tipe_biaya` */

DROP TABLE IF EXISTS `tipe_biaya`;

CREATE TABLE `tipe_biaya` (
  `id_tipe` int NOT NULL AUTO_INCREMENT,
  `nama_tipe` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tipe`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tipe_biaya` */

insert  into `tipe_biaya`(`id_tipe`,`nama_tipe`,`created_at`) values 
(1,'BBM','2025-06-26 23:04:18'),
(2,'Overtime','2025-06-26 23:04:18'),
(3,'Tol','2025-06-26 23:04:18'),
(4,'Parkir','2025-06-26 23:04:18'),
(5,'Penginapan','2025-06-26 23:04:18'),
(6,'coba','2025-06-27 00:11:53');

/*Table structure for table `transaksi` */

DROP TABLE IF EXISTS `transaksi`;

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_penyewa` int NOT NULL,
  `id_mobil` int NOT NULL,
  `id_driver` int DEFAULT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `total_hari` int NOT NULL,
  `total_biaya_mobil` decimal(10,2) NOT NULL,
  `total_biaya_driver` decimal(10,2) DEFAULT '0.00',
  `total_biaya_tambahan` decimal(10,2) DEFAULT '0.00',
  `total_keseluruhan` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('Lunas','DP') NOT NULL,
  `jumlah_dp` decimal(10,2) DEFAULT '0.00',
  `sisa_pembayaran` decimal(10,2) DEFAULT '0.00',
  `status_sewa` enum('Berlangsung','Selesai') DEFAULT 'Berlangsung',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_penyewa` (`id_penyewa`),
  KEY `id_mobil` (`id_mobil`),
  KEY `id_driver` (`id_driver`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa` (`id_penyewa`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`),
  CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_driver`) REFERENCES `driver` (`id_driver`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `transaksi` */

insert  into `transaksi`(`id_transaksi`,`id_penyewa`,`id_mobil`,`id_driver`,`tanggal_mulai`,`tanggal_selesai`,`total_hari`,`total_biaya_mobil`,`total_biaya_driver`,`total_biaya_tambahan`,`total_keseluruhan`,`status_pembayaran`,`jumlah_dp`,`sisa_pembayaran`,`status_sewa`,`created_at`) values 
(1,1,1,1,'2024-01-01 10:00:00','2024-01-03 10:00:00',2,700000.00,300000.00,100000.00,1100000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(2,2,2,2,'2024-01-05 09:00:00','2024-01-07 09:00:00',2,800000.00,300000.00,150000.00,1250000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(3,3,3,NULL,'2024-01-10 08:00:00','2024-01-12 08:00:00',2,600000.00,0.00,75000.00,675000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(4,4,4,3,'2024-01-15 11:00:00','2024-01-18 11:00:00',3,1050000.00,450000.00,200000.00,1700000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(5,5,5,4,'2024-01-20 13:00:00','2024-01-22 13:00:00',2,750000.00,300000.00,125000.00,1175000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(6,1,1,1,'2025-01-05 10:00:00','2025-01-06 10:00:00',1,350000.00,150000.00,100000.00,600000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(7,2,2,2,'2025-01-10 09:00:00','2025-01-14 09:00:00',4,1600000.00,600000.00,250000.00,2450000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(8,3,3,NULL,'2025-01-15 08:00:00','2025-01-21 08:00:00',6,1800000.00,0.00,300000.00,2100000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(9,4,4,3,'2025-01-20 11:00:00','2025-01-27 11:00:00',7,2450000.00,1050000.00,400000.00,3900000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(10,5,5,4,'2025-02-01 13:00:00','2025-02-08 13:00:00',7,2625000.00,1050000.00,500000.00,4175000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(11,1,2,NULL,'2025-02-10 10:00:00','2025-02-24 10:00:00',14,5600000.00,0.00,800000.00,6400000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(12,2,3,5,'2025-02-15 09:00:00','2025-02-28 09:00:00',13,3900000.00,1950000.00,700000.00,6550000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(13,3,4,1,'2025-03-01 08:00:00','2025-03-21 08:00:00',20,7000000.00,3000000.00,1000000.00,11000000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(14,4,1,2,'2025-03-05 11:00:00','2025-03-26 11:00:00',21,7350000.00,3150000.00,1200000.00,11700000.00,'DP',5000000.00,6700000.00,'Berlangsung','2025-06-26 23:04:18'),
(15,5,2,3,'2025-04-01 13:00:00','2025-04-30 13:00:00',29,11600000.00,4350000.00,1500000.00,17450000.00,'DP',8000000.00,9450000.00,'Berlangsung','2025-06-26 23:04:18'),
(16,1,3,NULL,'2025-04-05 10:00:00','2025-04-30 10:00:00',25,7500000.00,0.00,1000000.00,8500000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(17,2,4,4,'2025-05-01 09:00:00','2025-06-15 09:00:00',45,15750000.00,6750000.00,2000000.00,24500000.00,'DP',10000000.00,14500000.00,'Berlangsung','2025-06-26 23:04:18'),
(18,3,5,5,'2025-05-15 08:00:00','2025-06-30 08:00:00',45,16875000.00,6750000.00,2200000.00,25825000.00,'DP',12000000.00,13825000.00,'Berlangsung','2025-06-26 23:04:18'),
(19,4,1,1,'2025-06-25 11:00:00','2025-08-31 11:00:00',90,31500000.00,13500000.00,3000000.00,48000000.00,'DP',20000000.00,28000000.00,'Berlangsung','2025-06-26 23:04:18'),
(20,5,2,2,'2025-06-23 13:00:00','2025-09-14 13:00:00',90,36000000.00,13500000.00,3500000.00,53000000.00,'Lunas',0.00,0.00,'Selesai','2025-06-26 23:04:18'),
(24,3,6,6,'2025-06-27 00:54:00','2025-06-30 00:54:00',3,600000.00,300000.00,52000.00,952000.00,'DP',500000.00,452000.00,'Berlangsung','2025-06-27 00:55:10'),
(25,6,6,6,'2025-06-27 02:22:00','2025-06-28 02:23:00',1,200000.00,100000.00,400000.00,700000.00,'DP',500000.00,200000.00,'Berlangsung','2025-06-27 02:23:29');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id_user`,`username`,`password`,`created_at`) values 
(1,'admin','admin','2025-06-26 23:04:17');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
