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

$id_order_detail = $_GET["id_order_detail"];
$data_order_detail = query("SELECT * FROM order_detail WHERE id_order_detail = $id_order_detail")[0];
$id_order = $data_order_detail["id_order"];

if (hapus_order_detail($id_order_detail) > 0) {
  echo "<script>
          alert('Data order detail berhasil dihapus!');
          document.location.href = 'tampil_order_detail.php?id_order=$id_order';
        </script>
        ";
} else {
  echo "<script>
          alert('Data order detail gagal dihapus!');
          document.location.href = 'tampil_order_detail.php?id_order=$id_order';
        </script>
        ";
}
?>