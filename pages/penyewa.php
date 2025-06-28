<?php
include '../koneksi/koneksi.php';
session_start();

// Cek login
if(!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

if(isset($_POST['tambah'])) {
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_telp = $_POST['no_telp'];
  $no_ktp = $_POST['no_ktp'];
  
  mysqli_query($conn, "INSERT INTO penyewa (nama_penyewa, alamat, no_telp, no_ktp) VALUES ('$nama', '$alamat', '$no_telp', '$no_ktp')");
  header("Location: penyewa.php");
}

if(isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_telp = $_POST['no_telp'];
  $no_ktp = $_POST['no_ktp'];
  
  mysqli_query($conn, "UPDATE penyewa SET nama_penyewa='$nama', alamat='$alamat', no_telp='$no_telp', no_ktp='$no_ktp' WHERE id_penyewa='$id'");
  header("Location: penyewa.php");
}

if(isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM penyewa WHERE id_penyewa='$id'");
  header("Location: penyewa.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Data Penyewa - Sistem Pendataan Penyewaan Mobil</title>
  <link href="../styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../styling/assets/css/argon-dashboard.css" rel="stylesheet" />
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
          <a class="nav-link" href="../index.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-dashboard text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="penyewa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penyewa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="penumpang.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-plus text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penumpang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="mobil.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-car text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Mobil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="driver.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-id-card text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Driver</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="tipe_biaya.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-list text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tipe Biaya</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="transaksi.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-money text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Transaksi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
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
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6>Data Penyewa</h6>
              <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fa fa-plus"></i> Tambah
              </button>
            </div>
            <div class="card-body pt-3 pb-0">
              <form method="GET" class="row g-3 mb-3">
                <div class="col-md-4">
                  <input type="text" class="form-control" name="q" placeholder="Cari nama, no KTP, atau no HP" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
                  <?php if(isset($_GET['q']) && $_GET['q'] != '') { ?>
                  <a href="penyewa.php" class="btn btn-secondary">Reset</a>
                  <?php } ?>
                </div>
              </form>
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No. Telp</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No. KTP</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Query pencarian
                    $where = '';
                    if(isset($_GET['q']) && $_GET['q'] != '') {
                      $q = mysqli_real_escape_string($conn, $_GET['q']);
                      $where = "WHERE nama_penyewa LIKE '%$q%' OR no_ktp LIKE '%$q%' OR no_telp LIKE '%$q%'";
                    }
                    $query = mysqli_query($conn, "SELECT * FROM penyewa $where ORDER BY id_penyewa DESC");
                    while($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-3">
                          <div class="my-auto">
                            <h6 class="mb-0 text-sm"><?= $data['nama_penyewa'] ?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['alamat'] ?></p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['no_telp'] ?></p>
                      </td>
                      <td>
                        <p class="text-sm font-weight-bold mb-0"><?= $data['no_ktp'] ?></p>
                      </td>
                      <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $data['id_penyewa'] ?>">
                          <i class="fa fa-edit"></i> Edit
                        </button>
                        <a href="?hapus=<?= $data['id_penyewa'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                          <i class="fa fa-trash"></i> Hapus
                        </a>
                      </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $data['id_penyewa'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Edit Data Penyewa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="" method="POST">
                            <div class="modal-body">
                              <input type="hidden" name="id" value="<?= $data['id_penyewa'] ?>">
                              <div class="form-group">
                                <label>Nama Penyewa</label>
                                <input type="text" class="form-control" name="nama" value="<?= $data['nama_penyewa'] ?>" required>
                              </div>
                              <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" required><?= $data['alamat'] ?></textarea>
                              </div>
                              <div class="form-group">
                                <label>No. Telp</label>
                                <input type="text" class="form-control" name="no_telp" value="<?= $data['no_telp'] ?>" required>
                              </div>
                              <div class="form-group">
                                <label>No. KTP</label>
                                <input type="text" class="form-control" name="no_ktp" value="<?= $data['no_ktp'] ?>" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" name="edit" class="btn bg-gradient-primary">Simpan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Tambah -->
  <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Data Penyewa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <div class="form-group">
              <label>Nama Penyewa</label>
              <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="form-group">
              <label>Alamat</label>
              <textarea class="form-control" name="alamat" required></textarea>
            </div>
            <div class="form-group">
              <label>No. Telp</label>
              <input type="text" class="form-control" name="no_telp" required>
            </div>
            <div class="form-group">
              <label>No. KTP</label>
              <input type="text" class="form-control" name="no_ktp" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="tambah" class="btn bg-gradient-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../styling/assets/js/core/popper.min.js"></script>
  <script src="../styling/assets/js/core/bootstrap.min.js"></script>
  <script src="../styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../styling/assets/js/plugins/smooth-scrollbar.min.js"></script>
</body>
</html> 