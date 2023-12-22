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

if (isset($_POST["kirim"])) {
  if (tambah_order($_POST) > 0) {
    $id_order = mysqli_insert_id($conn);
    echo "<script>
            alert('Data order baru berhasil ditambahkan!');
            document.location.href = 'tambah_order_detail.php?id_order=$id_order';
          </script>
        ";
  } else {
    echo "<script>
            alert('Data order gagal ditambahkan!');
            document.location.href = 'tampil_order.php';
          </script>
        ";
  }
}

$data_pelayan = array("Budi", "Siti", "Eko", "Dewi", "Rudi", "Lina");

date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Tambah Order</title>

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
            <h3 class="card-title font-weight-bold">Tambah Order</h3>
          </div>
          <form action="" method="post">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                  <label for="nama_pelayan">Nama Pelayan:</label>
                  <select class="form-control" name="nama_pelayan" id="nama_pelayan" required>
                    <option value="" disabled selected>Pilih nama pelayan ...</option>
                    <?php foreach ($data_pelayan as $pelayan) : ?>
                      <option value="<?= $pelayan; ?>"><?= $pelayan; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="no_meja">No. Meja:</label>
                  <input type="number" class="form-control" name="no_meja" id="no_meja" min="1" max="5" placeholder="Masukkan nomor meja ..." required>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="tanggal">Tanggal:</label>
                  <input type="date" class="form-control" name="tanggal_order" id="tanggal" value="<?= date('Y-m-d'); ?>" readonly>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="waktu">Waktu:</label>
                  <input type="time" class="form-control" name="jam_order" id="waktu" value="<?= date('H:i:s'); ?>" readonly>
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
                  <button type="kirim" class="btn btn-primary" name="kirim">Kirim</button>
                </div>
                <div class="col text-right"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>