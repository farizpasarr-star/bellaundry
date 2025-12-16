<?php
require_once __DIR__ . '/../../core/Controller.php';

class LaporanController extends Controller {

    public function harian() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (array_key_exists('tanggal', $_REQUEST)) {
            $val = trim($_REQUEST['tanggal']);
            if ($val !== '') {
                $tanggal = $val;
                $_SESSION['laporan_tanggal'] = $tanggal;
            } else {
                $tanggal = $_SESSION['laporan_tanggal'] ?? date('Y-m-d');
            }
        } else {
            $tanggal = $_SESSION['laporan_tanggal'] ?? date('Y-m-d');
        }

        $transaksiModel = $this->model('Transaksi');
        $transaksi = $transaksiModel->getTransaksiHarian($tanggal);

        $this->view('laporan/transaksi_harian', [
            'tanggal' => $tanggal,
            'transaksi' => $transaksi
        ]);
    }

    public function bulanan() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (array_key_exists('bulan', $_REQUEST) || array_key_exists('tahun', $_REQUEST)) {
            $b = array_key_exists('bulan', $_REQUEST) ? trim($_REQUEST['bulan']) : '';
            $y = array_key_exists('tahun', $_REQUEST) ? trim($_REQUEST['tahun']) : '';

            $bulan = $b !== '' ? sprintf('%02d', (int)$b) : ($_SESSION['laporan_bulan'] ?? date('m'));
            $tahun = $y !== '' ? $y : ($_SESSION['laporan_tahun'] ?? date('Y'));

            if ($b !== '') {
                $_SESSION['laporan_bulan'] = $bulan;
            }
            if ($y !== '') {
                $_SESSION['laporan_tahun'] = $tahun;
            }
        } else {
            $bulan = $_SESSION['laporan_bulan'] ?? date('m');
            $tahun = $_SESSION['laporan_tahun'] ?? date('Y');
        }

        $transaksiModel = $this->model('Transaksi');
        $transaksi = $transaksiModel->getTransaksiBulanan($bulan, $tahun);

        $this->view('laporan/transaksi_bulanan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'transaksi' => $transaksi
        ]);
    }

    public function tahunan() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (array_key_exists('tahun', $_REQUEST)) {
            $val = trim($_REQUEST['tahun']);
            if ($val !== '') {
                $tahun = $val;
                $_SESSION['laporan_tahun'] = $tahun;
            } else {
                $tahun = $_SESSION['laporan_tahun'] ?? date('Y');
            }
        } else {
            $tahun = $_SESSION['laporan_tahun'] ?? date('Y');
        }

        $transaksiModel = $this->model('Transaksi');
        $transaksi = $transaksiModel->getTransaksiTahunan($tahun);

        $this->view('laporan/transaksi_tahunan', [
            'tahun' => $tahun,
            'transaksi' => $transaksi
        ]);
    }

    public function pendapatan() {
        $tahun = $_GET['tahun'] ?? date('Y');
        
        $transaksiModel = $this->model('Transaksi');
        $totalResult = $transaksiModel->getPendapatanTahunan($tahun);
        $totalPendapatan = $totalResult['total'] ?? 0;
        
        $perBulanResult = $transaksiModel->getPendapatanPerBulan($tahun);
        $perBulan = [];
        foreach ($perBulanResult as $row) {
            $perBulan[$row['bulan']] = $row['pendapatan'];
        }
        
        $this->view('laporan/laporan_pendapatan', [
            'tahun' => $tahun,
            'totalPendapatan' => $totalPendapatan,
            'pendapatanPerBulan' => $perBulan
        ]);
    }

    public function index() {
        $this->view('laporan/index');
    }
}

try {
    $aksi = $_GET['aksi'] ?? 'index';
    $controller = new LaporanController();

    switch ($aksi) {
        case 'harian':
            $controller->harian();
            break;
        case 'bulanan':
            $controller->bulanan();
            break;
        case 'tahunan':
            $controller->tahunan();
            break;
        case 'pendapatan':
            $controller->pendapatan();
            break;
        case 'index':
            $controller->index();
            break;
        default:
            $controller->index();
    }
} catch (Exception $e) {
    error_log("LaporanController Error: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine());
    die("Error: " . htmlspecialchars($e->getMessage()));
}
?>
