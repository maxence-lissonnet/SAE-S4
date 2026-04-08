<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/LogModel.php';

$logModel = new LogModel();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        echo json_encode(["logs" => $logModel->getLogs()]);
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}

exit();
