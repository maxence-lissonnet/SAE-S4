<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/ReunionModel.php';

$reunionModel = new ReunionModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                $meeting = $reunionModel->getMeetingById($id);
                if (!$meeting) throw new Exception("Réunion introuvable.");
                echo json_encode($meeting);
            } else {
                echo json_encode($reunionModel->getAllMeetings());
            }
            break;

        // --- CRÉER (Upload du PDF) ---
        case 'POST':
            if (!isset($_POST['date']) || !isset($_FILES['file'])) {
                throw new Exception("Date ou fichier manquants.");
            }

            $date = $_POST['date'];
            $file = $_FILES['file'];

            // On vérifie que c'est bien un PDF
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (strtolower($ext) !== 'pdf') {
                throw new Exception("Le fichier doit être un PDF.");
            }

            // Génère un nom unique
            $filename = uniqid('reunion_') . '.pdf';
            $filepath = __DIR__ . '/../api/files/' . $filename;

            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception("Erreur lors de l'enregistrement du PDF.");
            }

            // On ajoute en base et on renvoie l'ID
            $newId = $reunionModel->addMeeting($date, $filename);
            echo json_encode(["id_reunion" => $newId]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");

            // On trouve le fichier pour le supprimer du disque
            $meetingData = $reunionModel->getMeetingById($id);
            if ($meetingData && !empty($meetingData['fichier_reunion'])) {
                $physPath = __DIR__ . '/../api/files/' . $meetingData['fichier_reunion'];
                if (file_exists($physPath)) {
                    unlink($physPath);
                }
            }

            // On supprime de la base
            $reunionModel->deleteMeeting($id);
            echo json_encode(["message" => "Réunion supprimée"]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Méthode non autorisée"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}

exit();
