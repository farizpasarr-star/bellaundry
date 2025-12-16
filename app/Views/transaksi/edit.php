<?php 
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Transaksi</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
</head>
<body>

<div class="container-crud">
    <div class="card-crud card-crud--narrow">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Edit Transaksi #<?= htmlspecialchars($transaksi['id_transaksi']) ?></h2>
            <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn-gradient" style="padding:10px 16px; text-decoration:none;">&larr; Kembali</a>
        </div>

        <form method="post" action="/bellaundry/app/Controllers/TransaksiController.php?aksi=update">
            <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($transaksi['id_transaksi']) ?>">

            <div class="<?= !empty($errors['id_pelanggan']) ? 'form-group-error' : '' ?>">
                <label>Pelanggan</label>
                <select name="id_pelanggan" class="input-field" disabled>
                    <?php foreach ($pelanggan as $p): ?>
                        <option value="<?= $p['id_pelanggan'] ?>" <?= $p['id_pelanggan'] == $transaksi['id_pelanggan'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['id_pelanggan'])): ?><div class="error-message"><?= htmlspecialchars($errors['id_pelanggan']) ?></div><?php endif; ?>
            </div>

            <div class="<?= !empty($errors['tanggal_masuk']) ? 'form-group-error' : '' ?>">
                <label>Tanggal Masuk</label>
                <input type="datetime-local" name="tanggal_masuk" class="input-field" value="<?= date('Y-m-d\\TH:i', strtotime($transaksi['tanggal_masuk'] ?? 'now')) ?>">
                <?php if (!empty($errors['tanggal_masuk'])): ?><div class="error-message"><?= htmlspecialchars($errors['tanggal_masuk']) ?></div><?php endif; ?>
            </div>

            <div class="<?= !empty($errors['tanggal_selesai']) ? 'form-group-error' : '' ?>">
                <label>Tanggal Selesai</label>
                <input type="datetime-local" name="tanggal_selesai" class="input-field" value="<?= !empty($transaksi['tanggal_selesai']) ? date('Y-m-d\\TH:i', strtotime($transaksi['tanggal_selesai'])) : '' ?>">
                <?php if (!empty($errors['tanggal_selesai'])): ?><div class="error-message"><?= htmlspecialchars($errors['tanggal_selesai']) ?></div><?php endif; ?>
            </div>

            <div style="margin-top:10px;">
                <button type="submit" class="btn-gradient">Simpan Perubahan</button>
                <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn btn-secondary" style="display:inline-block; padding:10px 14px; margin-left:8px; text-decoration:none; border-radius:8px;">Batal</a>
            </div>

        </form>

    </div>

    <div class="footer-crud">
        © <?= date("Y") ?> Bellaundry
    </div>

</div>

</body>
</html>
