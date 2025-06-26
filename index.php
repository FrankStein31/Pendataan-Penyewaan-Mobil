<?php
include 'koneksi/koneksi.php';
session_start();

// Cek login
if(!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
// Statistik summary box
$qTransaksiHariIni = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(created_at) = CURDATE()");
$dataTransaksiHariIni = mysqli_fetch_assoc($qTransaksiHariIni);

$qMobilTersedia = mysqli_query($conn, "SELECT COUNT(*) as total FROM mobil");
$dataMobilTersedia = mysqli_fetch_assoc($qMobilTersedia);

$qDriverTersedia = mysqli_query($conn, "SELECT COUNT(*) as total FROM driver");
$dataDriverTersedia = mysqli_fetch_assoc($qDriverTersedia);

$qTotalPenyewa = mysqli_query($conn, "SELECT COUNT(*) as total FROM penyewa");
$dataTotalPenyewa = mysqli_fetch_assoc($qTotalPenyewa);

// Hitung total pendapatan
$qTotalPendapatan = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi");
$dataTotalPendapatan = mysqli_fetch_assoc($qTotalPendapatan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistem Pendataan Penyewaan Mobil</title>
  <link href="styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="styling/assets/css/argon-dashboard.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <div class="d-flex align-items-center justify-content-center">
        <i class="fa fa-car text-primary me-2" style="font-size: 24px;"></i>
        <h4 class="m-0">Rental Mobil</h4>
      </div>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-dashboard text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/penyewa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penyewa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/mobil.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-car text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Mobil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/driver.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-id-card text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Driver</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/tipe_biaya.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-list text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tipe Biaya</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages/transaksi.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-money text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Transaksi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-sign-out text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  
  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3 text-center">
              <h1 class="display-4 mb-0 text-primary"><?= $dataTransaksiHariIni['total'] ?></h1>
              <div class="text-xs text-muted">Transaksi Hari Ini</div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3 text-center">
              <h1 class="display-4 mb-0 text-success"><?= $dataMobilTersedia['total'] ?></h1>
              <div class="text-xs text-muted">Mobil Tersedia</div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3 text-center">
              <h1 class="display-4 mb-0 text-info"><?= $dataDriverTersedia['total'] ?></h1>
              <div class="text-xs text-muted">Driver Tersedia</div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3 text-center">
              <h1 class="display-4 mb-0 text-warning"><?= $dataTotalPenyewa['total'] ?></h1>
              <div class="text-xs text-muted">Total Penyewa</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-12 mb-2">
          <div class="card">
            <div class="card-body text-center">
              <h5 class="mb-0">Total Pendapatan</h5>
              <h2 class="text-success">Rp <?= number_format($dataTotalPendapatan['total'],0,',','.') ?></h2>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Mingguan</h6>
            </div>
            <div class="card-body">
              <canvas id="transaksiMingguanChart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Bulanan</h6>
            </div>
            <div class="card-body">
              <canvas id="transaksiBulananChart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Tahunan</h6>
            </div>
            <div class="card-body">
              <canvas id="transaksiTahunanChart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Per Mobil</h6>
            </div>
            <div class="card-body">
              <canvas id="transaksiMobilChart" class="chart-canvas" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Harian</h6></div>
            <div class="card-body"><canvas id="pendapatanHarianChart" class="chart-canvas" height="300"></canvas></div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Bulanan</h6></div>
            <div class="card-body"><canvas id="pendapatanBulananChart" class="chart-canvas" height="300"></canvas></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Tahunan</h6></div>
            <div class="card-body"><canvas id="pendapatanTahunanChart" class="chart-canvas" height="300"></canvas></div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Per Mobil</h6></div>
            <div class="card-body"><canvas id="pendapatanMobilChart" class="chart-canvas" height="300"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="styling/assets/js/core/popper.min.js"></script>
  <script src="styling/assets/js/core/bootstrap.min.js"></script>
  <script src="styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="styling/assets/js/plugins/smooth-scrollbar.min.js"></script>

  <?php
  // Data untuk grafik mingguan
  $labels_mingguan = [];
  $data_mingguan = [];

  $query_mingguan = mysqli_query($conn, "SELECT DATE_FORMAT(created_at, '%W') as hari, COUNT(*) as total 
                                        FROM transaksi 
                                        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                        GROUP BY DATE(created_at), DATE_FORMAT(created_at, '%W')
                                        ORDER BY DATE(created_at)");
  while($row = mysqli_fetch_assoc($query_mingguan)) {
    $labels_mingguan[] = $row['hari'];
    $data_mingguan[] = $row['total'];
  }

  // Data untuk grafik bulanan
  $labels_bulanan = [];
  $data_bulanan = [];

  $query_bulanan = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_mulai, '%M') as bulan, COUNT(*) as total 
                                       FROM transaksi 
                                       WHERE YEAR(tanggal_mulai) = YEAR(CURRENT_DATE())
                                       GROUP BY MONTH(tanggal_mulai), DATE_FORMAT(tanggal_mulai, '%M')
                                       ORDER BY MONTH(tanggal_mulai)");
  while($row = mysqli_fetch_assoc($query_bulanan)) {
    $labels_bulanan[] = $row['bulan'];
    $data_bulanan[] = $row['total'];
  }

  // Data untuk grafik tahunan
  $labels_tahunan = [];
  $data_tahunan = [];

  $query_tahunan = mysqli_query($conn, "SELECT YEAR(tanggal_mulai) as tahun, COUNT(*) as total 
                                       FROM transaksi 
                                       GROUP BY YEAR(tanggal_mulai)
                                       ORDER BY YEAR(tanggal_mulai)");
  while($row = mysqli_fetch_assoc($query_tahunan)) {
    $labels_tahunan[] = $row['tahun'];
    $data_tahunan[] = $row['total'];
  }

  // Data untuk grafik per mobil
  $labels_mobil = [];
  $data_mobil = [];

  $query_mobil = mysqli_query($conn, "SELECT m.nama_mobil, COUNT(t.id_transaksi) as total 
                                     FROM mobil m 
                                     LEFT JOIN transaksi t ON m.id_mobil = t.id_mobil 
                                     GROUP BY m.id_mobil, m.nama_mobil
                                     ORDER BY total DESC");
  while($row = mysqli_fetch_assoc($query_mobil)) {
    $labels_mobil[] = $row['nama_mobil'];
    $data_mobil[] = $row['total'];
  }

  // Data grafik pendapatan harian (7 hari terakhir)
  $labels_pendapatan_harian = [];
  $data_pendapatan_harian = [];
  $qPendapatanHarian = mysqli_query($conn, "SELECT DATE(tanggal_mulai) as tgl, SUM(total_keseluruhan) as total FROM transaksi WHERE tanggal_mulai >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(tanggal_mulai) ORDER BY DATE(tanggal_mulai)");
  while($row = mysqli_fetch_assoc($qPendapatanHarian)) {
    $labels_pendapatan_harian[] = date('d M', strtotime($row['tgl']));
    $data_pendapatan_harian[] = (float)$row['total'];
  }
  // Data grafik pendapatan bulanan (tahun ini)
  $labels_pendapatan_bulanan = [];
  $data_pendapatan_bulanan = [];
  $qPendapatanBulanan = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_mulai, '%M') as bulan, SUM(total_keseluruhan) as total FROM transaksi WHERE YEAR(tanggal_mulai) = YEAR(CURRENT_DATE()) GROUP BY MONTH(tanggal_mulai), DATE_FORMAT(tanggal_mulai, '%M') ORDER BY MONTH(tanggal_mulai)");
  while($row = mysqli_fetch_assoc($qPendapatanBulanan)) {
    $labels_pendapatan_bulanan[] = $row['bulan'];
    $data_pendapatan_bulanan[] = (float)$row['total'];
  }
  // Data grafik pendapatan tahunan
  $labels_pendapatan_tahunan = [];
  $data_pendapatan_tahunan = [];
  $qPendapatanTahunan = mysqli_query($conn, "SELECT YEAR(tanggal_mulai) as tahun, SUM(total_keseluruhan) as total FROM transaksi GROUP BY YEAR(tanggal_mulai) ORDER BY YEAR(tanggal_mulai)");
  while($row = mysqli_fetch_assoc($qPendapatanTahunan)) {
    $labels_pendapatan_tahunan[] = $row['tahun'];
    $data_pendapatan_tahunan[] = (float)$row['total'];
  }
  // Data grafik pendapatan per mobil
  $labels_pendapatan_mobil = [];
  $data_pendapatan_mobil = [];
  $qPendapatanMobil = mysqli_query($conn, "SELECT m.nama_mobil, SUM(t.total_keseluruhan) as total FROM mobil m LEFT JOIN transaksi t ON m.id_mobil = t.id_mobil GROUP BY m.id_mobil, m.nama_mobil ORDER BY total DESC");
  while($row = mysqli_fetch_assoc($qPendapatanMobil)) {
    $labels_pendapatan_mobil[] = $row['nama_mobil'];
    $data_pendapatan_mobil[] = (float)$row['total'];
  }
  ?>

  <script>
    // Fungsi untuk membuat grafik
    function createChart(ctx, type, labels, data, label, color) {
      return new Chart(ctx, {
        type: type,
        data: {
          labels: labels,
          datasets: [{
            label: label,
            data: data,
            backgroundColor: color.background,
            borderColor: color.border,
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              }
            }
          }
        }
      });
    }

    // Grafik Transaksi Mingguan (Line)
    var ctxMingguan = document.getElementById("transaksiMingguanChart").getContext("2d");
    createChart(ctxMingguan, "line", 
      <?= json_encode($labels_mingguan) ?>, 
      <?= json_encode($data_mingguan) ?>,
      "Transaksi Mingguan",
      {
        background: 'rgba(66, 153, 225, 0.5)',
        border: 'rgb(66, 153, 225)'
      }
    );

    // Grafik Transaksi Bulanan (Line)
    var ctxBulanan = document.getElementById("transaksiBulananChart").getContext("2d");
    createChart(ctxBulanan, "line", 
      <?= json_encode($labels_bulanan) ?>, 
      <?= json_encode($data_bulanan) ?>,
      "Transaksi Bulanan",
      {
        background: 'rgba(72, 187, 120, 0.5)',
        border: 'rgb(72, 187, 120)'
      }
    );

    // Grafik Transaksi Tahunan (Line)
    var ctxTahunan = document.getElementById("transaksiTahunanChart").getContext("2d");
    createChart(ctxTahunan, "line", 
      <?= json_encode($labels_tahunan) ?>, 
      <?= json_encode($data_tahunan) ?>,
      "Transaksi Tahunan",
      {
        background: 'rgba(237, 137, 54, 0.5)',
        border: 'rgb(237, 137, 54)'
      }
    );

    // Grafik Transaksi Per Mobil (Bar)
    var ctxMobil = document.getElementById("transaksiMobilChart").getContext("2d");
    createChart(ctxMobil, "bar", 
      <?= json_encode($labels_mobil) ?>, 
      <?= json_encode($data_mobil) ?>,
      "Transaksi Per Mobil",
      {
        background: 'rgba(245, 101, 101, 0.5)',
        border: 'rgb(245, 101, 101)'
      }
    );

    // Grafik Pendapatan Harian
    var ctxPHarian = document.getElementById("pendapatanHarianChart").getContext("2d");
    createChart(ctxPHarian, "line", <?= json_encode($labels_pendapatan_harian) ?>, <?= json_encode($data_pendapatan_harian) ?>, "Pendapatan Harian", {background: 'rgba(66,153,225,0.2)', border: 'rgb(66,153,225)'});

    // Grafik Pendapatan Bulanan
    var ctxPBulanan = document.getElementById("pendapatanBulananChart").getContext("2d");
    createChart(ctxPBulanan, "line", <?= json_encode($labels_pendapatan_bulanan) ?>, <?= json_encode($data_pendapatan_bulanan) ?>, "Pendapatan Bulanan", {background: 'rgba(72,187,120,0.2)', border: 'rgb(72,187,120)'});

    // Grafik Pendapatan Tahunan
    var ctxPTahunan = document.getElementById("pendapatanTahunanChart").getContext("2d");
    createChart(ctxPTahunan, "line", <?= json_encode($labels_pendapatan_tahunan) ?>, <?= json_encode($data_pendapatan_tahunan) ?>, "Pendapatan Tahunan", {background: 'rgba(237,137,54,0.2)', border: 'rgb(237,137,54)'});

    // Grafik Pendapatan Per Mobil
    var ctxPMobil = document.getElementById("pendapatanMobilChart").getContext("2d");
    createChart(ctxPMobil, "bar", <?= json_encode($labels_pendapatan_mobil) ?>, <?= json_encode($data_pendapatan_mobil) ?>, "Pendapatan Per Mobil", {background: 'rgba(245,101,101,0.5)', border: 'rgb(245,101,101)'});
  </script>
</body>
</html> 