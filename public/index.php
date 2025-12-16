<?php
session_start();
$scriptDir = str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME']));
$base = preg_replace('#/public$#', '', $scriptDir);
if ($base === '/') $base = '';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $base . '/app/Controllers/AuthController.php?aksi=login');
    exit;
}
?>

<?php
require_once __DIR__ . '/../app/Models/Transaksi.php';
try {
    $transaksiModel = new Transaksi();
    $recentTransaksi = $transaksiModel->getAll();
    if (!is_array($recentTransaksi)) {
        $recentTransaksi = [];
    }
    $recentTransaksi = array_slice($recentTransaksi, 0, 5);
} catch (Exception $e) {
    $recentTransaksi = [];
}
?>

<?php // base already computed above ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Bellaundry</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,
        #a1c4fd 0%,
        #c2e9fb 35%,
        #d4f7e4 70%,
        #f7f9e6 100%
    );
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background-size: 300% 300%;
    animation: flowBg 14s ease infinite;
}

@keyframes flowBg {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

.header {
    text-align: center;
    padding: 40px 40px 30px;
    color: #094d5f;
    margin-top: -20px;
}

.header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.header p {
    font-size: 0.95rem;
    opacity: 0.8;
    margin: 0;
}

.menu-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    gap: 30px;
    justify-content: center;
    flex-wrap: wrap;
}

.card-menu {
    background: rgba(255,255,255,0.22); 
    border: none;
    border-radius: 22px;
    padding: 30px 20px;
    width: 260px;
    height: 220px;
    text-align: center;
    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
    backdrop-filter: blur(6px);
    transition: 0.35s;
    cursor: pointer;
}

.card-menu:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 18px 35px rgba(0,0,0,0.25);
}

.card-menu h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0b4d5e;
}

.icon-circle {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #00bcd4, #3dd9b6);
    border-radius: 50%;
    margin: 0 auto 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.icon-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.icon-circle i {
    font-size: 32px;
    color: white;
}

.footer {
    margin-top: 50px;
    text-align: center;
    padding: 10px 20px;
    color: #0b4d5e;
    font-weight: 500;
    opacity: 0.8;
    font-size: 0.9rem;
}

.header-top {
    display: flex;
    justify-content: space-between; 
    align-items: center;
    gap: 12px;
    padding: 15px 30px;
}

.btn-logout {
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s;
    font-size: 0.95rem;
    display: inline-block;
}

.header-top .btn-logout { margin-left: 12px; margin-left: auto; }

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(255, 107, 107, 0.3);
    color: white;
}

.soap-bubble-wrap {
    position: fixed;
    inset: 0; 
    pointer-events: none;
    overflow: hidden;
    z-index: 0; 
}
.soap-bubble {
    position: absolute;
    bottom: -120px;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 30%, rgba(255,255,255,0.98), rgba(255,255,255,0.6) 28%, rgba(255,255,255,0.06) 60%);
    mix-blend-mode: screen;
    opacity: 0.45; 
    filter: blur(0.6px) saturate(1.05);
    transform: translateY(0) scale(1);
    animation-name: bubbleRise;
    animation-timing-function: cubic-bezier(.2,.9,.3,1);
    box-shadow: 0 6px 14px rgba(0,0,0,0.06), inset 0 1px 6px rgba(255,255,255,0.28);
    border: 1px solid rgba(255,255,255,0.25);
}

@keyframes bubbleRise {
    0% { transform: translateY(0) scale(0.85); opacity: 0; }
    5% { opacity: 1; }
    60% { opacity: 0.7; }
    100% { transform: translateY(-120vh) scale(1.2); opacity: 0; }
}

.header-top, .header, .menu-container, .card-menu, .footer {
    position: relative;
    z-index: 2;
}

</style>
</style>

<script src="https://kit.fontawesome.com/5b5f05b1d6.js" crossorigin="anonymous"></script>

</head>
<body>

<div class="header-top">
    <?php if (isset($_SESSION['nama_pegawai'])): ?>
        <span style="color: #0b4d5e; font-weight: 500; margin-right: 12px;">
            👤 <?= htmlspecialchars($_SESSION['nama_pegawai']) ?>
        </span>
        <a href="<?= $base ?>/app/Controllers/AuthController.php?aksi=changePassword" style="margin-right:12px; color:#0b4d5e; font-weight:600; text-decoration:none;">
            Ganti Password
        </a>
    <?php endif; ?>
    <a href="?logout=1" class="btn-logout">➜] Logout</a>
</div>

<div class="header">
    <h1>Bellaundry</h1>
    <p>Laundry Modern oleh Mahasiswa Sistem Informasi</p>
</div>

<div class="menu-container">

    <a href="<?= $base ?>/app/Controllers/PelangganController.php?aksi=index" style="text-decoration:none;">
        <div class="card-menu">
            <div class="icon-circle">
                <img src="<?= $base ?>/public/assets/img/profile.jpg" alt="Pelanggan">
            </div>
            <h3>Daftar Pelanggan</h3>
            <p>Lihat & kelola data pelanggan</p>
        </div>
    </a>

    <a href="<?= $base ?>/app/Controllers/LayananController.php?aksi=index" style="text-decoration:none;">
        <div class="card-menu">
            <div class="icon-circle">
                <img src="<?= $base ?>/public/assets/img/profile.jpg" alt="Laundry">
            </div>
            <h3>Laundry Sekarang</h3>
            <p>Input dan Lihat Cucian</p>
        </div>
    </a>

    <a href="<?= $base ?>/app/Controllers/TransaksiController.php?aksi=index" style="text-decoration:none;">
        <div class="card-menu">
            <div class="icon-circle">
                <img src="<?= $base ?>/public/assets/img/profile.jpg" alt="Transaksi">
            </div>
            <h3>Transaksi</h3>
            <p>Kelola transaksi</p>
        </div>
    </a>

    <a href="<?= $base ?>/app/Controllers/LaporanController.php?aksi=index" style="text-decoration:none;">
        <div class="card-menu">
            <div class="icon-circle">
                <img src="<?= $base ?>/public/assets/img/profile.jpg" alt="Laporan">
            </div>
            <h3>Laporan Transaksi</h3>
            <p>Ringkasan transaksi</p>
        </div>
    </a>

</div>

<div class="footer">
Clean, Fresh, and Professional
</div>

<div class="soap-bubble-wrap" id="soapWrap" aria-hidden="true"></div>

<script>
(() => {
    const wrap = document.getElementById('soapWrap');
    if (!wrap) return;

    const maxBubbles = 8; 
    const spawnInterval = 1000; 

    function rand(min, max) {
        return Math.random() * (max - min) + min;
    }

    function createBubble() {
        const b = document.createElement('div');
        b.className = 'soap-bubble';

        const size = Math.round(rand(28, 90)); 
        b.style.width = size + 'px';
        b.style.height = size + 'px';

        b.style.left = rand(2, 98) + '%';

        const duration = rand(8000, 20000); 
        b.style.animationDuration = duration + 'ms';

        b.style.transform = 'translateY(0) rotate(' + rand(-10,10) + 'deg)';

        wrap.appendChild(b);

        setTimeout(() => {
            b.remove();
        }, duration + 200);
    }

    const intervalId = setInterval(() => {
        if (wrap.children.length < maxBubbles) createBubble();
    }, spawnInterval);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) clearInterval(intervalId);
    });
})();
</script>

</body>
</html>