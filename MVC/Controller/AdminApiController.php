<?php
require_once __DIR__ . '/../Model/AdminModel.php';
require_once __DIR__ . '/../Model/MembreModel.php';

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    return;
}

$resourceMap = [
    'accounting' => ['file' => 'accounting.php', 'permission' => 'p_comptabilite'],
    'event' => ['file' => 'event.php', 'permission' => 'p_evenement'],
    'grade' => ['file' => 'grade.php', 'permission' => 'p_grade'],
    'item' => ['file' => 'item.php', 'permission' => 'p_boutique'],
    'logs' => ['file' => 'logs.php', 'permission' => 'p_log'],
    'meeting' => ['file' => 'meeting.php', 'permission' => 'p_reunion'],
    'news' => ['file' => 'news.php', 'permission' => 'p_actualite'],
    'permissions' => ['file' => 'permissions.php', 'permission' => 'p_role'],
    'purchase' => ['file' => 'purchase.php', 'permission' => 'p_boutique'],
    'role' => ['file' => 'role.php', 'permission' => 'p_role'],
    'userole' => ['file' => 'userole.php', 'permission' => 'p_utilisateur'],
    'users' => ['file' => 'users.php', 'permission' => 'p_utilisateur'],
];

$resource = strtolower(trim((string) ($_GET['resource'] ?? '')));

if (!isset($resourceMap[$resource])) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown admin resource']);
    return;
}

$membreModel = new MembreModel($db);
if (!$membreModel->check_admin_permission($_SESSION['userid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    return;
}

$adminModel = new AdminModel($db);
$permissions = $adminModel->getUserPermissions($_SESSION['userid']);
$requiredPermission = $resourceMap[$resource]['permission'];

if (!empty($requiredPermission) && empty($permissions[$requiredPermission])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    return;
}

$legacyApiDirectory = realpath(__DIR__ . '/../../Sans mvc/api');
$legacyApiFile = $legacyApiDirectory
    ? $legacyApiDirectory . DIRECTORY_SEPARATOR . $resourceMap[$resource]['file']
    : false;

if (!$legacyApiDirectory || !$legacyApiFile || !is_file($legacyApiFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Legacy admin API not found']);
    return;
}

$previousWorkingDirectory = getcwd();
$previousErrorReporting = error_reporting();

error_reporting($previousErrorReporting & ~E_WARNING & ~E_NOTICE);
chdir($legacyApiDirectory);

try {
    require $legacyApiFile;
} finally {
    if ($previousWorkingDirectory !== false) {
        chdir($previousWorkingDirectory);
    }
    error_reporting($previousErrorReporting);
}
