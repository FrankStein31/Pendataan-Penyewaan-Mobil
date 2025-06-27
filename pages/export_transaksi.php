<?php
include '../koneksi/koneksi.php';
session_start();

// Set header untuk download Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Transaksi.xls");

// Filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$id_mobil = isset($_GET['id_mobil']) ? $_GET['id_mobil'] : '';
$id_penyewa = isset($_GET['id_penyewa']) ? $_GET['id_penyewa'] : '';

$where = "WHERE 1=1";
$where .= " AND DATE(t.tanggal_mulai) BETWEEN '$start_date' AND '$end_date'";
if($id_mobil != '') {
  $where .= " AND t.id_mobil='$id_mobil'";
}
if($id_penyewa != '') {
  $where .= " AND t.id_penyewa='$id_penyewa'";
}

$query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, m.nama_mobil, m.plat_nomor, d.nama_driver 
                              FROM transaksi t 
                              JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                              JOIN mobil m ON t.id_mobil=m.id_mobil
                              LEFT JOIN driver d ON t.id_driver=d.id_driver
                              $where
                              ORDER BY t.tanggal_mulai DESC");
?>

<table border="1">
  <thead>
    <tr>
      <th>No</th>
      <th>Penyewa</th>
      <th>Mobil</th>
      <th>Plat Nomor</th>
      <th>Driver</th>
      <th>Tanggal Mulai</th>
      <th>Tanggal Selesai</th>
      <th>Total Hari</th>
      <th>Biaya Mobil</th>
      <th>Biaya Driver</th>
      <th>Biaya Tambahan</th>
      <th>Total Keseluruhan</th>
      <th>Status Pembayaran</th>
      <th>Jumlah DP</th>
      <th>Sisa Pembayaran</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    while($data = mysqli_fetch_array($query)) {
      // Ambil detail biaya tambahan
      $biaya_tambahan = [];
      $q_detail = mysqli_query($conn, "SELECT db.*, tb.nama_tipe 
                                      FROM detail_biaya db 
                                      JOIN tipe_biaya tb ON db.id_tipe=tb.id_tipe 
                                      WHERE db.id_transaksi='" . $data['id_transaksi'] . "'");
      while($d_detail = mysqli_fetch_array($q_detail)) {
        $biaya_tambahan[] = $d_detail['nama_tipe'] . ': Rp ' . number_format($d_detail['jumlah'],0,',','.');
      }
    ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= $data['nama_penyewa'] ?></td>
      <td><?= $data['nama_mobil'] ?></td>
      <td><?= $data['plat_nomor'] ?></td>
      <td><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?></td>
      <td><?= date('d/m/Y H:i', strtotime($data['tanggal_mulai'])) ?></td>
      <td><?= date('d/m/Y H:i', strtotime($data['tanggal_selesai'])) ?></td>
      <td><?= $data['total_hari'] ?> hari</td>
      <td>Rp <?= number_format($data['total_biaya_mobil'],0,',','.') ?></td>
      <td>Rp <?= number_format($data['total_biaya_driver'],0,',','.') ?></td>
      <td><?= !empty($biaya_tambahan) ? implode("\n", $biaya_tambahan) : '-' ?></td>
      <td>Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></td>
      <td><?= $data['status_pembayaran'] ?></td>
      <td><?= $data['status_pembayaran'] == 'DP' ? 'Rp ' . number_format($data['jumlah_dp'],0,',','.') : '-' ?></td>
      <td><?= $data['status_pembayaran'] == 'DP' ? 'Rp ' . number_format($data['sisa_pembayaran'],0,',','.') : '-' ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table> 