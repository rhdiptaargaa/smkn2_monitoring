<?php
session_start();
require_once '../config/database.php';

// Fitur ini diamankan: Hanya Admin yang sudah login yang bisa memvalidasi kepulangan
if (!isset($_SESSION['admin_logged'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak. Anda bukan admin.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tipe = $_POST['tipe']; // 'siswa' atau 'guru'
    $status = $_POST['status']; // 'sudah_kembali' atau 'belum_kembali'

    if ($tipe === 'siswa') {
        $stmt = $pdo->prepare("UPDATE log_izin_siswa SET status_kembali = ? WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE log_izin_guru SET status_kembali = ? WHERE id = ?");
    }

    if ($stmt->execute([$status, $id])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>