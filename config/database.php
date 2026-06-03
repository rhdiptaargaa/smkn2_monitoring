<?php
// ====================================================================
// 1. PENGATURAN ZONA WAKTU BERPUSAT DI JAKARTA (WIB)
// ====================================================================
date_default_timezone_set('Asia/Jakarta');

// ====================================================================
// 2. KONFIGURASI DATABASE LOCALHOST (PDO SYSTEM)
// ====================================================================
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika menggunakan XAMPP bawaan
$database = "smkn2_monitoring"; // Nama database kamu di phpMyAdmin
$charset  = "utf8mb4";

// Menyusun string DSN (Data Source Name) untuk koneksi PDO
$dsn = "mysql:host=$host;dbname=$database;charset=$charset";

// Pengaturan tambahan untuk keamanan dan performa query data
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menampilkan error jika query gagal
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil fetch otomatis jadi Array Asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menggunakan native prepared statements agar aman dari SQL Injection
];

try {
    // Membuat koneksi database baru menggunakan object PDO
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // ====================================================================
    // 3. MEMASTIKAN DATABASE MYSQL JUGA MENGGUNAKAN WAKTU ASIA/JAKARTA
    // ====================================================================
    $pdo->exec("SET time_zone = '+07:00'");

} catch (\PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan peringatan yang rapi
    echo "<div style='padding: 20px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-family: sans-serif; border-radius: 8px; margin: 20px;'>";
    echo "<strong style='font-size: 1.2rem;'>Gagal Terhubung ke Database (Sistem PDO)!</strong><br><br>";
    echo "Silakan periksa poin-poin berikut:<br>";
    echo "1. Pastikan aplikasi <strong>XAMPP</strong> sudah dijalankan (Apache & MySQL harus RUNNING).<br>";
    echo "2. Pastikan database bernama <strong>" . $database . "</strong> sudah dibuat di <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a>.<br>";
    echo "3. Detail error dari sistem: <u>" . $e->getMessage() . "</u>";
    echo "</div>";
    exit;
}
?>