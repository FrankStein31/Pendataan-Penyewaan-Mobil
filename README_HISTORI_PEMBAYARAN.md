# Fitur Histori Pembayaran

## Deskripsi

Fitur ini memungkinkan sistem untuk menyimpan dan menampilkan riwayat pembayaran transaksi, termasuk pembayaran DP dan pelunasan. Histori pembayaran akan tetap tersimpan meskipun status pembayaran diubah.

## Tabel Baru: histori_pembayaran

```sql
CREATE TABLE `histori_pembayaran` (
  `id_histori` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `jenis_pembayaran` enum('DP','Pelunasan','Lunas') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tanggal_pembayaran` datetime NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_histori`),
  FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`)
);
```

## Jenis Pembayaran

1. **DP**: Pembayaran uang muka
2. **Pelunasan**: Pembayaran sisa setelah DP
3. **Lunas**: Pembayaran penuh sekaligus

## Skenario Histori Pembayaran

### 1. Transaksi Baru dengan Status DP

- Sistem otomatis menyimpan histori pembayaran DP
- Keterangan: "Pembayaran DP awal"

### 2. Transaksi Baru dengan Status Lunas

- Sistem otomatis menyimpan histori pembayaran lunas
- Keterangan: "Pembayaran lunas"

### 3. Edit Status dari DP ke Lunas

- Sistem menyimpan histori DP lama dengan keterangan "Pembayaran DP awal"
- Sistem menyimpan histori pelunasan dengan keterangan "Pelunasan sisa pembayaran"

### 4. Edit Status dari Lunas ke DP

- Sistem menyimpan histori DP baru dengan keterangan "Pembayaran DP"

### 5. Edit Jumlah DP (tetap status DP)

- Sistem menyimpan histori update dengan keterangan "Update pembayaran DP"

## Tampilan Histori

### 1. Indikator di Card Transaksi

- Badge dengan ikon history muncul jika ada lebih dari 1 histori pembayaran
- Memberikan petunjuk visual bahwa transaksi memiliki riwayat pembayaran

### 2. Detail Histori di Modal Detail

- Tabel lengkap menampilkan semua histori pembayaran
- Informasi: No, Tanggal, Jenis Pembayaran, Jumlah, Keterangan
- Diurutkan berdasarkan tanggal pembayaran (terlama ke terbaru)

## Cara Penggunaan

### 1. Melihat Histori Pembayaran

1. Klik tombol detail (ikon list) pada transaksi
2. Scroll ke bagian "Histori Pembayaran"
3. Lihat riwayat lengkap pembayaran

### 2. Edit Status Pembayaran

1. Klik tombol edit (ikon edit) atau edit lengkap (ikon gear)
2. Ubah status pembayaran
3. Sistem otomatis menyimpan histori perubahan

### 3. Identifikasi Transaksi dengan Histori

- Transaksi dengan badge history (ikon history) memiliki riwayat pembayaran
- Transaksi tanpa badge history hanya memiliki 1 kali pembayaran

## Keuntungan

1. **Audit Trail**: Mencatat semua perubahan status pembayaran
2. **Transparansi**: Pelanggan dapat melihat riwayat pembayaran lengkap
3. **Pelaporan**: Memudahkan pembuatan laporan keuangan
4. **Pencarian**: Dapat melacak kapan pembayaran dilakukan

## File yang Diupdate

1. `pages/transaksi.php` - Logika histori pembayaran
2. `pendataan_penyewaan_mobil.sql` - Struktur database
3. `update_histori_pembayaran.sql` - Script update database

## Catatan Penting

- Histori pembayaran tidak dapat dihapus untuk menjaga integritas data
- Setiap perubahan status pembayaran akan menambah record baru di histori
- Data existing akan otomatis diisi histori berdasarkan status pembayaran saat ini
