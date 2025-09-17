<?php
session_start();
include '../include/db.php';
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

$qMerek = $conn->query("SELECT * FROM merek ORDER BY id_merek DESC");
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Data Merek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../include/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2">
                <?php include '../include/sidebar.php'; ?>
            </div>

            <!-- Konten -->
            <div class="col-md-9 col-lg-10 p-4">
                <h3>Data Brand</h3>
                <a href="merek_tambah.php" class="btn btn-success mb-3">+ Tambah Brand</a>
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nama Brand</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = $qMerek->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['nama_merek']); ?></td>
                                <td>
                                    <a href="merek_edit.php?id=<?= $row['id_merek']; ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="merek_hapus.php?id=<?= $row['id_merek']; ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>