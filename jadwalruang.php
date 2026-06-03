<?php 
session_start();
require_once 'config/database.php'; 
include 'includes/header.php'; 

// Logika simpan data BARU (Hanya diproses kalau Admin Login)
if (isset($_POST['tambah_ruang']) && isset($_SESSION['admin_logged'])) {
    $nama_ruang     = $_POST['nama_ruang'];
    $kelas_pengguna = $_POST['kelas_pengguna'];
    $jam_ke         = $_POST['jam_ke'];
    $keterangan     = $_POST['keterangan'];
    $hari           = $_POST['hari'];

    $sql = "INSERT INTO jadwal_ruang (hari, nama_ruang, kelas_pengguna, jam_ke, keterangan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$hari, $nama_ruang, $kelas_pengguna, $jam_ke, $keterangan])) {
        echo "<script>alert('Jadwal penggunaan ruang berhasil disimpan!'); window.location.href='jadwalruang.php';</script>";
        exit();
    }
}

// Logika HAPUS data (Hanya diproses kalau Admin Login)
if (isset($_GET['hapus']) && isset($_SESSION['admin_logged'])) {
    $id_hapus = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM jadwal_ruang WHERE id = ?");
    $stmt->execute([$id_hapus]);
    echo "<script>alert('Jadwal berhasil dihapus!'); window.location.href='jadwalruang.php';</script>";
    exit();
}
?>

<div style="max-width: 1000px; margin: 30px auto; padding: 25px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); font-family: 'Segoe UI', sans-serif;">
    <h2 style="color: #4f46e5; border-bottom: 3px solid #e0e7ff; padding-bottom: 10px; margin-top: 0;">🏢 Jadwal Penggunaan Ruang & Laboratorium</h2>
    
    <div style="margin: 20px 0;">
        <input type="text" id="searchRuang" placeholder="🔍 Ketik nama ruang, kelas, hari, atau mapel untuk menyaring jadwal..." style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-size: 1rem;">
    </div>

    <?php if (isset($_SESSION['admin_logged'])): ?>
        <div style="background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 25px;">
            <h4 style="margin-top: 0; color: #4f46e5; margin-bottom: 15px;">➕ Tambah Pemakaian Ruang (Fitur Manajemen Admin)</h4>
            <form method="POST" action="" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">
                <select name="hari" required style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; background: #fff;">
                    <option value="">-- Hari --</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                </select>
                <input type="text" name="nama_ruang" placeholder="Nama Ruang (Contoh: Lab TJKT 1)" required style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <input type="text" name="kelas_pengguna" placeholder="Kelas (Contoh: XI TJKT 2)" required style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <input type="text" name="jam_ke" placeholder="Jam ke- (Contoh: 5-8)" required style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <input type="text" name="keterangan" placeholder="Mata Pelajaran / Kegiatan" required style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <button type="submit" name="tambah_ruang" style="padding: 10px; background: #4f46e5; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Simpan</button>
            </form>
        </div>
    <?php endif; ?>

    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem; margin-top: 15px;">
        <thead>
            <tr style="background: #4f46e5; color: white; text-align: left;">
                <th style="padding: 12px; border: 1px solid #e2e8f0;">Hari</th>
                <th style="padding: 12px; border: 1px solid #e2e8f0;">Nama Ruang / Lab</th>
                <th style="padding: 12px; border: 1px solid #e2e8f0;">Kelas Pengguna</th>
                <th style="padding: 12px; border: 1px solid #e2e8f0;">Jam Ke</th>
                <th style="padding: 12px; border: 1px solid #e2e8f0;">Mata Pelajaran / Kegiatan</th>
                <?php if (isset($_SESSION['admin_logged'])): ?>
                    <th style="padding: 12px; border: 1px solid #e2e8f0; width: 80px;">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Ambil data ruang
            $stmt = $pdo->query("SELECT * FROM jadwal_ruang ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')");
            while($r = $stmt->fetch()):
            ?>
            <tr class="ruang-row" style="border-bottom: 1px solid #e2e8f0;">
                <td class="col-hari" style="padding: 12px; font-weight: bold; color: #1e293b;"><?= htmlspecialchars($r['hari']); ?></td>
                <td class="col-nama-ruang" style="padding: 12px; font-weight: 600; color: #4f46e5;"><?= htmlspecialchars($r['nama_ruang']); ?></td>
                <td class="col-kelas" style="padding: 12px; color: #334155;"><?= htmlspecialchars($r['kelas_pengguna']); ?></td>
                <td style="padding: 12px; color: #334155;"><?= htmlspecialchars($r['jam_ke']); ?></td>
                <td class="col-kegiatan" style="padding: 12px; color: #64748b;"><?= htmlspecialchars($r['keterangan']); ?></td>
                <?php if (isset($_SESSION['admin_logged'])): ?>
                    <td style="padding: 12px; text-align: center;">
                        <a href="jadwalruang.php?hapus=<?= $r['id']; ?>" onclick="return confirm('Hapus jadwal ruang ini?')" style="color: #ef4444; text-decoration: none; font-weight: bold;">❌ Hapus</a>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchRuang').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('.ruang-row');
    
    rows.forEach(function(row) {
        let hari = row.querySelector('.col-hari').textContent.toLowerCase();
        let ruang = row.querySelector('.col-nama-ruang').textContent.toLowerCase();
        let kelas = row.querySelector('.col-kelas').textContent.toLowerCase();
        let kegiatan = row.querySelector('.col-kegiatan').textContent.toLowerCase();
        
        if (hari.includes(filter) || ruang.includes(filter) || kelas.includes(filter) || kegiatan.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>