<?php 

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Transaksi</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
<style>
  .search-wrapper { position: relative; display: inline-block; width: 300px; }
  .search-dropdown {
    position: absolute; top: 100%; left: 0; right: 0;
    border: 1px solid #ddd; background: #fff; max-height: 250px; overflow-y: auto;
    display: none; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 6px; margin-top: -15px; padding-top: 15px;
  }
  .search-option {
    padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f0f0f0;
  }
  .search-option:hover { background: #f9f9f9; }
  .search-input {
    width: 100% !important;
    padding: 12px 15px !important;
    border-radius: 8px !important;
    border: 1px solid #b8e6df !important;
    font-size: 0.95rem !important;
  }
  .card-crud {
    padding: 20px 25px !important;
  }
</style>
</head>

<body>

<div class="container-crud">
    <div class="card-crud">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
            <h2 class="card-title">Daftar Transaksi</h2>

            <div style="display:flex; gap:5px; align-items:center;">
              <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=recycle" class="btn-gradient" style="background:#6c757d;">Recycle Bin</a>
              <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=create" class="btn-gradient">+ Buat Transaksi Baru</a>
              <a href="/bellaundry/public/index.php" class="btn-gradient">Kembali ke Dashboard</a>
            </div>
        </div>

        <div style="margin-bottom: 6px; margin-top: -22px;">
          <label style="display: block; color: #0b4d5e; font-weight: 600; margin-bottom: 4px;">Cari Transaksi (ID atau Pelanggan)</label>
          <div class="search-wrapper">
            <input type="text" id="searchInput" class="search-input" placeholder="Ketik ID transaksi atau nama pelanggan...">
            <div class="search-dropdown" id="searchDropdown">
              <?php 
              if (!empty($transaksi)): 
                foreach ($transaksi as $t): ?>
                <div class="search-option" onclick="selectTransaksi(this, <?= $t['id_transaksi'] ?>)">
                  <?= $t['id_transaksi'] ?> - <?= htmlspecialchars($t['nama_pelanggan'] ?? 'N/A') ?>
                </div>
              <?php endforeach; 
              endif; ?>
            </div>
          </div>
        </div>

        <table class="table-crud" id="tabelTransaksi" style="margin-top: 9px;">
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Tanggal Masuk</th>
                <th>Total Harga</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php 
            if (!empty($transaksi)):
              foreach ($transaksi as $row): ?>
              <tr class="row-transaksi" data-id="<?= $row['id_transaksi'] ?>" data-nama="<?= strtolower($row['nama_pelanggan'] ?? '') ?>">
                <td><?= htmlspecialchars($row['id_transaksi']) ?></td>
                <td><?= htmlspecialchars($row['nama_pelanggan'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['tanggal_masuk']) ?></td>
                <td>Rp <?= number_format($row['total_harga'] ?? 0, 0) ?></td>
                <td>
                  <form method="post" action="/bellaundry/app/Controllers/TransaksiController.php?aksi=update" style="display:inline;">
                    <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi'] ?>">
                    <select name="metode_pembayaran" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px;">
                      <option value="Cash" <?= (strtolower($row['metode_pembayaran'] ?? '') === 'cash') ? 'selected' : '' ?>>Cash</option>
                      <option value="Transfer" <?= (strtolower($row['metode_pembayaran'] ?? '') === 'transfer') ? 'selected' : '' ?>>Transfer</option>
                      <option value="QRIS" <?= (strtolower($row['metode_pembayaran'] ?? '') === 'qris') ? 'selected' : '' ?>>QRIS</option>
                    </select>
                  </form>
                </td>
                <td>
                  <form method="post" action="/bellaundry/app/Controllers/TransaksiController.php?aksi=update" style="display:inline;">
                    <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi'] ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:6px 8px; border-radius:6px;">
                      <option value="Proses" <?= ($row['status'] ?? '') === 'Proses' ? 'selected' : '' ?>>Proses</option>
                      <option value="Selesai" <?= ($row['status'] ?? '') === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                      <option value="Proses (lunas)" <?= ($row['status'] ?? '') === 'Proses (lunas)' ? 'selected' : '' ?>>Proses (lunas)</option>
                      <option value="Selesai (lunas)" <?= ($row['status'] ?? '') === 'Selesai (lunas)' ? 'selected' : '' ?>>Selesai (lunas)</option>
                      <option value="Diambil (lunas)" <?= ($row['status'] ?? '') === 'Diambil (lunas)' ? 'selected' : '' ?>>Diambil (lunas)</option>
                    </select>
                  </form>
                </td>
                <td>
                  <div style="display:flex; gap:6px;">
                    <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=edit&id_transaksi=<?= $row['id_transaksi'] ?>" class="btn-gradient" style="padding:6px 14px; font-size:0.85rem;">Edit</a>
                    <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=detail&id_transaksi=<?= $row['id_transaksi'] ?>" class="btn-gradient" style="padding:6px 14px; font-size:0.85rem;">Detail</a>
                    <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=nota&id_transaksi=<?= $row['id_transaksi'] ?>" class="btn-gradient" style="padding:6px 14px; font-size:0.85rem;">Nota</a>
                    <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=delete&id=<?= $row['id_transaksi'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 14px; font-size:0.85rem;">Hapus</a>
                  </div>
                </td>
              </tr>
            <?php endforeach;
            else: ?>
              <tr>
                <td colspan="7" style="text-align:center; padding:20px; color:#999;">Tidak ada data transaksi</td>
              </tr>
            <?php endif; ?>

        </table>

    </div>

    <div class="footer-crud">
      Clean, Fresh, and Professional
    </div>

</div>

<script>
function selectTransaksi(element, id) {
    const selectedOption = element.textContent;
    document.getElementById('searchInput').value = selectedOption;
    document.getElementById('searchDropdown').style.display = 'none';
    
    const tableRows = document.querySelectorAll('.row-transaksi');
    tableRows.forEach(row => {
        if (parseInt(row.dataset.id) === id) {
            row.style.display = '';
            row.style.backgroundColor = '#fff8e1';
        } else {
            row.style.display = 'none';
        }
    });
}

document.getElementById('searchInput').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase();
    const dropdown = document.getElementById('searchDropdown');
    const options = dropdown.querySelectorAll('.search-option');
    const tableRows = document.querySelectorAll('.row-transaksi');
    
    if (searchValue === '') {
        dropdown.style.display = 'none';
        tableRows.forEach(row => row.style.display = '');
    } else {
        dropdown.style.display = 'block';
        let hasMatch = false;
        
        options.forEach(option => {
            if (option.textContent.toLowerCase().includes(searchValue)) {
                option.style.display = '';
                hasMatch = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        tableRows.forEach(row => {
            if (row.dataset.id.includes(searchValue) || row.dataset.nama.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
});
</script>

</body>
</html>
