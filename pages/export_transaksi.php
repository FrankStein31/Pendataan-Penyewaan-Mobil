<?php
include '../koneksi/koneksi.php';

// Set header untuk download Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Laporan_Transaksi_Penyewaan_Mobil_' . date('Y-m-d_H-i-s') . '.xls"');

// Ambil parameter filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$id_mobil = isset($_GET['id_mobil']) ? $_GET['id_mobil'] : '';
$id_penyewa = isset($_GET['id_penyewa']) ? $_GET['id_penyewa'] : '';
$id_penumpang = isset($_GET['id_penumpang']) ? $_GET['id_penumpang'] : '';

// Buat query dengan filter
$where = "WHERE 1=1";
$where .= " AND DATE(t.tanggal_mulai) BETWEEN '$start_date' AND '$end_date'";
if($id_mobil != '') {
  $where .= " AND t.id_mobil='$id_mobil'";
}
if($id_penyewa != '') {
  $where .= " AND t.id_penyewa='$id_penyewa'";
}
if($id_penumpang != '') {
  $where .= " AND t.id_penumpang='$id_penumpang'";
}

$query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, p.alamat, p.no_telp, 
                              m.nama_mobil, m.plat_nomor, m.warna, m.tahun,
                              d.nama_driver, d.no_telp as no_telp_driver, pn.nama_penumpang, pn.no_telp as no_telp_penumpang
                              FROM transaksi t 
                              JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                              JOIN mobil m ON t.id_mobil=m.id_mobil
                              LEFT JOIN driver d ON t.id_driver=d.id_driver
                              LEFT JOIN penumpang pn ON t.id_penumpang=pn.id_penumpang
                              $where
                              ORDER BY t.tanggal_mulai DESC");

// Hitung total pendapatan
$total_pendapatan = 0;
$total_dp = 0;
$total_lunas = 0;
$jumlah_transaksi = 0;

