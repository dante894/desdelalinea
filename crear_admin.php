<?php
require_once __DIR__ . '/config/bootstrap.php';

use App\Core\Database;

$db = Database::getInstance();

$email    = 'admin@desdelalinea.com';
$password = password_hash('admin123', PASSWORD_BCRYPT);
$username = 'admin';

// Borrar usuario viejo y crear nuevo
$db->prepare("DELETE FROM users WHERE email = ?")->execute([$email]);
$stmt = $db->prepare("INSERT INTO users (username, email, password, role, active) VALUES (?, ?, ?, 'admin', 1)");
$stmt->execute([$username, $email, $password]);

echo "Usuario creado OK\n";
echo "Email: $email\n";
echo "Password: admin123\n";
