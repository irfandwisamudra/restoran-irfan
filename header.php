<style>
  body {
    background: linear-gradient(135deg, #6bffff, #000000);
  }
</style>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand text-white" href="../index.php"><strong>Restoran</strong> Irfan</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <?php if ($_SESSION["level"] != 3) : ?>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="../index.php">Beranda</a>
          </li>
          <?php if ($_SESSION["level"] == 1) : ?>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" href="../menu/tampil_menu.php">Menu</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="../order/tampil_order.php">Order</a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold" href="../order/order_qr.php">QR Code</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link font-weight-bold" href="../logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div style="height: 62px;"></div>