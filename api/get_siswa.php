<?php
require_once '../config/database.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE nis = :q OR nama LIKE :like_q LIMIT 5");
    $stmt->execute([
        'q' => $search,
        'like_q' => "%$search%"
    ]);
    $result = $stmt->fetchAll();
    echo json_encode($result);
} else {
    echo json_encode([]);
}
?>