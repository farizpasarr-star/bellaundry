<?php
require_once __DIR__ . '/../../core/Controller.php';

class TransaksiController extends Controller {

    public function index() {
        $transaksiModel = $this->model('Transaksi');
        $transaksi = $transaksiModel->getAll();
        $this->view('transaksi/index', ['transaksi' => $transaksi]);
    }

    public function edit() {
        $id = $_GET['id_transaksi'] ?? 0;
        $transaksiModel = $this->model('Transaksi');
        $pelangganModel = $this->model('Pelanggan');

        $transaksi = $transaksiModel->getById($id);
        if (!$transaksi) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        }

        $pelanggan = $pelangganModel->getAll();
        $this->view('transaksi/edit', ['transaksi' => $transaksi, 'pelanggan' => $pelanggan]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        }

        $id = $_POST['id_transaksi'] ?? 0;
        $status = $_POST['status'] ?? null;
        $metode = $_POST['metode_pembayaran'] ?? null;
        $tanggal_selesai = $_POST['tanggal_selesai'] ?? null;

        $transaksiModel = $this->model('Transaksi');
        $layananModel = $this->model('Layanan');
        $detailModel = $this->model('DetailTransaksi');

        $updateData = [];
        if ($status !== null) {
            $updateData['status'] = $status;
            if (stripos($status, 'lunas') !== false && empty($tanggal_selesai)) {
                $updateData['tanggal_selesai'] = date('Y-m-d H:i:s');
            } else {
                $updateData['tanggal_selesai'] = $tanggal_selesai ?: null;
            }
        }
        if ($metode !== null) {
            $updateData['metode_pembayaran'] = $metode;
        }

        if (!empty($updateData)) {
            $transaksiModel->update($id, $updateData);

            if (isset($updateData['status'])) {
                $details = $detailModel->getByTransaksi($id);
                foreach ($details as $d) {
                    $id_layanan = $d['id_layanan'] ?? null;
                    if ($id_layanan) {
                        $s = $updateData['status'];
                        if (stripos($s, 'diambil') !== false) {
                            $layananModel->updateStatus($id_layanan, 'Diambil (lunas)');
                        } elseif (stripos($s, 'lunas') !== false) {
                            $layananModel->updateStatus($id_layanan, 'Selesai (lunas)');
                        } else {
                            $layananModel->updateStatus($id_layanan, 'Proses');
                        }
                    }
                }
            }
        }

