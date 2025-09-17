<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: kategori.php");
