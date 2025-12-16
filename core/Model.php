<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/QueryBuilder.php';

class Model {
    protected $db;
    protected $qb;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::connect();
        
        if ($this->table) {
            $this->qb = new QueryBuilder($this->db, $this->table, $this->primaryKey);
        }
    }

    protected function getQueryBuilder($table = null, $pk = 'id') {
        return new QueryBuilder($this->db, $table ?? $this->table, $pk);
    }

    protected function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query Error: " . $e->getMessage());
        }
    }

    protected function insertRecord($data) {
        if (!$this->qb) {
            $this->qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        }
        return $this->qb->insert($data);
    }

    protected function updateRecord($id, $data) {
        if (!$this->qb) {
            $this->qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        }
        return $this->qb->update($id, $data);
    }

    protected function deleteRecord($id) {
        if (!$this->qb) {
            $this->qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        }
        return $this->qb->delete($id);
    }

    protected function findRecord($id) {
        if (!$this->qb) {
            $this->qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        }
        return $this->qb->find($id);
    }

    protected function getAllRecords() {
        if (!$this->qb) {
            $this->qb = $this->getQueryBuilder($this->table, $this->primaryKey);
        }
        return $this->qb->all();
    }
}
