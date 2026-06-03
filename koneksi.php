<?php
// Pengaturan Zona Waktu (Sangat penting untuk monitoring absensi/keterlambatan siswa agar akurat)
date_default_timezone_set('Asia/Jakarta');

// Konfigurasi Database Localhost
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika menggunakan bawaan XAMPP
$database = "smkn2_monitoring"; // Nama database yang kamu buat di phpMyAdmin

// Membuat koneksi ke database MySQL
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$koneksi) {
    echo "<div style='padding: 20px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-family: sans-serif; border-radius: 5px; margin: 20px;'>";
    echo "<strong style='font-size: 1.2rem;'>Gagal Terhubung ke Database!</strong><br><br>";
    echo "Silakan periksa poin-poin berikut:<br>";
    echo "1. Pastikan aplikasi <strong>XAMPP</strong> sudah dijalankan (Apache & MySQL harus RUNNING).<br>";
    echo "2. Pastikan database bernama <strong>" . $database . "</strong> sudah dibuat di <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a>.<br>";
    echo "3. Detail error dari sistem: <u>" . mysqli_connect_error() . "</u>";
    echo "</div>";
    exit();
}

// Opsional: Pengaturan karakter UTF-8 agar input teks seperti nama siswa tidak rusak/berubah jika ada simbol
mysqli_set_charset($koneksi, "utf8mb4");
?>