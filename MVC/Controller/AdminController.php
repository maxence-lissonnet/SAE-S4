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

if (!$membreModel->check_admin_permission($userId)) {
    http_response_code(403);
    require_once __DIR__ . '/../Vue/Admin/AdminUnauthorizedVue.php';
    return;
}

$permissions = $adminModel->getUserPermissions($userId);
$panels = $adminModel->getAuthorizedPanels($permissions);
$currentPanel = $_GET['panel'] ?? ($panels[0]['key'] ?? 'unauthorized');

if (!$adminModel->isPanelAuthorized($currentPanel, $permissions)) {
    $currentPanel = $panels[0]['key'] ?? 'unauthorized';
}

require_once __DIR__ . '/../Vue/Admin/AdminVue.php';
