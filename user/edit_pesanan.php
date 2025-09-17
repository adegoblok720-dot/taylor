<?php
session_start();
include '../include/db.php';

// cek login
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'pelanggan') {
    header("Location: ../login_user.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];
$id_pesanan = $_GET['id'] ?? 0;

// ambil data pesanan user
$stmt = $conn->prepare("
    SELECT p.*, b.nama_baju, b.stok 
    FROM pesanan p
    JOIN baju b ON p.id_baju = b.id_baju
    WHERE p.id_pesanan = ? AND p.id_pengguna = ? AND p.status = 'pending'
");
$stmt->bind_param("ii", $id_pesanan, $id_pengguna);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();

if (!$pesanan) {
    die("Pesanan tidak ditemukan atau sudah diproses.");
}

// update pesanan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = $_POST['jumlah'];
    $ukuran = $_POST['ukuran'];
    $alamat = $_POST['alamat'];
    $total  = $jumlah * $pesanan['harga_jahit']; // asumsikan harga_jahit ada di tabel baju

    $update = $conn->prepare("
        UPDATE pesanan 
        SET jumlah = ?, ukuran = ?, alamat = ?, total = ? 
        WHERE id_pesanan = ? AND id_pengguna = ?
    ");
    $update->bind_param("issdii", $jumlah, $ukuran, $alamat, $total, $id_pesanan, $id_pengguna);
    $update->execute();

    header("Location: pesanan_saya.php");
    exit;
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar_user.php'; ?>
    <div class="container py-4">
        <h3>Edit Pesanan: <?= htmlspecialchars($pesanan['nama_baju']); ?></h3>
        <form method="post" class="mt-3">
            <div class="mb-3">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" max="<?= $pesanan['stok']; ?>" value="<?= $pesanan['jumlah']; ?>" required>
            </div>
            <div class="mb-3">
                <label>Ukuran</label>
                <select name="ukuran" class="form-control" required>
                    <?php
                    $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
                    foreach ($sizes as $size) {
                        $selected = ($size == $pesanan['ukuran']) ? 'selected' : '';
                        echo "<option value='$size' $selected>$size</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($pesanan['alamat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update Pesanan</button>
            <a href="pesanan_saya.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>