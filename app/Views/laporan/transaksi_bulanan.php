<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan</title>
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
        .filter-form input, .filter-form select {
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
            <h2 class="card-title">Laporan Transaksi Bulanan</h2>
            <a href="/bellaundry/app/Controllers/LaporanController.php?aksi=index" class="btn-gradient">← Kembali</a>
        </div>

        <div class="filter-form">
            <form method="GET" action="/bellaundry/app/Controllers/LaporanController.php" style="display:flex; gap:15px; align-items:flex-end;">
                <input type="hidden" name="aksi" value="bulanan">
                <label>
                    Bulan:
                    <select name="bulan">
                        <?php for ($i=1;$i<=12;$i++): ?>
                            <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>>
                                <?= date('F', mktime(0,0,0,$i,1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </label>
                <label>
                    Tahun:
                    <input type="number" name="tahun" value="<?= htmlspecialchars($tahun) ?>" required>
                </label>
                <button type="submit" class="btn-gradient">🔍 Filter</button>
            </form>
        </div>

        

        <table class="table-crud">
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tanggal Masuk</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php if (!empty($transaksi)): 
                foreach ($transaksi as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=detail&id_pelanggan=<?= $row['id_pelanggan'] ?>&laporan_type=bulanan&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>" class="btn-gradient" style="padding:6px 12px; font-size:0.85rem;">Detail</a>
                    </td>
                </tr>
            <?php endforeach; 
            else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada data transaksi untuk bulan ini</td>
                </tr>
            <?php endif; ?>
        </table>

    </div>

    <div class="footer-crud">
    Clean, Fresh, and Professional
    </div>
</div>

</body>
</html>
