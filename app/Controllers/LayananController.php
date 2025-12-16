<?php
require_once __DIR__ . '/../../core/Controller.php';

class LayananController extends Controller {

    public function index() {

        $layananModel = $this->model('Layanan');
        $layanan = $layananModel->getAll();
        $this->view('layanan/index', ['layanan' => $layanan]);
    }

    public function create() {
        $pelangganModel = $this->model('Pelanggan');
        $pelanggan = $pelangganModel->getAll();
        $this->view('layanan/create', ['pelanggan' => $pelanggan]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
        }

            $data = [
                'id_pelanggan' => $_POST['id_pelanggan'] ?? null,
                'nama_layanan' => trim($_POST['nama_layanan'] ?? ''),
                'berat' => (float)($_POST['berat'] ?? 0),
                'estimasi' => $_POST['estimasi'] ?? '',
            ];

            $layananModel = $this->model('Layanan');
            $transaksiModel = $this->model('Transaksi');
            $detailModel = $this->model('DetailTransaksi');

            $errors = $layananModel->validate($data);
            if (!empty($errors)) {
                $pelangganModel = $this->model('Pelanggan');
                $pelanggan = $pelangganModel->getAll();
                $this->view('layanan/create', ['errors' => $errors, 'data' => $data, 'pelanggan' => $pelanggan]);
                return;
            }

            require_once __DIR__ . '/../../config/koneksi.php';
            $db = Database::connect();
            try {
                $db->beginTransaction();

                error_log('STORE: creating layanan with data=' . json_encode($data));
                $id_layanan = $layananModel->create($data);
                error_log('STORE: layanan create returned id=' . var_export($id_layanan, true));

                if (!$id_layanan) {
                    throw new Exception('Gagal membuat layanan');
                }

                if ($id_layanan) {
                    
                    $tanggal_day = date('Y-m-d');
                    $tanggal_now = date('Y-m-d H:i:s');
                    $id_pelanggan = $data['id_pelanggan'];

                if ($id_pelanggan) {
                    $existingTrans = $transaksiModel->findByPelangganAndDate($id_pelanggan, $tanggal_day);
                    if ($existingTrans) {
                        $id_transaksi = $existingTrans['id_transaksi'];
                    } else {
                        $id_pegawai_selected = null;
                        if (isset($_SESSION['id_pegawai']) && $_SESSION['id_pegawai']) {
                            $id_pegawai_selected = $_SESSION['id_pegawai'];
                        } else {
                            $pegawaiModel = $this->model('Pegawai');
                            $allPegawai = $pegawaiModel->getAll();
                            if (!empty($allPegawai)) {
                                $id_pegawai_selected = $allPegawai[0]['id_pegawai'];
                            }
                        }

                        if (!$id_pegawai_selected) {
                            error_log("STORE: Tidak ada pegawai tersedia untuk membuat transaksi");
                            $pelangganModel = $this->model('Pelanggan');
                            $pelanggan = $pelangganModel->getAll();
                            $this->view('layanan/create', ['error' => 'Tidak ditemukan pegawai. Silakan tambahkan data pegawai terlebih dahulu.', 'pelanggan' => $pelanggan, 'data' => $data]);
                            return;
                        }

                        $transData = [
                            'id_pelanggan' => $id_pelanggan,
                            'id_pegawai' => $id_pegawai_selected,
                            'tanggal_masuk' => $tanggal_now,
                            'status' => 'Baru',
                            'total_harga' => 0,
                        ];
                        $id_transaksi = $transaksiModel->create($transData);
                        error_log('STORE: transaksi create returned id=' . var_export($id_transaksi, true));
                        if (!$id_transaksi) {
                            throw new Exception('Gagal membuat transaksi');
                        }
                    }
                    if ($id_transaksi) {
                        $berat = (float)$data['berat'];
                        $subtotal = $this->calculateHarga($data['nama_layanan'], $berat);

                        $detailData = [
                            'id_transaksi' => $id_transaksi,
                            'id_layanan' => $id_layanan,
                            'berat' => $berat,
                            'subtotal' => $subtotal,
                        ];
                        $id_detail = $detailModel->create($detailData);
                        error_log('STORE: detail create returned id=' . var_export($id_detail, true));
                        if (!$id_detail) {
                            throw new Exception('Gagal membuat detail_transaksi');
                        }

                        $detailsForTrans = $detailModel->getByTransaksi($id_transaksi);
                        $sumTotal = 0;
                        foreach ($detailsForTrans as $dRow) {
                            $sumTotal += (float)($dRow['subtotal'] ?? 0);
                        }
                        $transaksiModel->update($id_transaksi, ['total_harga' => $sumTotal]);
                    }
                }
            }

                $db->commit();

                $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
                return;
            } catch (Exception $e) {
                $db->rollBack();
                error_log('STORE TRANSACTION FAILED: ' . $e->getMessage());
                $pelangganModel = $this->model('Pelanggan');
                $pelanggan = $pelangganModel->getAll();
                $this->view('layanan/create', ['error' => 'Gagal menyimpan data: ' . htmlspecialchars($e->getMessage()), 'pelanggan' => $pelanggan, 'data' => $data]);
                return;
            }
        
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        $layananModel = $this->model('Layanan');
        $layanan = $layananModel->getById($id);

        if (!$layanan) {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
        }

        $pelangganModel = $this->model('Pelanggan');
        $pelanggan = $pelangganModel->getAll();
        
        $this->view('layanan/edit', ['layanan' => $layanan, 'pelanggan' => $pelanggan]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
        }

        $id = $_POST['id_layanan'] ?? 0;
        $data = [
            'id_pelanggan' => $_POST['id_pelanggan'] ?? 0,
            'nama_layanan' => trim($_POST['nama_layanan'] ?? ''),
            'berat' => (float)($_POST['berat'] ?? 0),
            'estimasi' => $_POST['estimasi'] ?? '',
            'status' => $_POST['status'] ?? 'proses'
        ];

        error_log("UPDATE: nama_layanan = '" . $data['nama_layanan'] . "', berat = " . $data['berat']);

        $layananModel = $this->model('Layanan');
        $errors = $layananModel->validate($data);

        if (!empty($errors)) {
            $layanan = $layananModel->getById($id);
            $pelangganModel = $this->model('Pelanggan');
            $pelanggan = $pelangganModel->getAll();
            $this->view('layanan/edit', ['errors' => $errors, 'layanan' => $layanan, 'pelanggan' => $pelanggan]);
            return;
        }

        if ($layananModel->update($id, $data)) {
            require_once __DIR__ . '/../../config/koneksi.php';
            $db = Database::connect();
            try {
                $db->beginTransaction();

                $detailModel = $this->model('DetailTransaksi');
                $transaksiModel = $this->model('Transaksi');

                $detail = $detailModel->getByLayanan($id);
                if ($detail) {
                    $id_detail = $detail['id_detail'];
                    $id_transaksi = $detail['id_transaksi'];

                    $newBerat = (float)$data['berat'];
                    $newSubtotal = $this->calculateHarga($data['nama_layanan'], $newBerat);

                    error_log("UPDATE_SYNC: id_layanan={$id}, id_detail={$id_detail}, newBerat={$newBerat}, newSubtotal={$newSubtotal}");

                    $okDetail = $detailModel->update($id_detail, ['berat' => $newBerat, 'subtotal' => $newSubtotal]);
                    error_log('UPDATE_SYNC: detail update ok=' . ($okDetail ? '1' : '0'));

                    $details = $detailModel->getByTransaksi($id_transaksi);
                    $sum = 0;
                    $allLunas = true;
                    foreach ($details as $d) {
                        $sum += (float)($d['subtotal'] ?? 0);
                        $statusLayanan = strtolower($d['status'] ?? '');
                        if (strpos($statusLayanan, 'lunas') === false) {
                            $allLunas = false;
                        }
                    }

                    error_log('UPDATE_SYNC: recalculated sum=' . $sum);

                    $transaksiModel->update($id_transaksi, ['total_harga' => $sum]);

                    if ($allLunas) {
                        $tanggalSelesai = date('Y-m-d H:i:s');
                        $transaksiModel->update($id_transaksi, ['status' => 'Lunas', 'tanggal_selesai' => $tanggalSelesai]);
                    } else {
                        $transaksiModel->update($id_transaksi, ['status' => 'Belum Lunas', 'tanggal_selesai' => null]);
                    }
                }

                $db->commit();

                $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
                return;
            } catch (Exception $e) {
                $db->rollBack();
                error_log('UPDATE TRANSACTION FAILED: ' . $e->getMessage());
                $layanan = $layananModel->getById($id);
                $pelangganModel = $this->model('Pelanggan');
                $pelanggan = $pelangganModel->getAll();
                $this->view('layanan/edit', ['error' => 'Gagal menyimpan perubahan: ' . htmlspecialchars($e->getMessage()), 'layanan' => $layanan, 'pelanggan' => $pelanggan]);
                return;
            }
        } else {
            $layanan = $layananModel->getById($id);
            $pelangganModel = $this->model('Pelanggan');
            $pelanggan = $pelangganModel->getAll();
            $this->view('layanan/edit', ['error' => 'Gagal update', 'layanan' => $layanan, 'pelanggan' => $pelanggan]);
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        $layananModel = $this->model('Layanan');
        $layanan = $layananModel->getById($id);
        if (!$layanan) {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index&error=Not+found');
        }

        if ($layananModel->delete($id)) {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index');
        } else {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=index&error=Gagal+hapus');
        }
    }

    public function recycle() {
        $layananModel = $this->model('Layanan');
        $deleted = $layananModel->getDeleted();
        $this->view('layanan/recycle', ['deleted' => $deleted]);
    }

    public function restore() {
        $id = $_GET['id'] ?? 0;
        $layananModel = $this->model('Layanan');
        if ($layananModel->restore($id)) {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=recycle&error=Gagal+restore');
        }
    }

    public function purge() {
        $id = $_GET['id'] ?? 0;
        $layananModel = $this->model('Layanan');
        if ($layananModel->purge($id)) {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=recycle');
        } else {
            $this->redirect('/bellaundry/app/Controllers/LayananController.php?aksi=recycle&error=Gagal+purge');
        }
    }

    private function calculateHarga($nama_layanan, $berat) {
        $harga_per_kg = [
            'Cuci Setrika Reguler' => 10000,
            'Cuci Setrika Express' => 15000,
            'Cuci Lipat Reguler' => 8000,
            'Cuci Lipat Express' => 12000,
            'Cuci Saja Reguler' => 6000,
            'Cuci Saja Express' => 10000
        ];
        
        $rate = $harga_per_kg[$nama_layanan] ?? 0;
        $total = $berat * $rate;
        
        error_log("calculateHarga: nama='$nama_layanan', rate=$rate, berat=$berat, total=$total");
        
        return $total;
    }

    private function getLastInsertId() {
        require_once __DIR__ . '/../../config/koneksi.php';
        $db = Database::connect();
        $stmt = $db->query("SELECT MAX(id_layanan) as last_id FROM layanan");
        $result = $stmt->fetch();
        return $result['last_id'] ?? 0;
    }
    
    private function getLastTransaksiId() {
        require_once __DIR__ . '/../../config/koneksi.php';
        $db = Database::connect();
        $stmt = $db->query("SELECT MAX(id_transaksi) as last_id FROM transaksi");
        $result = $stmt->fetch();
        return $result['last_id'] ?? 0;
    }
}

try {
    $aksi = $_GET['aksi'] ?? 'index';
    $controller = new LayananController();

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