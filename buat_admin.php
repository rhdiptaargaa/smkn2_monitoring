<?php
// Hubungkan ke database utama menggunakan PDO
require_once 'config/database.php';

try {
    // 1. Perintah SQL untuk membuat tabel admin jika belum ada
    $sql_buat_tabel = "CREATE TABLE IF NOT EXISTS `admin` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `nama_lengkap` VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql_buat_tabel);
    echo "<h3>1. Tabel 'admin' berhasil diperiksa/dibuat!</h3>";

    // 2. Cek apakah username 'admin' sudah ada atau belum
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->fetchColumn() == 0) {
        // Enkripsi password 'admin123' secara aman sesuai kebutuhan login.php
        $password_aman = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Masukkan data admin default
        $sql_isi_data = "INSERT INTO admin (username, password, nama_lengkap) VALUES (?, ?, ?)";
        $stmt_insert = $pdo->prepare($sql_isi_data);
        $stmt_insert->execute(['admin', $password_aman, 'Aira TJKT']);
        
        echo "<h3>2. Akun admin default BERHASIL ditambahkan!</h3>";
    } else {
        echo "<h3>2. Akun dengan username 'admin' sudah ada sebelumnya.</h3>";
    }

    echo "<br><p style='color: green; font-weight: bold;'>Selesai! Sekarang silakan buka halaman <a href='login.php'>login.php</a> dan coba masuk.</p>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Terjadi Error: " . $e->getMessage() . "</h3>";
}
?>