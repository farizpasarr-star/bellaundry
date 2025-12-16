<?php
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Recycle Bin Cucian</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
</head>
<body>

<div class="container-crud">
    <div class="card-crud">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Recycle Bin Cucian</h2>
            <a href="/bellaundry/app/Controllers/LayananController.php?aksi=index" class="btn-gradient">&larr; Kembali</a>
        </div>

        <table class="table-crud" style="margin-top:12px;">
            <tr>
                <th>ID</th>
                <th>Nama Pelanggan</th>
                <th>Nama Layanan</th>
                <th>Berat (kg)</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
            <?php if (!empty($deleted)): ?>
                <?php foreach ($deleted as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_layanan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                    <td>
                        <?= htmlspecialchars(($row['berat'] !== null && $row['berat'] !== '') ? $row['berat'] . ' kg' : '-') ?>
                    </td>
                    <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                    <td>
                        <a href="/bellaundry/app/Controllers/LayananController.php?aksi=restore&id=<?= $row['id_layanan'] ?>" class="btn-gradient" style="padding:6px 12px;">Restore</a>
                        <a href="/bellaundry/app/Controllers/LayananController.php?aksi=purge&id=<?= $row['id_layanan'] ?>" onclick="return confirm('Yakin ingin menghapus permanen layanan ini?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 12px;">Hapus Permanen</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada layanan di recycle bin</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>