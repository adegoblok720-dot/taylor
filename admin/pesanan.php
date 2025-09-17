<?php
session_start();
include '../include/db.php';

// cek login & role admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
  header("Location: login_admin.php");
  exit;
}

// Ambil semua pesanan (termasuk kategori & ukuran)
$sql = "
  SELECT p.id_pesanan, u.username AS nama_pengguna, 
         b.nama_baju, b.gambar, k.nama_kategori,
         p.jumlah, p.ukuran, p.total, p.status, p.tanggal_pesanan, p.catatan
  FROM pesanan p
  JOIN pengguna u ON p.id_pengguna = u.id_pengguna
  JOIN baju b ON p.id_baju = b.id_baju
  LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
  ORDER BY p.tanggal_pesanan DESC
";
$result = $conn->query($sql);
if ($result === false) die("Query Error: " . $conn->error);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Manajemen Pesanan - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background-color: #f8f9fa;
    }

    .content {
      margin-left: 220px;
      padding: 20px;
    }

    .catatan {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    table img {
      border-radius: 5px;
    }

    @media (max-width: 768px) {
      .content {
        margin-left: 0;
        padding: 10px;
      }

      table {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <?php include '../include/navbar.php'; ?>
  <?php include '../include/sidebar.php'; ?>

  <div class="content">
    <h3 class="mb-4">📦 Manajemen Pesanan</h3>

    <?php if ($result->num_rows > 0) : ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Pelanggan</th>
              <th>Baju</th>
              <th>Kategori</th>
              <th>Jumlah</th>
              <th>Ukuran</th>
              <th>Total</th>
              <th>Tanggal</th>
              <th>Catatan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) :
              $status = $row['status'];
              switch ($status) {
                case 'baru':
                  $badge = 'warning';
                  $label = 'Baru';
                  break;
                case 'proses':
                  $badge = 'primary';
                  $label = 'Diproses';
                  break;
                case 'selesai':
                  $badge = 'success';
                  $label = 'Selesai';
                  break;
                case 'diambil':
                  $badge = 'info';
                  $label = 'Diambil';
                  break;
                default:
                  $badge = 'danger';
                  $label = 'Dibatalkan';
                  break;
              }
            ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_pengguna']); ?></td>
                <td>
                  <?php if (!empty($row['gambar'])) : ?>
                    <img src="../uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama_baju']); ?>" style="width:50px;height:50px;object-fit:cover;margin-right:6px;">
                  <?php endif; ?>
                  <?= htmlspecialchars($row['nama_baju']); ?>
                </td>
                <td><?= htmlspecialchars($row['nama_kategori'] ?: '-'); ?></td>
                <td><?= (int)$row['jumlah']; ?></td>
                <td><?= htmlspecialchars($row['ukuran'] ?: '-'); ?></td>
                <td>Rp <?= number_format($row['total'], 0, ",", "."); ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_pesanan'])); ?></td>
                <td class="catatan" title="<?= htmlspecialchars($row['catatan']); ?>">
                  <?= htmlspecialchars($row['catatan'] ?: '-'); ?>
                </td>
                <td><span class="badge bg-<?= $badge; ?>"><?= $label; ?></span></td>
                <td>
                  <a href="update_pesanan.php?id=<?= urlencode($row['id_pesanan']); ?>" class="btn btn-sm btn-outline-primary mb-1">Update</a>
                  <a href="hapus_pesanan.php?id=<?= urlencode($row['id_pesanan']); ?>" class="btn btn-sm btn-outline-danger mb-1" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else : ?>
      <div class="alert alert-info">Belum ada pesanan.</div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>