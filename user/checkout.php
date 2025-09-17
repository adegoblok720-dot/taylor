<?php
session_start();
include '../include/db.php';

// Pastikan user login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: login_user.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Ambil ID produk
if (!isset($_GET['id'])) {
    die("Produk tidak ditemukan!");
}

$id_baju = (int) $_GET['id'];

// Ambil detail produk beserta kategori
$stmt = $conn->prepare("
    SELECT b.*, k.nama_kategori 
    FROM baju b 
    LEFT JOIN kategori k ON b.id_kategori = k.id_kategori 
    WHERE b.id_baju=? 
    LIMIT 1
");
$stmt->bind_param("i", $id_baju);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produk) {
    die("Produk tidak ditemukan!");
}

// Jika submit checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = (int) $_POST['jumlah'];
    $ukuran = $_POST['ukuran'];
    $catatan = trim($_POST['catatan']);

    if ($jumlah <= 0) {
        $error = "Jumlah pesanan tidak valid!";
    } elseif ($jumlah > $produk['stok']) {
        $error = "Stok tidak mencukupi!";
    } else {
        $total_harga = $produk['harga_jahit'] * $jumlah;

        // Simpan ke tabel pesanan
        $stmt2 = $conn->prepare("
            INSERT INTO pesanan 
            (id_pengguna, id_baju, jumlah, ukuran, catatan, total, status, tanggal_pesanan) 
            VALUES (?, ?, ?, ?, ?, ?, 'baru', NOW())
        ");
        $stmt2->bind_param("iiissd", $id_pengguna, $id_baju, $jumlah, $ukuran, $catatan, $total_harga);
        $stmt2->execute();
        $stmt2->close();

        // Update stok produk
        $stmt3 = $conn->prepare("UPDATE baju SET stok = stok - ? WHERE id_baju=?");
        $stmt3->bind_param("ii", $jumlah, $id_baju);
        $stmt3->execute();
        $stmt3->close();

        header("Location: pesanan_saya.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Produk - Tailor Baju</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include 'navbar_user.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Checkout Produk</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error; ?></div>
                        <?php endif; ?>

                        <div class="text-center mb-4">
                            <img src="../uploads/<?= htmlspecialchars($produk['gambar']); ?>" class="img-fluid rounded" style="max-height:200px;">
                            <h5 class="mt-3"><?= htmlspecialchars($produk['nama_baju']); ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($produk['nama_kategori']); ?></p>
                            <p class="text-primary fw-bold">Rp <?= number_format($produk['harga_jahit'], 0, ',', '.'); ?></p>
                            <p><span class="badge bg-secondary">Stok: <?= $produk['stok']; ?></span></p>
                            <p><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>
                        </div>

                        <form method="post">
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" max="<?= $produk['stok']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="ukuran" class="form-label">Ukuran</label>
                                <select name="ukuran" id="ukuran" class="form-select" required>
                                    <option value="">-- Pilih Ukuran --</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="3" class="form-control" placeholder="Tulis catatan tambahan..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Pesan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>