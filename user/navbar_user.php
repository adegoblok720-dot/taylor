<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$nama_user = $_SESSION['nama_lengkap'] ?? 'Pelanggan';
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#0062cc;">
  <div class="container">
    <a class="navbar-brand fw-bold" href="user_dashboard.php">Tailor Baju</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="user_dashboard.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="produk_user.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="pesanan_saya.php">Pesanan Saya</a></li>
        <li class="nav-item"><a class="nav-link" href="profil_user.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="kontak.php">Kontak</a></li>
      </ul>
      <span class="navbar-text text-white me-3">
        👤 <?= htmlspecialchars($nama_user) ?>
      </span>
      <a href="logout_user.php" class="btn btn-light btn-sm">Keluar</a>
    </div>
  </div>
</nav>