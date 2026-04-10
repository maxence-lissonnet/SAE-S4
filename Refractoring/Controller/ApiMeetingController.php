<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Model/ReunionModel.php';
require_once __DIR__ . '/../Model/MembreModel.php';
require_once __DIR__ . '/../Model/AdminModel.php';

$reunionModel = new ReunionModel($db);
$membreModel = new MembreModel($db);
$adminModel = new AdminModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$userId = isset($_SESSION['userid']) ? (int) $_SESSION['userid'] : null;

try {
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    if (
        !$membreModel->check_admin_permission($userId) ||
        empty($adminModel->getUserPermissions($userId)['p_reunion'])
    ) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit();
    }

    switch ($method) {
        case 'GET':
            if ($id) {
                $meeting = $reunionModel->getMeetingById($id);
                if (!$meeting) {
                    throw new Exception('Reunion introuvable.');
                }

                echo json_encode($meeting);
            } else {
                echo json_encode($reunionModel->getAllMeetings());
            }
            break;

        case 'POST':
            if (empty($_POST['date']) || !isset($_FILES['file'])) {
                throw new Exception('Date ou fichier manquants.');
            }

            if (!isset($_FILES['file']['error']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erreur lors de l upload du fichier.');
            }

            $file = $_FILES['file'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($extension !== 'pdf' || $mimeType !== 'application/pdf') {
                throw new Exception('Le fichier doit etre un PDF.');
            }

            $filename = uniqid('reunion_', true) . '.pdf';
            $filepath = __DIR__ . '/../API/files/' . $filename;

            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Erreur lors de l enregistrement du PDF.');
            }

            $newId = $reunionModel->addMeeting($_POST['date'], $filename, $userId);
            http_response_code(201);
            echo json_encode(['id_reunion' => $newId]);
            break;

        case 'DELETE':
            if (!$id) {
                throw new Exception('ID manquant.');
            }

            $meetingData = $reunionModel->getMeetingById($id);
            if (!$meetingData) {
                throw new Exception('Reunion introuvable.');
            }

            if (!empty($meetingData['fichier_reunion'])) {
                $physPath = __DIR__ . '/../API/files/' . $meetingData['fichier_reunion'];
                if (is_file($physPath)) {
                    unlink($physPath);
                }
            }

            $reunionModel->deleteMeeting($id);
            echo json_encode(['message' => 'Reunion supprimee']);
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
