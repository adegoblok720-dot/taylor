<?php
session_start();
include '../include/db.php'; // sesuaikan path db.php kamu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Ambil data user
    $stmt = $conn->prepare("SELECT id_pengguna, nama_lengkap, username, password, role, status 
                            FROM pengguna 
                            WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // cek role admin
        if ($user['role'] !== 'admin') {
            header("Location: login_admin.php?error=Akun ini bukan admin");
            exit;
        }
        // cek status aktif
        if ($user['status'] !== 'aktif') {
            header("Location: login_admin.php?error=Akun admin nonaktif");
            exit;
        }
        // cek password
        if (password_verify($password, $user['password'])) {
            // simpan session
            $_SESSION['id_pengguna']  = $user['id_pengguna'];
            $_SESSION['username']     = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role']         = $user['role'];

            header("Location: admin_dashboard.php");
            exit;
        } else {
            header("Location: login_admin.php?error=Password salah");
            exit;
        }
    } else {
        header("Location: login_admin.php?error=Username tidak ditemukan");
        exit;
    }
}
