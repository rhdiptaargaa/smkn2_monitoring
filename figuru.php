<?php
session_start();
require_once 'config/database.php';

// Proses Simpan saat Form di-Submit
if (isset($_POST['simpan_guru'])) {
    $nama        = $_POST['nama'];
    $jabatan     = $_POST['jabatan']; // Kolom kelas diisi NIP Guru
    $tanggal     = $_POST['tanggal'];
    $waktu       = $_POST['waktu'];
    $tipe_izin   = $_POST['tipe_izin']; // 'terlambat', 'izin keluar', atau 'izin pulang'
    $keterangan  = $_POST['keterangan'];
    $tugas_guru  = $_POST['tugas_guru']; // Input Tugas yang diberikan guru

    // Gabungkan keterangan alasan dan tugas yang diberikan agar masuk ke database dengan aman
    $keterangan_full = $keterangan . " (Tugas: " . $tugas_guru . ")";

    $stmt = $pdo->prepare("INSERT INTO log_izin_siswa (tanggal, waktu, nama, kelas, keterangan, tipe_izin) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$tanggal, $waktu, $nama, $jabatan, $keterangan_full, $tipe_izin])) {
        echo "<script>alert('Data izin guru berhasil disimpan!'); window.location.href='index.php';</script>";
        exit();
    }
}

// Ambil daftar master guru untuk Autofill pencarian
$stmtGuru = $pdo->query("SELECT * FROM master_guru");
$daftarGuru = $stmtGuru->fetchAll();

include 'includes/header.php';
?>

<div style="max-width: 550px; margin: 40px auto; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.05); font-family: 'Segoe UI', sans-serif; border-top: 5px solid #1e3a8a;">
    
    <div style="text-align: center; margin-bottom: 25px;">
        <h3 style="color: #1e3a8a; margin: 0; font-size: 1.4rem; font-weight: bold; letter-spacing: 0.5px;">📋 INPUT DATA IZIN GURU</h3>
    </div>

    <form method="POST" action="">
        
        <!-- 1. KOTAK PENCARIAN UTAMA GURU -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #334155; font-size: 0.9rem;">Cari Nama / NIP Guru</label>
            <input type="text" id="pencarian_guru" list="data_guru" placeholder="Ketik nama atau NIP guru..." required style="width: 100%; padding: 12px; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none;">
            
            <datalist id="data_guru">
                <?php foreach($daftarGuru as $guru): ?>
                    <option value="<?= htmlspecialchars($guru['nip'] . ' - ' . $guru['nama_guru']); ?>" data-nama="<?= htmlspecialchars($guru['nama_guru']); ?>" data-nip="<?= htmlspecialchars($guru['nip']); ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <!-- 2. HASIL IDENTITAS GURU (AUTOFILL) -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Nama Lengkap Guru</label>
            <input type="text" name="nama" id="input_nama_guru" readonly required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #f8fafc; color: #1e3a8a; font-weight: bold;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">NIP / No. Identitas</label>
            <input type="text" name="jabatan" id="input_nip_guru" readonly required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; background: #f8fafc; color: #1e3a8a; font-weight: bold;">
        </div>

        <!-- OPSI PILIHAN TIPE LAYANAN -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Tipe Keterangan</label>
            <select name="tipe_izin" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white;">
                <option value="terlambat">⏰ Terlambat Datang</option>
                <option value="izin keluar">🚪 Izin Keluar Lingkungan Sekolah</option>
                <option value="izin pulang">🏠 Izin Pulang Awal</option>
            </select>
        </div>

        <!-- 3. TANGGAL & JAM SEKARANG (DI BAWAH IDENTITAS) -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Tanggal</label>
                <input type="date" name="tanggal" value="<?= date('Y-m-d'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #334155;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Jam</label>
                <input type="time" name="waktu" value="<?= date('H:i'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #334155;">
            </div>
        </div>

        <!-- 4. ALASAN / KETERANGAN -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Alasan / Keperluan</label>
            <textarea name="keterangan" rows="3" placeholder="Tulis alasan atau keperluan Bapak/Ibu Guru..." required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; resize: none;"></textarea>
        </div>

        <!-- 5. TUGAS YANG DIBERIKAN UNTUK KELAS YANG DITINGGALKAN (PERUBAHAN DI SINI) -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #475569; font-size: 0.9rem;">Tugas yang Diberikan (Amanah Pembelajaran)</label>
            <textarea name="tugas_guru" rows="2" placeholder="Contoh: Mengerjakan LKS hal 45, dikumpulkan di ketua kelas..." required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; resize: none;"></textarea>
        </div>

        <!-- 6. TOMBOL AKSI -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" name="simpan_guru" style="flex: 2; padding: 14px; background: #1e3a8a; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 1rem; cursor: pointer;">💾 Simpan Data Guru</button>
            <a href="index.php" style="flex: 1; padding: 14px; background: #e2e8f0; color: #334155; text-decoration: none; text-align: center; border-radius: 8px; font-weight: bold; font-size: 1rem; line-height: 1.5;">Batal</a>
        </div>
    </form>
</div>

<!-- JAVASCRIPT AUTOFILL GURU -->
<script>
document.getElementById('pencarian_guru').addEventListener('input', function() {
    var val = this.value;
    var opts = document.getElementById('data_guru').getElementsByTagName('option');
    
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            document.getElementById('input_nama_guru').value = opts[i].getAttribute('data-nama');
            document.getElementById('input_nip_guru').value = opts[i].getAttribute('data-nip');
            break;
        }
    }
});
</script>