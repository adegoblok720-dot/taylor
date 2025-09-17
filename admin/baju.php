<?php
session_start();
include '../include/db.php';
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

// Ambil semua baju beserta kategori dan merek
$qBaju = $conn->query("
    SELECT b.id_baju, b.nama_baju, b.ukuran, b.deskripsi, b.gambar, 
           b.harga_jahit, b.stok, k.nama_kategori, m.nama_merek
    FROM baju b
    LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
    LEFT JOIN merek m ON b.id_merek = m.id_merek
    ORDER BY b.id_baju DESC
");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Admin - Data Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .img-thumb {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .catatan {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  </style>
</head>

<body>

  <?php include '../include/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-2"><?php include '../include/sidebar.php'; ?></div>
      <div class="col-10 p-4" style="margin-left:220px;">
        <h3>👕 Data Baju</h3>
        <a href="baju_tambah.php" class="btn btn-success mb-3">+ Tambah Baju</a>

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-dark text-center">
              <tr>
                <th>#</th>
                <th>Gambar</th>
                <th>Nama Baju</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th>Ukuran</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              while ($row = $qBaju->fetch_assoc()): ?>
                <tr>
                  <td class="text-center"><?= $no++; ?></td>
                  <td class="text-center">
                    <?php if (!empty($row['gambar'])): ?>
                      <img src="../uploads/<?= htmlspecialchars($row['gambar']); ?>" class="img-thumb" alt="gambar">
                    <?php else: ?>
                      <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-thumb" alt="no image">
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($row['nama_baju']); ?></td>
                  <td><?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?></td>
                  <td><?= htmlspecialchars($row['nama_merek'] ?? '-'); ?></td>
                  <td><?= htmlspecialchars($row['ukuran'] ?? '-'); ?></td>
                  <td>Rp <?= number_format($row['harga_jahit'], 0, ',', '.'); ?></td>
                  <td class="text-center"><?= $row['stok']; ?></td>
                  <td class="catatan" title="<?= htmlspecialchars($row['deskripsi']); ?>"><?= $row['deskripsi'] ?? '-'; ?></td>
                  <td class="text-center">
                    <a href="baju_edit.php?id=<?= $row['id_baju']; ?>" class="btn btn-info btn-sm">Edit</a>
                    <a href="baju_hapus.php?id=<?= $row['id_baju']; ?>" onclick="return confirm('Yakin hapus baju ini?')" class="btn btn-danger btn-sm">Hapus</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>