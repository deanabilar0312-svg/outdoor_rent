<?php
session_start();
include 'db.php';

// 1. PROTEKSI KETAT: Hanya Admin yang boleh menambah data alat outdoor
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("location:login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_alat']);
    $harga = intval($_POST['harga']);
    $stok  = intval($_POST['stok']);

    // Proses Ambil Data Gambar
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];
    
    // Jika user mengupload gambar
    if ($nama_gambar != "") {
        // VALIDASI EKSTENSI: Batasi hanya file gambar murni
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $x = explode('.', $nama_gambar);
        $ekstensi = strtolower(end($x));

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            // Menggunakan kombinasi time() + rand() agar nama file dijamin 100% unik dan tidak bentrok
            $gambar_baru = time() . '_' . rand(1, 999) . '_' . $nama_gambar;
            $path        = "img/" . $gambar_baru;
            
            // Pindahkan file ke folder img
            move_uploaded_file($tmp_name, $path);
        } else {
            // Jika file bukan gambar, batalkan proses dan beri peringatan
            echo "<script>alert('Format file tidak didukung! Sasa harap gunakan format PNG, JPG, JPEG, atau WEBP.'); window.location='tambah.php';</script>";
            exit;
        }
    } else {
        // Kalau ga upload gambar, otomatis pake gambar default cadangan
        $gambar_baru = "default.jpg";
    }

    // 2. JALANKAN QUERY INSERT DATA
    $query = mysqli_query($conn, "INSERT INTO alat (nama_alat, harga, stok, gambar) VALUES ('$nama', '$harga', '$stok', '$gambar_baru')");

    if ($query) {
        // Jika berhasil, kembalikan ke dashboard manajemen admin
        header("location:index.php");
        exit;
    } else {
        echo "Gagal menyimpan data ke database: " . mysqli_error($conn);
    }
} else {
    // Jika diakses langsung tanpa method POST, lempar kembali ke dashboard
    header("location:index.php");
    exit;
}
?>