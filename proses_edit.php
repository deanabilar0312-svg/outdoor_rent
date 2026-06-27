<?php
session_start();
include 'db.php';

// 1. PROTEKSI KETAT: Hanya Admin yang boleh mengeksekusi proses ini
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("location:login.php");
    exit;
}

// 2. PROSES UPDATE DATA (Hanya menerima method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_alat']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);

    // Ambil data lama dari DB untuk mempertahankan nama gambar lama (jika admin TIDAK ganti gambar)
    $query_lama = mysqli_query($conn, "SELECT gambar FROM alat WHERE id = $id");
    $data_lama = mysqli_fetch_assoc($query_lama);
    $nama_gambar = $data_lama['gambar']; 

    // JIKA ADMIN MENGUPLOAD GAMBAR BARU
    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'webp');
        $x = explode('.', $_FILES['gambar']['name']);
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambar']['tmp_name'];
        
        // Membuat nama unik baru agar file tidak bentrok/tertimpa
        $nama_gambar_baru = time() . '-' . rand(1, 999) . '_' . $_FILES['gambar']['name'];

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            
            // Hapus gambar lama dari folder 'img/' agar tidak jadi sampah hosting (kecuali gambar default)
            if ($nama_gambar != "" && $nama_gambar != "default.jpg" && file_exists("img/" . $nama_gambar)) {
                unlink("img/" . $nama_gambar);
            }
            
            // Pindahkan file foto baru ke folder img/
            move_uploaded_file($file_tmp, 'img/' . $nama_gambar_baru);
            
            // Ubah isi variabel menjadi nama gambar yang baru
            $nama_gambar = $nama_gambar_baru;
        } else {
            echo "<script>alert('Ekstensi gambar tidak diperbolehkan! Gunakan png/jpg/jpeg/webp.'); window.location='edit.php?id=$id';</script>";
            exit;
        }
    }

    // 3. JALANKAN QUERY UPDATE DATA (Sekarang kolom gambar sudah dimasukkan ke query)
    $query_update = mysqli_query($conn, "UPDATE alat SET nama_alat = '$nama', harga = '$harga', stok = '$stok', gambar = '$nama_gambar' WHERE id = $id");

    if ($query_update) {
        // Berhasil di-update, lempar balik ke halaman utama admin
        header("location:index.php");
        exit;
    } else {
        echo "Gagal update data ke database: " . mysqli_error($conn);
    }
} else {
    header("location:index.php");
    exit;
}
?>