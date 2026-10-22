<?php
require_once __DIR__ . '/../includes/functions.php';
$email = $argv[1] ?? 'admin@example.com'; $password = $argv[2] ?? 'ChangeMe123!';
$stmt = db()->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?) ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash), status="active"');
$stmt->execute(['System Administrator', $email, password_hash($password, PASSWORD_DEFAULT)]);
echo "Admin ready: {$email}\n";
