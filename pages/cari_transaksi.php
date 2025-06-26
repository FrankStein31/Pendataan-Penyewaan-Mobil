<?php
include '../koneksi/koneksi.php';
session_start();

$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'harian';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$id_mobil = isset($_GET['id_mobil']) ? $_GET['id_mobil'] : '';

$where = "1=1";
if($tipe == 'harian') {
  $where .= " AND DATE(tanggal_mulai)='$tanggal'";
} else if($tipe == 'mingguan') {
  $start = date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
  $where .= " AND DATE(tanggal_mulai) BETWEEN '$start' AND '$tanggal'";
} else if($tipe == 'bulanan') {
  $where .= " AND DATE_FORMAT(tanggal_mulai, '%Y-%m')='$bulan'";
} else if($tipe == 'tahunan') {
  $where .= " AND YEAR(tanggal_mulai)='$tahun'";
}

if($id_mobil != '') {
  $where .= " AND t.id_mobil='$id_mobil'";
}

$query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, m.nama_mobil, d.nama_driver 
                            FROM transaksi t 
                            JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                            JOIN mobil m ON t.id_mobil=m.id_mobil
                            LEFT JOIN driver d ON t.id_driver=d.id_driver
                            WHERE $where
                            ORDER BY t.id_transaksi DESC");

$total = 0;
$data_transaksi = [];
while($data = mysqli_fetch_array($query)) {
  $total += $data['total_keseluruhan'];
  $data_transaksi[] = $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cari Transaksi - Sistem Pendataan Penyewaan Mobil</title>
  <link href="../styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../styling/assets/css/argon-dashboard.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <h4>Rental Mobil</h4>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../index.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="penyewa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penyewa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="mobil.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-car-sports text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Mobil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="driver.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-delivery-fast text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Driver</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="transaksi.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Transaksi</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Cari Transaksi</h6>
            </div>
            <div class="card-body">
              <form action="" method="GET">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Tipe Pencarian</label>
                      <select class="form-control" name="tipe" onchange="toggleTanggal(this.value)">
                        <option value="harian" <?= $tipe == 'harian' ? 'selected' : '' ?>>Harian</option>
                        <option value="mingguan" <?= $tipe == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                        <option value="bulanan" <?= $tipe == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                        <option value="tahunan" <?= $tipe == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3" id="tanggalGroup" style="display: <?= in_array($tipe, ['harian', 'mingguan']) ? 'block' : 'none' ?>;">
                    <div class="form-group">
                      <label>Tanggal</label>
                      <input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>">
                    </div>
                  </div>
                  <div class="col-md-3" id="bulanGroup" style="display: <?= $tipe == 'bulanan' ? 'block' : 'none' ?>;">
                    <div class="form-group">
                      <label>Bulan</label>
                      <input type="month" class="form-control" name="bulan" value="<?= $bulan ?>">
                    </div>
                  </div>
                  <div class="col-md-3" id="tahunGroup" style="display: <?= $tipe == 'tahunan' ? 'block' : 'none' ?>;">
                    <div class="form-group">
                      <label>Tahun</label>
                      <select class="form-control" name="tahun">
                        <?php
                        for($i = date('Y'); $i >= 2020; $i--) {
                          echo "<option value='$i' " . ($tahun == $i ? 'selected' : '') . ">$i</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Mobil</label>
                      <select class="form-control select2" name="id_mobil">
                        <option value="">Semua Mobil</option>
                        <?php
                        $query = mysqli_query($conn, "SELECT * FROM mobil ORDER BY nama_mobil");
                        while($data = mysqli_fetch_array($query)) {
                          echo "<option value='$data[id_mobil]' " . ($id_mobil == $data['id_mobil'] ? 'selected' : '') . ">$data[nama_mobil]</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>&nbsp;</label>
                      <button type="submit" class="btn btn-primary d-block">Cari</button>
                    </div>
                  </div>
                </div>
              </form>
              
              <hr>
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penyewa</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mobil</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Driver</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Sewa</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Hari</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($data_transaksi as $data) { ?>
                    <tr>
                      <td>
                        <div class="d-flex px-3">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm"><?= $data['nama_penyewa'] ?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['nama_mobil'] ?></p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?></p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">
                          <?= date('d/m/Y H:i', strtotime($data['tanggal_mulai'])) ?> - 
                          <?= date('d/m/Y H:i', strtotime($data['tanggal_selesai'])) ?>
                        </p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['total_hari'] ?> hari</p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0">Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></p>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="5" class="text-end"><strong>Total:</strong></td>
                      <td colspan="2"><strong>Rp <?= number_format($total,0,',','.') ?></strong></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="../styling/assets/js/core/popper.min.js"></script>
  <script src="../styling/assets/js/core/bootstrap.min.js"></script>
  <script src="../styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../styling/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  
  <script>
    $(document).ready(function() {
      $('.select2').select2();
    });
    
    function toggleTanggal(tipe) {
      if(tipe == 'harian' || tipe == 'mingguan') {
        $('#tanggalGroup').show();
        $('#bulanGroup').hide();
        $('#tahunGroup').hide();
      } else if(tipe == 'bulanan') {
        $('#tanggalGroup').hide();
        $('#bulanGroup').show();
        $('#tahunGroup').hide();
      } else if(tipe == 'tahunan') {
        $('#tanggalGroup').hide();
        $('#bulanGroup').hide();
        $('#tahunGroup').show();
      }
    }
  </script>
</body>
</html> 