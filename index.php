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
// Pendapatan hari ini
$qPendapatanHariIni = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi WHERE DATE(tanggal_mulai) = CURDATE()");
$dataPendapatanHariIni = mysqli_fetch_assoc($qPendapatanHariIni);
// Pendapatan bulan ini
$qPendapatanBulanIni = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi WHERE YEAR(tanggal_mulai) = YEAR(CURDATE()) AND MONTH(tanggal_mulai) = MONTH(CURDATE())");
$dataPendapatanBulanIni = mysqli_fetch_assoc($qPendapatanBulanIni);
// Pendapatan tahun ini
$qPendapatanTahunIni = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi WHERE YEAR(tanggal_mulai) = YEAR(CURDATE())");
$dataPendapatanTahunIni = mysqli_fetch_assoc($qPendapatanTahunIni);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistem Pendataan Penyewaan Mobil</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="styling/assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
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
              <h2 class="text-success">Rp <?= number_format($dataTotalPendapatan['total'] ?? 0,0,',','.') ?></h2>
              <div class="row mt-3">
                <div class="col-md-4 col-12 mb-2 mb-md-0">
                  <div class="bg-light rounded p-2">
                    <div class="text-xs text-muted">Hari Ini</div>
                    <div class="fw-bold text-success">Rp <?= number_format($dataPendapatanHariIni['total'] ?? 0,0,',','.') ?></div>
                  </div>
                </div>
                <div class="col-md-4 col-12 mb-2 mb-md-0">
                  <div class="bg-light rounded p-2">
                    <div class="text-xs text-muted">Bulan Ini</div>
                    <div class="fw-bold text-primary">Rp <?= number_format($dataPendapatanBulanIni['total'] ?? 0,0,',','.') ?></div>
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <div class="bg-light rounded p-2">
                    <div class="text-xs text-muted">Tahun Ini</div>
                    <div class="fw-bold text-warning">Rp <?= number_format($dataPendapatanTahunIni['total'] ?? 0,0,',','.') ?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Mingguan</h6>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="transaksiMingguanChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Bulanan</h6>
            </div>
            <div class="card-body p-3">
               <div class="chart">
                <canvas id="transaksiBulananChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Tahunan</h6>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                  <canvas id="transaksiTahunanChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0">
              <h6>Grafik Transaksi Per Mobil</h6>
            </div>
            <div class="card-body p-3">
               <div class="chart">
                <canvas id="transaksiMobilChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Harian</h6></div>
             <div class="card-body p-3">
              <div class="chart">
                <canvas id="pendapatanHarianChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Bulanan</h6></div>
             <div class="card-body p-3">
              <div class="chart">
                <canvas id="pendapatanBulananChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-2">
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Tahunan</h6></div>
             <div class="card-body p-3">
              <div class="chart">
                <canvas id="pendapatanTahunanChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-header pb-0"><h6>Grafik Pendapatan Per Mobil</h6></div>
             <div class="card-body p-3">
               <div class="chart">
                <canvas id="pendapatanMobilChart" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
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
  $qPendapatanHarian = mysqli_query($conn, "SELECT DATE(created_at) as tgl, SUM(total_keseluruhan) as total FROM transaksi WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY DATE(created_at)");
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
    // --- START ENHANCED CHART SCRIPT ---

    /**
     * Creates an enhanced Chart.js chart with a modern look and feel.
     * @param {CanvasRenderingContext2D} ctx - The context of the canvas element.
     * @param {('line'|'bar')} type - The type of chart.
     * @param {string[]} labels - The labels for the X-axis.
     * @param {number[]} data - The data points for the Y-axis.
     * @param {string} label - The label for the dataset.
     * @param {string} primaryColor - The primary hex color for the chart (e.g., '#5e72e4').
     */
    function createEnhancedChart(ctx, type, labels, data, label, primaryColor) {
      // Create a gradient for the fill area in line charts
      const gradient = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
      const colorWithOpacity = primaryColor + '33'; // Add ~20% opacity
      gradient.addColorStop(0, colorWithOpacity);
      gradient.addColorStop(1, '#ffffff00'); // Transparent at the bottom

      const isRevenueChart = label.toLowerCase().includes('pendapatan');

      // Common options for all charts for a consistent look
      const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false // Legend is turned off for a cleaner look
          },
          tooltip: {
            enabled: true,
            backgroundColor: '#fff',
            titleColor: '#344767',
            bodyColor: '#6c757d',
            borderColor: '#e9ecef',
            borderWidth: 1,
            padding: 12,
            caretSize: 8,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                let value = context.parsed.y;
                if (isRevenueChart) {
                  // Format as Indonesian Rupiah for revenue charts
                  return ` ${context.dataset.label}: Rp ${new Intl.NumberFormat('id-ID').format(value)}`;
                }
                return ` ${context.dataset.label}: ${value}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              color: '#6c757d',
              font: { size: 12, family: "Open Sans", weight: '300' }
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: '#e9ecef',
              drawBorder: false,
              borderDash: [5, 5] // Dashed grid lines
            },
            ticks: {
              color: '#6c757d',
              padding: 10,
              font: { size: 12, family: "Open Sans", weight: '300' },
              callback: function(value) {
                if (isRevenueChart) {
                  // Abbreviate large numbers for the Y-axis
                  if (value >= 1000000) return `Rp ${value / 1000000} Jt`;
                  if (value >= 1000) return `Rp ${value / 1000} rb`;
                  return `Rp ${value}`;
                }
                // Ensure integer steps for transaction counts
                if (Number.isInteger(value)) {
                    return value;
                }
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
      };

      let chartConfig;

      // Configuration specific to LINE charts
      if (type === 'line') {
        chartConfig = {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: data,
              backgroundColor: gradient,
              borderColor: primaryColor,
              borderWidth: 3,
              fill: true,
              tension: 0.4, // For smooth, curved lines
              pointBackgroundColor: primaryColor,
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 7,
              pointHoverBackgroundColor: primaryColor,
              pointHoverBorderColor: '#fff',
            }]
          },
          options: commonOptions
        };
      } 
      // Configuration specific to BAR charts
      else if (type === 'bar') {
        chartConfig = {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: label,
              data: data,
              backgroundColor: primaryColor + 'CC', // 80% opacity
              borderColor: primaryColor,
              borderWidth: 0,
              borderRadius: 8, // For rounded bar corners
              hoverBackgroundColor: primaryColor,
              barPercentage: 0.6,
              categoryPercentage: 0.7,
            }]
          },
          options: commonOptions
        };
      }

      return new Chart(ctx, chartConfig);
    }

    // Color palette based on Argon theme
    const argonColors = {
      primary: '#5e72e4',
      success: '#2dce89',
      warning: '#fb6340',
      danger: '#f5365c',
      info: '#11cdef'
    };

    // --- Instantiate all charts with the new enhanced function ---

    // Grafik Transaksi Mingguan (Line)
    var ctxMingguan = document.getElementById("transaksiMingguanChart").getContext("2d");
    createEnhancedChart(ctxMingguan, "line", 
      <?= json_encode($labels_mingguan) ?>, 
      <?= json_encode($data_mingguan) ?>,
      "Transaksi",
      argonColors.primary
    );

    // Grafik Transaksi Bulanan (Line)
    var ctxBulanan = document.getElementById("transaksiBulananChart").getContext("2d");
    createEnhancedChart(ctxBulanan, "line", 
      <?= json_encode($labels_bulanan) ?>, 
      <?= json_encode($data_bulanan) ?>,
      "Transaksi",
      argonColors.success
    );

    // Grafik Transaksi Tahunan (Line)
    var ctxTahunan = document.getElementById("transaksiTahunanChart").getContext("2d");
    createEnhancedChart(ctxTahunan, "line", 
      <?= json_encode($labels_tahunan) ?>, 
      <?= json_encode($data_tahunan) ?>,
      "Transaksi",
      argonColors.warning
    );

    // Grafik Transaksi Per Mobil (Bar)
    var ctxMobil = document.getElementById("transaksiMobilChart").getContext("2d");
    createEnhancedChart(ctxMobil, "bar", 
      <?= json_encode($labels_mobil) ?>, 
      <?= json_encode($data_mobil) ?>,
      "Jumlah Transaksi",
      argonColors.danger
    );

    // Grafik Pendapatan Harian
    var ctxPHarian = document.getElementById("pendapatanHarianChart").getContext("2d");
    createEnhancedChart(ctxPHarian, "line", 
        <?= json_encode($labels_pendapatan_harian) ?>, 
        <?= json_encode($data_pendapatan_harian) ?>, 
        "Pendapatan Harian", 
        argonColors.primary);

    // Grafik Pendapatan Bulanan
    var ctxPBulanan = document.getElementById("pendapatanBulananChart").getContext("2d");
    createEnhancedChart(ctxPBulanan, "line", 
        <?= json_encode($labels_pendapatan_bulanan) ?>, 
        <?= json_encode($data_pendapatan_bulanan) ?>, 
        "Pendapatan Bulanan", 
        argonColors.success);

    // Grafik Pendapatan Tahunan
    var ctxPTahunan = document.getElementById("pendapatanTahunanChart").getContext("2d");
    createEnhancedChart(ctxPTahunan, "line", 
        <?= json_encode($labels_pendapatan_tahunan) ?>, 
        <?= json_encode($data_pendapatan_tahunan) ?>, 
        "Pendapatan Tahunan", 
        argonColors.warning);

    // Grafik Pendapatan Per Mobil
    var ctxPMobil = document.getElementById("pendapatanMobilChart").getContext("2d");
    createEnhancedChart(ctxPMobil, "bar", 
        <?= json_encode($labels_pendapatan_mobil) ?>, 
        <?= json_encode($data_pendapatan_mobil) ?>, 
        "Pendapatan Per Mobil", 
        argonColors.danger);

    // --- END ENHANCED CHART SCRIPT ---
  </script>
</body>
</html>
