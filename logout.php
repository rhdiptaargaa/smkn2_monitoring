<?php
// 1. Memulai atau mengaktifkan sesi yang sedang berjalan
session_start();

// 2. Menghapus semua variabel sesi yang tersimpan (seperti data login guru piket)
$_SESSION = array();

// 3. Menghancurkan/menghapus seluruh sesi dari server
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// 4. Mengembalikan pengguna ke halaman utama (menu utama) atau halaman login
echo "<script>
    alert('Anda telah berhasil keluar dari sistem.');
    window.location.href = 'index.php';
</script>";
exit();
?>