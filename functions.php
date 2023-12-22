<?php
$conn = mysqli_connect("localhost", "root", "", "restoran-irfan");

function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

function tambah_menu($data)
{
  global $conn;

  $jumlah_data = count($data['nama_menu']);

  for ($i = 0; $i < $jumlah_data; $i++) {
    $jenis_menu = htmlspecialchars($data['jenis_menu'][$i]);
    $nama_menu = htmlspecialchars($data['nama_menu'][$i]);
    $harga_menu = htmlspecialchars($data['harga_menu'][$i]);
    $stok = htmlspecialchars($data['stok'][$i]);

    $query = "INSERT INTO menu (jenis_menu, nama_menu, harga_menu, stok) VALUES ('$jenis_menu', '$nama_menu', '$harga_menu', '$stok')";

    mysqli_query($conn, $query);
  }

  return mysqli_affected_rows($conn);
}

function ubah_menu($data)
{
  global $conn;

  $id_menu = $data["id_menu"];
  $jenis_menu = htmlspecialchars($data["jenis_menu"]);
  $nama_menu = htmlspecialchars($data["nama_menu"]);
  $harga_menu = htmlspecialchars($data["harga_menu"]);
  $stok = htmlspecialchars($data["stok"]);

  $query = "UPDATE menu SET
              jenis_menu = '$jenis_menu',
              nama_menu = '$nama_menu',
              harga_menu = '$harga_menu',
              stok = '$stok'
              WHERE id_menu = $id_menu
              ";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function hapus_menu($id)
{
  global $conn;

  mysqli_query($conn, "DELETE FROM menu WHERE id_menu = $id");

  return mysqli_affected_rows($conn);
}

function tambah_order($data)
{
  global $conn;

  $tanggal_order = htmlspecialchars($data["tanggal_order"]);
  $jam_order = htmlspecialchars($data["jam_order"]);
  $nama_pelayan = htmlspecialchars($data["nama_pelayan"]);
  $no_meja = htmlspecialchars($data["no_meja"]);

  $query = "INSERT INTO order_pesanan (tanggal_order, jam_order, nama_pelayan, no_meja, total_bayar, status_order) VALUES ('$tanggal_order', '$jam_order', '$nama_pelayan', '$no_meja', 0, 'Dalam proses')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function ubah_order($data)
{
  global $conn;

  $id_order = $data["id_order"];
  $nama_pelayan = htmlspecialchars($data["nama_pelayan"]);
  $no_meja = htmlspecialchars($data["no_meja"]);

  $query = "UPDATE order_pesanan SET
              nama_pelayan = '$nama_pelayan',
              no_meja = '$no_meja'
              WHERE id_order = $id_order
              ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function hapus_order($id)
{
  global $conn;

  $order_detail_terkait = mysqli_query($conn, "SELECT * FROM order_detail WHERE id_order = $id");

  if (mysqli_num_rows($order_detail_terkait) > 0) {
    echo "<script>
            alert('Tidak dapat menghapus data order ini karena masih ada data order detail terkait.');
            document.location.href = 'tampil_order.php';
          </script>
          ";
  } else {
    mysqli_query($conn, "DELETE FROM order_pesanan WHERE id_order = $id");
  };

  return mysqli_affected_rows($conn);
}

function selesai_order($id)
{
  global $conn;

  $query = "UPDATE order_pesanan SET
              status_order = 'Selesai'
              WHERE id_order = $id
              ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function batal_selesai_order($id)
{
  global $conn;

  $query = "UPDATE order_pesanan SET
              status_order = 'Dalam Proses'
              WHERE id_order = $id
              ";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function tambah_order_detail($data)
{
  global $conn;

  $id_order = $data["id_order"];
  $id_menu = $data['id_menu'];
  $jumlah_order = htmlspecialchars($data['jumlah_order']);

  $data_menu = query("SELECT nama_menu, harga_menu, stok FROM menu WHERE id_menu=$id_menu")[0];
  $harga = $data_menu["harga_menu"];
  $stok = $data_menu["stok"];

  if ($jumlah_order > $stok) {
    $sisa_stok = $stok;
    echo "<script>alert('Stok menu {$data_menu['nama_menu']} tidak mencukupi, sisa stok: $sisa_stok');</script>";
    return false;
  }

  $subtotal = $harga * $jumlah_order;

  $query = "INSERT INTO order_detail (id_order, id_menu, jumlah_order, harga, subtotal) VALUES ('$id_order', '$id_menu', '$jumlah_order', '$harga', '$subtotal')";
  mysqli_query($conn, $query);

  $total = query("SELECT SUM(subtotal) AS total_bayar FROM order_detail WHERE id_order=$id_order")[0];
  $total_bayar = $total["total_bayar"];

  $query = "UPDATE order_pesanan SET total_bayar = '$total_bayar' WHERE id_order = $id_order";
  mysqli_query($conn, $query);

  $sisa_stok = $stok - $jumlah_order;
  mysqli_query($conn, "UPDATE menu SET stok = '$sisa_stok' WHERE id_menu = $id_menu");

  return mysqli_affected_rows($conn);
}

function ubah_order_detail($data)
{
  global $conn;

  $id_order = $data["id_order"];
  $id_order_detail = $data["id_order_detail"];

  $id_menu = $data['id_menu'];
  $jumlah_order = htmlspecialchars($data['jumlah_order']);

  $order_detail_sebelum = query("SELECT * FROM order_detail WHERE id_order_detail = $id_order_detail")[0];
  $id_menu_sebelum = $order_detail_sebelum['id_menu'];
  $jumlah_order_sebelum = $order_detail_sebelum['jumlah_order'];

  if ($order_detail_sebelum['id_menu'] == $id_menu && $order_detail_sebelum['jumlah_order'] == $jumlah_order) {
    return false;
  }

  $data_menu = query("SELECT nama_menu, harga_menu, stok FROM menu WHERE id_menu=$id_menu")[0];

  if ($jumlah_order > $data_menu['stok']) {
    $sisa_stok = $data_menu['stok'];
    echo "<script>alert('Stok menu {$data_menu['nama_menu']} tidak mencukupi, sisa stok: $sisa_stok');</script>";
    return false;
  }

  $menu_sebelum = query("SELECT stok FROM menu WHERE id_menu = $id_menu_sebelum")[0];
  $stok_kembali = $menu_sebelum['stok'] + $jumlah_order_sebelum;
  mysqli_query($conn, "UPDATE menu SET stok = '$stok_kembali' WHERE id_menu = $id_menu_sebelum");

  $harga = $data_menu["harga_menu"];
  $subtotal = $harga * $jumlah_order;

  $query = "UPDATE order_detail SET
              id_order = '$id_order',
              id_menu = '$id_menu',
              jumlah_order = '$jumlah_order',
              harga = '$harga',
              subtotal = '$subtotal'
              WHERE id_order_detail = $id_order_detail
            ";
  mysqli_query($conn, $query);

  $total = query("SELECT SUM(subtotal) AS total_bayar FROM order_detail WHERE id_order=$id_order")[0];
  $total_bayar = $total["total_bayar"];

  $query = "UPDATE order_pesanan SET total_bayar = '$total_bayar' WHERE id_order = $id_order";
  mysqli_query($conn, $query);

  $sisa_stok = $data_menu['stok'] - $jumlah_order;
  mysqli_query($conn, "UPDATE menu SET stok = '$sisa_stok' WHERE id_menu = $id_menu");

  return mysqli_affected_rows($conn);
}

function hapus_order_detail($id)
{
  global $conn;

  $data_order = query("SELECT id_order FROM order_detail WHERE id_order_detail = $id")[0];
  $id_order = $data_order["id_order"];

  mysqli_query($conn, "DELETE FROM order_detail WHERE id_order_detail = $id");

  $total = query("SELECT SUM(subtotal) AS total_bayar FROM order_detail WHERE id_order=$id_order")[0];
  $total_bayar = $total["total_bayar"];

  $query = "UPDATE order_pesanan SET total_bayar = '$total_bayar' WHERE id_order = $id_order";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function tambah_order_detail_qr($data)
{
  global $conn;

  $id_order = $data["id_order"];
  $id_menu_array = $data['id_menu'];
  $jumlah_order_array = $data['jumlah_order'];

  for ($i = 0; $i < count($id_menu_array); $i++) {
    $id_menu = $id_menu_array[$i];
    $jumlah_order = htmlspecialchars($jumlah_order_array[$i]);

    $data_menu = query("SELECT nama_menu, harga_menu, stok FROM menu WHERE id_menu=$id_menu")[0];
    $harga = $data_menu["harga_menu"];
    $stok = $data_menu["stok"];

    if ($jumlah_order > $stok) {
      $sisa_stok = $stok;
      echo "<script>alert('Stok menu {$data_menu['nama_menu']} tidak mencukupi, sisa stok: $sisa_stok');</script>";
      return false;
    }

    $subtotal = $harga * $jumlah_order;

    $query = "INSERT INTO order_detail (id_order, id_menu, jumlah_order, harga, subtotal) VALUES ('$id_order', '$id_menu', '$jumlah_order', '$harga', '$subtotal')";
    mysqli_query($conn, $query);

    $total = query("SELECT SUM(subtotal) AS total_bayar FROM order_detail WHERE id_order=$id_order")[0];
    $total_bayar = $total["total_bayar"];

    $query = "UPDATE order_pesanan SET total_bayar = '$total_bayar' WHERE id_order = $id_order";
    mysqli_query($conn, $query);

    $sisa_stok = $stok - $jumlah_order;
    mysqli_query($conn, "UPDATE menu SET stok = '$sisa_stok' WHERE id_menu = $id_menu");
  }

  return true;
}

function register($data)
{
  global $conn;

  $nama_user = htmlspecialchars($data["nama_user"]);
  $username = strtolower(stripslashes($data["username"]));
  $password = md5(mysqli_real_escape_string($conn, $data["password1"]));
  $level = htmlspecialchars($data["level"]);

  $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

  if (mysqli_fetch_assoc($result)) {
    echo "<script>
				    alert('Username sudah terdaftar!')
		      </script>";
    return false;
  }

  $query = "INSERT INTO user (nama_user, username, `password`, `level`) VALUES ('$nama_user', '$username', '$password', '$level')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}
?>