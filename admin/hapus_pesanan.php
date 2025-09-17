<?php
session_start();
include '../include/db.php';

// Cek login & role admin
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

// Cek apakah ada parameter id
if (!isset($_GET['id'])) {
    header("Location: pesanan.php?msg=invalid");
    exit;
}

$id_pesanan = intval($_GET['id']);

// Hapus pesanan
$stmt = $conn->prepare("DELETE FROM pesanan WHERE id_pesanan = ?");
$stmt->bind_param("i", $id_pesanan);

if ($stmt->execute()) {
    header("Location: pesanan.php?msg=deleted");
    exit;
} else {
    die("Gagal menghapus pesanan: " . $conn->error);
}
