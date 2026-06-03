<?php
session_start();
require_once 'config/database.php';

// Fitur Kalender: Ambil tanggal dari input user, jika kosong default ke hari ini
$tanggal_pilih = isset($_GET['tanggal_filter']) ? $_GET['tanggal_filter'] : date('Y-m-d');

// Ambil data berdasarkan tanggal yang dipilih oleh user
$stmtTerlambat = $pdo->prepare("SELECT * FROM log_izin_siswa WHERE tipe_izin='terlambat' AND tanggal = ? ORDER BY waktu DESC");
$stmtTerlambat->execute([$tanggal_pilih]);
$dataTerlambat = $stmtTerlambat->fetchAll();

$stmtPulang = $pdo->prepare("SELECT * FROM log_izin_siswa WHERE tipe_izin='pulang' AND tanggal = ? ORDER BY waktu DESC");
$stmtPulang->execute([$tanggal_pilih]);
$dataPulang = $stmtPulang->fetchAll();

$stmtKeluar = $pdo->prepare("SELECT * FROM log_izin_siswa WHERE tipe_izin='keluar' AND tanggal = ? ORDER BY waktu DESC");
$stmtKeluar->execute([$tanggal_pilih]);
$dataKeluar = $stmtKeluar->fetchAll();

$stmtGuru = $pdo->prepare("SELECT * FROM log_izin_guru WHERE tanggal = ? ORDER BY waktu DESC");
$stmtGuru->execute([$tanggal_pilih]);
$dataGuru = $stmtGuru->fetchAll();

include 'includes/header.php';
?>

