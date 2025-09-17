<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login User - Tailor Baju</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6c63ff, #5bc0de);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
    }

    .login-card {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 400px;
    }

    .btn-primary {
      background-color: #6c63ff;
      border: none;
    }

    .btn-primary:hover {
      background-color: #574fd6;
    }

    a {
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <h3 class="text-center mb-4">Login User</h3>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" required autofocus>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>

    <div class="text-center mt-3">
      <small>Belum punya akun? <a href="register_user.php" class="text-primary fw-bold">Daftar di sini</a></small>
    </div>
  </div>
</body>

</html>