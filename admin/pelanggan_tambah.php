<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'] ?? '';
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $role = "user";

  if ($nama && $username && $password) {
    // cek apakah username sudah ada
    $cek = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE username=?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
      $error = "Username sudah dipakai, silakan pilih username lain!";
    } else {
      $password = md5($password);
      $stmt = $conn->prepare("INSERT INTO pengguna (nama, username, password, role) VALUES (?,?,?,?)");
      $stmt->bind_param("ssss", $nama, $username, $password, $role);
      $stmt->execute();
      header("Location: pelanggan.php");
      exit;
    }
  } else {
    $error = "Semua field wajib diisi!";
  }
}

?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Tambah Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <?php include '../include/sidebar.php'; ?>
  <div class="container mt-4">
    <h3>Tambah Pelanggan</h3>
    <form method="post">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
      <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>