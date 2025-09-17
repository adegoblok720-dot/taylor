<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna'])) {
  // Kalau belum login, arahkan ke halaman login
  header("Location: login_user.php");
  exit;
}

// Ambil data user jika login
$user = null;
$jumlah_pesanan_baru = 0;
if (isset($_SESSION['id_pengguna'])) {
  $id_pengguna = $_SESSION['id_pengguna'];

  $stmt = $conn->prepare("SELECT nama_lengkap FROM pengguna WHERE id_pengguna=? LIMIT 1");
  $stmt->bind_param("i", $id_pengguna);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM pesanan WHERE id_pengguna=? AND status='baru'");
  $stmt2->bind_param("i", $id_pengguna);
  $stmt2->execute();
  $res = $stmt2->get_result()->fetch_assoc();
  $jumlah_pesanan_baru = $res['total'] ?? 0;
  $stmt2->close();
}

// Produk terbaru
$produk = $conn->query("SELECT id_baju, nama_baju, harga_jahit, gambar 
                        FROM baju 
                        ORDER BY id_baju DESC 
                        LIMIT 12");
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>User Dashboard - Tailor Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f5f9;
    }

    .hero {
      background: linear-gradient(135deg, #0062cc, #3399ff);
      color: white;
      padding: 60px 20px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
    }

    .hero h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 0;
    }

    .product-card {
      border-radius: 15px;
      transition: transform 0.3s, box-shadow 0.3s;
      overflow: hidden;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .product-card img {
      height: 180px;
      object-fit: cover;
    }

    footer {
      background: #0062cc;
      color: white;
      padding: 30px 0;
      text-align: center;
      border-radius: 15px 15px 0 0;
      margin-top: 50px;
    }
  </style>
</head>

<body>
  <?php include '../user/navbar_user.php'; ?>

  <div class="container">
    <!-- Hero Section -->
    <div class="hero">
      <h1>Selamat Datang, <?= htmlspecialchars($user['nama_lengkap'] ?? 'Guest'); ?> 👋</h1>
      <p>Selamat berbelanja di Tailor Baju!</p>
    </div>

    <!-- Menu Utama -->
    <div class="container mt-5">
      <div class="row text-center g-4 justify-content-center">
        <div class="col-md-3">
          <div class="card shadow-sm h-100 p-3">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-shirt text-primary mb-3" style="font-size:3rem;"></i>
              <h5>Lihat Produk</h5>
              <a href="produk_user.php" class="btn btn-outline-primary mt-3">Browse</a>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm h-100 p-3">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-person-lines-fill text-info mb-3" style="font-size:3rem;"></i>
              <h5>Profil</h5>
              <a href="profil_user.php" class="btn btn-outline-info mt-3">Edit Profil</a>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow-sm h-100 p-3">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
              <i class="bi bi-box-seam text-warning mb-3" style="font-size:3rem;"></i>
              <h5>Pesanan Saya</h5>
              <a href="pesanan_saya.php" class="btn btn-outline-warning mt-3">
                Pesanan
                <?php if ($jumlah_pesanan_baru > 0): ?>
                  <span class="badge bg-danger"><?= $jumlah_pesanan_baru; ?></span>
                <?php endif; ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Produk Terbaru -->
    <h4 class="mb-3 mt-5">Produk Terbaru</h4>
    <div class="row g-4">
      <?php if ($produk->num_rows > 0): ?>
        <?php while ($p = $produk->fetch_assoc()): ?>
          <div class="col-md-3">
            <div class="card product-card shadow-sm">
              <img src="../uploads/<?= htmlspecialchars($p['gambar']); ?>" alt="<?= htmlspecialchars($p['nama_baju']); ?>">
              <div class="card-body text-center">
                <h6 class="fw-bold"><?= htmlspecialchars($p['nama_baju']); ?></h6>
                <p class="text-primary fw-bold">Rp <?= number_format($p['harga_jahit'], 0, ',', '.'); ?></p>
                <a href="checkout.php?id=<?= $p['id_baju']; ?>" class="btn btn-primary btn-sm">Lihat Produk</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada produk tersedia.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>© 2025 Tailor Baju. All Rights Reserved.</p>
    <p>Kontak: info@tailorbaju.com | 0812-3456-7890</p>
    <div>
      <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>