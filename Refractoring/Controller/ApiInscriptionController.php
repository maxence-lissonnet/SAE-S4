<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Model/InscriptionModel.php';
require_once __DIR__ . '/../Model/EvenementModel.php';
require_once __DIR__ . '/../Model/MembreModel.php';
require_once __DIR__ . '/../Model/AdminModel.php';

$inscriptionModel = new InscriptionModel($db);
$eventModel = new EvenementModel($db);
$membreModel = new MembreModel($db);
$adminModel = new AdminModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$currentUserId = isset($_SESSION['userid']) ? (int) $_SESSION['userid'] : null;
$memberId = isset($_GET['id_membre']) ? (int) $_GET['id_membre'] : null;
$eventId = isset($_GET['id_evenement']) ? (int) $_GET['id_evenement'] : null;

try {
    if (!$currentUserId) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    if (
        !$membreModel->check_admin_permission($currentUserId) ||
        empty($adminModel->getUserPermissions($currentUserId)['p_evenement'])
    ) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit();
    }

    switch ($method) {
        case 'GET':
            echo json_encode($inscriptionModel->getAllInscriptions());
            break;

        case 'DELETE':
            if (!$memberId || !$eventId) {
                throw new Exception('Identifiants manquants.');
            }

            if (!$eventModel->get_inscription_status($eventId, $memberId)) {
                throw new Exception('Inscription introuvable.');
            }

            $eventModel->unregister_user_from_event($memberId, $eventId);
            echo json_encode(['message' => 'Inscription supprimee']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Methode non autorisee']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

exit();
