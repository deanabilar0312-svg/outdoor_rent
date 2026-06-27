<?php 
session_start();

// 1. Bersihkan semua variabel session
$_SESSION = array();

// 2. Hancurkan session cookie di browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session utama di server
session_destroy();

// 4. Hapus Cookie 'user_login' dan 'user_role' (Fitur Ingat Saya) secara total
if (isset($_COOKIE['user_login'])) {
    setcookie("user_login", "", time() - 3600, "/");
}
if (isset($_COOKIE['user_role'])) {
    setcookie("user_role", "", time() - 3600, "/");
}

// 5. Alihkan kembali ke halaman login dengan parameter notifikasi sukses
header("location:login.php?pesan=logout");
exit;
?>