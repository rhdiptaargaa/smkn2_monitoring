<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring Izin & Terlambat - SMKN 2 SRAGEN</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            background-color: #f8fafc;
        }
        .navbar {
            background: #1e3a8a;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .nav-logo img {
            height: 40px;
        }
        .nav-menu {
            display: flex;
            gap: 15px;
            list-style: none;
            align-items: center;
        }
        .nav-item a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .nav-item a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .nav-item.active a {
            color: white;
            background: #3b82f6;
            font-weight: bold;
        }
        .btn-login {
            background: #22c55e;
            color: white !important;
            font-weight: bold !important;
        }
        .btn-login:hover {
            background: #16a34a !important;
        }
        .btn-logout {
            background: #ef4444;
            color: white !important;
            font-weight: bold !important;
        }
        .btn-logout:hover {
            background: #dc2626 !important;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-logo">
        <img src="assets/logo_tusra.png" alt="" onerror="this.style.display='none'">
        <span>SMKN 2 SRAGEN</span>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item"><a href="index.php">📊 Dashboard</a></li>
        <li class="nav-item"><a href="fterlambat.php">⏰ Terlambat</a></li>
        <li class="nav-item"><a href="fikeluar.php">🚪 Izin Keluar</a></li>
        <li class="nav-item"><a href="fipulang.php">🏠 Izin Pulang</a></li>
        <li class="nav-item"><a href="figuru.php">👨‍🏫 Izin Guru</a></li>
        
        <li class="nav-item"><a href="jadwalruang.php">🏢 Jadwal Ruang</a></li>
        <li class="nav-item"><a href="jadwalguru.php">📅 Jadwal Guru</a></li> <?php if (isset($_SESSION['admin_logged'])): ?>
            <li class="nav-item" style="margin-left: 10px;">
                <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin logout?')">🔒 Logout (Admin)</a>
            </li>
        <?php else: ?>
            <li class="nav-item" style="margin-left: 10px;">
                <a href="login.php" class="btn-login">🔑 Login Admin</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<div style="margin-bottom: 40px;"></div>