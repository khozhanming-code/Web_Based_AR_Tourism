<?php
declare(strict_types=1);
require_once __DIR__ . '/database.php';

function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419); exit('Invalid security token.');
    }
}
function redirect(string $path): never { header('Location: ' . $path); exit; }
function flash(string $type, string $message): void { $_SESSION['flash'][] = [$type, $message]; }
function flashes(): array { $items = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $items; }
function slug(string $value): string { return trim((string)preg_replace('/[^a-z0-9]+/i', '-', strtolower($value)), '-'); }
function youtube_id(?string $url): ?string {
    if (!$url) return null;
    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/))([A-Za-z0-9_-]{6,})~', $url, $m)) return $m[1];
    return null;
}
function upload_image(array $file, string $folder = 'images'): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if (($file['error'] ?? 1) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 8 * 1024 * 1024) throw new RuntimeException('Image upload failed or exceeds 8 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) throw new RuntimeException('Only valid JPG, PNG, or WebP images are allowed.');
    $directory = UPLOAD_DIR . $folder . '/'; if (!is_dir($directory)) mkdir($directory, 0755, true);
    $name = bin2hex(random_bytes(12)) . '.' . $allowed[$mime]; $relative = 'assets/uploads/' . $folder . '/' . $name;
    if (!move_uploaded_file($file['tmp_name'], $directory . $name)) throw new RuntimeException('Could not save uploaded image.');
    return $relative;
}
function admin_required(): void { if (empty($_SESSION['admin_id'])) redirect('login.php'); }
function log_activity(string $action, string $description): void {
    $stmt = db()->prepare('INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_SESSION['admin_id'] ?? null, $action, $description, $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
}
