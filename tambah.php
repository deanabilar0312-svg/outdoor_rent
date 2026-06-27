<?php
session_start();

// 1. PROTEKSI KETAT: Hanya Admin yang boleh mengakses halaman tambah data
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("location:login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat - Outdoor Rent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5" style="max-width: 650px;">
    <div class="mb-3">
        <a href="index.php" class="text-secondary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard</a>
    </div>

    <div class="card card-outline card-success shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-success" style="float: none;">
                <i class="fas fa-plus-circle mr-1"></i> Tambah Perlengkapan Outdoor Baru
            </h3>
        </div>
        
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Nama Perlengkapan / Alat</label>
                    <input type="text" name="nama_alat" class="form-control" placeholder="Contoh: Tenda Dome kapasitas 4 Orang" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Harga Sewa / Hari (Rp)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number" name="harga" class="form-control" placeholder="Contoh: 50000" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Stok Awal (Unit)</label>
                            <div class="input-group">
                                <input type="number" name="stok" class="form-control" placeholder="Contoh: 10" min="1" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label class="font-weight-bold text-dark">Foto Perlengkapan</label>
                    <div class="custom-file mb-2">
                        <input type="file" name="gambar" class="custom-file-input" id="inputFoto" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage()">
                        <label class="custom-file-label" for="inputFoto">Pilih file gambar...</label>
                    </div>
                    <small class="text-muted d-block mb-3"><i class="fas fa-info-circle mr-1"></i> Format wajib: PNG, JPG, JPEG, atau WEBP. Maksimal 2MB.</small>
                    
                    <div class="text-center bg-white p-3 border rounded shadow-sm d-none" id="previewContainer">
                        <p class="small text-muted font-weight-bold mb-2">Pratinjau Foto Pilihan:</p>
                        <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px; object-fit: cover;">
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white text-right">
                <a href="index.php" class="btn btn-default mr-2 font-weight-bold px-4">Batal</a>
                <button type="submit" class="btn btn-success font-weight-bold px-4">
                    <i class="fas fa-save mr-1"></i> Simpan ke Database
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage() {
    const fileInput = document.getElementById('inputFoto');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const fileLabel = document.querySelector('.custom-file-label');

    const file = fileInput.files[0];
    if (file) {
        // Tampilkan nama file di label kustom bootstrap
        fileLabel.textContent = file.name;

        // Baca file untuk dijadikan preview img src
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        fileLabel.textContent = "Pilih file gambar...";
        previewContainer.classList.add('d-none');
    }
}
</script>

</body>
</html>