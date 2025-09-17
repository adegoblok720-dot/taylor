<?php
session_start();
include '../include/db.php';
if (!isset($_SESSION['id_pengguna']) || $_SESSION['role'] != 'admin') {
    header("Location: login_admin.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM merek WHERE id_merek=$id");
header("Location: merek.php");
exit;
