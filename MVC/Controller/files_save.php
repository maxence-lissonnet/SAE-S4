<?php
function generateUUID()
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return bin2hex($data);
}

function getFilesDirectory(): string
{
    return __DIR__ . '/../API/files/';
}

function normalizeStoredFilename(string $fileName): string
{
    return ltrim(str_replace('\\', '/', $fileName), '/');
}

function saveFile(): string | null
{
    $name = generateUUID() . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $targetDirectory = getFilesDirectory();

    if (!is_dir($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDirectory . $name)) {
        return $name;
    }

    return null;
}

function saveImage(): string | null
{
    if (!isset($_FILES['file']) || $_FILES['file']['tmp_name'] === '') {
        return null;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($_FILES['file']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($mimeType, $allowedTypes, true)) {
        return null;
    }

    return saveFile();
}

function deleteFile(string $fileName): bool
{
    $normalizedFileName = normalizeStoredFilename($fileName);
    $fullPath = getFilesDirectory() . str_replace('/', DIRECTORY_SEPARATOR, $normalizedFileName);

    if (file_exists($fullPath)) {
        unlink($fullPath);
        return true;
    }

    return false;
}
