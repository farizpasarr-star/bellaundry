<?php

class QueryBuilder {
    protected $pdo;
    protected $table;
    protected $pk = 'id';

    public function __construct($pdo = null, $table = null, $pk = 'id') {
        $this->pdo = $pdo ?? $GLOBALS['db'] ?? null;
        $this->table = $table;
        $this->pk = $pk ?? 'id';
        
        if (!$this->pdo) {
            throw new Exception("PDO connection tidak tersedia");
        }
    }

    public function all() {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->pk} = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function where($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $parts = [];
            foreach ($conditions as $col => $val) {
                $parts[] = "$col = :$col";
            }
            $sql .= " WHERE " . implode(" AND ", $parts);
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll();
    }

    public function insert($data = []) {
        $cols = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$cols}) VALUES ({$placeholders})";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data = []) {
        $sets = [];
        foreach ($data as $col => $val) {
            $sets[] = "$col = :$col";
        }
        $setString = implode(", ", $sets);
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->pk} = :_pk";
        
        $params = $data;
        $params[':_pk'] = $id;
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->pk} = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function orderBy($column, $direction = 'ASC') {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$column} {$direction}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function limit($limit, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function raw($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function first() {
        $sql = "SELECT * FROM {$this->table} LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }
}
?>