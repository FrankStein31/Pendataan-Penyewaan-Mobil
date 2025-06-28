-- Menambahkan tabel histori_pembayaran
CREATE TABLE `histori_pembayaran` (
  `id_histori` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `jenis_pembayaran` enum('DP','Pelunasan','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_histori`),
  KEY `id_transaksi` (`id_transaksi`),
  CONSTRAINT `histori_pembayaran_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan histori untuk transaksi existing yang sudah DP
INSERT INTO histori_pembayaran (id_transaksi, jenis_pembayaran, jumlah, tanggal_pembayaran, keterangan)
SELECT id_transaksi, 'DP', jumlah_dp, created_at, 'Pembayaran DP awal'
FROM transaksi 
WHERE status_pembayaran = 'DP' AND jumlah_dp > 0;

-- Menambahkan histori untuk transaksi existing yang sudah Lunas
INSERT INTO histori_pembayaran (id_transaksi, jenis_pembayaran, jumlah, tanggal_pembayaran, keterangan)
SELECT id_transaksi, 'Lunas', total_keseluruhan, created_at, 'Pembayaran lunas'
FROM transaksi 
WHERE status_pembayaran = 'Lunas'; 