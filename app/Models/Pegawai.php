<?php
require_once __DIR__ . '/../../core/Model.php';

class Pegawai extends Model {
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    public function getById($id) {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->find($id);
    }

    public function getAll() {
        $qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        return $qb->raw("SELECT * FROM {$this->table} ORDER BY id_pegawai ASC");
    }

    public function validateLogin($id_pegawai, $password) {
        $pegawai = $this->getById($id_pegawai);
        
        if ($pegawai && $password === $pegawai['password']) {
            return $pegawai;
        }
        
        return null;
    }

    public function updatePassword($id, $newPassword) {
        return $this->updateRecord($id, ['password' => $newPassword]);
    }
}
