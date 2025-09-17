<?php
session_start();
include '../include/db.php';
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_merek = $_POST['nama_merek'];
    $stmt = $conn->prepare("INSERT INTO merek (nama_merek) VALUES (?)");
    $stmt->bind_param("s", $nama_merek);
    $stmt->execute();
    header("Location: merek.php");
    exit;
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Tambah Merek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../include/navbar.php'; ?>
    <div class="container p-4">
        <h3>Tambah Merek</h3>
        <form method="post">
            <div class="mb-3">
                <label>Nama Merek</label>
                <input type="text" name="nama_merek" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="merek.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>