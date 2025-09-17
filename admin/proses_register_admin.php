<?php
session_start();
include '../include/db.php'; // sesuaikan path db.php kamu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap  = trim($_POST['nama_lengkap']);
    $username      = trim($_POST['username']);
    $email         = trim($_POST['email']);
    $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : NULL;
    $password      = $_POST['password'];
    $confirm       = $_POST['confirm_password'];

    // 1. Validasi password & konfirmasi
    if ($password !== $confirm) {
        header("Location: register_admin.php?error=Password tidak sama");
        exit;
    }

    // 2. Cek username/email sudah ada
    $stmt = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: register_admin.php?error=Username atau Email sudah digunakan");
        exit;
    }
    $stmt->close();

    // 3. Hash password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // 4. Simpan ke tabel pengguna dengan role admin
    $stmt = $conn->prepare("
        INSERT INTO pengguna (username, nama_lengkap, email, tanggal_lahir, password, role, status, tanggal_daftar)
        VALUES (?, ?, ?, ?, ?, 'admin', 'aktif', NOW())
    ");
    $stmt->bind_param("sssss", $username, $nama_lengkap, $email, $tanggal_lahir, $hashed);

    if ($stmt->execute()) {
        header("Location: register_admin.php?success=Registrasi berhasil, silakan login");
        exit;
    } else {
        header("Location: register_admin.php?error=Gagal menyimpan data");
        exit;
    }
}
