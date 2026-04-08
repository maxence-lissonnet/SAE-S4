<?php
require_once __DIR__ . '/../Model/AdminModel.php';
require_once __DIR__ . '/../Model/MembreModel.php';

if (!isset($_SESSION['userid'])) {
    header('Location: index.php?page=login');
    exit;
}

$adminModel = new AdminModel($db);
$membreModel = new MembreModel($db);
$userId = $_SESSION['userid'];
$panel = $_GET['panel'] ?? 'chat';

if (!$membreModel->check_admin_permission($userId)) {
    $panel = 'unauthorized';
} else {
    $permissions = $adminModel->getUserPermissions($userId);
    if (!$adminModel->isPanelAuthorized($panel, $permissions)) {
        $panel = 'unauthorized';
    }
}

$panelFile = __DIR__ . '/../Vue/Admin/panels/' . $panel . '.html';

if (!is_file($panelFile)) {
    http_response_code(404);
    echo 'Panel not found';
    return;
}

$panelHtml = file_get_contents($panelFile);

if ($panelHtml === false) {
    http_response_code(500);
    echo 'Unable to load panel';
    return;
}

if (stripos($panelHtml, '<base ') === false) {
    $panelHtml = preg_replace(
        '/<head([^>]*)>/i',
        '<head$1>' . PHP_EOL . '    <base href="Asset/Admin/panels/">',
        $panelHtml,
        1
    );
}

header('Content-Type: text/html; charset=UTF-8');
echo $panelHtml;
