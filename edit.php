<?php
session_start();
include 'db.php';

// 1. PROTEKSI KETAT: Hanya Admin yang boleh mengakses halaman edit data
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("location:login.php");
    exit;
}

// Validasi jika parameter ID tidak ada di URL atau kosong
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location:index.php");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM alat WHERE id = $id");
$data = mysqli_fetch_assoc($query);

// Jika ID tidak ditemukan di database, kembalikan ke dashboard admin
if (!$data) {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Outdoor Rent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5" style="max-width: 650px;">
    <div class="mb-3">
        <a href="index.php" class="text-secondary font-weight-bold"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard</a>
    </div>

    <div class="card card-outline card-warning shadow-sm">
        <div class="card-header bg-white py-3">
            <h3 class="card-title font-weight-bold text-warning" style="float: none;">
                <i class="fas fa-edit mr-1"></i> Edit Perlengkapan Outdoor (ID: #<?= $data['id']; ?>)
            </h3>
        </div>
        
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            
            <div class="card-body">
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Nama Perlengkapan / Alat</label>
                    <input type="text" name="nama_alat" class="form-control" value="<?= htmlspecialchars($data['nama_alat']); ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Harga Sewa / Hari (Rp)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Stok Alat (Unit)</label>
                            <div class="input-group">
                                <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" min="0" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label class="font-weight-bold text-dark">Foto Perlengkapan</label>
                    
                    <div class="mb-3 text-center bg-white p-2 border rounded shadow-sm" style="max-width: 180px; margin: 0 auto;">
                        <small class="text-muted d-block mb-1 font-weight-bold">Foto Saat Ini:</small>
                        <img src="img/<?= $data['gambar']; ?>" onerror="this.onerror=null;this.src='https://placehold.co/300x200?text=No+Image';" class="img-thumbnail" style="max-height: 120px; object-fit: cover;">
                    </div>

                    <div class="custom-file mb-2">
                        <input type="file" name="gambar" class="custom-file-input" id="inputFoto" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage()">
                        <label class="custom-file-label" for="inputFoto">Ganti file gambar baru (opsional)...</label>
                    </div>
                    <small class="text-muted d-block mb-3"><i class="fas fa-info-circle mr-1"></i> Biarkan kosong jika tidak ingin mengubah foto. Format: PNG, JPG, JPEG, WEBP.</small>
                    
                    <div class="text-center bg-white p-3 border rounded shadow-sm d-none" id="previewContainer">
                        <p class="small text-success font-weight-bold mb-2">Pratinjau Foto Baru Pilihan:</p>
                        <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 180px; object-fit: cover;">
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-white text-right">
                <a href="index.php" class="btn btn-default mr-2 font-weight-bold px-4">Batal</a>
                <button type="submit" class="btn btn-warning font-weight-bold text-white px-4">
                    <i class="fas fa-sync mr-1"></i> Update Data
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
        // Tampilkan nama file baru di label kustom bootstrap
        fileLabel.textContent = file.name;

        // Baca file untuk dijadikan preview img src
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    } else {
        fileLabel.textContent = "Ganti file gambar baru (opsional)...";
        previewContainer.classList.add('d-none');
    }
}
</script>

</body>
</html>