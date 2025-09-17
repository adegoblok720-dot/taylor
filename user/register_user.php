<?php
session_start();
include '../include/db.php';

$error = '';
if (isset($_POST['daftar'])) {
  $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
  $username     = trim($_POST['username'] ?? '');
  $email        = trim($_POST['email'] ?? '');
  $password     = trim($_POST['password'] ?? '');
  $role         = "pelanggan";

  if ($nama_lengkap && $username && $email && $password) {
    // cek username atau email sudah ada
    $cek = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE username=? OR email=?");
    $cek->bind_param("ss", $username, $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
      $error = "Username atau Email sudah dipakai!";
    } else {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO pengguna (nama_lengkap, username, email, password, role) VALUES (?,?,?,?,?)");
      $stmt->bind_param("sssss", $nama_lengkap, $username, $email, $passwordHash, $role);
      $stmt->execute();
      header("Location: login_user.php");
      exit;
    }
  } else {
    $error = "Semua field wajib diisi!";
  }
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Register User - Taylor Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      /* Bisa pilih salah satu: gradient atau gambar */
      background: linear-gradient(135deg, #0052cc, #3399ff, #6a11cb, #2575fc);
      /* contoh pakai gambar kain/batik */
      /* background: url('../assets/bg-kain.jpg') no-repeat center center/cover; */
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      background: #fff;
    }

    h3 {
      color: #0052cc;
    }

    .btn-success {
      background: #0052cc;
      border: none;
    }

    .btn-success:hover {
      background: #003d99;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card p-4">
          <h3 class="fw-bold mb-3 text-center">Daftar Akun Pembeli</h3>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
          <?php endif; ?>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="daftar" class="btn btn-success w-100">Daftar</button>
          </form>
          <div class="mt-3 text-center">
            <small>Sudah punya akun? <a href="login_user.php">Login di sini</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>