<?php 

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Pelanggan</title>
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
            <h2 class="card-title">Daftar Pelanggan</h2>

            <div style="display:flex; gap:8px; align-items:center;">
              <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=recycle" class="btn-gradient" style="background:#6c757d;">Recycle Bin</a>
              <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=create" class="btn-gradient">+ Tambah Pelanggan</a>
              <a href="/bellaundry/public/index.php" class="btn-gradient">Kembali ke Dashboard</a>
            </div>
        </div>

        <div style="margin-bottom: 6px; margin-top: -22px;">
          <label style="display: block; color: #0b4d5e; font-weight: 600; margin-bottom: 4px;">Cari Pelanggan (ID atau Nama)</label>
          <div class="search-wrapper">
            <input type="text" id="searchInput" class="search-input" placeholder="Ketik ID atau nama pelanggan...">
            <div class="search-dropdown" id="searchDropdown">
              <?php 
              if (!empty($pelanggan)): 
                foreach ($pelanggan as $p): ?>
                <div class="search-option" onclick="selectPelanggan(this, <?= $p['id_pelanggan'] ?>)">
                  <?= $p['id_pelanggan'] ?> - <?= htmlspecialchars($p['nama']) ?>
                </div>
              <?php endforeach; 
              endif; ?>
            </div>
          </div>
        </div>

        <table class="table-crud" id="tabelPelanggan" style="margin-top: 9px;">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>

            <?php 
            if (!empty($pelanggan)):
              foreach ($pelanggan as $row): ?>
              <tr class="row-pelanggan" data-id="<?= $row['id_pelanggan'] ?>" data-nama="<?= strtolower($row['nama']) ?>">
                <td><?= $row['id_pelanggan'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td>
                    <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=edit&id=<?= $row['id_pelanggan'] ?>" class="btn-gradient" style="padding:6px 14px; font-size:0.85rem;">Edit</a>
                    <a href="/bellaundry/app/Controllers/PelangganController.php?aksi=delete&id=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 14px; font-size:0.85rem;">Hapus</a>
                </td>
              </tr>
            <?php endforeach;
            else: ?>
              <tr>
                <td colspan="5" style="text-align:center; padding:20px; color:#999;">Tidak ada data pelanggan</td>
              </tr>
            <?php endif; ?>

        </table>

    </div>

    <div class="footer-crud">
        Clean, Fresh, and Professional
    </div>
</div>

<script>
  function filterPelanggan() {
    const input = document.getElementById('searchInput');
    const dropdown = document.getElementById('searchDropdown');
    const searchVal = input.value.toLowerCase();
    const options = dropdown.querySelectorAll('.search-option');
    let hasVisible = false;

    options.forEach(opt => {
      const text = opt.textContent.toLowerCase();
      if (text.includes(searchVal)) {
        opt.style.display = '';
        hasVisible = true;
      } else {
        opt.style.display = 'none';
      }
    });

    dropdown.style.display = (searchVal.length > 0 && hasVisible) ? 'block' : 'none';

    const rows = document.querySelectorAll('.row-pelanggan');
    rows.forEach(row => {
      const id = row.dataset.id;
      const nama = row.dataset.nama;
      if (id.includes(searchVal) || nama.includes(searchVal)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function selectPelanggan(element, id) {
    const input = document.getElementById('searchInput');
    input.value = id;
    document.getElementById('searchDropdown').style.display = 'none';
    filterPelanggan();
  }

  document.getElementById('searchInput').addEventListener('input', filterPelanggan);
  document.getElementById('searchInput').addEventListener('focus', function() {
    const dropdown = document.getElementById('searchDropdown');
    if (this.value.length > 0) dropdown.style.display = 'block';
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-wrapper')) {
      document.getElementById('searchDropdown').style.display = 'none';
    }
  });
</script>

</body>
</html>
