<?php 

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Buat Transaksi Baru</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
<style>
  .card-crud {
    padding: 20px 25px !important;
  }
  .form-group {
    margin-bottom: 18px;
  }
  .form-group label {
    display: block;
    color: #0b4d5e;
    font-weight: 600;
    margin-bottom: 8px;
  }
  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #b8e6df;
    border-radius: 8px;
    font-size: 0.95rem;
    font-family: inherit;
    box-sizing: border-box;
  }
  .form-group textarea {
    resize: vertical;
    min-height: 100px;
  }
  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    outline: none;
    border-color: #17a2b8;
    box-shadow: 0 0 0 3px rgba(23, 162, 184, 0.1);
  }
  .error-msg {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 4px;
  }
  .btn-container {
    display: flex;
    gap: 10px;
    margin-top: 25px;
  }
  .btn-submit {
    background: #0b4d5e;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
  }
  .btn-submit:hover {
    background: #083a47;
  }
  .btn-cancel {
    background: #999;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    display: inline-block;
  }
  .btn-cancel:hover {
    background: #777;
  }
  .service-item {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
  }
  .service-item input {
    width: 80px;
  }
  .service-item button {
    padding: 6px 12px;
    background: #ef4444;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  .btn-add-service {
    background: #17a2b8;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
  }
</style>
</head>

<body>

<div class="container-crud">
    <div class="card-crud">

        <div style="margin-bottom: 20px;">
            <h2 class="card-title">Buat Transaksi Baru</h2>
        </div>

        <?php if (!empty($errors)): ?>
            <div style="background:#ffe6e6; border:1px solid #ff6b6b; color:#c92a2a; padding:15px; border-radius:6px; margin-bottom:15px;">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/bellaundry/app/Controllers/TransaksiController.php?aksi=store">

            <div class="form-group">
                <label for="id_pelanggan">Pelanggan *</label>
                <select name="id_pelanggan" id="id_pelanggan" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php if (!empty($pelanggan)): 
                        foreach ($pelanggan as $p): ?>
                        <option value="<?= $p['id_pelanggan'] ?>" 
                            <?= (isset($_POST['id_pelanggan']) && $_POST['id_pelanggan'] === $p['id_pelanggan']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nama']) ?> (<?= $p['id_pelanggan'] ?>)
                        </option>
                    <?php endforeach; 
                    endif; ?>
                </select>
                <?php if (!empty($errors['id_pelanggan'])): ?>
                    <div class="error-msg"><?= htmlspecialchars($errors['id_pelanggan']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tanggal_masuk">Tanggal Masuk *</label>
                <input type="datetime-local" name="tanggal_masuk" id="tanggal_masuk" 
                    value="<?= isset($_POST['tanggal_masuk']) ? htmlspecialchars($_POST['tanggal_masuk']) : '' ?>" required>
                <?php if (!empty($errors['tanggal_masuk'])): ?>
                    <div class="error-msg"><?= htmlspecialchars($errors['tanggal_masuk']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai (Estimasi)</label>
                <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" 
                    value="<?= isset($_POST['tanggal_selesai']) ? htmlspecialchars($_POST['tanggal_selesai']) : '' ?>">
            </div>

            <div class="form-group">
              <label for="metode_pembayaran">Metode Pembayaran</label>
              <select name="metode_pembayaran" id="metode_pembayaran">
                <option value="Cash" <?= (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'Cash') ? 'selected' : '' ?>>Cash</option>
                <option value="Transfer" <?= (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'Transfer') ? 'selected' : '' ?>>Transfer</option>
                <option value="QRIS" <?= (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'QRIS') ? 'selected' : '' ?>>QRIS</option>
              </select>
            </div>

            <div class="form-group">
                <label>Layanan/Item *</label>
                <div id="servicesContainer">
                    <div class="service-item">
                        <select name="layanan[]" class="layanan-select" required>
                            <option value="">-- Pilih Layanan --</option>
                            <?php if (!empty($layanan)): 
                                foreach ($layanan as $l): ?>
                                <option value="<?= $l['id_layanan'] ?>" data-harga="<?= $l['harga'] ?>">
                                    <?= htmlspecialchars($l['nama_layanan']) ?> (Rp <?= number_format($l['harga'], 0) ?>/kg)
                                </option>
                            <?php endforeach; 
                            endif; ?>
                        </select>
                        <input type="number" name="berat[]" placeholder="Berat (kg)" step="0.1" min="0" required>
                        <button type="button" onclick="this.parentElement.remove()">Hapus</button>
                    </div>
                </div>
                <button type="button" class="btn-add-service" onclick="addService()">+ Tambah Item</button>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan"><?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?></textarea>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn-submit">Simpan Transaksi</button>
                <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn-cancel">Batal</a>
            </div>

        </form>

    </div>

    <div class="footer-crud">
      Clean, Fresh, and Professional
    </div>

</div>

<script>
function addService() {
    const container = document.getElementById('servicesContainer');
    const newService = document.createElement('div');
    newService.className = 'service-item';
    
    const layananOptions = `
        <select name="layanan[]" class="layanan-select" required>
            <option value="">-- Pilih Layanan --</option>
            <?php if (!empty($layanan)): 
                foreach ($layanan as $l): ?>
                <option value="<?= $l['id_layanan'] ?>" data-harga="<?= $l['harga'] ?>">
                    <?= htmlspecialchars($l['nama_layanan']) ?> (Rp <?= number_format($l['harga'], 0) ?>/kg)
                </option>
            <?php endforeach; 
            endif; ?>
        </select>
    `;
    
    newService.innerHTML = `
        ${layananOptions}
        <input type="number" name="berat[]" placeholder="Berat (kg)" step="0.1" min="0" required>
        <button type="button" onclick="this.parentElement.remove()">Hapus</button>
    `;
    
    container.appendChild(newService);
}
</script>

</body>
</html>
