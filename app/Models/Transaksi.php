<?php
require_once __DIR__ . '/../../core/Model.php';

class Transaksi extends Model {
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    public function getAll() {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT t.*, p.nama as nama_pelanggan, p.no_hp as no_hp
            FROM {$this->table} t
            LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
            WHERE t.deleted_at IS NULL
            ORDER BY t.tanggal_masuk DESC"
        );
    }

    public function getById($id) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT t.*, p.nama as nama_pelanggan, p.no_hp as no_hp
            FROM {$this->table} t
            LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
            WHERE t.id_transaksi = ? AND t.deleted_at IS NULL",
            [$id]
        );
        return $result[0] ?? null;
    }

    public function findByPelangganAndDate($id_pelanggan, $tanggal) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT t.* FROM {$this->table} t
            WHERE t.id_pelanggan = ? AND DATE(t.tanggal_masuk) = ? AND t.deleted_at IS NULL LIMIT 1",
            [$id_pelanggan, $tanggal]
        );
        return $result[0] ?? null;
    }

    public function findByPelangganAndMonth($id_pelanggan, $bulan, $tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT t.* FROM {$this->table} t
            WHERE t.id_pelanggan = ? AND MONTH(t.tanggal_masuk) = ? AND YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            ORDER BY t.tanggal_masuk ASC LIMIT 1",
            [$id_pelanggan, $bulan, $tahun]
        );
        return $result[0] ?? null;
    }

    public function findByPelangganAndYear($id_pelanggan, $tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT t.* FROM {$this->table} t
            WHERE t.id_pelanggan = ? AND YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            ORDER BY t.tanggal_masuk ASC LIMIT 1",
            [$id_pelanggan, $tahun]
        );
        return $result[0] ?? null;
    }

    public function getTransaksiHarian($tanggal) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT 
                p.id_pelanggan as id_transaksi,
                p.id_pelanggan,
                p.nama as nama_pelanggan,
                t.tanggal_masuk,
                SUM(
                    CASE 
                        WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN l.berat * 10000
                        WHEN l.nama_layanan = 'Cuci Setrika Express' THEN l.berat * 15000
                        WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN l.berat * 8000
                        WHEN l.nama_layanan = 'Cuci Lipat Express' THEN l.berat * 12000
                        WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN l.berat * 6000
                        WHEN l.nama_layanan = 'Cuci Saja Express' THEN l.berat * 10000
                        ELSE 0
                    END
                ) as total_harga,
                CASE 
                    WHEN MIN(CASE WHEN LOWER(l.status) LIKE '%lunas%' THEN 1 ELSE 0 END) = 1 THEN 'Lunas'
                    ELSE 'Belum Lunas'
                END as status
            FROM layanan l
            JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
            WHERE DATE(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            GROUP BY p.id_pelanggan, p.nama, t.tanggal_masuk
            ORDER BY MAX(l.id_layanan) DESC",
            [$tanggal]
        );
    }

    public function getTransaksiBulanan($bulan, $tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT 
                p.id_pelanggan as id_transaksi,
                p.id_pelanggan,
                p.nama as nama_pelanggan,
                DATE_FORMAT(t.tanggal_masuk, '%Y-%m-01') as tanggal_masuk,
                SUM(
                    CASE 
                        WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN l.berat * 10000
                        WHEN l.nama_layanan = 'Cuci Setrika Express' THEN l.berat * 15000
                        WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN l.berat * 8000
                        WHEN l.nama_layanan = 'Cuci Lipat Express' THEN l.berat * 12000
                        WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN l.berat * 6000
                        WHEN l.nama_layanan = 'Cuci Saja Express' THEN l.berat * 10000
                        ELSE 0
                    END
                ) as total_harga,
                CASE 
                    WHEN MIN(CASE WHEN LOWER(l.status) LIKE '%lunas%' THEN 1 ELSE 0 END) = 1 THEN 'Lunas'
                    ELSE 'Belum Sepenuhnya Lunas'
                END as status
            FROM layanan l
            JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
            WHERE MONTH(t.tanggal_masuk) = ? AND YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            GROUP BY p.id_pelanggan, p.nama
            ORDER BY MAX(l.id_layanan) DESC",
            [$bulan, $tahun]
        );
    }

    public function getTransaksiTahunan($tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT 
                p.id_pelanggan as id_transaksi,
                p.id_pelanggan,
                p.nama as nama_pelanggan,
                DATE_FORMAT(t.tanggal_masuk, '%Y-01-01') as tanggal_masuk,
                SUM(
                    CASE 
                        WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN l.berat * 10000
                        WHEN l.nama_layanan = 'Cuci Setrika Express' THEN l.berat * 15000
                        WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN l.berat * 8000
                        WHEN l.nama_layanan = 'Cuci Lipat Express' THEN l.berat * 12000
                        WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN l.berat * 6000
                        WHEN l.nama_layanan = 'Cuci Saja Express' THEN l.berat * 10000
                        ELSE 0
                    END
                ) as total_harga,
                CASE 
                    WHEN MIN(CASE WHEN LOWER(l.status) LIKE '%lunas%' THEN 1 ELSE 0 END) = 1 THEN 'Lunas'
                    ELSE 'Belum Sepenuhnya Lunas'
                END as status
            FROM layanan l
            JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
            WHERE YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            GROUP BY p.id_pelanggan, p.nama
            ORDER BY MAX(l.id_layanan) DESC",
            [$tahun]
        );
    }

    public function getPendapatanTahunan($tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $rows = $qb->raw(
            "SELECT COALESCE(SUM(
                CASE 
                    WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN l.berat * 10000
                    WHEN l.nama_layanan = 'Cuci Setrika Express' THEN l.berat * 15000
                    WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN l.berat * 8000
                    WHEN l.nama_layanan = 'Cuci Lipat Express' THEN l.berat * 12000
                    WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN l.berat * 6000
                    WHEN l.nama_layanan = 'Cuci Saja Express' THEN l.berat * 10000
                    ELSE 0
                END
            ), 0) as total
            FROM layanan l
            JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            WHERE LOWER(l.status) LIKE '%lunas%' AND YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL",
            [$tahun]
        );
        return $rows[0] ?? ['total' => 0];
    }

    public function getPendapatanPerBulan($tahun) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT 
                MONTH(t.tanggal_masuk) as bulan,
                COALESCE(SUM(
                    CASE 
                        WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN l.berat * 10000
                        WHEN l.nama_layanan = 'Cuci Setrika Express' THEN l.berat * 15000
                        WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN l.berat * 8000
                        WHEN l.nama_layanan = 'Cuci Lipat Express' THEN l.berat * 12000
                        WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN l.berat * 6000
                        WHEN l.nama_layanan = 'Cuci Saja Express' THEN l.berat * 10000
                        ELSE 0
                    END
                ), 0) as pendapatan
            FROM layanan l
            JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
            JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
            JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
            WHERE LOWER(l.status) LIKE '%lunas%' AND YEAR(t.tanggal_masuk) = ? AND t.deleted_at IS NULL
            GROUP BY MONTH(t.tanggal_masuk)
            ORDER BY bulan ASC",
            [$tahun]
        );
    }

    public function create($data) {
        $data_insert = [
            'id_pelanggan' => (int)($data['id_pelanggan'] ?? 0),
            'id_pegawai' => isset($data['id_pegawai']) && $data['id_pegawai'] !== '' ? (int)$data['id_pegawai'] : null,
            'tanggal_masuk' => $data['tanggal_masuk'] ?? date('Y-m-d H:i:s'),
            'tanggal_selesai' => $data['tanggal_selesai'] ?? null,
            'status' => htmlspecialchars($data['status'] ?? 'Belum Lunas'),
            'total_harga' => (float)($data['total_harga'] ?? 0),
            'metode_pembayaran' => isset($data['metode_pembayaran']) ? htmlspecialchars($data['metode_pembayaran']) : null
        ];
        
        error_log("Transaksi::create called with data: " . json_encode($data_insert));
        $result = $this->insertRecord($data_insert);
        error_log("Transaksi::create result: " . ($result ? "TRUE" : "FALSE"));
        return $result;
    }

    public function update($id, $data) {
        $data_update = [];

        if (array_key_exists('tanggal_selesai', $data)) {
            $data_update['tanggal_selesai'] = $data['tanggal_selesai'] ?: null;
        }

        if (array_key_exists('status', $data)) {
            $data_update['status'] = htmlspecialchars($data['status']);
        }

        if (array_key_exists('total_harga', $data)) {
            $data_update['total_harga'] = (float)$data['total_harga'];
        }

        if (array_key_exists('metode_pembayaran', $data)) {
            $data_update['metode_pembayaran'] = $data['metode_pembayaran'] !== null ? htmlspecialchars($data['metode_pembayaran']) : null;
        }

        if (empty($data_update)) {
            return false;
        }

        return $this->updateRecord($id, $data_update);
    }

    public function deleteByPelangganAndDate($id_pelanggan, $date) {
        $sql = "UPDATE {$this->table} SET deleted_at = ? WHERE id_pelanggan = ? AND DATE(tanggal_masuk) = ? AND deleted_at IS NULL";
        $stmt = $this->executeQuery($sql, [date('Y-m-d H:i:s'), $id_pelanggan, $date]);
        if ($stmt instanceof PDOStatement) {
            return $stmt->rowCount() > 0;
        }
        return false;
    }

    public function updateByPelanggan($id_pelanggan, $date, $status, $tanggal_selesai = null) {
        if ($tanggal_selesai) {
            $sql = "UPDATE {$this->table} SET status = ?, tanggal_selesai = ? WHERE id_pelanggan = ? AND DATE(tanggal_masuk) = ? AND deleted_at IS NULL";
            $stmt = $this->executeQuery($sql, [$status, $tanggal_selesai, $id_pelanggan, $date]);
        } else {
            $sql = "UPDATE {$this->table} SET status = ? WHERE id_pelanggan = ? AND DATE(tanggal_masuk) = ? AND deleted_at IS NULL";
            $stmt = $this->executeQuery($sql, [$status, $id_pelanggan, $date]);
        }

        if ($stmt instanceof PDOStatement) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    public function delete($id) {
        return $this->updateRecord($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    public function getDeleted() {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT t.*, p.nama as nama_pelanggan, p.no_hp as no_hp
            FROM {$this->table} t
            LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
            WHERE t.deleted_at IS NOT NULL
            ORDER BY t.deleted_at DESC"
        );
    }

    public function restore($id) {
        return $this->updateRecord($id, ['deleted_at' => null]);
    }

    public function purge($id) {
        $stmt = $this->db->prepare("DELETE FROM detail_transaksi WHERE id_transaksi = ?");
        $stmt->execute([$id]);
        return $this->deleteRecord($id);
    }
}

