<?php
session_start();
require_once 'config/database.php';

// Proses Simpan saat Form di-Submit
if (isset($_POST['simpan_keluar'])) {
    $nama        = $_POST['nama'];
    $kelas       = $_POST['kelas'];
    $tanggal     = $_POST['tanggal'];
    $waktu       = $_POST['waktu'];
    $keterangan  = $_POST['keterangan'];
    $guru_piket  = $_POST['guru_piket'];
    $tipe_izin   = 'izin keluar';

    // Gabungkan nama guru piket ke kolom keterangan agar tidak merubah struktur tabel DB
    $keterangan_full = $keterangan . " (Petugas Piket: " . $guru_piket . ")";

    $stmt = $pdo->prepare("INSERT INTO log_izin_siswa (tanggal, waktu, nama, kelas, keterangan, tipe_izin) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$tanggal, $waktu, $nama, $kelas, $keterangan_full, $tipe_izin])) {
        echo "<script>alert('Data izin keluar siswa berhasil disimpan!'); window.location.href='index.php';</script>";
        exit();
    }
}

// Ambil daftar master siswa untuk Autofill
$stmtSiswa = $pdo->query("SELECT * FROM master_siswa");
$daftarSiswa = $stmtSiswa->fetchAll();

include 'includes/header.php';
?>

<div style="max-width: 550px; margin: 40px auto; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); font-family: 'Segoe UI', sans-serif; border-top: 5px solid #eab308;">
    
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="color: #1e3a8a; margin: 0; font-size: 1.4rem; font-weight: bold; letter-spacing: 0.5px;">🚪 INPUT SISWA IZIN KELUAR</h3>
    </div>

    <form method="POST" action="">
        
        <!-- 1. KOTAK PENCARIAN UTAMA -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #334155; font-size: 0.9rem;">Cari Nama / NIS Siswa</label>
            <input type="text" id="pencarian_siswa" list="data_siswa" placeholder="Ketik nama atau NIS..." required style="width: 100%; padding: 12px; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none;">
            
            <datalist id="data_siswa">
                <?php foreach($daftarSiswa as $siswa): ?>
                    <option value="<?= htmlspecialchars($siswa['nis'] . ' - ' . $siswa['nama']); ?>" data-nama="<?= htmlspecialchars($siswa['nama']); ?>" data-kelas="<?= htmlspecialchars($siswa['kelas']); ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <!-- 2. HASIL IDENTITAS (AUTOFILL) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Nama Lengkap</label>
            <input type="text" name="nama" id="input_nama" readonly required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #f8fafc; color: #1e3a8a; font-weight: bold;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Kelas & Jurusan</label>
            <input type="text" name="kelas" id="input_kelas" readonly required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #f8fafc; color: #1e3a8a; font-weight: bold;">
        </div>

        <!-- 3. TANGGAL & JAM SEKARANG -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Tanggal</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #334155;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Jam Keluar</label>
                <input type="time" name="waktu" value="<?= date('H:i'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #334155;">
            </div>
        </div>

        <!-- 4. ALASAN KETERANGAN -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Alasan Izin Keluar</label>
            <textarea name="keterangan" rows="3" placeholder="Tulis alasan atau keperluan izin keluar..." required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; resize: none;"></textarea>
        </div>

        <!-- 5. GURU PIKET YANG BERTUGAS -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Guru Piket (Petugas Gerbang)</label>
            <input type="text" name="guru_piket" placeholder="Ketik nama Guru Piket..." required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;">
        </div>

        <!-- 6. TOMBOL AKSI -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" name="simpan_keluar" style="flex: 2; padding: 14px; background: #1e3a8a; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 1rem; cursor: pointer;">💾 Simpan Data</button>
            <a href="index.php" style="flex: 1; padding: 14px; background: #e2e8f0; color: #334155; text-decoration: none; text-align: center; border-radius: 8px; font-weight: bold; font-size: 1rem; line-height: 1.5;">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('pencarian_siswa').addEventListener('input', function() {
    var val = this.value;
    var opts = document.getElementById('data_siswa').getElementsByTagName('option');
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            document.getElementById('input_nama').value = opts[i].getAttribute('data-nama');
            document.getElementById('input_kelas').value = opts[i].getAttribute('data-kelas');
            break;
        }
    }
});
</script>