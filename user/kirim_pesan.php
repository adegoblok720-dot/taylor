<?php
session_start();
include '../include/db.php';

// Cek login
if (!isset($_SESSION['id_pengguna'])) {
    header("Location: ../login_user.php");
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'];

// Validasi input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pesan = trim($_POST['isi_pesan'] ?? '');

    if (!empty($pesan)) {
        $stmt = $conn->prepare("INSERT INTO pesan (id_pengguna, pesan) VALUES (?, ?)");
        $stmt->bind_param("is", $id_pengguna, $pesan);

        if ($stmt->execute()) {
            // Balik ke halaman sebelumnya
            $_SESSION['success'] = "Pesan berhasil dikirim!";
        } else {
            $_SESSION['error'] = "Gagal mengirim pesan.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Pesan tidak boleh kosong.";
    }
}

// Redirect kembali ke dashboard
header("Location: user_dashboard.php");
exit;
