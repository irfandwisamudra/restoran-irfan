<?php
session_start();

if (!isset($_SESSION["login"]) && $_SESSION['login'] != true) {
  header("Location: login.php");
  exit;
} else {
  $menit = 15;
  $batas_waktu = $menit * 60;
  if (time() - $_SESSION["login_time"] > $batas_waktu) {
    echo "<script>
            alert('Sesi Anda sudah habis. Silahkan login kembali!');
            document.location.href = 'logout.php';
          </script>";
  } else {
    if ($_SESSION["level"] == 3) {
      header("Location: order/tambah_order_qr.php");
      exit;
    }
  }
}

include "./functions.php";

$menu = query("SELECT * FROM menu");
$order = query("SELECT * FROM order_pesanan");
$jumlah_menu = count($menu);
$jumlah_order = count($order);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran Irfan</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
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
          <?php if ($_SESSION["level"] == 1) : ?>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="./menu/tampil_menu.php">Menu</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./order/tampil_order.php">Order</a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./order/order_qr.php">QR Code</a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="./logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div style="height: 70px;"></div>

  <section class="content">
    <div class="container-fluid">
      <div class="jumbotron text-center bg-info text-white pt-4 pb-4">
        <h1 class="display-5">Selamat Datang <?= $_SESSION["nama"]; ?> di Restoran Irfan!</h1>
        <p class="lead">Nikmati menu lezat kami dan pesan sekarang.</p>
        <?php if ($_SESSION["level"] == 1) : ?>
          <p class="lead font-weight-bold">Level Anda adalah Admin.</p>
          <hr class="my-4">
          <p>Jangan lupa untuk menjelajahi daftar menu dan pesanan yang sudah ada.</p>
        <?php else : ?>
          <p class="lead font-weight-bold">Level Anda adalah Kasir.</p>
          <hr class="my-4">
          <p>Jangan lupa untuk menjelajahi daftar pesanan yang sudah ada.</p>
        <?php endif; ?>
      </div>

      <div class="row justify-content-center">
        <?php if ($_SESSION["level"] == 1) : ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?= $jumlah_menu; ?></h3>

                <p>Daftar Menu</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="./menu/tampil_menu.php" class="small-box-footer">Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php endif; ?>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= $jumlah_order; ?></sup></h3>

              <p>Data Order</p>
            </div>
            <div class="icon">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="./order/tampil_order.php" class="small-box-footer">Lihat Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>