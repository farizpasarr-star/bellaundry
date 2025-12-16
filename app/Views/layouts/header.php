<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Bellaundry' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .topbar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:12px 20px;
            background: #f6fffd;
            border-bottom:1px solid #e6f3f2;
        }
        .topbar .user-name { font-weight:600; color:#094d5f; }
        .topbar .top-actions { display:flex; gap:8px; align-items:center; }
        .top-actions a { text-decoration:none; padding:8px 12px; border-radius:8px; font-size:14px; }
        .btn-outline { background:transparent; border:1px solid #bcdedd; color:#094d5f; }
        .btn-danger { background:#e53935; color:#fff; border:none; }
    </style>
</head>
<body>
<?php if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); } ?>

<header class="topbar">
    <div class="user-name">Halo, <?= htmlspecialchars($_SESSION['nama_pegawai'] ?? 'Pengguna') ?></div>
    <div class="top-actions">
        <a href="/bellaundry/app/Controllers/AuthController.php?aksi=changePassword" class="btn-outline">Ganti Password</a>
        <a href="/bellaundry/app/Controllers/AuthController.php?aksi=logout" class="btn-danger">Logout</a>
    </div>
</header>
