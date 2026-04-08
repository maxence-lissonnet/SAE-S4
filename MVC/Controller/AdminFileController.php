<?php
require_once __DIR__ . '/../Model/MembreModel.php';

if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    echo 'Unauthorized';
    return;
}

$membreModel = new MembreModel($db);
if (!$membreModel->check_admin_permission($_SESSION['userid'])) {
    http_response_code(403);
    echo 'Forbidden';
    return;
}

$requestedFile = trim((string) ($_GET['file'] ?? ''));

if ($requestedFile === '') {
    http_response_code(400);
    echo 'Missing file parameter';
    return;
}

if (preg_match('#^https?://#i', $requestedFile)) {
    $requestedFile = (string) parse_url($requestedFile, PHP_URL_PATH);
}

$requestedFile = ltrim(str_replace('\\', '/', rawurldecode($requestedFile)), '/');

if (
    $requestedFile === ''
    || str_contains($requestedFile, '../')
    || str_contains($requestedFile, '..\\')
    || preg_match('#^[A-Za-z]:#', $requestedFile)
) {
    http_response_code(400);
    echo 'Invalid file path';
    return;
}

$fileCandidates = [
    __DIR__ . '/../../Sans mvc/api/files/' . str_replace('/', DIRECTORY_SEPARATOR, $requestedFile),
    __DIR__ . '/../API/files/' . str_replace('/', DIRECTORY_SEPARATOR, $requestedFile),
];

$filePath = null;

foreach ($fileCandidates as $candidate) {
    if (is_file($candidate)) {
        $filePath = $candidate;
        break;
    }
}

if ($filePath === null) {
    http_response_code(404);
    echo 'File not found';
    return;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($filePath) ?: 'application/octet-stream';

header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
