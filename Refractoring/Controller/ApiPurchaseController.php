<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/HistoriqueModel.php';
require_once __DIR__ . '/../Model/MembreModel.php';
require_once __DIR__ . '/../Model/AdminModel.php';

$historiqueModel = new HistoriqueModel($db);
$membreModel = new MembreModel($db);
$adminModel = new AdminModel($db);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if (empty($_SESSION['userid'])) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        exit();
    }

    if (!$membreModel->check_admin_permission($_SESSION['userid'])) {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden"]);
        exit();
    }

    $permissions = $adminModel->getUserPermissions($_SESSION['userid']);
    if (empty($permissions['p_boutique'])) {
        http_response_code(403);
        echo json_encode(["error" => "Forbidden"]);
        exit();
    }

    if ($method === 'GET') {
        echo json_encode($historiqueModel->getAllPurchases());
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Methode non autorisee"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}

exit();
