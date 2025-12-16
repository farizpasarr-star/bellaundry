<?php 
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hapus Transaksi</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
</head>
<body>

<div class="container-crud">
    <div class="card-crud">
        <h2 class="card-title">Hapus Transaksi #<?= htmlspecialchars($transaksi['id_transaksi']) ?></h2>
        <p>Anda akan menghapus transaksi untuk pelanggan: <strong><?= htmlspecialchars($transaksi['nama_pelanggan'] ?? $transaksi['id_pelanggan']) ?></strong></p>
        <p>Tanggal Masuk: <?= htmlspecialchars($transaksi['tanggal_masuk'] ?? '') ?></p>
        <p>Total Harga: Rp <?= number_format($transaksi['total_harga'] ?? 0, 0) ?></p>

        <form method="post" action="/bellaundry/app/Controllers/TransaksiController.php?aksi=delete">
            <input type="hidden" name="id_transaksi" value="<?= htmlspecialchars($transaksi['id_transaksi']) ?>">
            <div style="margin-top:18px; display:flex; gap:10px;">
                <button type="submit" class="btn-submit" style="background:#ff4d4f;">Hapus Permanen</button>
                <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
