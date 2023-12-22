<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION['login'] == true) {
  header("Location: index.php");
  exit;
}

include "./functions.php";

if (isset($_POST["register"])) {
  if (strtolower(stripslashes($_POST["password1"])) == strtolower(stripslashes($_POST["password2"]))) {
    if (register($_POST) > 0) {
      echo "<script>
              alert('User baru berhasil disimpan!');
              document.location.href = 'login.php';
            </script>";
    } else {
      echo "<script>
              alert('Data user gagal disimpan!');
              document.location.href = 'register.php';
            </script>";
    }
  } else {
    $error = "Password tidak cocok dengan Konfirmasi Password.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css">
</head>

<body style="background: linear-gradient(135deg, #6bffff, #000000);">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand text-white" href="./index.php"><strong>Restoran</strong> Irfan</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item mr-3">
            <a class="nav-link font-weight-bold" href="./login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div style="height: 70px;"></div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0"></div>
          <div class="row">
            <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
            <div class="col-lg-7">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Register</h1>
                </div>
                <hr>
                <?php if (isset($error)) : ?>
                  <div class="form-group text-center">
                    <small class="text-danger"><?= $error; ?></small>
                  </div>
                <?php endif; ?>
                <form class="user" action="" method="post">
                  <div class="form-group">
                    <label for="nama_user">Nama User:</label>
                    <input type="text" name="nama_user" id="nama_user" class="form-control form-control-user" placeholder="Masukkan nama user...">
                  </div>
                  <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control form-control-user" placeholder="Masukkan username...">
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <label for="password1">Password:</label>
                      <input type="password" name="password1" id="password1" class="form-control form-control-user" placeholder="Masukkan password...">
                    </div>
                    <div class="col-sm-6">
                      <label for="password2">Konfirmasi Password:</label>
                      <input type="password" name="password2" id="password2" class="form-control form-control-user" placeholder="Ulangi password...">
                    </div>
                  </div>
                  <div class="form-group mb-4">
                    <label for="level">Jenis User:</label>
                    <select class="form-control" name="level" id="level" required>
                      <option value="" disabled selected>--- Pilih Jenis User ---</option>
                      <option value="1">Admin</option>
                      <option value="2">Kasir</option>
                      <option value="3">Guest</option>
                    </select>
                  </div>
                  <button name="register" type="submit" class="btn btn-primary btn-user btn-block">
                    Daftar
                  </button>
                </form>
                <hr>
                <div class="text-center">
                  <span class="small">Sudah punya akun user? <a href="login.php">Login!</a></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>