<?php
session_start();

require_once __DIR__ . '/../../core/Controller.php';

class AuthController extends Controller {

    public function login() {
        $error = $_GET['error'] ?? '';
        $this->view('auth/login', ['error' => $error]);
    }

    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login');
        }

        $id_pegawai = trim($_POST['id_pegawai'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($id_pegawai) || empty($password)) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login&error=Isi ID dan password');
        }

        $pegawaiModel = $this->model('Pegawai');
        $user = $pegawaiModel->validateLogin($id_pegawai, $password);

        if (!$user) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login&error=ID atau password salah');
        }

        $_SESSION['id_pegawai'] = $user['id_pegawai'];
        $_SESSION['nama_pegawai'] = $user['nama'] ?? $user['id_pegawai'];

        $this->redirect('/bellaundry/public/index.php');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login');
    }

    public function changePassword() {
        if (empty($_SESSION['id_pegawai'])) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login');
        }

        $errors = [];
        $success = '';
        if (!empty($_GET['success'])) {
            $success = 'Password berhasil diubah.';
        }
        if (!empty($_GET['error'])) {
            $errors[] = $_GET['error'];
        }

        $this->view('pegawai/ganti_password', ['errors' => $errors, 'success' => $success]);
    }

    public function changePasswordProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=changePassword');
        }

        if (empty($_SESSION['id_pegawai'])) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login');
        }

        $id = $_SESSION['id_pegawai'];
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $errors = [];
        if (empty($current) || empty($new) || empty($confirm)) {
            $errors[] = 'Semua field wajib diisi.';
        }
        if ($new !== $confirm) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }
        if (strlen($new) < 4) {
            $errors[] = 'Password baru minimal 4 karakter.';
        }

        if (!empty($errors)) {
            $this->view('pegawai/ganti_password', ['errors' => $errors]);
            return;
        }

        $pegawaiModel = $this->model('Pegawai');
        $pegawai = $pegawaiModel->getById($id);
        if (!$pegawai) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=login');
        }

        if ($current !== $pegawai['password']) {
            $errors[] = 'Password saat ini salah.';
            $this->view('pegawai/ganti_password', ['errors' => $errors]);
            return;
        }

        $ok = $pegawaiModel->updatePassword($id, $new);
        if ($ok) {
            $this->redirect('/bellaundry/app/Controllers/AuthController.php?aksi=changePassword&success=1');
        } else {
            $errors[] = 'Gagal menyimpan password baru.';
            $this->view('pegawai/ganti_password', ['errors' => $errors]);
        }
    }
}

try {
    $aksi = $_GET['aksi'] ?? 'login';
    $controller = new AuthController();

    switch ($aksi) {
        case 'login':
            $controller->login();
            break;
        case 'loginProcess':
            $controller->loginProcess();
            break;
        case 'changePassword':
            $controller->changePassword();
            break;
        case 'changePasswordProcess':
            $controller->changePasswordProcess();
            break;
        case 'logout':
            $controller->logout();
            break;
        default:
            $controller->login();
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>
