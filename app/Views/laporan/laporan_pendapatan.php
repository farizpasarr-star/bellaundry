<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan</title>
    <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
    <style>
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.8);
            border-radius: 10px;
        }
        .filter-form label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-weight: 600;
            color: #0b4d5e;
        }
        .filter-form input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="container-crud">
    <div class="card-crud">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Laporan Pendapatan</h2>
            <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=index" class="btn-gradient">← Kembali</a>
        </div>

        <div class="filter-form">
            <form method="GET" action="/bellaundry/app/Controllers/LaporanController.php" style="display:flex; gap:15px; align-items:flex-end;">
                <input type="hidden" name="aksi" value="pendapatan">
                <label>
                    Tahun:
                    <input type="number" name="tahun" value="<?= htmlspecialchars($tahun) ?>" min="2020" max="2099">
                </label>
                <button type="submit" class="btn-gradient">🔍 Filter</button>
            </form>
        </div>

        <div style="background: rgba(0,188,212,0.1); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <h3 style="color: #0b4d5e; margin: 0; font-size: 1.3rem;">Total Pendapatan: Rp <?= number_format($totalPendapatan, 0) ?></h3>
        </div>

        <table class="table-crud">
            <tr>
                <th>Bulan</th>
                <th>Pendapatan</th>
            </tr>
            <?php for ($m=1;$m<=12;$m++): 
                $val = isset($pendapatanPerBulan[$m]) ? $pendapatanPerBulan[$m] : 0;
            ?>
                <tr>
                    <td><?= date('F', mktime(0,0,0,$m,1)) ?></td>
                    <td>Rp <?= number_format($val, 0) ?></td>
                </tr>
            <?php endfor; ?>
        </table>

    </div>

    <div class="footer-crud">
    Clean, Fresh, and Professional
    </div>
</div>

</body>
</html>