// Simpan data untuk perhitungan
$data_transaksi = array();
while($row = mysqli_fetch_array($query)) {
  $data_transaksi[] = $row;
  $total_pendapatan += $row['total_keseluruhan'];
  if($row['status_pembayaran'] == 'DP') {
    $total_dp += $row['jumlah_dp'];
  } else {
    $total_lunas += $row['total_keseluruhan'];
  }
  $jumlah_transaksi++;
}
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
  <meta charset="UTF-8">
  <style>
    table { border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 5px; }
    .header { background-color: #4472C4; color: white; font-weight: bold; text-align: center; }
    .subheader { background-color: #8EAADB; color: white; font-weight: bold; text-align: center; }
    .summary { background-color: #E7E6E6; font-weight: bold; }
    .total { background-color: #FFD700; font-weight: bold; }
    .success { background-color: #90EE90; }
    .warning { background-color: #FFE4B5; }
    .info { background-color: #ADD8E6; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-bold { font-weight: bold; }
    .border-top { border-top: 2px solid #000; }
    .border-bottom { border-bottom: 2px solid #000; }
    /* Atur lebar kolom utama agar proporsional dan rapi */
    table tr.subheader td {
      width: auto;
    }
    table tr.subheader td:nth-child(1) { width: 30px; }
    table tr.subheader td:nth-child(2) { width: 40px; }
    table tr.subheader td:nth-child(3) { width: 120px; }
    table tr.subheader td:nth-child(4) { width: 120px; }
    table tr.subheader td:nth-child(5) { width: 100px; }
    table tr.subheader td:nth-child(6) { width: 100px; }
    table tr.subheader td:nth-child(7) { width: 120px; }
    table tr.subheader td:nth-child(8) { width: 70px; }
    table tr.subheader td:nth-child(9) { width: 70px; }
    table tr.subheader td:nth-child(10) { width: 40px; }
    table tr.subheader td:nth-child(11) { width: 80px; }
    table tr.subheader td:nth-child(12) { width: 80px; }
    table tr.subheader td:nth-child(13) { width: 90px; }
    table tr.subheader td:nth-child(14) { width: 90px; }
    table tr.subheader td:nth-child(15) { width: 60px; }
    table tr.subheader td:nth-child(16) { width: 180px; }
    table tr.subheader td:nth-child(17) { width: 180px; }
  </style>
</head>
<body>

<!-- HEADER LAPORAN -->
<table style="width: 100%; margin-bottom: 20px;">
  <tr>
    <td colspan="17" class="header" style="font-size: 18px; padding: 15px;">LAPORAN TRANSAKSI PENYEWAAN MOBIL</td>
  </tr>
  <tr>
    <td colspan="17" class="subheader" style="font-size: 14px; padding: 10px;">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></td>
  </tr>
  <tr>
    <td colspan="17" class="subheader" style="font-size: 12px; padding: 8px;">Tanggal Export: <?= date('d/m/Y H:i:s') ?> | Total Transaksi: <?= $jumlah_transaksi ?> transaksi</td>
  </tr>
</table>

<!-- RINGKASAN PENDAPATAN (TENGAH) -->
<table style="width: 100%; margin-bottom: 10px;">
  <tr>
    <td colspan="4"></td>
    <td colspan="9" style="text-align: center;">
      <div style="font-size: 16px; font-weight: bold; margin-bottom: 8px;">RINGKASAN PENDAPATAN</div>
      <table style="margin: 0 auto;">
        <tr>
          <td style="background: #E8F5E8; padding: 8px 16px; text-align: center;">
            <div style="font-size: 13px; font-weight: bold;">Total Pendapatan</div>
            <div style="font-size: 15px; color: #2E7D32;">Rp <?= number_format($total_pendapatan,0,',','.') ?></div>
          </td>
          <td style="background: #FFF3E0; padding: 8px 16px; text-align: center;">
            <div style="font-size: 13px; font-weight: bold;">Total DP</div>
            <div style="font-size: 15px; color: #F57C00;">Rp <?= number_format($total_dp,0,',','.') ?></div>
          </td>
          <td style="background: #E3F2FD; padding: 8px 16px; text-align: center;">
            <div style="font-size: 13px; font-weight: bold;">Total Lunas</div>
            <div style="font-size: 15px; color: #1976D2;">Rp <?= number_format($total_lunas,0,',','.') ?></div>
          </td>
          <td style="background: #F3E5F5; padding: 8px 16px; text-align: center;">
            <div style="font-size: 13px; font-weight: bold;">Jumlah Transaksi</div>
            <div style="font-size: 15px; color: #7B1FA2;"><?= $jumlah_transaksi ?> transaksi</div>
          </td>
        </tr>
      </table>
    </td>
    <td colspan="4"></td>
  </tr>
</table>

<!-- TABEL DETAIL TRANSAKSI LENGKAP -->
<table style="width: 100%; margin-bottom: 20px;">
  <tr class="header">
    <td colspan="17" style="font-size: 16px; padding: 12px;">DETAIL TRANSAKSI (LENGKAP)</td>
  </tr>
  <tr class="subheader">
    <td>No</td>
    <td>ID</td>
    <td>Penyewa</td>
    <td>Mobil</td>
    <td>Driver</td>
    <td>Penumpang</td>
    <td>Tujuan</td>
    <td>Mulai</td>
    <td>Selesai</td>
    <td>Hari</td>
    <td>Biaya Mobil</td>
    <td>Biaya Driver</td>
    <td>Biaya Tambahan</td>
    <td>Total</td>
    <td>Status</td>
    <td>Rincian Biaya Tambahan</td>
    <td>Histori Pembayaran</td>
  </tr>
  <?php 
  $no = 1;
  foreach($data_transaksi as $data) { 
    $status_class = ($data['status_pembayaran'] == 'Lunas') ? 'success' : 'warning';
    // Ambil detail biaya tambahan
    $query_detail = mysqli_query($conn, "SELECT db.*, tb.nama_tipe FROM detail_biaya db JOIN tipe_biaya tb ON db.id_tipe=tb.id_tipe WHERE db.id_transaksi='".$data['id_transaksi']."'");
    $arr_biaya = [];
    while($detail = mysqli_fetch_array($query_detail)) {
      $arr_biaya[] = $detail['nama_tipe'].': Rp '.number_format($detail['jumlah'],0,',','.');
    }
    $biaya_tambahan_str = $arr_biaya ? implode(' / ', $arr_biaya) : '-';
    // Ambil histori pembayaran
    $query_histori = mysqli_query($conn, "SELECT * FROM histori_pembayaran WHERE id_transaksi='".$data['id_transaksi']."' ORDER BY tanggal_pembayaran ASC");
    $arr_histori = [];
    while($histori = mysqli_fetch_array($query_histori)) {
      $arr_histori[] = $histori['jenis_pembayaran'].' ('.date('d/m/Y', strtotime($histori['tanggal_pembayaran'])).'): Rp '.number_format($histori['jumlah'],0,',','.');
    }
    $histori_str = $arr_histori ? implode(' / ', $arr_histori) : '-';
  ?>
  <tr>
    <td class="text-center"><?= $no++ ?></td>
    <td class="text-center"><?= $data['id_transaksi'] ?></td>
    <td><div style="font-weight: bold;"><?= $data['nama_penyewa'] ?></div><div style="font-size: 10px; color: #666;"><?= $data['no_telp'] ?></div></td>
    <td><div style="font-weight: bold;"><?= $data['nama_mobil'] ?></div><div style="font-size: 10px; color: #666;"><?= $data['plat_nomor'] ?> | <?= $data['warna'] ?> (<?= $data['tahun'] ?>)</div></td>
    <td class="text-center"><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?><?php if($data['nama_driver']) { ?><div style="font-size: 10px; color: #666;"><?= $data['no_telp_driver'] ?></div><?php } ?></td>
    <td class="text-center"><?= $data['nama_penumpang'] ? $data['nama_penumpang'] : '-' ?><?php if($data['nama_penumpang']) { ?><div style="font-size: 10px; color: #666;"><?= $data['no_telp_penumpang'] ?></div><?php } ?></td>
    <td><?= $data['tujuan_sewa'] ?></td>
    <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal_mulai'])) ?><br><?= date('H:i', strtotime($data['tanggal_mulai'])) ?></td>
    <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal_selesai'])) ?><br><?= date('H:i', strtotime($data['tanggal_selesai'])) ?></td>
    <td class="text-center"><?= $data['total_hari'] ?> hari</td>
    <td class="text-right">Rp <?= number_format($data['harga_mobil'],0,',','.') ?></td>
    <td class="text-right">Rp <?= number_format($data['harga_driver'],0,',','.') ?></td>
    <td class="text-right">Rp <?= number_format($data['total_biaya_tambahan'],0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></td>
    <td class="text-center <?= $status_class ?>">
      <div style="font-weight: bold;"><?= $data['status_pembayaran'] ?></div>
      <div style="font-size: 10px;"><?= $data['status_sewa'] ?></div>
      <?php if($data['status_pembayaran'] == 'DP') { ?>
      <div style="font-size: 9px; color: #D32F2F;">Sisa: Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></div>
      <?php } ?>
    </td>
    <td><?= $biaya_tambahan_str ?></td>
    <td><?= $histori_str ?></td>
  </tr>
  <?php } ?>
  <!-- TOTAL ROW -->
  <tr class="total border-top">
    <td colspan="10" class="text-center text-bold">TOTAL</td>
    <td class="text-right text-bold">Rp <?= number_format(array_sum(array_column($data_transaksi, 'harga_mobil')),0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format(array_sum(array_column($data_transaksi, 'harga_driver')),0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format(array_sum(array_column($data_transaksi, 'total_biaya_tambahan')),0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format($total_pendapatan,0,',','.') ?></td>
    <td colspan="3"></td>
  </tr>
</table>

<!-- FOOTER -->
<table style="width: 100%; margin-top: 30px;">
  <tr>
    <td colspan="17" style="text-align: center; padding: 20px; border-top: 2px solid #000;">
      <div style="font-size: 12px; color: #666;">
        Laporan ini dibuat secara otomatis oleh Sistem Pendataan Penyewaan Mobil<br>
        © <?= date('Y') ?> - Semua hak cipta dilindungi
      </div>
    </td>
  </tr>
</table>

</body>
</html> 