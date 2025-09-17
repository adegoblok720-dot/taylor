<?php
session_start();
include '../include/db.php';


$id_pengguna = $_SESSION['id_pengguna'];

// Batalkan pesanan jika ada request
if (isset($_GET['batal_id'])) {
  $id_pesanan = intval($_GET['batal_id']);
  $stmt = $conn->prepare("UPDATE pesanan SET status='dibatalkan' WHERE id_pesanan=? AND id_pengguna=? AND status='baru'");
  $stmt->bind_param("ii", $id_pesanan, $id_pengguna);
  $stmt->execute();
  header("Location: pesanan_saya.php");
  exit;
}

// Ambil daftar pesanan user
$sql = "
  SELECT p.id_pesanan, b.nama_baju, b.gambar, 
         p.jumlah, p.ukuran, p.catatan, 
         p.total, p.status, p.tanggal_pesanan
  FROM pesanan p
  JOIN baju b ON p.id_baju = b.id_baju
  WHERE p.id_pengguna=?
  ORDER BY p.tanggal_pesanan DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$pesanan = $stmt->get_result();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Pesanan Saya - Tailor Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body style="background-color:#f4f6f9;">

  <!-- Navbar -->
  <?php include '../user/navbar_user.php'; ?>

  <div class="container mt-5">
    <h3 class="mb-4"><i class="bi bi-box-seam"></i> Pesanan Saya</h3>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Ukuran</th>
            <th>Catatan</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($pesanan->num_rows > 0): ?>
            <?php while ($row = $pesanan->fetch_assoc()): ?>
              <tr>
                <td>
                  <img src="../uploads/<?= htmlspecialchars($row['gambar']); ?>" width="60" class="me-2 rounded">
                  <?= htmlspecialchars($row['nama_baju']); ?>
                </td>
                <td><?= $row['jumlah']; ?></td>
                <td><?= htmlspecialchars($row['ukuran']); ?></td>
                <td><?= htmlspecialchars($row['catatan']); ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
                <td>
                  <?php if ($row['status'] == 'baru'): ?>
                    <span class="badge bg-warning text-dark">Baru</span>
                  <?php elseif ($row['status'] == 'diproses'): ?>
                    <span class="badge bg-info">Diproses</span>
                  <?php elseif ($row['status'] == 'selesai'): ?>
                    <span class="badge bg-success">Selesai</span>
                  <?php else: ?>
                    <span class="badge bg-danger">Dibatalkan</span>
                  <?php endif; ?>
                </td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_pesanan'])); ?></td>
                <td>
                  <?php if ($row['status'] == 'baru'): ?>
                    <a href="?batal_id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin batalkan pesanan ini?')">
                      Batalkan
                    </a>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>