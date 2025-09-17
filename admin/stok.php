<?php
session_start();
include '../include/db.php';

// Cek apakah admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

// Ambil data stok
$q = $conn->query("
    SELECT b.id_baju, b.nama_baju, k.nama_kategori, b.harga_jahit, b.stok
    FROM baju b
    JOIN kategori k ON b.id_kategori = k.id_kategori
    ORDER BY b.id_baju DESC
");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Manajemen Stok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-2"><?php include '../include/sidebar.php'; ?></div>
      <div class="col-10 p-4" style="margin-left:220px;">
        <h3>📦 Manajemen Stok</h3>
        <table class="table table-bordered align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Nama Baju</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            while ($row = $q->fetch_assoc()): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_baju']); ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                <td>Rp <?= number_format($row['harga_jahit'], 0, ",", "."); ?></td>
                <td><?= $row['stok']; ?></td>
                <td>
                  <a href="stok_edit.php?id=<?= $row['id_baju']; ?>" class="btn btn-warning btn-sm">Update Stok</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>