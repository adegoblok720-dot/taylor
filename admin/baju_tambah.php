<?php
session_start();
include '../include/navbar.php';
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
  header("Location: login_admin.php");
  exit;
}

$kategori = $conn->query("SELECT * FROM kategori");
$merek = $conn->query("SELECT * FROM merek");
$ukuran_list = ['S', 'M', 'L', 'XL', 'XXL'];
$error = '';

if (isset($_POST['simpan'])) {
  $nama_baju = $_POST['nama_baju'];
  $id_kategori = $_POST['id_kategori'];
  $id_merek = $_POST['id_merek'] ?: NULL;
  $harga_jahit = $_POST['harga_jahit'];
  $deskripsi = $_POST['deskripsi'] ?: NULL;
  $ukuran = $_POST['ukuran'] ?: NULL;
  $stok = $_POST['stok'] ?: 0;

  $gambar = NULL;
  if (!empty($_FILES['gambar']['name'])) {
    $ext = pathinfo($_FILES['gambar']['name'],);
    $gambar = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../uploads/$gambar");
  }

  $stmt = $conn->prepare("INSERT INTO baju (nama_baju,id_kategori,id_merek,harga_jahit,deskripsi,gambar,ukuran,stok) VALUES (?,?,?,?,?,?,?,?)");
  $stmt->bind_param("siissssi", $nama_baju, $id_kategori, $id_merek, $harga_jahit, $deskripsi, $gambar, $ukuran, $stok);

  if ($stmt->execute()) {
    header("Location: baju.php");
    exit;
  } else {
    $error = "Gagal menyimpan data!";
  }
}
?>

<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Tambah Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-5">
    <h3>+ Tambah Baju</h3>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Nama Baju</label>
        <input type="text" name="nama_baju" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Kategori</label>
        <select name="id_kategori" class="form-control" required>
          <option value="">-- Pilih --</option>
          <?php while ($row = $kategori->fetch_assoc()): ?>
            <option value="<?= $row['id_kategori']; ?>"><?= $row['nama_kategori']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Merek (Opsional)</label>
        <select name="id_merek" class="form-control">
          <option value="">-- Pilih --</option>
          <?php while ($row = $merek->fetch_assoc()): ?>
            <option value="<?= $row['id_merek']; ?>"><?= $row['nama_merek']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Harga Jahit</label>
        <input type="number" step="0.01" name="harga_jahit" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label>Ukuran</label>
        <select name="ukuran" class="form-control">
          <option value="">-- Pilih --</option>
          <?php foreach ($ukuran_list as $u): ?>
            <option value="<?= $u; ?>"><?= $u; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" value="0">
      </div>

      <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="gambar" class="form-control">
      </div>

      <div class="mb-3">
        <a href="baju.php" class="btn btn-secondary">← Kembali</a>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</body>

</html>