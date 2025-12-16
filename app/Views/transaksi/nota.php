<?php 

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Nota Transaksi</title>
<link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
<style>
  .card-crud {
    padding: 20px 25px !important;
  }
  .nota-container {
    background: white;
    padding: 30px;
    border: 2px solid #0b4d5e;
    border-radius: 8px;
    max-width: 600px;
    margin: 0 auto;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
  }
  .nota-header {
    text-align: center;
    border-bottom: 2px solid #0b4d5e;
    padding-bottom: 15px;
    margin-bottom: 15px;
  }
  .nota-header h1 {
    margin: 0;
    color: #0b4d5e;
    font-size: 1.8rem;
  }
  .nota-header p {
    margin: 5px 0;
    color: #666;
  }
  .nota-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
  }
  .nota-info-col {
    flex: 1;
  }
  .nota-info-col label {
    font-weight: bold;
    color: #0b4d5e;
    display: block;
  }
  .nota-info-col span {
    display: block;
    color: #333;
  }
  .nota-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
  }
  .nota-table thead tr {
    border-bottom: 2px solid #0b4d5e;
  }
  .nota-table thead th {
    text-align: left;
    padding: 8px 0;
    color: #0b4d5e;
    font-weight: bold;
  }
  .nota-table tbody tr {
    border-bottom: 1px solid #ddd;
  }
  .nota-table tbody td {
    padding: 8px 0;
  }
  .nota-table td.right {
    text-align: right;
  }
  .nota-total {
    border-top: 2px solid #0b4d5e;
    border-bottom: 2px solid #0b4d5e;
    padding: 12px 0;
    margin-bottom: 15px;
  }
  .nota-total-row {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    color: #0b4d5e;
    font-size: 1.1rem;
  }
  .nota-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    font-size: 0.85rem;
    color: #666;
  }
  .btn-container {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
  }
  .btn-print {
    background: #0b4d5e;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.95rem;
  }
  .btn-print:hover {
    background: #083a47;
  }
  .btn-back {
    background: #999;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
    font-size: 0.95rem;
    display: inline-block;
  }
  .btn-back:hover {
    background: #777;
  }
  @media print {
    body {
      background: white;
    }
    .btn-container,
    .container-crud > div:first-child,
    .footer-crud {
      display: none;
    }
    .nota-container {
      border: none;
      padding: 0;
      max-width: 100%;
    }
  }
</style>
</head>

<body>

<div class="container-crud">
    <div class="card-crud" style="text-align: center; margin-bottom: 15px;">
        <h2 class="card-title">Nota Transaksi</h2>
    </div>

    <div class="nota-container">
        <div class="nota-header">
            <h1>BELLAUNDRY</h1>
            <p>Layanan Laundry Terpercaya</p>
            <p>Jl. Sei Wain No.88  | No. HP: 08 berapa ya kak? Asik</p>
        </div>

        <div class="nota-info">
            <div class="nota-info-col">
                <label>No. Transaksi:</label>
                <span><?= htmlspecialchars($transaksi['id_transaksi'] ?? '-') ?></span>
            </div>
            <div class="nota-info-col">
                <label>Tanggal:</label>
                <span><?= isset($transaksi['tanggal_masuk']) ? date('d/m/Y H:i', strtotime($transaksi['tanggal_masuk'])) : '-' ?></span>
            </div>
        </div>

        <div class="nota-info">
            <div class="nota-info-col">
                <label>Pelanggan:</label>
                <span><?= htmlspecialchars($transaksi['nama_pelanggan'] ?? 'N/A') ?></span>
            </div>
            <div class="nota-info-col">
                <label>No. HP:</label>
                <span><?= htmlspecialchars($transaksi['no_hp'] ?? '-') ?></span>
            </div>
        </div>

        <table class="nota-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th class="right">Berat (kg)</th>
                    <th class="right">Harga/kg</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($detail)): 
                    foreach ($detail as $item): 
                        $subtotal = ($item['berat'] ?? 0) * ($item['harga'] ?? 0);
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama_layanan'] ?? 'Item') ?></td>
                        <td class="right"><?= number_format($item['berat'] ?? 0, 2) ?></td>
                        <td class="right">Rp <?= number_format($item['harga'] ?? 0, 0) ?></td>
                        <td class="right">Rp <?= number_format($subtotal, 0) ?></td>
                    </tr>
                <?php endforeach; 
                else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 15px;">Tidak ada item detail</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="nota-total">
            <div class="nota-total-row">
                <span>TOTAL:</span>
                <span>Rp <?= number_format($transaksi['total_harga'] ?? 0, 0) ?></span>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <p><strong>Status:</strong> <?= htmlspecialchars($transaksi['status'] ?? 'Pending') ?></p>
            <?php if (!empty($transaksi['tanggal_selesai'])): ?>
                <p><strong>Tanggal Selesai:</strong> <?= date('d/m/Y', strtotime($transaksi['tanggal_selesai'])) ?></p>
            <?php endif; ?>
        </div>

        <div class="nota-footer">
            <p>Terima kasih telah sudah laundry di kami.</p>
            <p>Silakan ambil cucian Anda sesuai jadwal, Cihuyyyyyy</p>
            <p style="margin-top: 20px; color: #999;">Dicetak: <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>

    <div class="btn-container">
      <button type="button" class="btn-print" onclick="window.print()">Cetak</button>
      <button id="wa-notify-btn" type="button" class="btn-print" data-phone="<?= htmlspecialchars($transaksi['no_hp'] ?? '') ?>" data-name="<?= htmlspecialchars($transaksi['nama_pelanggan'] ?? '') ?>">Kirim ke pelanggan</button>
      <a href="/bellaundry/app/Controllers/TransaksiController.php?aksi=index" class="btn-back">← Kembali</a>
    </div>

    <div class="footer-crud">
      Clean, Fresh, and Professional
    </div>

</div>

<script>
  (function(){
    function normalizePhone(raw){
      if(!raw) return '';
      var s = raw.replace(/[^0-9+]/g, '');
      if(s.startsWith('+')) s = s.slice(1);
      if(s.startsWith('0')){
        s = '62' + s.slice(1);
      } else if(s.startsWith('8')){
        s = '62' + s;
      }
      return s;
    }

    function buildMessage(name, notaUrl){
      var greeting = 'Halo ' + (name || 'Pelanggan') + ', Laundry Kamu Sudah Bisa Diambil Cihuyyyy';
      return greeting + '\n' + notaUrl;
    }

    var btn = document.getElementById('wa-notify-btn');
    if(btn){
      btn.addEventListener('click', function(){
        var rawPhone = btn.getAttribute('data-phone') || '';
        var name = btn.getAttribute('data-name') || '';
        var phone = normalizePhone(rawPhone);
        if(!phone){
          alert('Nomor HP pelanggan tidak tersedia.');
          return;
        }
        var notaUrl = window.location.href;
        var text = buildMessage(name, notaUrl);
        var encoded = encodeURIComponent(text);
        var waLink = 'https://wa.me/' + phone + '?text=' + encoded;
        window.open(waLink, '_blank');
      });
    }
  })();
</script>

</body>
</html>
