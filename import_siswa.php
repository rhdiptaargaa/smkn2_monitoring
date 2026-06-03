<?php
require_once 'config/database.php';

// Pastikan nama file CSV hasil Save As kamu sudah sama persis di sini ya, Ra!
$files = ['siswa_x.csv', 'siswa_xi.csv', 'siswa_xii.csv'];
$total_sukses = 0;

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "<p style='color: #ef4444;'>❌ File <strong>$file</strong> tidak ditemukan di folder utama!</p>";
        continue;
    }

    echo "<p style='color: #1e3a8a;'>⏳ Memproses file: <strong>$file</strong>...</p>";
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) { 
            // Jika baris kosong atau dibaca sebagai satu kesatuan karena pemisah koma, kita switch ke koma
            if (count($row) == 1) {
                rewind($handle);
                // Coba baca ulang menggunakan pemisah koma
                while (($row_koma = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    proses_baris_data($row_koma, $pdo, $total_sukses);
                }
                break;
            } else {
                proses_baris_data($row, $pdo, $total_sukses);
            }
        }
        fclose($handle);
    }
}

// Fungsi pendeteksi otomatis kolom NIS, Nama, dan Kelas di file sekolahmu
function proses_baris_data($row, $pdo, &$total_sukses) {
    // Bersihkan spasi kosong
    $row = array_map('trim', $row);
    
    // Skip baris yang isinya judul/kosong (Ciri: tidak ada nomor NIS berupa angka)
    if (!isset($row[2]) || !is_numeric($row[2])) {
        // Cek alternatif indeks kalau di kelas X kolomnya agak geser
        if (isset($row[3]) && is_numeric($row[3])) {
            $nis   = $row[3];
            $nama  = $row[4];
            $kelas = $row[1];
        } else {
            return; // Lewati baris judul / kosong
        }
    } else {
        // Format kelas XI & XII biasa
        $kelas = $row[0];
        $nis   = $row[2];
        $nama  = $row[3];
    }

    // Eksekusi Simpan ke Database
    if (!empty($nis) && !empty($nama)) {
        $stmt = $pdo->prepare("INSERT INTO master_siswa (nis, nama, kelas) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nama=?, kelas=?");
        $stmt->execute([$nis, $nama, $kelas, $nama, $kelas]);
        $total_sukses++;
    }
}

echo "<div style='padding: 20px; background: #dcfce7; color: #15803d; border-radius: 8px; margin-top: 20px; font-family: sans-serif;'>";
echo "<h3>🎉 HOREEE! Proses Selesai.</h3>";
echo "<p>Berhasil memasukkan <strong>$total_sukses siswa</strong> ke dalam database master!</p>";
echo "<a href='index.php' style='display:inline-block; padding:10px 15px; background:#15803d; color:white; text-decoration:none; border-radius:5px; margin-top:10px;'>Coba Cari di Form Sekarang ➔</a>";
echo "</div>";
?>