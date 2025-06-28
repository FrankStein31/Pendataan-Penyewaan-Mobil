# Update Sistem Pendataan Penyewaan Mobil

## Perubahan yang Telah Ditambahkan

### 1. Field Baru pada Tabel Transaksi

- **id_penumpang**: Foreign key ke tabel penumpang (opsional)
- **tujuan_sewa**: Text field untuk menyimpan tujuan sewa

### 2. Update Database

- Menambahkan field baru ke tabel `transaksi`
- Menambahkan foreign key constraint untuk `id_penumpang`
- Mengupdate data existing dengan tujuan sewa dan penumpang default

### 3. Update Halaman Transaksi (transaksi.php)

#### A. PHP Logic

- Mengupdate fungsi tambah transaksi untuk menyimpan `id_penumpang` dan `tujuan_sewa`
- Mengupdate fungsi edit lengkap transaksi
- Mengupdate query untuk include data penumpang

#### B. Tampilan Data

- Menampilkan informasi penumpang di card transaksi
- Menampilkan tujuan sewa di card transaksi
- Menambahkan informasi penumpang dan tujuan sewa di modal detail

#### C. Form Input

- Menambahkan dropdown pilih penumpang di modal tambah transaksi
- Menambahkan textarea tujuan sewa di modal tambah transaksi
- Menambahkan field yang sama di modal edit lengkap

#### D. Filter

- Menambahkan filter berdasarkan penumpang
- Mengupdate logic filter untuk include penumpang
- Mengupdate kondisi reset filter

#### E. Export Excel

- Mengupdate fungsi export untuk include filter penumpang
- Menambahkan kolom penumpang dan tujuan sewa di export Excel

### 4. File Export (export_transaksi.php)

- Mengupdate query untuk include data penumpang
- Menambahkan kolom penumpang dan tujuan sewa
- Mengupdate header dan format export

### 5. Data Penumpang

- Menambahkan penumpang baru "Agung Prasetyo" untuk testing

## Cara Penggunaan

### 1. Menambah Transaksi Baru

1. Klik tombol "Tambah" di halaman transaksi
2. Pilih penyewa, mobil, dan driver (opsional)
3. **Pilih penumpang (opsional)** - field baru
4. **Masukkan tujuan sewa** - field baru
5. Input harga mobil dan driver
6. Pilih tanggal dan waktu sewa
7. Pilih biaya tambahan (opsional)
8. Pilih status pembayaran
9. Klik "Simpan Transaksi"

### 2. Edit Transaksi

1. Klik tombol "Edit Lengkap" (ikon gear) untuk edit semua data
2. Atau klik tombol "Edit" (ikon edit) untuk edit status pembayaran dan biaya tambahan saja

### 3. Filter Data

1. Gunakan filter tanggal, mobil, penyewa, dan **penumpang** (field baru)
2. Klik "Cari" untuk menerapkan filter
3. Klik "Reset" untuk menghapus semua filter

### 4. Export Excel

1. Terapkan filter yang diinginkan
2. Klik tombol "Export Excel"
3. File akan terdownload dengan data sesuai filter

## Struktur Database

### Tabel Transaksi (Updated)

```sql
CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_penyewa` int NOT NULL,
  `id_mobil` int NOT NULL,
  `id_driver` int DEFAULT NULL,
  `id_penumpang` int DEFAULT NULL,  -- NEW FIELD
  `tujuan_sewa` text,               -- NEW FIELD
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `total_hari` int NOT NULL,
  `harga_mobil` decimal(10,2) NOT NULL,
  `harga_driver` decimal(10,2) DEFAULT '0.00',
  `total_biaya_tambahan` decimal(10,2) DEFAULT '0.00',
  `total_keseluruhan` decimal(10,2) NOT NULL,
  `status_pembayaran` enum('Lunas','DP') NOT NULL,
  `jumlah_dp` decimal(10,2) DEFAULT '0.00',
  `sisa_pembayaran` decimal(10,2) DEFAULT '0.00',
  `status_sewa` enum('Berlangsung','Selesai') DEFAULT 'Berlangsung',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa` (`id_penyewa`),
  FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`),
  FOREIGN KEY (`id_driver`) REFERENCES `driver` (`id_driver`),
  FOREIGN KEY (`id_penumpang`) REFERENCES `penumpang` (`id_penumpang`)  -- NEW CONSTRAINT
);
```

## Catatan

- Field penumpang bersifat opsional (bisa NULL)
- Field tujuan sewa wajib diisi
- Data existing telah diupdate dengan tujuan sewa dan penumpang default
- Semua fitur existing tetap berfungsi normal
