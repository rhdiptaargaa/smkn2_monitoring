<?php
session_start();
include 'config/database.php';
include 'includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: menu.php");
    exit;
}

// Otomatis mencari koneksi database aktif
$db_koneksi = null;
foreach (get_defined_vars() as $key => $value) {
    if ($value instanceof mysqli) {
        $db_koneksi = $value;
        break;
    }
}
if (!$db_koneksi) { $db_koneksi = mysqli_connect("localhost", "root", "", "smkn2_monitoring"); }

$search = isset($_GET['search']) ? $_GET['search'] : '';
// FIX: Mengambil dari log_izin_siswa dengan filter tipe_izin = 'terlambat'
$query = "SELECT * FROM log_izin_siswa WHERE tipe_izin = 'terlambat' AND (nama LIKE '%$search%') ORDER BY tanggal DESC, waktu DESC";
$result = mysqli_query($db_koneksi, $query);
?>

<div style="max-width: 1200px; margin: 30px auto; padding: 20px; font-family: 'Segoe UI', sans-serif;">
    <h2 style="color: #ef4444; border-bottom: 3px solid #ef4444; padding-bottom: 10px;">🔴 Rekapitulasi Siswa Terlambat</h2>
    
    <form method="GET" style="margin: 20px 0; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Cari Nama Siswa..." value="<?= htmlspecialchars($search) ?>" style="padding: 10px; flex: 1; border: 1px solid #ddd; border-radius: 6px;">
        <button type="submit" style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">Cari Data</button>
    </form>

    <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
        <thead>
            <tr style="background: #ef4444; color: white; text-align: left;">
                <th style="padding: 15px; border: 1px solid #ddd;">Tanggal</th>
                <th style="padding: 15px; border: 1px solid #ddd;">Jam</th>
                <th style="padding: 15px; border: 1px solid #ddd;">Nama Siswa</th>
                <th style="padding: 15px; border: 1px solid #ddd;">Kelas</th>
                <th style="padding: 15px; border: 1px solid #ddd;">Keterangan / Alasan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px; border: 1px solid #ddd;"><?= $row['tanggal'] ?></td>
                    <td style="padding: 12px; border: 1px solid #ddd;"><?= $row['waktu'] ?></td>
                    <td style="padding: 12px; border: 1px solid #ddd;"><?= $row['nama'] ?></td>
                    <td style="padding: 12px; border: 1px solid #ddd;"><?= $row['kelas'] ?></td>
                    <td style="padding: 12px; border: 1px solid #ddd;"><?= $row['keterangan'] ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #64748b;">Tidak ada data siswa terlambat.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>