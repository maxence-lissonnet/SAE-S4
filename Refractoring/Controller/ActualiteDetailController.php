<?php
$actualiteModel = new ActualiteModel($db);

$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int) $_GET['id'] : null;

if (!$id) {
    header('Location: index.php?page=actualite');
    exit;
}

$actualite = $actualiteModel->get_actualite_by_id($id);

if (!$actualite) {
    header('Location: index.php?page=actualite');
    exit;
}

$date_formattee = date('d/m/Y', strtotime($actualite['date_actualite']));
$resolvedImageActualite = 'Asset/image/event.jpg';

if (!empty($actualite['image_actualite'])) {
    $imagePath = trim((string) $actualite['image_actualite']);

    if (preg_match('#^https?://#i', $imagePath)) {
        $resolvedImageActualite = $imagePath;
    } else {
        $relativePath = ltrim(str_replace('\\', '/', $imagePath), '/');
        $fullPath = __DIR__ . '/../API/files/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        if (is_file($fullPath)) {
            $resolvedImageActualite = 'API/files/' . $relativePath;
        }
    }
}

require_once __DIR__ . '/../Vue/ActualiteDetailVue.php';
