<?php
session_start();

if (isset($_SESSION['user_logged'])) {
    header("Location: menu.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // HAK AKSES MULTIUSER
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user_logged'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['user_name'] = 'Admin Utama'; // Diubah sedikit biar lebih formal saat diuji guru
        header("Location: menu.php");
        exit;
    } elseif ($username === 'piket' && $password === 'piket123') {
        $_SESSION['user_logged'] = true;
        $_SESSION['role'] = 'user';
        $_SESSION['user_name'] = 'Petugas Piket';
        header("Location: menu.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Monitoring - SMKN 2 SRAGEN</title>
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f1f5f9; font-family: 'Segoe UI', sans-serif; margin:0; }
        .login-box { width: 100%; max-width: 400px; background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        h2 { text-align: center; color: #1e3a8a; margin-bottom: 5px; font-size: 1.5rem; }
        .sub-title { text-align: center; color: #64748b; font-size: 0.9rem; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #334155; font-size: 0.95rem; }
        .form-control { width: 100%; padding: 11px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; transition: all 0.2s; }
        .form-control:focus { outline: none; border-color: #1e3a8a; box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15); }
        .alert { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; text-align: center; margin-bottom: 15px; border: 1px solid #fca5a5; font-size: 0.9rem; }
        .btn { width: 100%; padding: 12px; background: #1e3a8a; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: background 0.2s; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>SISTEM MONITORING</h2>
    <div class="sub-title">SMKN 2 SRAGEN</div>
    
    <?php if($error): ?> 
        <div class="alert"><?= $error; ?></div> 
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Masukkan username" autocomplete="off">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
        </div>
        <button type="submit" class="btn">MASUK SISTEM</button>
    </form>
</div>
</body>
</html>