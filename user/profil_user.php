<?php
session_start();
include '../include/db.php';

// Cek login user
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'pelanggan') {
  header("Location: login_user.php");
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();
$pengguna = $result->fetch_assoc();

if (!$pengguna) {
  die("User tidak ditemukan.");
}

// Update profil jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;

  $update = $conn->prepare("
        UPDATE pengguna 
        SET nama_lengkap = ?, email = ?, tanggal_lahir = ?
        WHERE id_pengguna = ?
    ");
  $update->bind_param("sssi", $nama, $email, $tanggal_lahir, $id_pengguna);
  $update->execute();

  // Refresh data
  header("Location: profil_user.php");
  exit;
}
?>

<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Profil User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php include 'navbar_user.php'; ?>

  <div class="container py-4">
    <h3 class="mb-4">👤 Profil Saya</h3>

    <form method="post">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required
          value="<?= htmlspecialchars($pengguna['nama'] ?? ''); ?>">
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required
          value="<?= htmlspecialchars($pengguna['email'] ?? ''); ?>">
      </div>

      <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control"
          value="<?= !empty($pengguna['tanggal_lahir']) ? htmlspecialchars($pengguna['tanggal_lahir']) : ''; ?>">
      </div>

      <button type="submit" class="btn btn-success">Update Profil</button>
    </form>

    <hr>
    <p><strong>Info Lain:</strong></p>
    <p>ID Pengguna: <?= $pengguna['id_pengguna']; ?></p>
    <p>Tanggal Bergabung: <?= !empty($pengguna['created_at']) ? date('d-m-Y', strtotime($pengguna['created_at'])) : '-'; ?></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>