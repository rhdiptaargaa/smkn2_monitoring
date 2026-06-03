<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged'])) {
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM log_izin_siswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data berkas izin tidak ditemukan.");
}

// Format Teks Tembusan untuk Dikirim via WhatsApp API ke Guru BK / Kesiswaan
$pesan_wa = "INFO IZIN SISWA SMKN 2 SRAGEN\n"
          . "===========================\n"
          . "Nama: " . $data['nama'] . "\n"
          . "Kelas/Jurusan: " . $data['kelas'] . " " . $data['jurusan'] . "\n"
          . "Tipe Izin: Izin " . ucfirst($data['tipe_izin']) . "\n"
          . "Keperluan: " . $data['keterangan'] . "\n"
          . "Waktu/Tanggal: " . $data['waktu'] . " / " . $data['tanggal'] . "\n"
          . "Status Kehadiran saat ini: " . ($data['status_kembali'] == 'sudah_kembali' ? 'Sudah Kembali' : 'Belum Kembali Ke Sekolah') . "\n\n"
          . "Disetujui oleh Piket: " . $data['guru_piket'];

$wa_url = "https://api.whatsapp.com/send?text=" . urlencode($pesan_wa);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cetak Surat Izin Resmi</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; padding: 30px; }
        .kopsurat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .btn-print-area { margin-bottom: 20px; background: #e2e8f0; padding: 10px; }
    </style>
</head>
<body>

<div class="btn-print-area">
    <button onclick="window.print()">🖨️ Cetak Dokumen Lembar Piket</button>
    <button onclick="window.location.href='<?= $wa_url; ?>'">📲 Tembuskan Berita via WhatsApp ke Guru BK/Kesiswaan</button>
</div>

<div class="kopsurat">
    <h2>SMK NEGERI 2 SRAGEN</h2>
    <p>Jl. Jenderal Ahmad Yani No.2, Sragen, Jawa Tengah</p>
    <strong>SURAT KETERANGAN REKAPITULASI IZIN AKSES KELUAR/MASUK</strong>
</div>

<table border="0" cellpadding="8">
    <tr><td>Nomor Log Berkas</td><td>: LOG-00<?= $data['id']; ?></td></tr>
    <tr><td>Nama Siswa</td><td>: <?= $data['nama']; ?></td></tr>
    <tr><td>Kelas / Jurusan</td><td>: <?= $data['kelas']; ?> / <?= $data['jurusan']; ?></td></tr>
    <tr><td>Tipe Izin Keterangan</td><td>: SURAT IZIN <?= strtoupper($data['tipe_izin']); ?></td></tr>
    <tr><td>Alasan / Keperluan</td><td>: <?= $data['keterangan']; ?></td></tr>
    <tr><td>Jam Mulai / Tanggal</td><td>: <?= $data['waktu']; ?> / <?= $data['tanggal']; ?></td></tr>
    <tr><td>Tanda Tangan Piket</td><td>: <?= $data['guru_piket']; ?></td></tr>
</table>

</body>
</html>