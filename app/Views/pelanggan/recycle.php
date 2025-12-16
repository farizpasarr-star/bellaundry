<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Recycle Bin Pelanggan</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
</head>
<body>

<div class="container-crud">
    <div class="card-crud">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Recycle Bin Pelanggan</h2>
            <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=index" class="btn-gradient">&larr; Kembali</a>
        </div>

        <table class="table-crud" style="margin-top:12px;">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Dihapus Pada</th>
                <th>Aksi</th>
            </tr>
            <?php if (!empty($deleted)): ?>
                <?php foreach ($deleted as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['deleted_at']) ?></td>
                    <td>
                        <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=restore&id=<?= $row['id_pelanggan'] ?>" class="btn-gradient" style="padding:6px 12px;">Restore</a>
                        <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=purge&id=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin ingin menghapus permanen pelanggan ini?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 12px;">Hapus Permanen</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada pelanggan di recycle bin</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>