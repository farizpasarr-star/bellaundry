<?php
require_once __DIR__ . '/../../core/Controller.php';

class PelangganController extends Controller {

    public function index() {
        $pelangganModel = $this->model('Pelanggan');
        $pelanggan = $pelangganModel->getAll();
        $this->view('pelanggan/index', ['pelanggan' => $pelanggan]);
    }

    public function create() {
        $this->view('pelanggan/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        }

        $data = [
            'nama' => $_POST['nama'] ?? '',
            'no_hp' => $_POST['no_hp'] ?? '',
            'alamat' => $_POST['alamat'] ?? ''
        ];

        $pelangganModel = $this->model('Pelanggan');
        $errors = $pelangganModel->validate($data);

        if (!empty($errors)) {
            $this->view('pelanggan/create', ['errors' => $errors, 'data' => $data]);
            return;
        }

        if ($pelangganModel->create($data)) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        } else {
            $this->view('pelanggan/create', ['error' => 'Gagal menambah pelanggan']);
        }
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $pelangganModel = $this->model('Pelanggan');
        $pelanggan = $pelangganModel->getById($id);

        if (!$pelanggan) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        }

        $this->view('pelanggan/edit', ['pelanggan' => $pelanggan]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        }

        $id = $_POST['id_pelanggan'] ?? 0;
        $data = [
            'nama' => $_POST['nama'] ?? '',
            'no_hp' => $_POST['no_hp'] ?? '',
            'alamat' => $_POST['alamat'] ?? ''
        ];

        $pelangganModel = $this->model('Pelanggan');
        $errors = $pelangganModel->validate($data);

        if (!empty($errors)) {
            $pelanggan = $pelangganModel->getById($id);
            $this->view('pelanggan/edit', ['errors' => $errors, 'pelanggan' => $pelanggan]);
            return;
        }

        if ($pelangganModel->update($id, $data)) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        } else {
            $pelanggan = $pelangganModel->getById($id);
            $this->view('pelanggan/edit', ['error' => 'Gagal update pelanggan', 'pelanggan' => $pelanggan]);
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        $pelangganModel = $this->model('Pelanggan');

        if ($pelangganModel->delete($id)) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index');
        } else {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=index&error=Gagal+hapus');
        }
    }

    public function recycle() {
        $pelangganModel = $this->model('Pelanggan');
        $deleted = $pelangganModel->getDeleted();
        $this->view('pelanggan/recycle', ['deleted' => $deleted]);
    }

    public function restore() {
        $id = $_GET['id'] ?? 0;
        $pelangganModel = $this->model('Pelanggan');
        if ($pelangganModel->restore($id)) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=recycle&error=Gagal+restore');
        }
    }

    public function purge() {
        $id = $_GET['id'] ?? 0;
        $pelangganModel = $this->model('Pelanggan');
        if ($pelangganModel->purge($id)) {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/PelangganController.php?aksi=recycle&error=Gagal+purge');
        }
    }
}

try {
    $aksi = $_GET['aksi'] ?? 'index';
    $controller = new PelangganController();

    switch ($aksi) {
        case 'index':
            $controller->index();
            break;
        case 'create':
            $controller->create();
            break;
        case 'store':
            $controller->store();
            break;
        case 'edit':
            $controller->edit();
            break;
        case 'update':
            $controller->update();
            break;
        case 'delete':
            $controller->delete();
            break;
        case 'recycle':
            $controller->recycle();
            break;
        case 'restore':
            $controller->restore();
            break;
        case 'purge':
            $controller->purge();
            break;
        default:
            $controller->index();
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>
