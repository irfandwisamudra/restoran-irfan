<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION['login'] == true) {
  header("Location: index.php");
  exit;
}

include "./functions.php";

if (isset($_POST["login"]) || isset($_POST["guest"])) {
  $username = isset($_POST["login"]) ? $_POST["username"] : "guest";
  $password = isset($_POST["login"]) ? $_POST["password"] : "guest";

  if (empty($username) || empty($password)) {
    $error = "Username dan Password harus diisi.";
  } else {
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_assoc($result);
      if (md5($password) == $row["password"]) {
        $nama = $row["nama_user"];
        $_SESSION["login"] = true;
        $_SESSION["level"] = $row["level"];
        $_SESSION["login_time"] = time();
        $_SESSION["nama"] = $nama;

        $redirect_url = ($_SESSION["level"] != 3) ? 'index.php' : 'order/tambah_order_qr.php';

        echo "<script>
                alert('Selamat datang $nama!');
                document.location.href = '$redirect_url';
              </script>";
      } else {
        $error = "Password tidak cocok dengan Username.";
      }
    } else {
      $error = "Username tidak ditemukan.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css">

  <style>
    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }
  </style>
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
            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
            <div class="col-lg-6">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4 font-weight-bold">Login</h1>
                </div>
                <hr>
                <?php if (isset($error)) : ?>
                  <div class="form-group text-center">
                    <small class="text-danger"><?= $error; ?></small>
                  </div>
                <?php endif; ?>
                <form class="user" action="" method="post">
                  <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="form-control form-control-user" placeholder="Masukkan username...">
                  </div>
                  <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control form-control-user" placeholder="Masukkan password...">
                  </div>
                  <button name="login" type="submit" class="btn btn-primary btn-user btn-block">
                    Masuk
                  </button>
                  <div class="divider d-flex align-items-center my-2">
                    <p class="text-center font-weight-bold mx-3 mb-0 text-muted">Atau</p>
                  </div>
                  <button name="guest" type="submit" class="btn btn-primary btn-user btn-block">
                    Masuk sebagai Guest
                  </button>
                </form>
                <hr>
                <div class="text-center">
                  <span class="small">Belum punya akun user? <a href="register.php">Register sekarang!</a></span>
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