<?php
session_start();
include "../include/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

// cari user role 'user' yang aktif
$sql = "SELECT * FROM pengguna WHERE username = ? AND role IN ('user','pelanggan') AND status = 'aktif' LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['id_pengguna']  = $user['id_pengguna'];
        $_SESSION['username']     = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];
        $_SESSION['status']       = $user['status'];
        $_SESSION['dibuat_pada']       = $user['dibuat_pada'];
        header("Location: user_dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Password salah!";
    }
} else {
    $_SESSION['error'] = "Username tidak ditemukan atau akun nonaktif!";
}

header("Location: login_user.php");
exit;
