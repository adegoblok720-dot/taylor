<?php
session_start();
include '../include/db.php';

// cek login
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

$qPelanggan = $conn->query("SELECT * FROM pengguna WHERE role='pelanggan' ORDER BY id_pengguna DESC");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Data Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-2">
        <?php include '../include/sidebar.php'; ?>
      </div>
      <div class="col-10 p-4" style="margin-left:220px;">
        <h3>👤 Data Pelanggan</h3>
        <a href="pelanggan_tambah.php" class="btn btn-success mb-3">+ Tambah Pelanggan</a>
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            while ($row = $qPelanggan->fetch_assoc()): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama_lengkap']; ?></td>
                <td><?= $row['username']; ?></td>
                <td>
                  <a href="pelanggan_edit.php?id=<?= $row['id_pengguna']; ?>" class="btn btn-info btn-sm">Edit</a>
                  <a href="pelanggan_hapus.php?id=<?= $row['id_pengguna']; ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</a>
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