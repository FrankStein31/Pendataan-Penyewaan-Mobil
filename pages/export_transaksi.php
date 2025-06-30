<?php
include '../koneksi/koneksi.php';

// Set header untuk download Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Transaksi_".date('Y-m-d').".xls");

// Ambil parameter filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
$id_mobil = isset($_GET['id_mobil']) ? $_GET['id_mobil'] : '';
$id_penyewa = isset($_GET['id_penyewa']) ? $_GET['id_penyewa'] : '';
$id_penumpang = isset($_GET['id_penumpang']) ? $_GET['id_penumpang'] : '';

// Buat query filter
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

// Query data transaksi
$query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, p.no_telp, m.nama_mobil, m.plat_nomor, d.nama_driver, pn.nama_penumpang 
                            FROM transaksi t 
                            JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                            JOIN mobil m ON t.id_mobil=m.id_mobil
                            LEFT JOIN driver d ON t.id_driver=d.id_driver
                            LEFT JOIN penumpang pn ON t.id_penumpang=pn.id_penumpang
                            $where
                            ORDER BY t.tanggal_mulai DESC");

$data_transaksi = [];
$total_pendapatan = 0;
while($data = mysqli_fetch_array($query)) {
  // Ambil history pembayaran
  $query_pembayaran = mysqli_query($conn, "SELECT * FROM histori_pembayaran WHERE id_transaksi='".$data['id_transaksi']."' ORDER BY tanggal_pembayaran ASC");
  $arr_pembayaran = [];
  while($pembayaran = mysqli_fetch_array($query_pembayaran)) {
    $arr_pembayaran[] = date('d/m/Y', strtotime($pembayaran['tanggal_pembayaran'])).': Rp '.number_format($pembayaran['jumlah'],0,',','.');
  }
  $data['history_pembayaran'] = $arr_pembayaran;
  
  $data_transaksi[] = $data;
  $total_pendapatan += $data['total_keseluruhan'];
}
?>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 1rem;
    font-family: Arial, sans-serif;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    font-size: 11px;
  }
  
  .header {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 15px;
  }
  
  .subheader {
    background-color: #f5f5f5;
    font-weight: bold;
    text-align: center;
  }
  
  .text-center { text-align: center; }
  .text-right { text-align: right; }
  .text-bold { font-weight: bold; }
  
  .success { background-color: #E8F5E9; }
  .warning { background-color: #FFF3E0; }
  
  .border-top { border-top: 2px solid #333; }
  .total { background-color: #f5f5f5; }
</style>

<table>
  <tr class="header">
    <td colspan="15">DATA TRANSAKSI RENTAL MOBIL <?= strtoupper(date('d/m/Y', strtotime($start_date))) ?> - <?= strtoupper(date('d/m/Y', strtotime($end_date))) ?></td>
  </tr>
  <tr class="subheader">
    <td>No</td>
    <td>Penyewa</td>
    <td>Mobil</td>
    <td>Driver</td>
    <td>Penumpang</td>
    <td>Tujuan</td>
    <td>Mulai</td>
    <td>Selesai</td>
    <td>Total Hari</td>
    <td>Day</td>
    <td>Biaya Mobil</td>
    <td>Biaya Driver</td>
    <td>Total</td>
    <td>Status</td>
    <td>Rincian Biaya</td>
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
  ?>
  <tr>
    <td class="text-center"><?= $no++ ?></td>
    <td><div style="font-weight: bold;"><?= $data['nama_penyewa'] ?></div><div style="font-size: 10px; color: #666;"><?= $data['no_telp'] ?></div></td>
    <td><div style="font-weight: bold;"><?= $data['nama_mobil'] ?></div><div style="font-size: 10px; color: #666;"><?= $data['plat_nomor'] ?></div></td>
    <td class="text-center"><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?></td>
    <td class="text-center"><?= $data['nama_penumpang'] ? $data['nama_penumpang'] : '-' ?></td>
    <td><?= $data['tujuan_sewa'] ?></td>
    <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal_mulai'])) ?></td>
    <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal_selesai'])) ?></td>
    <td class="text-center"><?= $data['total_hari'] ?></td>
    <td class="text-center"><?= $data['day'] ?></td>
    <td class="text-right">Rp <?= number_format($data['harga_mobil'],0,',','.') ?></td>
    <td class="text-right">Rp <?= number_format($data['harga_driver'],0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></td>
    <td class="text-center <?= $status_class ?>">
      <div style="font-weight: bold;"><?= $data['status_pembayaran'] ?></div>
      <?php if($data['status_pembayaran'] == 'DP') { ?>
      <div style="font-size: 9px; color: #D32F2F;">Sisa: Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></div>
      <?php } ?>
      <?php if(!empty($data['history_pembayaran'])) { ?>
      <div style="font-size: 9px; color: #1976D2; margin-top: 3px;">History Pembayaran:</div>
      <?php foreach($data['history_pembayaran'] as $pembayaran) { ?>
      <div style="font-size: 9px;"><?= $pembayaran ?></div>
      <?php } ?>
      <?php } ?>
    </td>
    <td><?= $biaya_tambahan_str ?></td>
  </tr>
  <?php } ?>
  <!-- TOTAL ROW -->
  <tr class="total border-top">
    <td colspan="10" class="text-center text-bold">TOTAL</td>
    <td class="text-right text-bold">Rp <?= number_format(array_sum(array_column($data_transaksi, 'harga_mobil')),0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format(array_sum(array_column($data_transaksi, 'harga_driver')),0,',','.') ?></td>
    <td class="text-right text-bold">Rp <?= number_format($total_pendapatan,0,',','.') ?></td>
    <td colspan="2"></td>
  </tr>
</table> 