<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama_kategori'];
  $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
  $stmt->bind_param("s", $nama);
  $stmt->execute();
  header("Location: kategori.php");
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Tambah Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <?php include '../include/sidebar.php'; ?>
  <div class="container mt-4">
    <h3>Tambah Kategori</h3>
    <form method="post">
      <div class="mb-3">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" class="form-control" required>
      </div>
      <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
      <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>