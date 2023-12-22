<?php
session_start();

include "../functions.php";

$data_meja = [1, 2, 3, 4, 5];

if (isset($_GET["no_meja"])) {
  if (!in_array($_GET["no_meja"], $data_meja)) {
    echo "<script>alert('No. Meja Tidak Tersedia');</script>";
    die;
  }
}

if (isset($_POST["kirim"])) {
  $no_meja = $_POST["no_meja"];

  $query = "SELECT id_order, tanggal_order, jam_order 
            FROM order_pesanan 
            WHERE no_meja = $no_meja AND status_order = 'Dalam proses'
            ORDER BY CONCAT(tanggal_order, ' ', jam_order) DESC
            LIMIT 1";

  $result = mysqli_query($conn, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
      $waktu_db = strtotime($row['tanggal_order'] . ' ' . $row['jam_order']);
      $waktu_sekarang = strtotime($_POST['tanggal_order'] . ' ' . $_POST['jam_order']);
      $waktu_beda = $waktu_sekarang - $waktu_db;

      $batas_waktu = 15 * 60;
      if ($waktu_beda <= $batas_waktu) {
        $waktu_tunggu = $batas_waktu - $waktu_beda;
        $menit_tunggu = floor($waktu_tunggu / 60);
        $detik_tunggu = $waktu_tunggu % 60;

        echo "<script>
                alert('Maaf, meja ini sedang dalam proses pemesanan. Silakan tunggu $menit_tunggu menit $detik_tunggu detik sebelum memesan kembali.');
                window.location.href = 'tambah_order_qr.php?no_meja=$no_meja';
              </script>";
        exit;
      }
    }
  }

  if (tambah_order($_POST) > 0) {
    $id_order = mysqli_insert_id($conn);
    echo "<script>
            alert('Data pesanan Anda berhasil disimpan. Silahkan pilih menu yang akan dipesan.');
            window.location.href = 'tambah_order_detail_qr.php?id_order=$id_order';
          </script>";
    exit;
  } else {
    echo "<script>
            alert('Data pesanan Anda gagal ditambahkan!');
            window.location.href = 'tambah_order_qr.php?no_meja=$no_meja';
          </script>";
    exit;
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
  <title>Restoran - Tambah Pesanan</title>

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
            <h3 class="card-title font-weight-bold">Tambah Pesanan</h3>
          </div>
          <form action="" method="post">
            <div class="card-body">
              <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                  <label for="nama_pelayan">Nama Pelayan:</label>
                  <select class="form-control" name="nama_pelayan" id="nama_pelayan" required>
                    <option value="" disabled selected>--- Pilih nama pelayan ---</option>
                    <?php foreach ($data_pelayan as $pelayan) : ?>
                      <option value="<?= $pelayan; ?>"><?= $pelayan; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="no_meja">No. Meja:</label>
                  <?php if (!isset($_GET["no_meja"])) : ?>
                    <select class="form-control" name="no_meja" id="no_meja" required>
                      <option value="" disabled selected>--- Pilih No. Meja ---</option>
                      <?php foreach ($data_meja as $meja) : ?>
                        <option value="<?= $meja; ?>"><?= $meja; ?></option>
                      <?php endforeach; ?>
                    </select>
                  <?php else : ?>
                    <input type="number" class="form-control" name="no_meja" id="no_meja" min="1" placeholder="Masukkan nomor meja ..." value="<?= $_GET["no_meja"]; ?>" readonly required>
                  <?php endif; ?>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="tanggal">Tanggal:</label>
                  <input type="date" class="form-control" name="tanggal_order" id="tanggal" value="<?= date('Y-m-d'); ?>" readonly required>
                </div>
                <div class="form-group col-md-3 mb-0">
                  <label for="waktu">Waktu:</label>
                  <input type="time" class="form-control" name="jam_order" id="waktu" value="<?= date('H:i:s'); ?>" readonly required>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col text-left"></div>
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