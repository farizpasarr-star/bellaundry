<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
    <style>
        .detail-info {
            background: rgba(0, 188, 212, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #00bcd4;
        }
        .detail-info p {
            margin: 8px 0;
            color: #0b4d5e;
            font-weight: 500;
        }
        .detail-info span {
            color: #333;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container-crud">
    <div class="card-crud">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Detail Transaksi #<?= htmlspecialchars($id_transaksi) ?></h2>
            <?php
                $kembali_link = "/bellaundry/app/Controllers/TransaksiController.php?aksi=index";
            ?>
            <a href="<?= $kembali_link ?>" class="btn-gradient">← Ke Transaksi</a>
        </div>

        <div class="detail-info">
            <p>Pelanggan: <span><?= htmlspecialchars($pelanggan['nama'] ?? '-') ?></span></p>
            <p>No. HP: <span><?= htmlspecialchars($pelanggan['no_hp'] ?? '-') ?></span></p>
            <p>Alamat: <span><?= htmlspecialchars($pelanggan['alamat'] ?? '-') ?></span></p>
            <p>Tanggal Masuk: <span><?= htmlspecialchars($transaksi['tanggal_masuk'] ?? '-') ?></span></p>
            <p>Tanggal Selesai: <span><?= htmlspecialchars($transaksi['tanggal_selesai'] ?? '-') ?></span></p>
        </div>

        <h3 style="color: #0b4d5e; margin: 20px 0 15px 0;">Daftar Cucian</h3>

        <?php if (empty($detail)): ?>
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <strong>⚠️ Tidak ada layanan untuk periode yang dicari</strong>
            </div>
        <?php endif; ?>

        <table class="table-crud">
            <tr>
                <th>ID</th>
                <th>Nama Layanan</th>
                <th>Berat (kg)</th>
                <th>Estimasi</th>
                <th>Status</th>
                <th>Subtotal</th>
            </tr>
            <?php 
            $totalBerat = 0;
            $totalSubtotal = 0;
            $jumlahLayanan = 0;
            if (!empty($detail)) {
                foreach ($detail as $row): 
                    $totalBerat += $row['berat'];
                    $jumlahLayanan++;
                    
                    $subtotal = 0;
                    switch($row['nama_layanan']) {
                        case 'Cuci Setrika Reguler': $subtotal = $row['berat'] * 10000; break;
                        case 'Cuci Setrika Express': $subtotal = $row['berat'] * 15000; break;
                        case 'Cuci Lipat Reguler': $subtotal = $row['berat'] * 8000; break;
                        case 'Cuci Lipat Express': $subtotal = $row['berat'] * 12000; break;
                        case 'Cuci Saja Reguler': $subtotal = $row['berat'] * 6000; break;
                        case 'Cuci Saja Express': $subtotal = $row['berat'] * 10000; break;
                    }
                    $totalSubtotal += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_layanan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                    <td><?= number_format($row['berat'], 2) ?> kg</td>
                    <td><?= htmlspecialchars($row['estimasi']) ?></td>
                    <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                    <td>Rp <?= number_format($subtotal, 0) ?></td>
                </tr>
            <?php 
                endforeach;
            } else {
                echo '<tr><td colspan="6" style="text-align:center; padding:20px;">Tidak ada data layanan</td></tr>';
            }
            ?>
        </table>

        <?php if (!empty($detail)): ?>
        <div style="text-align:left; margin-top:15px; padding-top:15px; border-top:2px solid #ddd;">
            <p style="font-size:1rem; color:#0b4d5e; font-weight:500;">
                Total Berat: <span style="color:#00bcd4; font-size:1.1rem;">
                    <?= number_format($totalBerat, 2) ?> kg
                </span>
            </p>
            <p style="font-size:1rem; color:#0b4d5e; font-weight:500;">
                Jumlah Layanan: <span style="color:#00bcd4; font-size:1.1rem;">
                    <?= $jumlahLayanan ?> item
                </span>
            </p>
            <p style="font-size:1.1rem; color:#0b4d5e; font-weight:600; margin-top:10px;">
                Total Harga: <span style="color:#00bcd4; font-size:1.2rem;">
                    Rp <?= number_format($totalSubtotal ?? 0, 0) ?>
                </span>
            </p>
        </div>
        <?php endif; ?>

    </div>

    <div class="footer-crud">
    Clean, Fresh, and Professional
    </div>
</div>

</body>
</html>
