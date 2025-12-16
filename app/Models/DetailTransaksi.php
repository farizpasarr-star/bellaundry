<?php
require_once __DIR__ . '/../../core/Model.php';

class DetailTransaksi extends Model {
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';

    public function getByTransaksi($id_transaksi) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT dt.*, l.nama_layanan, l.estimasi, l.status
            FROM {$this->table} dt
            LEFT JOIN layanan l ON dt.id_layanan = l.id_layanan
            WHERE dt.id_transaksi = ?
            ORDER BY dt.id_detail ASC",
            [$id_transaksi]
        );
    }

    public function getById($id) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT dt.*, l.nama_layanan
            FROM {$this->table} dt
            LEFT JOIN layanan l ON dt.id_layanan = l.id_layanan
            WHERE dt.id_detail = ?",
            [$id]
        );
        return $result[0] ?? null;
    }

    public function create($data) {
        $data_insert = [
            'id_transaksi' => (int)($data['id_transaksi'] ?? 0),
            'id_layanan' => (int)($data['id_layanan'] ?? 0),
            'berat' => (float)($data['berat'] ?? 0),
            'subtotal' => (float)($data['subtotal'] ?? 0)
        ];
        
        return $this->insertRecord($data_insert);
    }

    public function deleteByTransaksi($id_transaksi) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $sql = "DELETE FROM {$this->table} WHERE id_transaksi = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_transaksi]);
    }

    public function deleteByLayanan($id_layanan) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $sql = "DELETE FROM {$this->table} WHERE id_layanan = ?";
        $result = $qb->raw($sql, [$id_layanan]);
        return $result ? true : false;
    }

    public function getByLayanan($id_layanan) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $result = $qb->raw(
            "SELECT dt.* FROM {$this->table} dt WHERE dt.id_layanan = ? LIMIT 1",
            [$id_layanan]
        );
        return $result[0] ?? null;
    }

    public function update($id_detail, $data) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $sets = [];
        $params = [];
        foreach ($data as $col => $val) {
            $sets[] = "{$col} = :{$col}";
            $params[$col] = $val;
        }
        $params['id'] = $id_detail;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getTotalBerat($id_transaksi) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT SUM(berat) as total FROM {$this->table} WHERE id_transaksi = ?",
            [$id_transaksi]
        );
    }
}
