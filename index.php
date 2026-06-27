<?php
session_start();

//PROTEKSI KETAT: Jika belum login atau role-nya BUKAN admin, langsung ditendang ke login.php
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login" || $_SESSION['role'] !== "admin") {
    header("location:login.php");
    exit;
}

// Hubungkan ke database
include 'db.php'; 

// Menangkap keyword pencarian data oleh admin jika ada
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Outdoor Rent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand font-weight-bold text-warning" href="index.php">
                <i class="fas fa-tools mr-2"></i>OUTDOOR RENT <span class="badge badge-warning text-dark ml-1">ADMIN</span>
            </a>
            
            <div class="ml-auto d-flex align-items-center">
                <span class="text-light small mr-3 d-none d-sm-inline">
                    Logged in as: <strong class="text-success"><?= htmlspecialchars($_SESSION['user']); ?></strong>
                </span>

                <a href="katalog.php" class="btn btn-sm btn-info font-weight-bold mr-2">
                    <i class="fas fa-eye mr-1"></i> Lihat Katalog
                </a>

                <a href="logout.php" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('Yakin ingin keluar dari panel admin?');">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 my-5">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-4 col-12 mb-3 mb-md-0 text-center text-md-left">
                        <h4 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-database text-success mr-2"></i>Manajemen Data Alat Outdoor
                        </h4>
                    </div>
                    
                    <div class="col-md-5 col-12 mb-3 mb-md-0">
                        <form action="" method="GET" class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama alat..." value="<?= htmlspecialchars($search); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if($search !== ""): ?>
                                    <a href="index.php" class="btn btn-secondary" title="Reset"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-3 col-12 text-center text-md-right">
                        <a href="tambah.php" class="btn btn-success btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Alat Baru
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="10%" class="text-center">Gambar</th>
                                <th width="35%">Nama Perlengkapan</th>
                                <th width="20%">Harga Sewa / Hari</th>
                                <th width="12%" class="text-center">Stok</th>
                                <th width="18%" class="text-center">Aksi / Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Jalankan query dengan filter pencarian jika kolom cari diisi
                            if ($search !== "") {
                                $query = mysqli_query($conn, "SELECT * FROM alat WHERE nama_alat LIKE '%$search%' ORDER BY id DESC");
                            } else {
                                $query = mysqli_query($conn, "SELECT * FROM alat ORDER BY id DESC");
                            }

                            if (mysqli_num_rows($query) > 0) {
                                $no = 1;
                                while($data = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>
                                <td class="text-center">
                                    <img src="img/<?= $data['gambar']; ?>" 
                                         onerror="this.onerror=null;this.src='https://placehold.co/100x100?text=No+Img';" 
                                         class="img-thumbnail rounded shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark d-block"><?= htmlspecialchars($data['nama_alat']); ?></span>
                                    <small class="text-muted">ID Alat: #<?= $data['id']; ?></small>
                                </td>
                                <td class="font-weight-bold text-success">
                                    Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <?php if($data['stok'] <= 0): ?>
                                        <span class="badge badge-danger px-3 py-2">Habis</span>
                                    <?php else: ?>
                                        <span class="badge badge-info px-3 py-2"><?= $data['stok']; ?> Unit</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="edit.php?id=<?= $data['id']; ?>" class="btn btn-warning btn-sm font-weight-bold text-dark mr-1 rounded" title="Ubah Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm font-weight-bold rounded" onclick="return confirm('Yakin ingin menghapus data alat \'<?= htmlspecialchars($data['nama_alat']); ?>\'?');" title="Hapus Data">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else {
                                echo '<tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-folder-open fa-2x d-block mb-2"></i>Belum ada data barang di database atau pencarian tidak cocok.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white-50 text-center py-3">
        <p class="mb-0 small">&copy; <?= date('Y'); ?> Outdoor Rent - Admin Database Panel Core. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>