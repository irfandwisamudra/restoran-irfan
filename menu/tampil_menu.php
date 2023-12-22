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

$query = "SELECT * FROM menu";
if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];

  switch ($sort) {
    case 'nama_menu_asc':
      $query .= " ORDER BY nama_menu ASC";
      break;
    case 'nama_menu_desc':
      $query .= " ORDER BY nama_menu DESC";
      break;
    case 'harga_menu_asc':
      $query .= " ORDER BY harga_menu ASC";
      break;
    case 'harga_menu_desc':
      $query .= " ORDER BY harga_menu DESC";
      break;
    default:
      $query .= " ORDER BY nama_menu ASC";
  }
}

$menu = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran - Daftar Menu</title>

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
            <h3 class="card-title font-weight-bold">Daftar Menu</h3>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-end">
              <a href="tambah_menu.php" class="d-flex justify-content-end"><button class="btn btn-success mb-3">Tambah Menu</button></a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover table-bordered mb-0">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>ID Menu</th>
                    <th>Jenis Menu</th>
                    <th>
                      <a class="table-link" href="?sort=<?= (isset($_GET['sort']) && $_GET['sort'] == 'nama_menu_asc') ? 'nama_menu_desc' : 'nama_menu_asc'; ?>">
                        Nama Menu
                        <i class="fa <?= (isset($_GET['sort']) && ($_GET['sort'] == 'nama_menu_asc' || $_GET['sort'] == 'nama_menu_desc')) ? ($_GET['sort'] == 'nama_menu_asc' ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'; ?>"></i>
                      </a>
                    </th>
                    <th>
                      <a class="table-link" href="?sort=<?= (isset($_GET['sort']) && $_GET['sort'] == 'harga_menu_asc') ? 'harga_menu_desc' : 'harga_menu_asc'; ?>">
                        Harga Menu
                        <i class="fa <?= (isset($_GET['sort']) && ($_GET['sort'] == 'harga_menu_asc' || $_GET['sort'] == 'harga_menu_desc')) ? ($_GET['sort'] == 'harga_menu_asc' ? 'fa-sort-asc' : 'fa-sort-desc') : 'fa-sort'; ?>"></i>
                      </a>
                    </th>
                    <th>Stok</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; ?>
                  <?php foreach ($menu as $row) : ?>
                    <tr>
                      <td><?= $no; ?></td>
                      <td><?= $row["id_menu"]; ?></td>
                      <td><?= $row["jenis_menu"]; ?></td>
                      <td><?= $row["nama_menu"]; ?></td>
                      <td>Rp<?php echo number_format($row["harga_menu"], 0, ',', '.'); ?></td>
                      <td><?= $row["stok"]; ?></td>
                      <td>
                        <a href="ubah_menu.php?id=<?= $row["id_menu"]; ?>"><button class='btn btn-warning btn-sm mr-2 mb-1'>Ubah</button></a>
                        <a href="hapus_menu.php?id=<?= $row["id_menu"]; ?>" onclick="return confirm('Yakin akan dihapus?');"><button class='btn btn-danger btn-sm mb-1'>Hapus</button></a>
                      </td>
                    </tr>
                    <?php $no++ ?>
                  <?php endforeach; ?>
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