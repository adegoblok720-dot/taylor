<?php
session_start();
include '../include/db.php';

// cek login & role admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

// pastikan ada id pesanan
if (!isset($_GET['id'])) {
    header("Location: pesanan_admin.php");
    exit;
}

$id_pesanan = (int) $_GET['id'];

// ambil data pesanan
$sql = "
  SELECT p.*, u.username, b.nama_baju
  FROM pesanan p
  JOIN pengguna u ON p.id_pengguna = u.id_pengguna
  JOIN baju b ON p.id_baju = b.id_baju
  WHERE p.id_pesanan = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    die("Pesanan tidak ditemukan!");
}

// proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE pesanan SET status = ? WHERE id_pesanan = ?");
    $update->bind_param("si", $status, $id_pesanan);
    if ($update->execute()) {
        header("Location: pesanan.php");
        exit;
    } else {
        die("Gagal update pesanan: " . $conn->error);
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Update Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../include/navbar.php'; ?>

    <div class="container py-4">
        <h3 class="mb-4">✏️ Update Pesanan</h3>

        <div class="card p-3 shadow-sm">
            <p><b>ID Pesanan:</b> <?= $pesanan['id_pesanan']; ?></p>
            <p><b>Pelanggan:</b> <?= htmlspecialchars($pesanan['username']); ?></p>
            <p><b>Baju:</b> <?= htmlspecialchars($pesanan['nama_baju']); ?></p>
            <p><b>Jumlah:</b> <?= $pesanan['jumlah']; ?></p>
            <p><b>Total:</b> Rp <?= number_format($pesanan['total'], 0, ",", "."); ?></p>
            <p><b>Status Saat Ini:</b> <span class="badge bg-info"><?= htmlspecialchars($pesanan['status']); ?></span></p>

            <form method="post">
                <div class="mb-3">
                    <label for="status" class="form-label">Ubah Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="baru" <?= $pesanan['status'] == 'baru' ? 'selected' : ''; ?>>Baru</option>
                        <option value="proses" <?= $pesanan['status'] == 'proses' ? 'selected' : ''; ?>>Diproses</option>
                        <option value="selesai" <?= $pesanan['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                        <option value="diambil" <?= $pesanan['status'] == 'diambil' ? 'selected' : ''; ?>>Diambil</option>
                        <option value="dibatalkan" <?= $pesanan['status'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">💾 Simpan</button>
                <a href="pesanan.php" class="btn btn-secondary">⬅️ Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>