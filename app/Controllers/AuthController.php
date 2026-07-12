<?php

namespace App\Controllers;

use App\Core\Database;

class AuthController
{
    public function login(): void
    {
        require __DIR__ . '/../Views/login.php';
    }

    public function authenticate(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Completá todos los campos.';
            header('Location: /login');
            exit;
        }

        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND active = 1 LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Credenciales incorrectas.';
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: /admin');
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
