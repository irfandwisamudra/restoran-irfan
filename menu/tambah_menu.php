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
    if ($_SESSION["level"] == 2) {
      header("Location: ../index.php");
      exit;
    }
    if ($_SESSION["level"] == 3) {
      header("Location: ../order/tambah_order_qr.php");
      exit;
    }
  }
}

include "../functions.php";

if (isset($_POST["kirim"])) {
  if (tambah_menu($_POST) > 0) {
    echo "<script>
            alert('Data menu baru berhasil ditambahkan!');
            document.location.href = 'tampil_menu.php';
          </script>
        ";
  } else {
    echo "<script>
            alert('Data menu gagal ditambahkan!');
            document.location.href = 'tampil_menu.php';
          </script>
        ";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Tambah Menu</title>

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
            <h3 class="card-title font-weight-bold">Tambah Menu</h3>
          </div>
          <form action="" method="post">
            <div class="card-body" id="form-asli">
              <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                  <label for="jenis_menu">Jenis Menu:</label>
                  <select class="form-control" name="jenis_menu[]" id="jenis_menu" required>
                    <option value="" disabled selected>Pilih jenis menu ...</option>
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                  </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="nama_menu">Nama Menu:</label>
                  <input type="text" class="form-control" name="nama_menu[]" id="nama_menu" placeholder="Masukkan nama ..." required>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="harga_menu">Harga Menu:</label>
                  <input type="number" class="form-control" name="harga_menu[]" id="harga_menu" placeholder="Masukkan harga ..." required>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="stok">Stok:</label>
                  <input type="number" class="form-control" name="stok[]" id="stok" placeholder="Masukkan stok ..." required>
                </div>
              </div>
            </div>

            <div id="form-dinamis">
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col text-left">
                  <a href="tampil_menu.php">
                    <div class="btn btn-default">Batal</div>
                  </a>
                </div>
                <div class="col text-center">
                  <button type="kirim" class="btn btn-primary" name="kirim">Kirim</button>
                </div>
                <div class="col text-right">
                  <button type="button" class="btn btn-info" onclick="copyForm()">Tambah Form</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    function copyForm() {
      var clonedForm = $("#form-asli").clone();
      clonedForm.find("input[type='text'], input[type='number']").val("");
      clonedForm.appendTo($("#form-dinamis"));
    }
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>