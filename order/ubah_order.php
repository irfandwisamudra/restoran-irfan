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

include "../functions.php";

$id_order = $_GET["id_order"];

if (isset($_POST["ubah"])) {
  if (ubah_order($_POST) > 0) {
    echo "<script>
            alert('Data order berhasil diubah!');
            document.location.href = 'tampil_order.php';
          </script>
        ";
  } else {
    echo "<script>
            alert('Data order gagal diubah!');
            document.location.href = 'tampil_order.php';
          </script>
        ";
  }
}

$data_pelayan = array("Budi", "Siti", "Eko", "Dewi", "Rudi", "Lina",);

$data_menu = query("SELECT * FROM menu");
$order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
$order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");
$jumlah_data = count($order_detail);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Ubah Order</title>

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
            <h3 class="card-title font-weight-bold">Ubah Order</h3>
          </div>
          <form action="" method="post">
            <input type="hidden" name="id_order" value="<?= $order['id_order']; ?>">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                  <label for="nama_pelayan">Nama Pelayan:</label>
                  <select class="form-control" name="nama_pelayan" id="nama_pelayan" required>
                    <?php foreach ($data_pelayan as $pelayan) : ?>
                      <option value="<?= $pelayan; ?>" <?php if ($order['nama_pelayan'] == $pelayan) echo 'selected'; ?>><?= $pelayan; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="no_meja">No. Meja:</label>
                  <input type="number" class="form-control" name="no_meja" id="no_meja" min="1" placeholder="Masukkan nomor meja ..." value="<?= $order['no_meja']; ?>" required>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="tanggal">Tanggal:</label>
                  <input type="date" class="form-control" name="tanggal_order" id="tanggal" value="<?= $order['tanggal_order']; ?>" readonly>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="waktu">Waktu:</label>
                  <input type="time" class="form-control" name="waktu_order" id="waktu" value="<?= $order['jam_order']; ?>" readonly>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col text-left">
                  <a href="tampil_order.php">
                    <div class="btn btn-default">Batal</div>
                  </a>
                </div>
                <div class="col text-center">
                  <button type="submit" class="btn btn-primary" name="ubah">Ubah</button>
                </div>
                <div class="col text-right"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>