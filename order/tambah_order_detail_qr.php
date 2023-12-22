<?php
session_start();

include "../functions.php";

$data_menu = query("SELECT * FROM menu");

if (isset($_GET["id_order"])) {
  $id_order = $_GET["id_order"];

  $cek_id_order = mysqli_query($conn, "SELECT id_order FROM order_pesanan WHERE id_order = $id_order");
  if (mysqli_num_rows($cek_id_order) == 0) {
    echo "<script>alert('ID Order tidak ditemukan');</script>";
    die;
  }

  if (isset($_POST["selesai"])) {
    if (tambah_order_detail_qr($_POST) > 0) {
      echo "<script>
              alert('Pesanan berhasil ditambahkan! Terima kasih atas pesanannya.');
              document.location.href = 'tampil_order_detail_qr.php?id_order=$id_order';
            </script>
          ";
    } else {
      echo "<script>
              alert('Gagal menambahkan pesanan. Silakan coba lagi atau hubungi pelayan.');
              document.location.href = 'tambah_order_detail_qr.php?id_order=$id_order';
            </script>
          ";
    }
  }

  $data_order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
  $data_order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");
} else {
  echo "<script>alert('ID Order Invalid!');</script>";
  die;
}

date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Tambah Menu Pesanan</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>

<body>
  <?php include "../header.php" ?>

  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mx-auto mt-2">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">Tambah Menu Pesanan</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary text-center">
                  <tr>
                    <th class="align-middle">ID Order</th>
                    <th class="align-middle">Tanggal Order</th>
                    <th class="align-middle">Jam Order</th>
                    <th class="align-middle">Pelayan</th>
                    <th class="align-middle">No. Meja</th>
                    <th class="align-middle">Total Bayar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $id_order; ?></td>
                    <td><?= $data_order["tanggal_order"]; ?></td>
                    <td><?= $data_order["jam_order"]; ?></td>
                    <td><?= $data_order["nama_pelayan"]; ?></td>
                    <td><?= $data_order["no_meja"]; ?></td>
                    <?php
                    $total_bayar = 0;
                    foreach ($data_order_detail as $order_detail) {
                      $total_bayar += $order_detail["subtotal"];
                    }
                    ?>
                    <td>Rp<?php echo number_format($total_bayar, 0, ',', '.'); ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <form action="" method="post" class="mb-0">
            <input type="hidden" name="id_order" value="<?= $id_order; ?>">

            <div class="card-body" id="form-asli">
              <div class="form-row">
                <div class="form-group col-md-6 mb-0">
                  <label for="menu">Nama Menu:</label>
                  <select class="form-control" id="menu" name="id_menu[]" class="nama_menu" required>
                    <option value="" disabled selected>Pilih nama menu ...</option>
                    <?php foreach ($data_menu as $menu) : ?>
                      <option value="<?= $menu['id_menu']; ?>"><?= $menu["nama_menu"]; ?> # Rp<?= $menu["harga_menu"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-6 mb-0">
                  <label for="jumlah_order">Jumlah:</label>
                  <input type="number" class="form-control" id="jumlah_order" name="jumlah_order[]" min="1" placeholder="Masukkan jumlah ..." required>
                </div>
              </div>
            </div>

            <div id="form-dinamis">
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col text-left">
                  <button type="button" class="btn btn-info" onclick="copyForm()">More</button>
                </div>
                <div class="col text-center">
                  <button type="submit" class="btn btn-primary" name="selesai">Selesai</button>
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

  <script src="../jquery.min.js"></script>
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