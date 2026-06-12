<?php
session_start();

// Proteksi halaman: kalau belum login, tendang balik ke login.php
if (!isset($_SESSION['user_logged'])) {
    header("Location: login.php");
    exit;
}

// FIX: Tambahkan baris ini agar koneksi database terhubung dengan aman di panel menu
include 'config/database.php'; 

// MENYAMAKAN NAVBAR DENGAN FILE UTAMA (Memanggil Header Utama Biar Serasi)
include 'includes/header.php'; 
?>

<div style="max-width: 1200px; margin: 30px auto; padding: 0 20px; font-family: 'Segoe UI', sans-serif;">
    
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); margin-bottom: 30px; border-left: 6px solid #1e3a8a;">
        <h2 style="margin-top: 0; color: #1e3a8a;">Selamat Datang di Panel Utama Sekolah</h2>
        <p style="color: #64748b; margin-bottom: 0;">
            Login sebagai: <strong style="color: #1e3a8a"><?= $_SESSION['user_name']; ?></strong> (<?= strtoupper($_SESSION['role']); ?>). 
            Silakan gunakan panel rekapitulasi di bawah ini untuk memantau data log monitoring.
        </p>
    </div>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <h3 style="font-size: 1.2rem; color: #1e3a8a; margin: 30px 0 15px 0; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #cbd5e1; padding-bottom: 5px;">
            Panel Rekapitulasi Laporan
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
            
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #ef4444;">
                <h3 style="margin-top: 0; font-size: 1.1rem; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; color: #ef4444;">Rekap Data Terlambat</h3>
                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">Melihat, mencari data siswa, serta memantau tanggal record keterlambatan pagi.</p>
                <a href="rekap_terlambat.php" style="display: block; text-align: center; padding: 10px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; font-weight: bold; color: white; background: #ef4444;">Buka Rekap Terlambat</a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #f97316;">
                <h3 style="margin-top: 0; font-size: 1.1rem; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; color: #f97316;">Rekap Izin Pulang</h3>
                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">Memantau arsip histori laporan, tanggal, and nama siswa yang mengajukan izin pulang darurat.</p>
                <a href="rekap_ipulang.php" style="display: block; text-align: center; padding: 10px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; font-weight: bold; color: white; background: #f97316;">Buka Rekap Izin Pulang</a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #10b981;">
                <h3 style="margin-top: 0; font-size: 1.1rem; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; color: #10b981;">Rekap Izin Keluar</h3>
                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">Memantau log harian siswa keluar-masuk gerbang sekolah pada jam KBM aktif.</p>
                <a href="rekap_ikeluar.php" style="display: block; text-align: center; padding: 10px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; font-weight: bold; color: white; background: #10b981;">Buka Rekap Izin Keluar</a>
            </div>

            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #4f46e5;">
                <h3 style="margin-top: 0; font-size: 1.1rem; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; color: #4f46e5;">Rekap Izin Guru</h3>
                <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-bottom: 15px;">Mengecek rekapitulasi data log tanggal serta nama guru-guru yang sedang izin dinas luar.</p>
                <a href="rekap_figuru.php" style="display: block; text-align: center; padding: 10px; text-decoration: none; border-radius: 6px; font-size: 0.9rem; font-weight: bold; color: white; background: #4f46e5;">Buka Rekap Izin Guru</a>
            </div>

        </div>

        <div style="margin-top: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            
            <div style="display: flex; gap: 15px;">
                <a href="jadwal_ruang.php" style="display: inline-block; text-align: center; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-size: 0.95rem; font-weight: bold; color: white; background: #854d0e; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    📅 Jadwal Ruang
                </a>
                <a href="jadwal_guru.php" style="display: inline-block; text-align: center; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-size: 0.95rem; font-weight: bold; color: white; background: #2563eb; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    👨‍🏫 Jadwal Guru
                </a>
            </div>

            <div>
                <a href="logout.php" style="display: inline-block; text-align: center; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-size: 0.95rem; font-weight: bold; color: white; background: #ef4444; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);">
                    ↩️ Logout 
                </a>
            </div>

        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 40px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); margin-top: 20px;">
            <p style="color: #64748b; font-size: 1rem;">Anda masuk sebagai Petugas Piket. Silakan gunakan menu navigasi di atas untuk menginput data kehadiran.</p>
        </div>
    <?php endif; ?>

</div>