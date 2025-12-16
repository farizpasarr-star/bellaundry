<?php
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Cucian</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
<style>
    .search-wrapper { position: relative; display: inline-block; width: 380px; }
    .search-dropdown {
        position: absolute; top: 100%; left: 0; right: 0;
        border: 1px solid #ddd; background: #fff; max-height: 250px; overflow-y: auto;
        display: none; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 6px; margin-top: -15px; padding-top: 15px;
    }
    .search-option { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f0f0f0; }
    .search-option:hover { background: #f9f9f9; }
    .search-input { width: 100% !important; padding: 12px 15px !important; border-radius: 8px !important; border: 1px solid #b8e6df !important; font-size: 0.95rem !important; }
</style>
</head>

<body>

<div class="container-crud">
        <div class="card-crud">

                <div style="display:flex; justify-content:space-between; align-items:center;">
                        <h2 class="card-title">Daftar Cucian</h2>

                        <div style="display:flex; gap:10px; align-items:center;">
                                <a href="/bellaundry/app/Controllers/LayananController.php?aksi=recycle" class="btn-gradient" style="background:#6c757d;">Recycle Bin</a>
                                <a href="/bellaundry/app/Controllers/LayananController.php?aksi=create" class="btn-gradient">+ Tambah Cucian</a>
                                <a href="/bellaundry/public/index.php" class="btn-gradient">Kembali ke Dashboard</a>
                        </div>
                </div>

                <div style="margin-bottom: 6px; margin-top: -22px;">
                    <label style="display: block; color: #0b4d5e; font-weight: 600; margin-bottom: 4px;">Cari Cucian (ID atau Nama Pelanggan)</label>
                    <div class="search-wrapper">
                        <input type="text" id="searchInput" class="search-input" placeholder="Ketik ID atau nama pelanggan...">
                        <div class="search-dropdown" id="searchDropdown">
                            <?php 
                            if (!empty($layanan)): 
                                foreach ($layanan as $l): ?>
                                <div class="search-option" onclick="selectCucian(this, <?= $l['id_layanan'] ?>)">
                                    <?= $l['id_layanan'] ?> - <?= htmlspecialchars($l['nama_pelanggan'] ?? '-') ?>
                                </div>
                            <?php endforeach; 
                            endif; ?>
                        </div>
                    </div>
                </div>

                <table class="table-crud" id="tabelCucian" style="margin-top: 9px;">
                        <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Layanan</th>
                                <th>Berat (kg)</th>
                                <th>Estimasi</th>
                                <th>Aksi</th>
                        </tr>

                        <?php if (!empty($layanan)): 
                                foreach ($layanan as $row): ?>
                                <tr class="row-layanan" data-id="<?= $row['id_layanan'] ?>" data-nama="<?= strtolower(htmlspecialchars($row['nama_pelanggan'] ?? '')) ?>">
                                        <td><?= $row['id_layanan'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                        <td><?= $row['berat'] ?? 0 ?> kg</td>
                                        <td><?= htmlspecialchars($row['estimasi']) ?></td>
                                        <td>
                                                <a href="/bellaundry/app/Controllers/LayananController.php?aksi=edit&id=<?= $row['id_layanan'] ?>" class="btn-gradient" style="padding:6px 14px; font-size:0.85rem;">Edit</a>
                                                <a href="/bellaundry/app/Controllers/LayananController.php?aksi=delete&id=<?= $row['id_layanan'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="btn-gradient" style="background:#ff6b6b; padding:6px 14px; font-size:0.85rem;">Hapus</a>
                                        </td>
                                </tr>
                        <?php endforeach; 
                        else: ?>
                                <tr>
                                        <td colspan="6" style="text-align:center; padding:20px; color:#999;">Tidak ada data cucian</td>
                                </tr>
                        <?php endif; ?>

                </table>

        </div>

        <div class="footer-crud">
            Clean, Fresh, and Professional
        </div>
</div>

<script>
    function filterCucian() {
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

        const rows = document.querySelectorAll('.row-layanan');
        rows.forEach(row => {
            const id = row.dataset.id.toString();
            const nama = row.dataset.nama;
            if (id.includes(searchVal) || nama.includes(searchVal)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function selectCucian(element, id) {
        const input = document.getElementById('searchInput');
        input.value = id;
        document.getElementById('searchDropdown').style.display = 'none';
        filterCucian();
    }

    document.getElementById('searchInput').addEventListener('input', filterCucian);
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
