<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    die("Akses ditolak! Hanya admin yang dapat mencetak surat izin.");
}
require_once 'config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM log_izin_siswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin Keluar/Terlambat - <?= htmlspecialchars($data['nama']); ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; padding: 20px; color: #000; background: #fff; }
        .surat-box { border: 2px dashed #000; padding: 20px; max-width: 500px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px double #000; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 1.2rem; font-weight: bold; margin-top: 5px; }
        .isi-surat { font-size: 0.95rem; line-height: 1.6; }
        .ttd-box { display: flex; justify-content: space-between; margin-top: 40px; text-align: center; font-size: 0.9rem; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="no-print" style="text-align: center; margin-bottom: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; background: #22c55e; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">🖨️ Cetak Surat / Simpan ke PDF</button>
    <button onclick="window.close()" style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Tutup</button>
</div>

<div class="surat-box">
    <div class="header">
        <h3>SMKN 2 SRAGEN</h3>
        <div class="title">SURAT KETERANGAN IZIN PIKET</div>
    </div>
    
    <div class="isi-surat">
        <p>Tanggal : <strong><?= date('d-m-Y', strtotime($data['tanggal'])); ?></strong></p>
        <p>Waktu   : <strong><?= substr($data['waktu'], 0, 5); ?> WIB</strong></p>
        <p>Tipe    : <strong style="text-transform: uppercase;"><?= htmlspecialchars($data['tipe_izin']); ?></strong></p>
        <hr style="border-top: 1px dashed #000; margin: 10px 0;">
        <table style="width: 100%;">
            <tr><td style="width: 30%;">Nama</td><td>: <?= htmlspecialchars($data['nama']); ?></td></tr>
            <tr><td>Kelas</td><td>: <?= htmlspecialchars($data['kelas']); ?></td></tr>
            <tr><td>Alasan</td><td>: <?= htmlspecialchars($data['keterangan']); ?></td></tr>
        </table>
    </div>

    <div class="ttd-box">
        <div>
            <p>Siswa Bersangkutan</p>
            <br><br><br>
            <p>(....................)</p>
        </div>
        <div>
            <p>Guru Piket / Admin</p>
            <br><br><br>
            <p>(📋 Petugas Piket)</p>
        </div>
    </div>
</div>

<script>
    // Otomatis memicu cetak saat halaman dimuat
    window.onload = function() { window.print(); }
</script>
</body>
</html>