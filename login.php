<?php
include 'koneksi/koneksi.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if(mysqli_num_rows($query) > 0) {
        $_SESSION['login'] = true;
        header("Location: index.php");
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login - Sistem Rental Mobil</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="styling/assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
    .bg-cover {
      background-image: url('https://images.unsplash.com/photo-1552519507-da3b142c6e3d?q=80&w=2070&auto=format&fit=crop');
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>

<body class="">
  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient"><i class="fa fa-car me-2"></i>Sistem Rental Mobil</h3>
                  <p class="mb-0">Selamat datang! Silakan login untuk melanjutkan.</p>
                </div>
                <div class="card-body">
                  <?php if(isset($error)) { ?>
                    <div class="alert alert-danger text-white font-weight-bold" role="alert">
                      <i class="fa fa-exclamation-triangle me-2"></i><?= $error ?>
                    </div>
                  <?php } ?>
                  <form role="form" action="" method="POST">
                    <label><i class="fa fa-user me-1"></i>Username</label>
                    <div class="mb-3">
                      <div class="input-group">
                          <span class="input-group-text"><i class="fa fa-user"></i></span>
                          <input type="text" class="form-control" placeholder="Masukkan username Anda" name="username" required>
                      </div>
                    </div>
                    <label><i class="fa fa-lock me-1"></i>Password</label>
                    <div class="mb-3">
                      <div class="input-group">
                          <span class="input-group-text"><i class="fa fa-lock"></i></span>
                          <input type="password" class="form-control" placeholder="Masukkan password Anda" id="password" name="password" required>
                          <button class="btn btn-outline-secondary mb-0" type="button" id="togglePassword">
                            <i class="fa fa-eye"></i>
                          </button>
                      </div>
                    </div>
                    
                    <div class="text-center">
                      <button type="submit" name="login" class="btn btn-lg bg-gradient-info w-100 mt-4 mb-0">
                        <i class="fa fa-sign-in-alt me-2"></i>Login Sekarang
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <script src="styling/assets/js/core/popper.min.js"></script>
  <script src="styling/assets/js/core/bootstrap.min.js"></script>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const toggleIcon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye icon
      toggleIcon.classList.toggle('fa-eye-slash');
      toggleIcon.classList.toggle('fa-eye');
    });
  </script>
</body>
</html>