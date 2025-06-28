<?php
include '../koneksi/koneksi.php';
session_start();

// Cek login
if(!isset($_SESSION['login'])) {
  header("Location: ../login.php");
  exit;
}

// PHP logic for adding a passenger
if(isset($_POST['tambah'])) {
  $nama_penumpang = $_POST['nama_penumpang'];
  $alamat = $_POST['alamat'];
  $no_telp = $_POST['no_telp'];
  $no_ktp = $_POST['no_ktp'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $umur = $_POST['umur'];
  
  mysqli_query($conn, "INSERT INTO penumpang (nama_penumpang, alamat, no_telp, no_ktp, jenis_kelamin, umur) 
                      VALUES ('$nama_penumpang', '$alamat', '$no_telp', '$no_ktp', '$jenis_kelamin', '$umur')");
  
  header("Location: penumpang.php");
}

// PHP logic for editing a passenger
if(isset($_POST['edit'])) {
  $id_penumpang = $_POST['id_penumpang'];
  $nama_penumpang = $_POST['nama_penumpang'];
  $alamat = $_POST['alamat'];
  $no_telp = $_POST['no_telp'];
  $no_ktp = $_POST['no_ktp'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $umur = $_POST['umur'];
  
  mysqli_query($conn, "UPDATE penumpang SET 
                    nama_penumpang='$nama_penumpang',
                    alamat='$alamat',
                    no_telp='$no_telp',
                    no_ktp='$no_ktp',
                    jenis_kelamin='$jenis_kelamin',
                    umur='$umur'
                    WHERE id_penumpang='$id_penumpang'");
  
  header("Location: penumpang.php");
}

// PHP logic for deleting a passenger
if(isset($_POST['hapus'])) {
  $id_penumpang = $_POST['id_penumpang'];
  mysqli_query($conn, "DELETE FROM penumpang WHERE id_penumpang='$id_penumpang'");
  header("Location: penumpang.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Penumpang - Sistem Pendataan Penyewaan Mobil</title>
  <link href="../styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../styling/assets/css/argon-dashboard.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <script src="../styling/assets/js/core/popper.min.js"></script>
  <script src="../styling/assets/js/core/bootstrap.min.js"></script>
  <script src="../styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../styling/assets/js/plugins/smooth-scrollbar.min.js"></script>
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
          <a class="nav-link" href="penyewa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penyewa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="penumpang.php">
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
              <h6>Data Penumpang</h6>
              <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fa fa-plus"></i> Tambah
              </button>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-4">
                <form method="GET" class="row g-3 mb-3">
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="q" placeholder="Cari nama, no KTP, atau no HP" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
                    <?php if(isset($_GET['q']) && $_GET['q'] != '') { ?>
                    <a href="penumpang.php" class="btn btn-secondary">Reset</a>
                    <?php } ?>
                  </div>
                </form>
              </div>
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penumpang</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontak</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Kelamin</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Umur</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Query pencarian
                    $where = '';
                    if(isset($_GET['q']) && $_GET['q'] != '') {
                      $q = mysqli_real_escape_string($conn, $_GET['q']);
                      $where = "WHERE nama_penumpang LIKE '%$q%' OR no_ktp LIKE '%$q%' OR no_telp LIKE '%$q%'";
                    }
                    $query = mysqli_query($conn, "SELECT * FROM penumpang $where ORDER BY nama_penumpang");
                    while($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?= $data['nama_penumpang'] ?></h6>
                            <p class="text-xs text-secondary mb-0"><?= $data['no_ktp'] ?></p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?= $data['alamat'] ?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <p class="text-xs font-weight-bold mb-0"><?= $data['no_telp'] ?></p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="badge badge-sm bg-gradient-<?= $data['jenis_kelamin'] == 'Laki-laki' ? 'primary' : 'warning' ?>">
                          <?= $data['jenis_kelamin'] ?>
                        </span>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?= $data['umur'] ?> tahun</span>
                      </td>
                      <td class="align-middle text-center">
                        <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $data['id_penumpang'] ?>">
                          <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="if(confirm('Hapus penumpang ini?')) { document.getElementById('formHapus<?= $data['id_penumpang'] ?>').submit(); }">
                          <i class="fa fa-trash"></i>
                        </button>
                        <form id="formHapus<?= $data['id_penumpang'] ?>" action="" method="POST">
                          <input type="hidden" name="id_penumpang" value="<?= $data['id_penumpang'] ?>">
                          <input type="hidden" name="hapus" value="1">
                        </form>
                      </td>
                    </tr>
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
          <h5 class="modal-title d-flex align-items-center">
            <i class="fa fa-plus text-primary me-2"></i>Tambah Penumpang Baru
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label class="form-label">Nama Penumpang</label>
              <input type="text" class="form-control" name="nama_penumpang" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="alamat" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">No. Telepon</label>
              <input type="text" class="form-control" name="no_telp">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">No. KTP</label>
              <input type="text" class="form-control" name="no_ktp">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Jenis Kelamin</label>
              <select class="form-control" name="jenis_kelamin">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Umur</label>
              <input type="number" class="form-control" name="umur" min="1" max="120">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="tambah" class="btn bg-gradient-primary">
              <i class="fa fa-save me-2"></i>Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit -->
  <?php
  $query = mysqli_query($conn, "SELECT * FROM penumpang ORDER BY nama_penumpang");
  while($data = mysqli_fetch_array($query)) {
  ?>
  <div class="modal fade" id="editModal<?= $data['id_penumpang'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="fa fa-edit text-warning me-2"></i>Edit Penumpang
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <input type="hidden" name="id_penumpang" value="<?= $data['id_penumpang'] ?>">
          <div class="modal-body">
            <div class="form-group mb-3">
              <label class="form-label">Nama Penumpang</label>
              <input type="text" class="form-control" name="nama_penumpang" value="<?= $data['nama_penumpang'] ?>" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Alamat</label>
              <textarea class="form-control" name="alamat" rows="3"><?= $data['alamat'] ?></textarea>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">No. Telepon</label>
              <input type="text" class="form-control" name="no_telp" value="<?= $data['no_telp'] ?>">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">No. KTP</label>
              <input type="text" class="form-control" name="no_ktp" value="<?= $data['no_ktp'] ?>">
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Jenis Kelamin</label>
              <select class="form-control" name="jenis_kelamin">
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Umur</label>
              <input type="number" class="form-control" name="umur" min="1" max="120" value="<?= $data['umur'] ?>">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="edit" class="btn bg-gradient-primary">
              <i class="fa fa-save me-2"></i>Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php } ?>

</body>
</html> 