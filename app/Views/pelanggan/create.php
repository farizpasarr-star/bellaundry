<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Pelanggan</title>
    <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
    <style>
    .card-crud--narrow { max-width: 720px; margin: 0 auto; }
    .error-message { color: #dc3545; font-size: 0.85rem; margin-top: 4px; }
    .form-group-error input,
    .form-group-error textarea {
        border-color: #dc3545 !important;
        background-color: #fff5f5;
    }
    </style>
</head>

<body>

<div class="container-crud">
    <div class="card-crud card-crud--narrow">

        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title">Tambah Pelanggan</h2>
            <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=index" class="btn-gradient" style="padding:10px 16px; text-decoration:none;">&larr; Kembali</a>
        </div>

        <form method="post" action="/bellaundry/app/Controllers/PelangganController.php?aksi=store">

            <div class="<?= !empty($errors['nama']) ? 'form-group-error' : '' ?>">
                <label>Nama</label>
                <input type="text" name="nama" class="input-field" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required>
                <?php if (!empty($errors['nama'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['nama']) ?></div>
                <?php endif; ?>
            </div>

            <div class="<?= !empty($errors['no_hp']) ? 'form-group-error' : '' ?>">
                <label>No HP</label>
                <input type="text" name="no_hp" class="input-field" value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>" required>
                <?php if (!empty($errors['no_hp'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['no_hp']) ?></div>
                <?php endif; ?>
            </div>

            <div class="<?= !empty($errors['alamat']) ? 'form-group-error' : '' ?>">
                <label>Alamat</label>
                <textarea name="alamat" class="input-field" required><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
                <?php if (!empty($errors['alamat'])): ?>
                    <div class="error-message"><?= htmlspecialchars($errors['alamat']) ?></div>
                <?php endif; ?>
            </div>

            <div style="margin-top:10px;">
                <button type="submit" class="btn-gradient">Simpan</button>
                <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=index" class="btn btn-secondary" style="display:inline-block; padding:10px 14px; margin-left:8px; text-decoration:none; border-radius:8px;">Batal</a>
            </div>

        </form>

    </div>

    <div class="footer-crud">
        © <?= date("Y") ?> Bellaundry
    </div>

</div>

</body>
</html>