        $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
    }

    public function delete() {
        $id = $_GET['id'] ?? $_GET['id_transaksi'] ?? 0;
        $transaksiModel = $this->model('Transaksi');
        $layananModel = $this->model('Layanan');
        $detailModel = $this->model('DetailTransaksi');

        if (!$id) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
            return;
        }

        $transaksi = $transaksiModel->getById($id);
        if (!$transaksi) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
            return;
        }

        if ($transaksiModel->delete($id)) {
            $details = $detailModel->getByTransaksi($id);
            if (!empty($details)) {
                foreach ($details as $d) {
                    $id_layanan = $d['id_layanan'] ?? null;
                    if ($id_layanan) {
                        $layananModel->delete($id_layanan);
                    }
                }
                $detailModel->deleteByTransaksi($id);
            }

            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        } else {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index&error=Gagal+hapus');
        }
    }

    public function recycle() {
        $transaksiModel = $this->model('Transaksi');
        $deleted = $transaksiModel->getDeleted();
        $this->view('transaksi/recycle', ['deleted' => $deleted]);
    }

    public function restore() {
        $id = $_GET['id_transaksi'] ?? $_GET['id'] ?? 0;
        $transaksiModel = $this->model('Transaksi');
        if ($transaksiModel->restore($id)) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=recycle&error=Gagal+restore');
        }
    }

    public function purge() {
        $id = $_GET['id_transaksi'] ?? $_GET['id'] ?? 0;
        $transaksiModel = $this->model('Transaksi');
        if ($transaksiModel->purge($id)) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=recycle&error=Gagal+purge');
        }
    }

    public function create() {
        $pelangganModel = $this->model('Pelanggan');
        $layananModel = $this->model('Layanan');
        
        $pelanggan = $pelangganModel->getAll();
        $layanan = $layananModel->getAll();
        
        $this->view('transaksi/create', ['pelanggan' => $pelanggan, 'layanan' => $layanan]);
    }

    public function detail() {
        $id_pelanggan = $_GET['id_pelanggan'] ?? 0;
        $id_transaksi = $_GET['id_transaksi'] ?? 0;
        $laporan_type = $_GET['laporan_type'] ?? 'harian';
        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');
        $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
        
        if ($id_transaksi && !$id_pelanggan) {
            $transaksiModel = $this->model('Transaksi');
            $transaksi = $transaksiModel->getById($id_transaksi);
            if (!$transaksi) {
                $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
            }
            $id_pelanggan = $transaksi['id_pelanggan'];
            if (!empty($transaksi['tanggal_masuk'])) {
                $tanggal = date('Y-m-d', strtotime($transaksi['tanggal_masuk']));
            }
        }
        
        if (!$id_pelanggan) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        }
        
        $pelangganModel = $this->model('Pelanggan');
        $layananModel = $this->model('Layanan');
        $transaksiModel = $this->model('Transaksi');

        $pelanggan = $pelangganModel->getById($id_pelanggan);
        if (!$pelanggan) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        }

        $detail = [];
        $transaksi = null;
        if ($laporan_type === 'bulanan') {
            $detail = $layananModel->getByPelangganAndMonth($id_pelanggan, $bulan, $tahun);
            $transaksi = $transaksiModel->findByPelangganAndMonth($id_pelanggan, $bulan, $tahun);
        } elseif ($laporan_type === 'tahunan') {
            $detail = $layananModel->getByPelangganAndYear($id_pelanggan, $tahun);
            $transaksi = $transaksiModel->findByPelangganAndYear($id_pelanggan, $tahun);
        } else {
            $detail = $layananModel->getByPelangganAndDate($id_pelanggan, $tanggal);
            $transaksi = $transaksiModel->findByPelangganAndDate($id_pelanggan, $tanggal);
        }

        $this->view('laporan/detail_transaksi', [
            'id_transaksi' => $transaksi['id_transaksi'] ?? 0,
            'pelanggan' => $pelanggan,
            'transaksi' => $transaksi,
            'detail' => $detail,
            'laporan_type' => $laporan_type,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal' => $tanggal
        ]);
    }

    public function nota() {
        $id_transaksi = $_GET['id_transaksi'] ?? 0;
        
        $transaksiModel = $this->model('Transaksi');
        $detailModel = $this->model('DetailTransaksi');
        
        $transaksi = $transaksiModel->getById($id_transaksi);
        if (!$transaksi) {
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=index');
        }

        $detail = $detailModel->getByTransaksi($id_transaksi);
        
        $this->view('transaksi/nota', [
            'transaksi' => $transaksi,
            'detail' => $detail
        ]);
    }

    public function store() {
        $transaksiModel = $this->model('Transaksi');
        $detailModel = $this->model('DetailTransaksi');
        
        $errors = [];
        
        if (empty($_POST['id_pelanggan'])) {
            $errors[] = 'Pelanggan wajib dipilih';
        }
        
        if (empty($_POST['tanggal_masuk'])) {
            $errors[] = 'Tanggal masuk wajib diisi';
        }
        
        if (empty($_POST['layanan']) || empty($_POST['berat'])) {
            $errors[] = 'Minimal harus ada 1 item layanan';
        }
        
        if (!empty($errors)) {
            $pelangganModel = $this->model('Pelanggan');
            $layananModel = $this->model('Layanan');
            
            $this->view('transaksi/create', [
                'pelanggan' => $pelangganModel->getAll(),
                'layanan' => $layananModel->getAll(),
                'errors' => $errors
            ]);
            return;
        }
        
        try {
            $totalHarga = 0;
            for ($i = 0; $i < count($_POST['layanan']); $i++) {
                $layananId = $_POST['layanan'][$i];
                $berat = floatval($_POST['berat'][$i]);
                
                $layananModel = $this->model('Layanan');
                $layanan = $layananModel->getById($layananId);
                
                if ($layanan) {
                    $totalHarga += $layanan['harga'] * $berat;
                }
            }
            
            $transaksiData = [
                'id_pelanggan' => $_POST['id_pelanggan'],
                'tanggal_masuk' => $_POST['tanggal_masuk'],
                'tanggal_selesai' => $_POST['tanggal_selesai'] ?? null,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'keterangan' => $_POST['keterangan'] ?? '',
                'metode_pembayaran' => $_POST['metode_pembayaran'] ?? 'Cash'
            ];
            
            $id_transaksi = $transaksiModel->create($transaksiData);
            
            for ($i = 0; $i < count($_POST['layanan']); $i++) {
                $layananId = $_POST['layanan'][$i];
                $berat = floatval($_POST['berat'][$i]);
                
                $detailData = [
                    'id_transaksi' => $id_transaksi,
                    'id_layanan' => $layananId,
                    'berat' => $berat
                ];
                
                $detailModel->create($detailData);
            }
            
            $this->redirect('/bellaundry/app/Controllers/TransaksiController.php?aksi=detail&id_transaksi=' . $id_transaksi);
            
        } catch (Exception $e) {
            $pelangganModel = $this->model('Pelanggan');
            $layananModel = $this->model('Layanan');
            
            $this->view('transaksi/create', [
                'pelanggan' => $pelangganModel->getAll(),
                'layanan' => $layananModel->getAll(),
                'errors' => [$e->getMessage()]
            ]);
        }
    }
}

try {
    $aksi = $_GET['aksi'] ?? 'index';
    $controller = new TransaksiController();

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
        case 'detail':
            $controller->detail();
            break;
        case 'nota':
            $controller->nota();
            break;
        default:
            $controller->index();
    }
} catch (Exception $e) {
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>
