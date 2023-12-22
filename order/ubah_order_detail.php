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

$data_menu = query("SELECT * FROM menu");

if (isset($_GET["id_order_detail"])) {
  $id_order_detail = $_GET["id_order_detail"];
  $data_order_detail_ubah = query("SELECT * FROM order_detail WHERE id_order_detail = $id_order_detail")[0];
  $id_order = $data_order_detail_ubah["id_order"];
  $data_order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
  $data_order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");

  if (isset($_POST["ubah"])) {
    if (ubah_order_detail($_POST) > 0) {
      echo "<script>
              alert('Data order detail berhasil diubah!');
              document.location.href = 'tampil_order_detail.php?id_order=$id_order';
            </script>
            ";
    } else {
      echo "<script>
              alert('Data order detail gagal diubah!');
              document.location.href = 'tampil_order_detail.php?id_order=$id_order';
            </script>
            ";
    }
  }
} else {
  echo "<script>
          alert('ID Order Detail Invalid!');
          document.location.href = 'tampil_order.php';
        </script>
        ";
}


date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Ubah Order Detail</title>

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
            <h3 class="card-title font-weight-bold">Ubah Order Detail</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary">
                  <tr>
                    <th>ID Order</th>
                    <th>Tanggal Order</th>
                    <th>Jam Order</th>
                    <th>Pelayan</th>
                    <th>No. Meja</th>
                    <th>Total Bayar</th>
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

          <?php  ?>
          <?php if (!empty($data_order_detail)) : ?>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="myTable">
                  <thead class="table-primary">
                    <tr>
                      <th>ID Order Detail</th>
                      <th>Nama Menu</th>
                      <th>Harga</th>
                      <th>Jumlah</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($data_order_detail as $row) : ?>
                      <tr <?php if ($row['id_order_detail'] == $id_order_detail) echo 'class="table-secondary"'; ?>>
                        <td><?= $row["id_order_detail"]; ?></td>
                        <?php
                        $id_menu = $row['id_menu'];
                        $menu = query("SELECT * FROM menu WHERE id_menu = $id_menu")[0];
                        ?>
                        <td><?= $menu["nama_menu"]; ?></td>
                        <td>Rp<?php echo number_format($row["harga"], 0, ',', '.'); ?></td>
                        <td><?= $row["jumlah_order"]; ?></td>
                        <td>Rp<?php echo number_format($row["subtotal"], 0, ',', '.'); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>

          <form action="" method="post" class="mb-0">
            <input type="hidden" name="id_order" value="<?= $id_order; ?>">
            <input type="hidden" name="id_order_detail" value="<?= $id_order_detail; ?>">
            <div class="card-body" id="form-asli">
              <div class="form-row">
                <div class="form-group col-md-4 mb-0">
                  <label for="nama_menu">Nama Menu:</label>
                  <select class="form-control" name="id_menu" id="nama_menu" required>
                    <option value="" disabled selected>Pilih nama menu ...</option>
                    <?php foreach ($data_menu as $menu) : ?>
                      <option value="<?= $menu['id_menu']; ?>" data-harga="<?= $menu['harga_menu']; ?>" <?php if ($data_order_detail_ubah['id_menu'] == $menu['id_menu']) echo 'selected'; ?>><?= $menu["nama_menu"]; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-md-4 mb-0">
                  <label for="harga">Harga:</label>
                  <input type="number" class="form-control" name="harga" id="harga" value="<?= $data_order_detail_ubah['harga']; ?>" readonly required>
                </div>
                <div class="form-group col-md-4 mb-0">
                  <label for="jumlah_order">Jumlah:</label>
                  <input type="number" class="form-control" name="jumlah_order" id="jumlah_order" min="1" placeholder="Masukkan jumlah ..." value="<?= $data_order_detail_ubah['jumlah_order']; ?>" required>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col text-left">
                  <a href="tampil_order_detail.php?id_order=<?= $id_order; ?>"><button type="button" class="btn btn-default" name="kembali">Kembali</button></a>
                </div>
                <div class="col text-center">
                  <button type="submit" class="btn btn-info" name="ubah">Ubah</button>
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

  <script>
    $(document).ready(function() {
      $('#nama_menu').change(function() {
        var selectedOption = $('#nama_menu option:selected');
        var hargaMenu = selectedOption.data('harga');
        $('#harga').val(hargaMenu);
      });
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>