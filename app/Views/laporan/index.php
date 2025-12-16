<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #b6f0ff, #9be9d8, #c3fff1);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-size: 300% 300%;
            animation: flowBg 12s ease infinite;
        }

        @keyframes flowBg {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .header {
            text-align: center;
            padding: 30px 20px;
            color: #094d5f;
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

        .container-laporan {
            max-width: 950px;
            margin: 5px auto;
            padding: 0 20px;
        }

        .card-laporan {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            transition: 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .card-laporan:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
            text-decoration: none;
            color: inherit;
        }

        .card-laporan h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0b4d5e;
            margin: 0 0 4px 0;
        }

        .card-laporan p {
            color: #666;
            margin: 0;
            font-size: 0.9rem;
        }

        .card-laporan-content {
            flex: 1;
        }

        .icon-laporan {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00bcd4, #3dd9b6);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 32px;
        }

        .btn-kembali {
            background: linear-gradient(135deg, #00bcd4, #3dd9b6);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-kembali:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,188,212,0.3);
            text-decoration: none;
            color: white;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            padding: 10px 20px;
            color: #0b4d5e;
            font-weight: 500;
            opacity: 0.8;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Transaksi</h1>
    <p>Pilih jenis laporan yang ingin Anda lihat</p>
</div>

<div class="container-laporan">
    <a href="/bellaundry/public/index.php" class="btn-kembali">← Kembali ke Dashboard</a>

    <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=harian" class="card-laporan">
        <div class="icon-laporan">📅</div>
        <div class="card-laporan-content">
            <h3>Laporan Harian</h3>
            <p>Lihat transaksi berdasarkan tanggal tertentu</p>
        </div>
    </a>

    <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=bulanan" class="card-laporan">
        <div class="icon-laporan">🌙</div>
        <div class="card-laporan-content">
            <h3>Laporan Bulanan</h3>
            <p>Lihat transaksi dalam satu bulan</p>
        </div>
    </a>

    <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=tahunan" class="card-laporan">
        <div class="icon-laporan">🎂</div>
        <div class="card-laporan-content">
            <h3>Laporan Tahunan</h3>
            <p>Lihat transaksi sepanjang tahun</p>
        </div>
    </a>

    <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=pendapatan" class="card-laporan">
        <div class="icon-laporan">💰</div>
        <div class="card-laporan-content">
            <h3>Laporan Pendapatan</h3>
            <p>Lihat ringkasan pendapatan total</p>
        </div>
    </a>
</div>

<div class="footer">
Clean, Fresh, and Professional
</div>

</body>
</html>
