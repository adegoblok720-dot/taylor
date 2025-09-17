<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php">Taylor Baju</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span class="nav-link text-white">
            👤 <?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Guest'; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout_admin.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
