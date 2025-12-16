<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Recycle Bin Transaksi</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
</head>
<body>

<div class="container-crud">
    <div class="card-crud">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Recycle Bin Transaksi</h2>
            <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn-gradient">&larr; Kembali</a>
        </div>

        <table class="table-crud" style="margin-top:12px;">
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tanggal Masuk</th>
                <th>Total Harga</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
            <?php if (!empty($deleted)): ?>
                <?php foreach ($deleted as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                    <td>Rp <?= number_format($row['total_harga'] ?? 0, 0) ?></td>
                    <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                    <td>
                        <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=restore&id=<?= $row['id_transaksi'] ?>" class="btn-gradient" style="padding:6px 12px; font-size:0.85rem;">Restore</a>
                        <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=purge&id=<?= $row['id_transaksi'] ?>" onclick="return confirm('Yakin ingin menghapus permanen transaksi ini?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 12px; font-size:0.85rem; margin-left:8px;">Hapus Permanen</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada transaksi di recycle bin</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
