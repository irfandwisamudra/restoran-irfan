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

$id_order = $_GET["id_order"];

if (batal_selesai_order($id_order) > 0) {
  echo "<script>
          alert('Data order telah dibatalkan selesai!');
          document.location.href = 'tampil_order.php';
        </script>
        ";
} else {
  echo "<script>
          alert('Data order gagal dibatalkan selesai!');
          document.location.href = 'tampil_order.php';
        </script>
        ";
}
?>