<div style="max-width: 1200px; margin: 30px auto; padding: 0 20px; font-family: 'Segoe UI', sans-serif;">
    
    <div style="text-align: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <h2 style="color: #1e3a8a; margin-bottom: 10px;">📊 DATA REKAP MONITORING SEKOLAH</h2>
        
        <form method="GET" action="" style="margin: 15px 0; display: inline-flex; align-items: center; gap: 10px;">
            <label style="font-weight: 600; color: #475569;">Pilih Tanggal Data:</label>
            <input type="date" name="tanggal_filter" value="<?= $tanggal_pilih; ?>" onchange="this.form.submit()" style="padding: 8px 12px; border: 2px solid #3b82f6; border-radius: 6px; font-size: 1rem; color: #1e3a8a; font-weight: bold; cursor: pointer;">
        </form>

        <div style="margin-top: 10px;">
            <?php if(isset($_SESSION['admin_logged'])): ?>
                <span style="background: #dcfce7; color: #166534; padding: 6px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                    🟢 Admin Akses: Fitur Cetak PDF, Hapus, & Broadcast WA Aktif
                </span>
            <?php else: ?>
                <span style="background: #f1f5f9; color: #475569; padding: 6px 15px; border-radius: 20px; font-size: 0.9rem;">
                    ⚪ Mode Tamu (Silakan Login Admin untuk akses fitur kontrol data)
                </span>
            <?php endif; ?>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        
        <!-- 1. KOTAK SISWA TERLAMBAT (SAMA SEPERTI FTERLAMBAT: MERAH) -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border-top: 4px solid #ef4444;">
            <div style="padding: 15px; background: #fef2f2; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #ef4444;">⏰ SISWA TERLAMBAT</h4>
                <span style="background: #ef4444; color: white; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 0.9rem;"><?= count($dataTerlambat); ?></span>
            </div>
            <div style="padding: 15px; max-height: 350px; overflow-y: auto;">
                <?php foreach($dataTerlambat as $row): ?>
                    <div style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <div style="margin-bottom: 8px;">
                            <strong style="font-size: 0.95rem; color: #334155;"><?= htmlspecialchars($row['nama']); ?></strong> <span style="font-size: 0.85rem; color: #64748b;">(<?= htmlspecialchars($row['kelas']); ?>)</span>
                            <div style="font-size: 0.8rem; color: #94a3b8;">Jam: <?= substr($row['waktu'], 0, 5); ?> - Alasan: <?= htmlspecialchars($row['keterangan']); ?></div>
                        </div>
                        
                        <?php if (isset($_SESSION['admin_logged'])): 
                            $textWA = "Pemberitahuan Izin/Terlambat Sekolah:\r\nSiswa: " . $row['nama'] . "\r\nKelas: " . $row['kelas'] . "\r\nKeterangan: Terlambat Masuk\r\nJam: " . substr($row['waktu'],0,5);
                        ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; background: #f8fafc; padding: 6px; border-radius: 6px;">
                                <a href="cetak_pdf.php?id=<?= $row['id']; ?>" target="_blank" style="padding: 4px 6px; background: #ef4444; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">📄 PDF</a>
                                <a href="hapus_siswa.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus data <?= htmlspecialchars($row['nama']); ?>?')" style="padding: 4px 6px; background: #475569; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">❌ Hapus</a>
                                <span style="font-size: 0.75rem; width: 100%; color: #64748b; margin-top: 2px; font-weight: 600;">Kirim WA Ke:</span>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU BK]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #22c55e; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru BK</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK KESISWAAN]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #16a34a; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Kesiswaan</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU MENGAJAR]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #15803d; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru Kls</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($dataTerlambat)) echo "<p style='color: #94a3b8; font-size: 0.9rem; text-align: center; padding: 10px 0;'>Tidak ada data masuk tanggal ini.</p>"; ?>
            </div>
        </div>

        <!-- 2. KOTAK SISWA IZIN KELUAR (SAMA SEPERTI FKELUAR: KUNING/ORANGE #eab308) -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border-top: 4px solid #eab308;">
            <div style="padding: 15px; background: #fefce8; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #eab308;">🚪 IZIN KELUAR</h4>
                <span style="background: #eab308; color: white; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 0.9rem;"><?= count($dataKeluar); ?></span>
            </div>
            <div style="padding: 15px; max-height: 350px; overflow-y: auto;">
                <?php foreach($dataKeluar as $row): ?>
                    <div style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <div style="margin-bottom: 8px;">
                            <strong style="font-size: 0.95rem; color: #334155;"><?= htmlspecialchars($row['nama']); ?></strong> <span style="font-size: 0.85rem; color: #64748b;">(<?= htmlspecialchars($row['kelas']); ?>)</span>
                            <div style="font-size: 0.8rem; color: #94a3b8;">Jam: <?= substr($row['waktu'], 0, 5); ?> - Keperluan: <?= htmlspecialchars($row['keterangan']); ?></div>
                        </div>
                        <?php if (isset($_SESSION['admin_logged'])): 
                            $textWA = "Pemberitahuan Izin/Terlambat Sekolah:\r\nSiswa: " . $row['nama'] . "\r\nKelas: " . $row['kelas'] . "\r\nKeterangan: Izin Keluar Sementara (" . $row['keterangan'] . ")\r\nJam: " . substr($row['waktu'],0,5);
                        ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; background: #f8fafc; padding: 6px; border-radius: 6px;">
                                <a href="cetak_pdf.php?id=<?= $row['id']; ?>" target="_blank" style="padding: 4px 6px; background: #ef4444; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">📄 PDF</a>
                                <a href="hapus_siswa.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus data <?= htmlspecialchars($row['nama']); ?>?')" style="padding: 4px 6px; background: #475569; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">❌ Hapus</a>
                                <span style="font-size: 0.75rem; width: 100%; color: #64748b; margin-top: 2px; font-weight: 600;">Kirim WA Ke:</span>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU BK]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #22c55e; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru BK</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK KESISWAAN]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #16a34a; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Kesiswaan</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU MENGAJAR]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #15803d; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru Kls</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($dataKeluar)) echo "<p style='color: #94a3b8; font-size: 0.9rem; text-align: center; padding: 10px 0;'>Tidak ada data masuk tanggal ini.</p>"; ?>
            </div>
        </div>

        <!-- 3. KOTAK SISWA IZIN PULANG (SAMA SEPERTI FPULANG: HIJAU #22c55e) -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border-top: 4px solid #22c55e;">
            <div style="padding: 15px; background: #f0fdf4; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #22c55e;">🏠 IZIN PULANG</h4>
                <span style="background: #22c55e; color: white; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 0.9rem;"><?= count($dataPulang); ?></span>
            </div>
            <div style="padding: 15px; max-height: 350px; overflow-y: auto;">
                <?php foreach($dataPulang as $row): ?>
                    <div style="padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <div style="margin-bottom: 8px;">
                            <strong style="font-size: 0.95rem; color: #334155;"><?= htmlspecialchars($row['nama']); ?></strong> <span style="font-size: 0.85rem; color: #64748b;">(<?= htmlspecialchars($row['kelas']); ?>)</span>
                            <div style="font-size: 0.8rem; color: #94a3b8;">Jam: <?= substr($row['waktu'], 0, 5); ?> - Alasan: <?= htmlspecialchars($row['keterangan']); ?></div>
                        </div>
                        <?php if (isset($_SESSION['admin_logged'])): 
                            $textWA = "Pemberitahuan Izin/Terlambat Sekolah:\r\nSiswa: " . $row['nama'] . "\r\nKelas: " . $row['kelas'] . "\r\nKeterangan: Izin Pulang Sekolah Darurat (" . $row['keterangan'] . ")\r\nJam: " . substr($row['waktu'],0,5);
                        ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; background: #f8fafc; padding: 6px; border-radius: 6px;">
                                <a href="cetak_pdf.php?id=<?= $row['id']; ?>" target="_blank" style="padding: 4px 6px; background: #ef4444; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">📄 PDF</a>
                                <a href="hapus_siswa.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus data <?= htmlspecialchars($row['nama']); ?>?')" style="padding: 4px 6px; background: #475569; color: white; border-radius: 4px; text-decoration: none; font-size: 0.75rem; font-weight: bold;">❌ Hapus</a>
                                <span style="font-size: 0.75rem; width: 100%; color: #64748b; margin-top: 2px; font-weight: 600;">Kirim WA Ke:</span>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU BK]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #22c55e; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru BK</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK KESISWAAN]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #16a34a; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Kesiswaan</a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode("[UNTUK GURU MENGAJAR]\r\n".$textWA); ?>" target="_blank" style="padding: 3px 6px; background: #15803d; color: white; border-radius: 4px; text-decoration: none; font-size: 0.7rem;">Guru Kls</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($dataPulang)) echo "<p style='color: #94a3b8; font-size: 0.9rem; text-align: center; padding: 10px 0;'>Tidak ada data masuk tanggal ini.</p>"; ?>
            </div>
        </div>

        <!-- 4. KOTAK IZIN GURU (SAMA SEPERTI FGURU: BIRU NAVY #1e3a8a) -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; border-top: 4px solid #1e3a8a;">
            <div style="padding: 15px; background: #eff6ff; display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #1e3a8a;">👨‍🏫 IZIN GURU</h4>
                <span style="background: #1e3a8a; color: white; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 0.9rem;"><?= count($dataGuru); ?></span>
            </div>
            <div style="padding: 15px; max-height: 350px; overflow-y: auto;">
                <?php foreach($dataGuru as $row): ?>
                    <div style="padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                        <strong style="font-size: 0.95rem; color: #334155;"><?= htmlspecialchars($row['nama_guru']); ?></strong>
                        <div style="font-size: 0.8rem; color: #64748b;">Jam: <?= substr($row['waktu'], 0, 5); ?> - Alasan: <?= htmlspecialchars($row['keterangan']); ?></div>
                        <div style="font-size: 0.75rem; color: #1e3a8a; font-style: italic;">Tugas: <?= htmlspecialchars($row['tugas']); ?></div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($dataGuru)) echo "<p style='color: #94a3b8; font-size: 0.9rem; text-align: center; padding: 10px 0;'>Tidak ada data masuk tanggal ini.</p>"; ?>
            </div>
        </div>

    </div>
</div>