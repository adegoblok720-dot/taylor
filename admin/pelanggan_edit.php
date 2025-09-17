<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

$id = $_GET['id'];
$q = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna=? AND role='user'");
$q->bind_param("i", $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if (isset($_POST['update'])) {
  $nama = $_POST['nama'];
  $username = $_POST['username'];

  if (!empty($_POST['password'])) {
    $password = md5($_POST['password']);
    $stmt = $conn->prepare("UPDATE pengguna SET nama=?, username=?, password=? WHERE id_pengguna=?");
    $stmt->bind_param("sssi", $nama, $username, $password, $id);
  } else {
    $stmt = $conn->prepare("UPDATE pengguna SET nama=?, username=? WHERE id_pengguna=?");
    $stmt->bind_param("ssi", $nama, $username, $id);
  }

  $stmt->execute();
  header("Location: pelanggan.php");
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Edit Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <?php include '../include/sidebar.php'; ?>
  <div class="container mt-4">
    <h3>Edit Pelanggan</h3>
    <form method="post">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" value="<?= $data['username']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password (Kosongkan jika tidak diubah)</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="pelanggan.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>