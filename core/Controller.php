<?php
class Controller {

    public function model($model) {
        require_once __DIR__ . "/../app/Models/$model.php";
        return new $model;
    }

    public function view($path, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../app/Views/$path.php";
        
        if (!file_exists($viewFile)) {
            die("View file tidak ditemukan: $viewFile");
        }
        
        require_once $viewFile;
    }

    public function redirect($path) {
        header("Location: $path");
        exit;
    }

    public function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            if (isset($rule['required']) && $rule['required'] && empty($data[$field])) {
                $errors[$field] = "{$field} wajib diisi";
            }
            
            if (isset($rule['min']) && strlen($data[$field]) < $rule['min']) {
                $errors[$field] = "{$field} minimal {$rule['min']} karakter";
            }
            
            if (isset($rule['max']) && strlen($data[$field]) > $rule['max']) {
                $errors[$field] = "{$field} maksimal {$rule['max']} karakter";
            }
            
            if (isset($rule['email']) && $rule['email'] && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "{$field} harus email yang valid";
            }
        }
        
        return $errors;
    }

    public function json($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
