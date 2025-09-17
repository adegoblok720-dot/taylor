<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #3a7bd5, #00d2ff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      width: 400px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
      color: #fff;
    }

    .login-card h3 {
      font-weight: 600;
      text-align: center;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 12px;
      border: none;
      padding: 12px;
    }

    .btn-custom {
      border-radius: 12px;
      font-weight: 600;
      padding: 12px;
      transition: 0.3s;
    }

    .btn-custom:hover {
      transform: scale(1.05);
      background-color: #2a5298 !important;
    }

    .alert {
      border-radius: 12px;
      text-align: center;
      padding: 10px;
    }

    .footer-text {
      margin-top: 15px;
      font-size: 14px;
      text-align: center;
    }

    .footer-text a {
      color: #fff;
      text-decoration: underline;
      font-weight: 500;
    }
  </style>
</head>

<body>

  <div class="login-card">
    <h3>🔐 Login Admin</h3>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success">
        <?= htmlspecialchars($_GET['success']); ?>
      </div>
    <?php endif; ?>

    <form action="proses_login_admin.php" method="POST">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="👤 Username" required autofocus>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="🔑 Password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 btn-custom">Login</button>
    </form>

    <div class="footer-text">
      Belum punya akun? <a href="register_admin.php">Daftar Admin</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>