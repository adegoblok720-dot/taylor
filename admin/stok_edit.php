<?php
session_start();
include '../include/db.php';

// Cek admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

$id = $_GET['id'];
$q = $conn->prepare("SELECT * FROM baju WHERE id_baju=?");
$q->bind_param("i", $id);
$q->execute();
$data = $q->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $stok = $_POST['stok'];

  $update = $conn->prepare("UPDATE baju SET stok=? WHERE id_baju=?");
  $update->bind_param("ii", $stok, $id);
  $update->execute();

  header("Location: stok.php");
  exit;
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Edit Stok</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-4">
    <h3>Update Stok</h3>
    <form method="post">
      <div class="mb-3">
        <label>Nama Baju</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_baju']); ?>" readonly>
      </div>
      <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
      </div>
      <button type="submit" class="btn btn-success">Simpan</button>
      <a href="stok.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>