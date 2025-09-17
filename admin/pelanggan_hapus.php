<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM pengguna WHERE id_pengguna=? AND role='user'");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: pelanggan.php");
