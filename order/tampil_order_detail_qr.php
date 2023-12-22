<?php
session_start();

include "../functions.php";

if (isset($_GET["id_order"])) {
  $id_order = $_GET["id_order"];

  $cek_id_order = mysqli_query($conn, "SELECT id_order FROM order_pesanan WHERE id_order = $id_order");
  if (mysqli_num_rows($cek_id_order) == 0) {
    echo "<script>alert('ID Order tidak ditemukan');</script>";
    die;
  }
} else {
  echo "<script>alert('ID Order Invalid!');</script>";
  die;
}

$data_order = query("SELECT * FROM order_pesanan WHERE id_order = $id_order")[0];
$data_order_detail = query("SELECT * FROM order_detail WHERE id_order = $id_order");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Rincian Detail Pesanan</title>

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
            <h3 class="card-title font-weight-bold">Rincian Detail Pesanan</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-start">
              <a href="tambah_order_qr.php?no_meja=<?= $data_order["no_meja"]; ?>"><button class="btn btn-default mb-3 mr-2">Kembali</button></a>
            </div>
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
                    <th class="align-middle">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?= $id_order; ?></td>
                    <td><?= $data_order["tanggal_order"]; ?></td>
                    <td><?= $data_order["jam_order"]; ?></td>
                    <td><?= $data_order["nama_pelayan"]; ?></td>
                    <td><?= $data_order["no_meja"]; ?></td>
                    <td>Rp<?php echo number_format($data_order["total_bayar"], 0, ',', '.'); ?></td>
                    <td><?= $data_order["status_order"]; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0" id="myTable">
                <?php if (!empty($data_order_detail)) : ?>
                  <thead class="table-primary text-center">
                    <tr>
                      <th class="align-middle">No</th>
                      <th class="align-middle">ID Order Detail</th>
                      <th class="align-middle">Nama Menu</th>
                      <th class="align-middle">Harga</th>
                      <th class="align-middle">Jumlah</th>
                      <th class="align-middle">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($data_order_detail as $row) : ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $row["id_order_detail"]; ?></td>

                        <?php
                        $id_menu = $row['id_menu'];
                        $menu = query("SELECT * FROM menu WHERE id_menu = $id_menu")[0];
                        $nama_menu = $menu["nama_menu"];
                        $harga_menu = $row["harga"];
                        $jumlah_order = $row["jumlah_order"];
                        $subharga = $harga_menu * $jumlah_order;
                        ?>

                        <td><?= $nama_menu; ?></td>
                        <td>Rp<?php echo number_format($harga_menu, 0, ',', '.'); ?></td>
                        <td><?= $jumlah_order; ?></td>
                        <td>Rp<?php echo number_format($subharga, 0, ',', '.'); ?></td>
                      </tr>
                      <?php
                      $no++;
                      ?>
                    <?php endforeach; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="5">Total Bayar</th>
                      <th colspan="2">Rp<?php echo number_format($data_order["total_bayar"], 0, ',', '.'); ?></th>
                    </tr>
                  </tfoot>
                <?php else : ?>
                  <tr>
                    <td class="text-center font-weight-bold">BELUM ADA MENU YANG DIORDER</td>
                  </tr>
                <?php endif; ?>
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