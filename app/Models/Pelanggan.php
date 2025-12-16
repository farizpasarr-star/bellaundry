<?php
require_once __DIR__ . '/../../core/Model.php';

class Pelanggan extends Model {
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    public function getAll() {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw("SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY id_pelanggan DESC");
    }

    public function getById($id) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->find($id);
    }

    public function search($keyword) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw(
            "SELECT id_pelanggan, nama FROM {$this->table} 
            WHERE (id_pelanggan LIKE ? OR nama LIKE ?) AND deleted_at IS NULL
            ORDER BY nama ASC",
            ["%$keyword%", "%$keyword%"]
        );
    }

    public function create($data) {
        $data_insert = [
            'nama' => htmlspecialchars($data['nama'] ?? ''),
            'no_hp' => htmlspecialchars($data['no_hp'] ?? ''),
            'alamat' => htmlspecialchars($data['alamat'] ?? '')
        ];
        
        return $this->insertRecord($data_insert);
    }

    public function update($id, $data) {
        $data_update = [
            'nama' => htmlspecialchars($data['nama'] ?? ''),
            'no_hp' => htmlspecialchars($data['no_hp'] ?? ''),
            'alamat' => htmlspecialchars($data['alamat'] ?? '')
        ];
        
        return $this->updateRecord($id, $data_update);
    }

    public function delete($id) {
        $now = date('Y-m-d H:i:s');
        return $this->updateRecord($id, ['deleted_at' => $now]);
    }

    public function getDeleted() {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw("SELECT * FROM {$this->table} WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC");
    }

    public function restore($id) {
        return $this->updateRecord($id, ['deleted_at' => null]);
    }

    public function purge($id) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM layanan WHERE id_pelanggan = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && (int)$row['cnt'] > 0) {
            return false;
        }
        return $this->deleteRecord($id);
    }

    public function validate($data) {
        $errors = [];

        if (empty($data['nama'])) {
            $errors['nama'] = 'Nama pelanggan wajib diisi';
        } elseif (strlen($data['nama']) > 100) {
            $errors['nama'] = 'Nama maksimal 100 karakter';
        }

        if (empty($data['no_hp'])) {
            $errors['no_hp'] = 'No HP wajib diisi';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $data['no_hp'])) {
            $errors['no_hp'] = 'No HP harus 10-15 digit angka';
        }

        if (empty($data['alamat'])) {
            $errors['alamat'] = 'Alamat wajib diisi';
        } elseif (strlen($data['alamat']) > 255) {
            $errors['alamat'] = 'Alamat maksimal 255 karakter';
        }

        return $errors;
    }
}
