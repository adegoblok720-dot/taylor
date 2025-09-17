<?php
session_start();
include '../include/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM baju WHERE id_baju=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: baju.php");
exit;
