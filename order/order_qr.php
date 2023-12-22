<?php
session_start();

if (!isset($_SESSION["login"]) && $_SESSION['login'] != true) {
  header("Location: ../login.php");
  exit;
} else {
  $menit = 15;
  $batas_waktu = $menit * 60;
  if (time() - $_SESSION["login_time"] > $batas_waktu) {
    echo "<script>
            alert('Sesi Anda sudah habis. Silahkan login kembali!');
            document.location.href = '../logout.php';
          </script>";
  } else {
    if ($_SESSION["level"] == 3) {
      header("Location: tambah_order_qr.php");
      exit;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - QR Code Order</title>

  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>

<body>
  <?php include "../header.php" ?>

  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mx-auto mt-2">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">QR Code Order</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <a href="tampil_order.php"><button class="btn btn-default mb-3 mr-2">Kembali ke Data Order</button></a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0 text-center">
                <thead class="table-primary">
                  <tr>
                    <th class="align-middle">No. Meja</th>
                    <th class="align-middle">QR Code</th>
                    <th class="align-middle">Link</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <tr>
                      <td class="align-middle"><?= $i; ?></td>
                      <td class="align-middle"><img src="../img/qr_no_meja_<?= $i; ?>.png" alt="QR Code No. Meja <?= $i; ?>" style="width: 50%; max-width: 200px; min-width: 100px;" class="img-fluid"></td>
                      <td class="align-middle"><a href="tambah_order_qr.php?no_meja=<?= $i; ?>">Link No. Meja <?= $i; ?></a></td>
                    </tr>
                  <?php endfor; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>