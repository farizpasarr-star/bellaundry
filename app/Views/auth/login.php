<?php ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Pegawai - Bellaundry</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #b6f0ff, #9be9d8, #c3fff1);
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background-size: 300% 300%;
    animation: flowBg 12s ease infinite;

    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    flex-direction: column !important;
}

@keyframes flowBg {
    0% {background-position: 0% 50%;}
    50% {background-position: 100% 50%;}
    100% {background-position: 0% 50%;}
}

.login-card {
    width: 380px;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    border-radius: 22px;
    padding: 35px 28px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity:0; transform: translateY(10px); }
    to { opacity:1; transform: translateY(0); }
}

h2 {
    text-align: center;
    font-weight: 700;
    color: #0b4d5e;
    margin-bottom: 20px;
}

.form-control {
    border-radius: 14px;
    padding: 12px;
    border: 1px solid #b8e6df;
    font-family: 'Poppins', sans-serif;
}

.form-label {
    font-weight: 500;
    color: #0b4d5e;
    margin-bottom: 2px;
    display: block;
}

.btn-login {
    width: 100%;
    padding: 12px;
    border-radius: 14px;
    background: linear-gradient(135deg, #00bcd4, #3dd9b6);
    border: none;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    transition: 0.25s;
    cursor: pointer;
}

.btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.error-text {
    color: #d9534f;
    text-align: center;
    margin-bottom: 10px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

form button {
    margin-top: 12px;
}
</style>
</head>
<body>

<div class="login-card">

    <h2>Login Pegawai</h2>

    <?php if (!empty($error)): ?>
        <p class="error-text"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/bellaundry/app/Controllers/AuthController.php?aksi=loginProcess">

        <label class="form-label">ID Pegawai</label>
        <input type="text" name="id_pegawai" class="form-control mb-3" placeholder="Masukkan ID Pegawai" required>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" placeholder="Masukkan password" required>

        <button type="submit" class="btn btn-login">Masuk</button>

    </form>
</div>

</body>
</html>
