<?php
require_once __DIR__ . '/../../core/Model.php';

    class Layanan extends Model {
        protected $table = 'layanan';
        protected $primaryKey = 'id_layanan';

        public function getAll() {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT l.*, p.nama as nama_pelanggan
                FROM {$this->table} l
                LEFT JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
                WHERE l.deleted_at IS NULL
                ORDER BY l.id_layanan ASC"
            );
        }

        public function getById($id) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT l.*, p.nama as nama_pelanggan
                FROM {$this->table} l
                LEFT JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan
                WHERE l.id_layanan = ?",
                [$id]
            )[0] ?? null;
        }

        public function getByPelanggan($id_pelanggan) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT * FROM {$this->table} 
                WHERE id_pelanggan = ? AND deleted_at IS NULL
                ORDER BY id_layanan ASC",
                [$id_pelanggan]
            );
        }

        public function getByPelangganLatest($id_pelanggan) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT * FROM {$this->table} 
                WHERE id_pelanggan = ? AND deleted_at IS NULL
                ORDER BY id_layanan ASC
                LIMIT 10",
                [$id_pelanggan]
            );
        }

        public function getByPelangganAndDate($id_pelanggan, $tanggal) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT l.* FROM {$this->table} l
                LEFT JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
                LEFT JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
                WHERE l.id_pelanggan = ? AND DATE(t.tanggal_masuk) = ? AND l.deleted_at IS NULL
                ORDER BY l.id_layanan ASC",
                [$id_pelanggan, $tanggal]
            );
        }

        public function getByPelangganAndMonth($id_pelanggan, $bulan, $tahun) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT l.* FROM {$this->table} l
                LEFT JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
                LEFT JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
                WHERE l.id_pelanggan = ? AND MONTH(t.tanggal_masuk) = ? AND YEAR(t.tanggal_masuk) = ? AND l.deleted_at IS NULL
                ORDER BY l.id_layanan ASC",
                [$id_pelanggan, $bulan, $tahun]
            );
        }

        public function getByPelangganAndYear($id_pelanggan, $tahun) {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw(
                "SELECT l.* FROM {$this->table} l
                LEFT JOIN detail_transaksi dt ON l.id_layanan = dt.id_layanan
                LEFT JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
                WHERE l.id_pelanggan = ? AND YEAR(t.tanggal_masuk) = ? AND l.deleted_at IS NULL
                ORDER BY l.id_layanan ASC",
                [$id_pelanggan, $tahun]
            );
        }

        public function create($data) {
            $data_insert = [
                'id_pelanggan' => (int)($data['id_pelanggan'] ?? 0),
                'nama_layanan' => htmlspecialchars($data['nama_layanan'] ?? ''),
                'berat' => (float)($data['berat'] ?? 0),
                'estimasi' => htmlspecialchars($data['estimasi'] ?? ''),
                'status' => 'proses'
            ];
        
            return $this->insertRecord($data_insert);
        }

        public function update($id, $data) {
            $data_update = [
                'id_pelanggan' => (int)($data['id_pelanggan'] ?? 0),
                'nama_layanan' => htmlspecialchars($data['nama_layanan'] ?? ''),
                'berat' => (float)($data['berat'] ?? 0),
                'estimasi' => htmlspecialchars($data['estimasi'] ?? ''),
                'status' => htmlspecialchars($data['status'] ?? 'proses')
            ];
        
            return $this->updateRecord($id, $data_update);
        }

        public function updateStatus($id, $status) {
            return $this->updateRecord($id, ['status' => $status]);
        }

        public function delete($id) {
            $now = date('Y-m-d H:i:s');
            return $this->updateRecord($id, ['deleted_at' => $now]);
        }

        public function getDeleted() {
            $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
            return $qb->raw("SELECT l.*, p.nama as nama_pelanggan FROM {$this->table} l LEFT JOIN pelanggan p ON l.id_pelanggan = p.id_pelanggan WHERE l.deleted_at IS NOT NULL ORDER BY l.deleted_at DESC");
        }

        public function restore($id) {
            return $this->updateRecord($id, ['deleted_at' => null]);
        }

        public function purge($id) {
                $stmt = $this->db->prepare("SELECT DISTINCT id_transaksi FROM detail_transaksi WHERE id_layanan = ?");
                $stmt->execute([$id]);
                $transaksiIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                $stmtDel = $this->db->prepare("DELETE FROM detail_transaksi WHERE id_layanan = ?");
                $stmtDel->execute([$id]);

                $calcStmt = $this->db->prepare(
                    "SELECT COALESCE(SUM(
                        CASE 
                            WHEN l.nama_layanan = 'Cuci Setrika Reguler' THEN dt.berat * 10000
                            WHEN l.nama_layanan = 'Cuci Setrika Express' THEN dt.berat * 15000
                            WHEN l.nama_layanan = 'Cuci Lipat Reguler' THEN dt.berat * 8000
                            WHEN l.nama_layanan = 'Cuci Lipat Express' THEN dt.berat * 12000
                            WHEN l.nama_layanan = 'Cuci Saja Reguler' THEN dt.berat * 6000
                            WHEN l.nama_layanan = 'Cuci Saja Express' THEN dt.berat * 10000
                            ELSE dt.subtotal
                        END
                    ), 0) as total
                    FROM detail_transaksi dt
                    LEFT JOIN layanan l ON dt.id_layanan = l.id_layanan
                    WHERE dt.id_transaksi = ?"
                );

                $updateStmt = $this->db->prepare("UPDATE transaksi SET total_harga = ? WHERE id_transaksi = ?");
                $countStmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM detail_transaksi WHERE id_transaksi = ?");
                $deleteTransaksiStmt = $this->db->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");

                foreach ($transaksiIds as $tid) {
                    $calcStmt->execute([$tid]);
                    $res = $calcStmt->fetch(PDO::FETCH_ASSOC);
                    $total = $res['total'] ?? 0;
                    $updateStmt->execute([$total, $tid]);

                    $countStmt->execute([$tid]);
                    $c = $countStmt->fetch(PDO::FETCH_ASSOC);
                    $cnt = (int)($c['cnt'] ?? 0);
                    if ($cnt === 0) {
                        $deleteTransaksiStmt->execute([$tid]);
                    }
                }

                return $this->deleteRecord($id);
        }

        public function validate($data) {
            $errors = [];

            if (empty($data['id_pelanggan'])) {
                $errors['id_pelanggan'] = 'Pelanggan wajib dipilih';
            }

            if (empty($data['nama_layanan'])) {
                $errors['nama_layanan'] = 'Nama layanan wajib diisi';
            }

            if (empty($data['berat']) || $data['berat'] <= 0) {
                $errors['berat'] = 'Berat harus lebih dari 0';
            }

            if (empty($data['estimasi'])) {
                $errors['estimasi'] = 'Estimasi wajib diisi';
            }

            return $errors;
        }
    }