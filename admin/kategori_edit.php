<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

$id = $_GET['id'];
$qKategori = $conn->prepare("SELECT * FROM kategori WHERE id_kategori=?");
$qKategori->bind_param("i", $id);
$qKategori->execute();
$result = $qKategori->get_result();
$data = $result->fetch_assoc();

if (isset($_POST['update'])) {
  $nama = $_POST['nama_kategori'];
  $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=? WHERE id_kategori=?");
  $stmt->bind_param("si", $nama, $id);
  $stmt->execute();
  header("Location: kategori.php");
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Edit Kategori</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <?php include '../include/sidebar.php'; ?>
  <div class="container mt-4">
    <h3>Edit Kategori</h3>
    <form method="post">
      <div class="mb-3">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" value="<?= $data['nama_kategori']; ?>" class="form-control" required>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>