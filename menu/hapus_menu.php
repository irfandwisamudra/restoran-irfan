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

$id = $_GET["id"];

if (hapus_menu($id) > 0) {
  echo "<script>
          alert('Data menu berhasil dihapus!');
          document.location.href = 'tampil_menu.php';
        </script>
        ";
} else {
  echo "<script>
          alert('Data menu gagal dihapus!');
          document.location.href = 'tampil_menu.php';
        </script>
        ";
}
?>