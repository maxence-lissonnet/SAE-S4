<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/EvenementModel.php';

$eventModel = new EvenementModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                $event = $eventModel->get_event_by_id($id);
                if (!$event) throw new Exception("Événement introuvable.");
                echo json_encode($event);
            } else {
                echo json_encode($eventModel->getAllEvents());
            }
            break;

        // --- CRÉER ---
        case 'POST':
            $newId = $eventModel->createEmptyEvent();
            // Ton fichier events.js ligne 153 attend exactement `id_evenement` en retour
            echo json_encode(["id_evenement" => $newId]);
            break;

        // --- METTRE À JOUR (Données) ---
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            // Le JS envoie `reductions` en true/false (via getToggleStatus). On convertit en 1 ou 0.
            $reductions = $input['reductions'] ? 1 : 0;

            $eventModel->updateEvent(
                $id,
                $input['nom'],
                $input['description'],
                $input['xp'],
                $input['places'],
                $input['prix'],
                $input['lieu'],
                $input['date'],
                $reductions
            );
            echo json_encode(["message" => "Événement mis à jour avec succès"]);
            break;

        // --- METTRE À JOUR (Image) ---
        case 'PATCH':
            if (!$id) throw new Exception("ID manquant.");

            $fileData = file_get_contents('php://input');
            if (empty($fileData)) throw new Exception("Aucune image reçue.");

            // On sauvegarde l'image
            $filename = uniqid('event_') . '.png';
            $filepath = __DIR__ . '/../api/files/' . $filename;
            file_put_contents($filepath, $fileData);

            $eventModel->updateEventImage($id, $filename);
            echo json_encode(["message" => "Image mise à jour"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");
            $eventModel->deleteItemsByEventId($id); // ATTENTION : C'est deleteEvent() et pas deleteItem()
            $eventModel->deleteEvent($id);
            echo json_encode(["message" => "Événement supprimé"]);
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
