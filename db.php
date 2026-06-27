<?php
$conn = mysqli_connect("localhost", "root", "", "db_outdoor_rent");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>