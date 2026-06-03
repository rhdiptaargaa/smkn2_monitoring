<?php
session_start();
require_once 'config/database.php';

// 1. PROSES TAMBAH JADWAL (Hanya Bisa Dieksekusi jika Sudah Login Admin)
if (isset($_POST['tambah_jadwal'])) {
    if (!isset($_SESSION['admin_logged'])) {
        echo "<script>alert('Akses ditolak!'); window.location.href='jadwalguru.php';</script>";
        exit();
    }
    
    $hari       = $_POST['hari'];
    $nama_guru  = $_POST['nama_guru'];
    $lokasi     = $_POST['lokasi']; // Ruang mengajar/kelas

    $sql = "INSERT INTO jadwal_piket (hari, nama_guru, lokasi) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$hari, $nama_guru, $lokasi])) {
        echo "<script>alert('Jadwal mengajar guru berhasil ditambahkan!'); window.location.href='jadwalguru.php';</script>";
        exit();
    }
}

// 2. PROSES HAPUS JADWAL (Hanya Bisa Dieksekusi jika Sudah Login Admin)
if (isset($_GET['hapus'])) {
    if (!isset($_SESSION['admin_logged'])) {
        echo "<script>alert('Akses ditolak!'); window.location.href='jadwalguru.php';</script>";
        exit();
    }
    
    $id = $_GET['hapus'];
    $sql = "DELETE FROM jadwal_piket WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo "<script>alert('Jadwal berhasil dihapus!'); window.location.href='jadwalguru.php';</script>";
        exit();
    }
}

// 3. AMBIL DATA JADWAL UNTUK DITAMPILKAN
$stmt = $pdo->query("SELECT * FROM jadwal_piket ORDER BY FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')");
$allJadwal = $stmt->fetchAll();

include 'includes/header.php';
?>

<div style="max-width: 1000px; margin: 30px auto; padding: 0 20px; font-family: 'Segoe UI', sans-serif;">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #1e3a8a; margin-bottom: 5px;">📅 JADWAL RUANG MENGAJAR GURU</h2>
        <p style="color: #64748b; margin-top: 0;">Digunakan oleh siswa untuk memantau lokasi ruang mengajar Bapak/Ibu Guru.</p>
    </div>

    <!-- 🔑 FORM TAMBAH JADWAL (KHUSUS ADMIN) -->
    <?php if (isset($_SESSION['admin_logged'])): ?>
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px; border-left: 4px solid #22c55e;">
            <h3 style="color: #166534; margin-bottom: 15px;">➕ Tambah Jadwal Mengajar Guru</h3>
            <form method="POST" action="" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px; color: #475569;">Hari</label>
                    <select name="hari" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; background: white;">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                    </select>
                </div>
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px; color: #475569;">Nama Lengkap Guru</label>
                    <input type="text" name="nama_guru" placeholder="Contoh: Drs. Eko Santoso, M.Kom." required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                <div style="flex: 1; min-width: 180px;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 5px; color: #475569;">Ruang Kelas / Mengajar</label>
                    <input type="text" name="lokasi" placeholder="Contoh: XI TJKT 1" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;">
                </div>
                <button type="submit" name="tambah_jadwal" style="padding: 11px 20px; background: #22c55e; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    💾 Simpan
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- 🔍 LIVE SEARCH KECIL -->
    <div style="margin-bottom: 15px;">
        <input type="text" id="inputCari" onkeyup="fiturCari()" placeholder="🔍 Cari Nama Guru atau Ruangan..." style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    </div>

    <!-- 📊 TABEL TAMPILAN JADWAL -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        <table id="tabelGuru" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #1e3a8a; color: white;">
                    <th style="padding: 12px 15px;">Hari</th>
                    <th style="padding: 12px 15px;">Nama Guru</th>
                    <th style="padding: 12px 15px;">Ruang / Kelas Mengajar</th>
                    <?php if (isset($_SESSION['admin_logged'])): ?>
                        <th style="padding: 12px 15px; text-align: center;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allJadwal as $row): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;">
                        <td style="padding: 12px 15px; font-weight: 600; color: #1e3a8a;"><?= htmlspecialchars($row['hari']); ?></td>
                        <td style="padding: 12px 15px; color: #334155;"><?= htmlspecialchars($row['nama_guru']); ?></td>
                        <td style="padding: 12px 15px;"><span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;"><?= htmlspecialchars($row['lokasi']); ?></span></td>
                        <?php if (isset($_SESSION['admin_logged'])): ?>
                            <td style="padding: 12px 15px; text-align: center;">
                                <a href="jadwalguru.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Hapus jadwal mengajar <?= htmlspecialchars($row['nama_guru']); ?>?')" style="padding: 4px 8px; background: #ef4444; color: white; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: bold;">❌ Hapus</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($allJadwal)) echo "<tr><td colspan='4' style='text-align:center; padding: 20px; color: #94a3b8;'>Belum ada data jadwal mengajar.</td></tr>"; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- ⚙️ JAVASCRIPT LIVE SEARCH CEPAT -->
<script>
function fiturCari() {
    var input, filter, table, tr, tdGuru, tdLokasi, i, txtValueGuru, txtValueLokasi;
    input = document.getElementById("inputCari");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabelGuru");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tdGuru = tr[i].getElementsByTagName("td")[1];
        tdLokasi = tr[i].getElementsByTagName("td")[2];
        if (tdGuru || tdLokasi) {
            txtValueGuru = tdGuru.textContent || tdGuru.innerText;
            txtValueLokasi = tdLokasi.textContent || tdLokasi.innerText;
            if (txtValueGuru.toUpperCase().indexOf(filter) > -1 || txtValueLokasi.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>