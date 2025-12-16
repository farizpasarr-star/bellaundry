<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Ganti Password - Pegawai</title>
  <link rel="stylesheet" href="/bellaundry/public/assets/css/style.css">
  <style>
    .card-center { max-width:480px; margin:40px auto; padding:20px; box-sizing:border-box; background:#ffffff; border-radius:12px; }

    .error-msg { color:#b00020; margin-bottom:10px; }
    .success-msg { color: #0b7a3d; margin-bottom:10px; }

    .form-label { display:block; font-weight:600; color:#094d5f; margin-bottom:8px; }
    .form-group { margin-bottom:14px; }

    .input-field {
      width:100%;
      padding:12px 14px;
      border:1px solid #e0e0e0;
      border-radius:18px;
      background:#fff;
      box-sizing:border-box; 
      font-size:14px;
    }

    .btn-gradient {
      background: linear-gradient(90deg,#09c6a4,#67e8f9);
      color:#fff;
      border:none;
      padding:10px 18px;
      border-radius:10px;
      cursor:pointer;
      text-decoration:none;
      display:inline-block;
    }

    body { margin:0; font-family: Arial, Helvetica, sans-serif; -webkit-font-smoothing:antialiased; }
    .card-crud { box-sizing:border-box; overflow:hidden; }

    @media (max-width:520px) {
      .card-center { margin:20px; padding:16px; }
      .input-field { padding:10px 12px; }
    }
  </style>
</head>
<body>

<div class="card-center card-crud">
  <h2 class="card-title">Ganti Password</h2>

  <?php if (!empty($errors) && is_array($errors)): ?>
    <div class="error-msg">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="success-msg"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post" action="/bellaundry/app/Controllers/AuthController.php?aksi=changePasswordProcess">
    <div class="form-group">
      <label class="form-label">Password Saat Ini</label>
      <input type="password" name="current_password" class="input-field" required>
    </div>

    <div class="form-group">
      <label class="form-label">Password Baru</label>
      <input type="password" name="new_password" class="input-field" required>
    </div>

    <div class="form-group">
      <label class="form-label">Konfirmasi Password Baru</label>
      <input type="password" name="confirm_password" class="input-field" required>
    </div>

    <div style="display:flex; gap:8px; margin-top:12px;">
      <button type="submit" class="btn-gradient">Simpan</button>
      <a href="/bellaundry/public/index.php" class="btn-gradient" style="background:#6c757d; text-decoration:none; padding:10px 14px; display:inline-block;">Kembali</a>
    </div>
  </form>

</div>

</body>
</html>
