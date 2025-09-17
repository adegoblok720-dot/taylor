<?php
session_start();
include '../include/db.php';
include '../user/navbar_user.php'; // Include navbar

// Ambil user
$user = null;
if (isset($_SESSION['id_pengguna'])) {
  $stmt = $conn->prepare("SELECT nama_lengkap FROM pengguna WHERE id_pengguna=? LIMIT 1");
  $stmt->bind_param("i", $_SESSION['id_pengguna']);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

// Ambil produk
$search = $_GET['search'] ?? '';
if ($search) {
  $stmt = $conn->prepare("SELECT id_baju, nama_baju, harga_jahit, gambar, deskripsi FROM baju WHERE nama_baju LIKE ? ORDER BY id_baju DESC");
  $like = "%$search%";
  $stmt->bind_param("s", $like);
  $stmt->execute();
  $produk = $stmt->get_result();
} else {
  $produk = $conn->query("SELECT id_baju, nama_baju, harga_jahit, gambar, deskripsi FROM baju ORDER BY id_baju DESC");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Daftar Produk - Tailor Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .product-card {
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .product-card img {
      height: 180px;
      object-fit: cover;
    }

    .desc {
      font-size: 0.9rem;
      color: #555;
      height: 50px;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  </style>
</head>

<body>

  <div class="container mt-4">
    <h2 class="text-center mb-4">Daftar Produk</h2>

    <!-- Search -->
    <form class="d-flex justify-content-center mb-4" action="produk_user.php" method="get">
      <input class="form-control me-2 w-50" type="search" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search); ?>">
      <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
    </form>

    <!-- Produk -->
    <div class="row g-4">
      <?php if ($produk && $produk->num_rows > 0): ?>
        <?php while ($p = $produk->fetch_assoc()): ?>
          <div class="col-md-3">
            <div class="card product-card shadow-sm">
              <img src="../uploads/<?= htmlspecialchars($p['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nama_baju']); ?>">
              <div class="card-body text-center">
                <h6 class="fw-bold"><?= htmlspecialchars($p['nama_baju']); ?></h6>
                <p class="desc"><?= htmlspecialchars($p['deskripsi'] ?? '-'); ?></p>
                <p class="text-primary fw-bold">Rp <?= number_format($p['harga_jahit'], 0, ',', '.'); ?></p>
                <a href="checkout.php?id=<?= $p['id_baju']; ?>" class="btn btn-primary btn-sm">Pesan / Checkout</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada produk tersedia.</p>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>