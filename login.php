<?php
session_start();
include 'db.php';

// 1. CEK COOKIE (Jika user pernah mencentang 'Tetap Login')
if (isset($_COOKIE['user_login']) && isset($_COOKIE['user_role'])) {
    $_SESSION['status'] = "login";
    $_SESSION['user'] = $_COOKIE['user_login'];
    $_SESSION['role'] = $_COOKIE['user_role'];
    
    if ($_SESSION['role'] == 'admin') {
        header("location: index.php");
        exit;
    } else {
        header("location: katalog.php");
        exit;
    }
}

// 2. PROSES LOGIN SAAT TOMBOL SUBMIT DIKLIK
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role_pilihan = mysqli_real_escape_string($conn, $_POST['role']);
    $remember = isset($_POST['remember']);

    // Query mencocokkan username, password, dan role yang dipilih di form
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' AND role='$role_pilihan'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Daftarkan Session
        $_SESSION['status'] = "login";
        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role']; 

        // Jika 'Tetap Login' dicentang, buat cookie berlaku 30 hari
        if ($remember) {
            setcookie("user_login", $row['username'], time() + (86400 * 30), "/");
            setcookie("user_role", $row['role'], time() + (86400 * 30), "/");
        }

        // Pengalihan halaman berdasarkan hak akses
        if ($row['role'] == 'admin') {
            header("location: index.php"); // Admin kelola DB di index.php
            exit;
        } else {
            header("location: katalog.php"); // Customer langsung ke katalog
            exit;
        }
    } else {
        $error = "Username, Password, atau Pilihan Akses salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Outdoor Rent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition login-page" style="background: #e9ecef;">
<div class="login-box">
    <div class="card card-outline card-success">
        <div class="card-header text-center">
            <h1 class="h1"><b>Outdoor</b>Rent</h1>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Masuk untuk meminjam alat outdoor</p>
            
            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="form-group mb-3">
                    <label class="text-muted small font-weight-bold">Login Sebagai :</label>
                    <select name="role" class="form-control" required>
                        <option value="user">Customer (Penyewa)</option>
                        <option value="admin">Admin (Pengelola)</option>
                    </select>
                </div>
                
                <div class="row pt-2">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Tetap Login</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" name="submit" class="btn btn-success btn-block">Sign In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(isset($error)): ?>
<script>
    Swal.fire({ icon: 'error', title: 'Login Gagal', text: '<?= $error; ?>' });
</script>
<?php endif; ?>

<?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'logout'): ?>
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil Logout', text: 'Sesi Anda telah berakhir.', timer: 2500, showConfirmButton: false });
</script>
<?php endif; ?>
</body>
</html>