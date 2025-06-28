<?php
include '../koneksi/koneksi.php';

// Set header untuk download Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Data_Transaksi_' . date('Y-m-d') . '.xls"');

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

$query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, m.nama_mobil, m.plat_nomor, d.nama_driver, pn.nama_penumpang 
                              FROM transaksi t 
                              JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                              JOIN mobil m ON t.id_mobil=m.id_mobil
                              LEFT JOIN driver d ON t.id_driver=d.id_driver
                              LEFT JOIN penumpang pn ON t.id_penumpang=pn.id_penumpang
                              $where
                              ORDER BY t.tanggal_mulai DESC");
?>

<table border="1">
  <thead>
    <tr>
      <th colspan="12" style="text-align: center; font-size: 16px; font-weight: bold;">DATA TRANSAKSI PENYEWAAN MOBIL</th>
    </tr>
    <tr>
      <th colspan="12" style="text-align: center;">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> - <?= date('d/m/Y', strtotime($end_date)) ?></th>
    </tr>
    <tr>
      <th>No</th>
      <th>ID Transaksi</th>
      <th>Penyewa</th>
      <th>Mobil</th>
      <th>Driver</th>
      <th>Penumpang</th>
      <th>Tujuan Sewa</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Total Hari</th>
      <th>Total Biaya</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $no = 1;
    while($data = mysqli_fetch_array($query)) { 
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $data['id_transaksi'] ?></td>
      <td><?= $data['nama_penyewa'] ?></td>
      <td><?= $data['nama_mobil'] ?> - <?= $data['plat_nomor'] ?></td>
      <td><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?></td>
      <td><?= $data['nama_penumpang'] ? $data['nama_penumpang'] : '-' ?></td>
      <td><?= $data['tujuan_sewa'] ?></td>
      <td><?= date('d/m/Y H:i', strtotime($data['tanggal_mulai'])) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($data['tanggal_selesai'])) ?></td>
      <td><?= $data['total_hari'] ?> hari</td>
      <td>Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></td>
      <td><?= $data['status_pembayaran'] ?> - <?= $data['status_sewa'] ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table> 