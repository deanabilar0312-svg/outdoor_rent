<?php
session_start();
// Proteksi halaman: Jika belum login, dilempar balik ke login.php
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

// Hubungkan ke database
include 'db.php'; 

// Menangkap keyword pencarian jika ada
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
    <title>Katalog Alat Outdoor - Outdoor Rent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .jumbotron {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?ixlib=rb-4.0.3') no-repeat center center;
            background-size: cover;
            color: white;
            border-radius: 0;
        }
        .card-product {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
        }
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-whatsapp:hover {
            background-color: #1ebd58;
            color: white;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="katalog.php"><i class="fas fa-campground mr-2"></i>OUTDOOR RENT</a>
            
            <div class="ml-auto d-flex align-items-center">
                <span class="text-white small mr-3 d-none d-sm-inline">
                    Halo, <strong><?= htmlspecialchars($_SESSION['user'] ?? 'Pelanggan'); ?></strong> (<?= ucfirst($_SESSION['role'] ?? 'User'); ?>)
                </span>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <a href="index.php" class="btn btn-sm btn-warning font-weight-bold mr-2">
                        <i class="fas fa-database mr-1"></i> Kelola DB (Admin)
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="btn btn-sm btn-danger font-weight-bold" onclick="return confirm('Yakin ingin keluar, Kii?');">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <div class="jumbotron text-center shadow-sm mb-0">
        <div class="container py-4">
            <h1 class="display-4 font-weight-bold">Petualanganmu Dimulai Di Sini!</h1>
            <p class="lead font-weight-normal text-light">Temukan perlengkapan outdoor terbaik dengan harga sewa paling bersahabat.</p>
        </div>
    </div>

    <div class="container my-5">
        <div class="row align-items-center mb-4">
            <div class="col-md-6 col-12 text-center text-md-left mb-3 mb-md-0">
                <h3 class="font-weight-bold text-dark mb-0"><i class="fas fa-store mr-2 text-success"></i>Katalog Perlengkapan</h3>
            </div>
            <div class="col-md-6 col-12">
                <form action="" method="GET" class="input-group">
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari tenda, carrier, atau perlengkapan lainnya..." value="<?= htmlspecialchars($search); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-success shadow-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if($search !== ""): ?>
                            <a href="katalog.php" class="btn btn-secondary shadow-sm" title="Reset Pencarian"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <?php
            // Modifikasi Query untuk mengakomodasi pencarian
            if ($search !== "") {
                $query = mysqli_query($conn, "SELECT * FROM alat WHERE stok > 0 AND nama_alat LIKE '%$search%' ORDER BY id DESC");
            } else {
                $query = mysqli_query($conn, "SELECT * FROM alat WHERE stok > 0 ORDER BY id DESC");
            }
            
            if (mysqli_num_rows($query) > 0) {
                while($data = mysqli_fetch_assoc($query)) {
                    // Buat teks otomatis buat chat WA pas customer klik sewa
                    $pesan_wa = "Halo Admin Outdoor Rent, saya mau sewa alat ini dong:\n\n"
                              . "Nama Alat: " . $data['nama_alat'] . "\n"
                              . "Harga Sewa: Rp " . number_format($data['harga'], 0, ',', '.') . " / Hari\n\n"
                              . "Apakah masih tersedia?";
                    $link_wa = "https://wa.me/6285230352824?text=" . urlencode($pesan_wa);
            ?>
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 card-product shadow-sm">
                    <img src="img/<?= $data['gambar']; ?>" 
                         onerror="this.onerror=null;this.src='https://placehold.co/300x200?text=Outdoor+Rent';" 
                         class="card-img-top rounded-top" 
                         style="height: 180px; object-fit: cover;" 
                         alt="<?= htmlspecialchars($data['nama_alat']); ?>">
                    
                    <div class="card-body d-flex flex-column justify-content-between p-3">
                        <div>
                            <h6 class="card-title font-weight-bold text-dark text-truncate mb-1" title="<?= htmlspecialchars($data['nama_alat']); ?>">
                                <?= htmlspecialchars($data['nama_alat']); ?>
                            </h6>
                            <p class="card-text text-success font-weight-bold mb-1">
                                Rp <?= number_format($data['harga'], 0, ',', '.'); ?> <span class="text-muted small font-weight-normal">/ hari</span>
                            </p>
                        </div>
                        
                        <div class="mt-2">
                            <span class="badge badge-pill badge-info mb-3">
                                <i class="fas fa-boxes mr-1"></i> Stok: <?= $data['stok']; ?> unit
                            </span>
                            
                            <a href="<?= $link_wa; ?>" target="_blank" class="btn btn-whatsapp btn-block btn-sm shadow-sm">
                                <i class="fab fa-whatsapp mr-1"></i> Sewa Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                } 
            } else {
                echo '<div class="col-12 text-center my-5"><p class="text-muted lead">Waduh Kii, barang yang kamu cari tidak ditemukan atau stok kosong. ⛺</p></div>';
            }
            ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0 small">&copy; <?= date('Y'); ?> Outdoor Rent - Crafted for Zack's Project. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>