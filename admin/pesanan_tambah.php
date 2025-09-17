<?php
session_start();
include '../include/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

// Ambil data pelanggan dan baju untuk dropdown select
$qPelanggan = $conn->query("SELECT id_pengguna, nama_lengkap FROM pengguna WHERE role='pelanggan'");
$qBaju = $conn->query("SELECT id_baju, nama_baju, harga_jahit FROM baju");

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_baju = $_POST['id_baju'];
    $jumlah = (int) $_POST['jumlah'];
    $tanggal_pesanan = $_POST['tanggal_pesanan'];

    // Ambil harga baju
    $hargaResult = $conn->query("SELECT harga FROM baju WHERE id_baju = $id_baju");
    $hargaRow = $hargaResult->fetch_assoc();
    $harga = $hargaRow['harga'];

    $total = $harga * $jumlah;
    $status = 'pending'; // default status

    // Insert ke tabel pesanan
    $insertPesanan = $conn->query("
        INSERT INTO pesanan (id_pelanggan, id_baju, jumlah, total, status, tanggal_pesanan)
        VALUES ($id_pelanggan, $id_baju, $jumlah, $total, '$status', '$tanggal_pesanan')
    ");

    if ($insertPesanan) {
        // Redirect ke halaman list pesanan
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Gagal menambah pesanan: " . $conn->error;
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Tambah Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../include/navbar.php'; ?>
    <div class="container mt-4">
        <h3>Tambah Pesanan</h3>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label for="id_pelanggan" class="form-label">Pelanggan</label>
                <select class="form-select" name="id_pelanggan" id="id_pelanggan" required>
                    <option value="" disabled selected>Pilih pelanggan</option>
                    <?php while ($pelanggan = $qPelanggan->fetch_assoc()) : ?>
                        <option value="<?= $pelanggan['id_pengguna']; ?>">
                            <?= htmlspecialchars($pelanggan['nama_lengkap']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_baju" class="form-label">Baju</label>
                <select class="form-select" name="id_baju" id="id_baju" required>
                    <option value="" disabled selected>Pilih baju</option>
                    <?php while ($baju = $qBaju->fetch_assoc()) : ?>
                        <option value="<?= $baju['id_baju']; ?>" data-harga="<?= $baju['harga']; ?>">
                            <?= htmlspecialchars($baju['nama_baju']); ?> - Rp <?= number_format($baju['harga'], 0, ',', '.'); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah" value="1" min="1" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan</label>
                <input type="date" class="form-control" name="tanggal_pesanan" id="tanggal_pesanan" value="<?= date('Y-m-d'); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Tambah Pesanan</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>