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

$order_query = "SELECT * FROM order_pesanan";

if (isset($_GET['key'])) {
  if ($_GET['key'] == "") {
    header("Location: tampil_order.php");
    exit();
  }
  $key = $_GET['key'];
  $order_query .= " WHERE tanggal_order LIKE '%$key%' OR jam_order LIKE '%$key%' OR nama_pelayan LIKE '%$key%' OR no_meja LIKE '%$key%' OR total_bayar LIKE '%$key%' OR status_order LIKE '%$key%'";
}

if (isset($_GET['sort'])) {
  switch ($_GET['sort']) {
    case 'waktu_asc':
      $order_query .= " ORDER BY tanggal_order ASC, jam_order ASC";
      break;
    case 'waktu_desc':
      $order_query .= " ORDER BY tanggal_order DESC, jam_order DESC";
      break;
    case 'meja_asc':
      $order_query .= " ORDER BY no_meja ASC";
      break;
    case 'meja_desc':
      $order_query .= " ORDER BY no_meja DESC";
      break;
    case 'total_bayar_asc':
      $order_query .= " ORDER BY total_bayar ASC";
      break;
    case 'total_bayar_desc':
      $order_query .= " ORDER BY total_bayar DESC";
      break;
    default:
      $order_query .= " ORDER BY status_order ASC, id_order DESC";
  }
} else {
  $order_query .= " ORDER BY status_order ASC, id_order DESC";
}

