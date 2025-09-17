<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna'])) {
  header("Location: login_user.php");
  exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil data pesanan user
$stmt = $conn->prepare("
  SELECT p.*, b.nama_baju, b.gambar 
  FROM pesanan p
  JOIN baju b ON p.id_baju = b.id_baju
  WHERE p.id_pengguna=?
  ORDER BY p.tanggal_pesanan DESC
");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$pesanan = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Pesanan Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <?php include 'navbar_user.php'; ?> <!-- ✅ navbar -->

  <div class="container py-5">
    <h3 class="mb-4">Pesanan Saya</h3>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">Pesanan berhasil dibuat!</div>
    <?php endif; ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-primary">
          <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Ukuran</th>
            <th>Catatan</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($pesanan->num_rows > 0): ?>
            <?php $no = 1;
            while ($row = $pesanan->fetch_assoc()): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td>
                  <img src="../uploads/<?= htmlspecialchars($row['gambar']); ?>" width="60" class="me-2 rounded">
                  <?= htmlspecialchars($row['nama_baju']); ?>
                </td>
                <td><?= $row['jumlah']; ?></td>
                <td><?= htmlspecialchars($row['ukuran']); ?></td>
                <td><?= htmlspecialchars($row['catatan']); ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td>
                  <span class="badge bg-<?= $row['status'] == 'baru' ? 'warning' : 'success'; ?>">
                    <?= ucfirst($row['status']); ?>
                  </span>
                </td>
                <td><?= $row['tanggal_pesanan']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center">Belum ada pesanan.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>