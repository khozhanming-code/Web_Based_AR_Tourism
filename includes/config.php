<?php
declare(strict_types=1);

const APP_NAME = 'AR Tourism Explorer';
const DB_HOST = '127.0.0.1';
const DB_NAME = 'ar_tourism';
const DB_USER = 'root';
const DB_PASS = '';
const UPLOAD_DIR = __DIR__ . '/../assets/uploads/';
const TARGET_DIR = __DIR__ . '/../assets/ar-targets/';

date_default_timezone_set('Asia/Manila');
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
$documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
$projectRoot = realpath(__DIR__ . '/..') ?: '';
$basePath = $documentRoot && str_starts_with($projectRoot, $documentRoot)
    ? str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)))
    : '';
define('BASE_URL', (($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim($basePath, '/') . '/'));

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => $https]);
    session_start();
}