$param_sort = "?";
$param_page = "?";
if (isset($_GET) && count($_GET) > 0) {
  foreach ($_GET as $key => $value) {
    if ($key !== "sort") {
      $param_sort .= $key . "=" . $value . "&";
    }
    if ($key !== "page") {
      $param_page .= $key . "=" . $value . "&";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Data Order</title>

  <style>
    .table-link {
      color: inherit;
      text-decoration: none;
    }

    .table-link:hover {
      color: black;
      text-decoration: underline;
    }
  </style>

  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <?php include "../header.php" ?>

  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mx-auto mt-2">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title font-weight-bold">Data Order</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
              <a href="order_qr.php"><button class="btn btn-primary mr-3">QR Code Order</button></a>
              <a href="tambah_order.php"><button class="btn btn-success">Tambah Order</button></a>
            </div>
            <div class="d-flex justify-content-start mb-2">
              <form action="" method="get" class="d-flex">
                <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search" name="key" autocomplete="off">
                <button class="btn btn-outline-primary" type="submit">Search</button>
              </form>
            </div>
            <?php if (isset($_GET['key'])) : ?>
              <div class="mb-3">
                <b>Hasil pencarian: <?= $_GET['key']; ?></b>
              </div>
            <?php endif; ?>
            <div class="table-responsive mb-3">
              <table class="table table-hover table-bordered mb-0" id="myTable">
                <thead class="table-primary text-center">
                  <tr>
                    <th class="align-middle">No</th>
                    <th class="align-middle">ID Order</th>
                    <th class="align-middle">
                      <a class="table-link" href="<?= $param_sort; ?>sort=<?= (isset($_GET['sort']) && $_GET['sort'] == 'waktu_asc') ? 'waktu_desc' : 'waktu_asc'; ?>">
                        Waktu <i class="fa <?= (isset($_GET['sort']) && ($_GET['sort'] == 'waktu_asc' || $_GET['sort'] == 'waktu_desc')) ? ($_GET['sort'] == 'waktu_asc' ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'; ?>"></i>
                      </a>
                    </th>
                    <th class="align-middle">Pelayan</th>
                    <th class="align-middle">
                      <a class="table-link" href="<?= $param_sort; ?>sort=<?= (isset($_GET['sort']) && $_GET['sort'] == 'meja_asc') ? 'meja_desc' : 'meja_asc'; ?>">
                        No. Meja <i class="fa <?= (isset($_GET['sort']) && ($_GET['sort'] == 'meja_asc' || $_GET['sort'] == 'meja_desc')) ? ($_GET['sort'] == 'meja_asc' ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'; ?>"></i>
                      </a>
                    </th>
                    <th class="align-middle">
                      <a class="table-link" href="<?= $param_sort; ?>sort=<?= (isset($_GET['sort']) && $_GET['sort'] == 'total_bayar_asc') ? 'total_bayar_desc' : 'total_bayar_asc'; ?>">
                        Total Bayar <i class="fa <?= (isset($_GET['sort']) && ($_GET['sort'] == 'total_bayar_asc' || $_GET['sort'] == 'total_bayar_desc')) ? ($_GET['sort'] == 'total_bayar_asc' ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'; ?>"></i>
                      </a>
                    </th>
                    <th class="align-middle">Status</th>
                    <th class="align-middle">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $batas = 10;
                  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                  $page_awal = ($page > 1) ? ($page * $batas) - $batas : 0;

                  $previous = $page - 1;
                  $next = $page + 1;

                  $data = mysqli_query($conn, $order_query);
                  $jumlah_data = mysqli_num_rows($data);
                  $total_page = ceil($jumlah_data / $batas);

                  $order_query .= " LIMIT $page_awal, $batas";

                  $orders = query($order_query);

                  $nomor = $page_awal + 1;
                  foreach ($orders as $row) : ?>
                    <tr>
                      <td><?= $nomor++; ?></td>
                      <td><?= $row["id_order"]; ?></td>
                      <td><?= $row["tanggal_order"] . ' ' . $row["jam_order"]; ?></td>
                      <td><?= $row["nama_pelayan"]; ?></td>
                      <td><?= $row["no_meja"]; ?></td>
                      <td>Rp<?= number_format($row["total_bayar"], 0, ',', '.'); ?></td>
                      <td><?= $row["status_order"]; ?></td>
                      <td>
                        <a href="tampil_order_detail.php?id_order=<?= $row["id_order"]; ?>"><button class='btn btn-info btn-sm mb-1'>Lihat Detail</button></a>
                        <a href="ubah_order.php?id_order=<?= $row["id_order"]; ?>"><button class='btn btn-warning btn-sm mb-1'>Ubah</button></a>
                        <a href="hapus_order.php?id_order=<?= $row["id_order"]; ?>" onclick="return confirm('Yakin akan dihapus?');"><button class='btn btn-danger btn-sm mb-1'>Hapus</button></a>
                        <?php if ($row["status_order"] != "Selesai") : ?>
                          <a href="selesai_order.php?id_order=<?= $row["id_order"]; ?>" onclick="return confirm('Tandai order sebagai selesai?');"><button class='btn btn-success btn-sm mb-1'>Selesai</button></a>
                        <?php else : ?>
                          <a href="batal_selesai_order.php?id_order=<?= $row["id_order"]; ?>" onclick="return confirm('Batalkan order sebagai selesai?');"><button class='btn btn-success btn-sm mb-1'>Batal Selesai</button></a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-end">
              <nav>
                <ul class="pagination justify-content-center">
                  <li class="page-item <?= ($page > 1) ? '' : 'disabled' ?>">
                    <a class="page-link" <?= ($page > 1) ? "href='" . $param_page . "page=$previous'" : "tabindex='-1' aria-disabled='true'" ?>>Previous</a>
                  </li>
                  <?php for ($x = 1; $x <= $total_page; $x++) : ?>
                    <?php if ($x >= $page - 2 && $x <= $page + 2) : ?>
                      <li class="page-item <?= ($x == $page) ? 'active' : '' ?>"><a class="page-link" href="<?= $param_page; ?>page=<?= $x ?>"><?= $x; ?></a></li>
                    <?php elseif ($x == $page - 3 || $x == $page + 3) : ?>
                      <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                  <?php endfor; ?>
                  <li class="page-item <?= ($page < $total_page) ? '' : 'disabled' ?>">
                    <a class="page-link" <?= ($page < $total_page) ? "href='" . $param_page . "page=$next'" : "tabindex='-1' aria-disabled='true'" ?>>Next</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>