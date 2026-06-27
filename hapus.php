<?php
session_start();
include 'db.php';
if (!isset($_SESSION['status'])) { header("location:login.php"); exit; }

$id = intval($_GET['id']);
$query = mysqli_query($conn, "DELETE FROM alat WHERE id = $id");

if ($query) {
    header("location:index.php");
} else {
    echo "Gagal hapus data nih Kii: " . mysqli_error($conn);
}
?>