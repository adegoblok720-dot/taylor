<?php
session_start();
include '../include/db.php';

// Cek login admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

// Statistik
$jmlBaju     = $conn->query("SELECT COUNT(*) AS jml FROM baju")->fetch_assoc()['jml'];
$jmlKategori = $conn->query("SELECT COUNT(*) AS jml FROM kategori")->fetch_assoc()['jml'];
$jmlUser     = $conn->query("SELECT COUNT(*) AS jml FROM pengguna WHERE role='pelanggan'")->fetch_assoc()['jml'];
$jmlPesanan  = $conn->query("SELECT COUNT(*) AS jml FROM pesanan")->fetch_assoc()['jml'];

// Ambil pesanan terbaru + catatan + ukuran
$qPesanan = $conn->query("
    SELECT p.id_pesanan, u.nama_lengkap, b.nama_baju, 
           p.jumlah, p.total, p.status, p.tanggal_pesanan, 
           p.ukuran, p.catatan
    FROM pesanan p
    JOIN pengguna u ON p.id_pengguna = u.id_pengguna
    JOIN baju b ON p.id_baju = b.id_baju
    ORDER BY p.tanggal_pesanan DESC
");
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-card {
      transition: 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-2">
        <?php include '../include/sidebar.php'; ?>
      </div>
      <div class="col-10 p-4" style="margin-left:220px;">
        <h2 class="mb-4"> Dashboard Admin</h2>

        <!-- Statistik -->
        <div class="row g-4 mb-4">
          <div class="col-md-3">
            <div class="card stat-card bg-primary text-white text-center p-3">
              <h4><?= $jmlBaju; ?></h4>
              <p>Produk Baju</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card stat-card bg-success text-white text-center p-3">
              <h4><?= $jmlKategori; ?></h4>
              <p>Kategori</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark text-center p-3">
              <h4><?= $jmlUser; ?></h4>
              <p>Pelanggan</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card stat-card bg-danger text-white text-center p-3">
              <h4><?= $jmlPesanan; ?></h4>
              <p>Total Pesanan</p>
            </div>
          </div>
        </div>

        <!-- Pesanan Terbaru -->
        <div class="card p-3">
          <h5> Pesanan Terbaru</h5>
          <table class="table table-hover mt-3">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Pelanggan</th>
                <th>Baju</th>
                <th>Jumlah</th>
                <th>Ukuran</th>
                <th>Catatan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($qPesanan->num_rows > 0): $no = 1;
                while ($row = $qPesanan->fetch_assoc()): ?>
                  <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?= htmlspecialchars($row['nama_baju']); ?></td>
                    <td><?= (int)$row['jumlah']; ?></td>
                    <td><?= htmlspecialchars($row['ukuran'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['catatan'] ?? '-'); ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal_pesanan'])); ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ",", "."); ?></td>
                    <td>
                      <span class="badge 
                        <?= $row['status'] == 'pending' ? 'bg-warning' : ($row['status'] == 'selesai' ? 'bg-success' : 'bg-info'); ?>">
                        <?= ucfirst($row['status']); ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile;
              else: ?>
                <tr>
                  <td colspan="9" class="text-center">Belum ada pesanan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>