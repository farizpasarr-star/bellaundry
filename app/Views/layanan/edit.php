<?php 

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Cucian</title>
  <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
  <style>
    .card-crud--narrow { max-width: 720px; margin: 0 auto; }
    .error-message { color: #dc3545; font-size: 0.85rem; margin-top: 4px; }
    .form-group-error input,
    .form-group-error select,
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
      <h2 class="card-title">Edit Cucian</h2>
      <a href="/bellaundry/app/Controllers/LayananController.php?aksi=index" class="btn-gradient" style="padding:10px 16px; text-decoration:none;">&larr; Kembali</a>
    </div>

    <form method="post" action="/bellaundry/app/Controllers/LayananController.php?aksi=update">

      <input type="hidden" name="id_layanan" value="<?= htmlspecialchars($layanan['id_layanan'] ?? '') ?>">

      <div class="<?= !empty($errors['id_pelanggan']) ? 'form-group-error' : '' ?>">
        <label>Cari Pelanggan (ID atau Nama)</label>
        <input type="text" name="id_pelanggan" class="input-field" list="pelanggan-list" placeholder="Ketik ID atau Nama Pelanggan" value="<?= htmlspecialchars($layanan['id_pelanggan'] ?? '') ?>" required>
        <datalist id="pelanggan-list">
          <?php foreach ($pelanggan as $p): ?>
            <option value="<?= $p['id_pelanggan'] ?>" label="<?= htmlspecialchars($p['nama']) ?>">
              <?= htmlspecialchars($p['id_pelanggan'] . ' - ' . $p['nama']) ?>
            </option>
          <?php endforeach; ?>
        </datalist>
        <?php if (!empty($errors['id_pelanggan'])): ?>
          <div class="error-message"><?= htmlspecialchars($errors['id_pelanggan']) ?></div>
        <?php endif; ?>
      </div>

      <div class="<?= !empty($errors['nama_layanan']) ? 'form-group-error' : '' ?>">
        <label>Nama Layanan</label>
        <select name="nama_layanan" id="nama_layanan" class="input-field" required>
          <option value="">-- Pilih Layanan --</option>
          <option value="Cuci Setrika Reguler" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Setrika Reguler' ? 'selected' : '' ?>>Cuci Setrika Reguler</option>
          <option value="Cuci Setrika Express" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Setrika Express' ? 'selected' : '' ?>>Cuci Setrika Express</option>
          <option value="Cuci Lipat Reguler" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Lipat Reguler' ? 'selected' : '' ?>>Cuci Lipat Reguler</option>
          <option value="Cuci Lipat Express" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Lipat Express' ? 'selected' : '' ?>>Cuci Lipat Express</option>
          <option value="Cuci Saja Reguler" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Saja Reguler' ? 'selected' : '' ?>>Cuci Saja Reguler</option>
          <option value="Cuci Saja Express" <?= ($layanan['nama_layanan'] ?? '') === 'Cuci Saja Express' ? 'selected' : '' ?>>Cuci Saja Express</option>
        </select>
        <?php if (!empty($errors['nama_layanan'])): ?>
          <div class="error-message"><?= htmlspecialchars($errors['nama_layanan']) ?></div>
        <?php endif; ?>
      </div>

      <div class="<?= !empty($errors['berat']) ? 'form-group-error' : '' ?>">
        <label>Berat (kg)</label>
        <input type="number" step="0.1" name="berat" id="berat" class="input-field" value="<?= htmlspecialchars($layanan['berat'] ?? '') ?>" required>
        <?php if (!empty($errors['berat'])): ?>
          <div class="error-message"><?= htmlspecialchars($errors['berat']) ?></div>
        <?php endif; ?>
      </div>

      <div class="<?= !empty($errors['estimasi']) ? 'form-group-error' : '' ?>">
        <label>Estimasi Waktu</label>
        <input type="text" name="estimasi" id="estimasi" class="input-field" placeholder="Auto-terisi saat memilih layanan" value="<?= htmlspecialchars($layanan['estimasi'] ?? '') ?>" required>
        <?php if (!empty($errors['estimasi'])): ?>
          <div class="error-message"><?= htmlspecialchars($errors['estimasi']) ?></div>
        <?php endif; ?>
      </div>

      <div style="margin-top:10px;">
        <button type="submit" class="btn-gradient">Update</button>
        <a href="/bellaundry/app/Controllers/LayananController.php?aksi=index" class="btn btn-secondary" style="display:inline-block; padding:10px 14px; margin-left:8px; text-decoration:none; border-radius:8px;">Batal</a>
      </div>

    </form>

  </div>

  <div class="footer-crud">
    © <?= date("Y") ?> Bellaundry
  </div>
</div>

<script>
const layananData = {
  'Cuci Setrika Reguler': '2 hari',
  'Cuci Setrika Express': '6 jam',
  'Cuci Lipat Reguler': '2 hari',
  'Cuci Lipat Express': '5 jam',
  'Cuci Saja Reguler': '2 hari',
  'Cuci Saja Express': '5 jam'
};

document.getElementById('nama_layanan').addEventListener('change', function() {
  const selectedLayanan = this.value;
  const estimasiField = document.getElementById('estimasi');
  
  if (selectedLayanan && layananData[selectedLayanan]) {
    estimasiField.value = layananData[selectedLayanan];
  } else {
    estimasiField.value = '';
  }
});

window.addEventListener('load', function() {
  const selectedLayanan = document.getElementById('nama_layanan').value;
  if (selectedLayanan && layananData[selectedLayanan]) {
    document.getElementById('estimasi').value = layananData[selectedLayanan];
  }
});
</script>

</body>
</html>
