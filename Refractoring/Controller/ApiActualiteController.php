<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/ActualiteModel.php';

$newsModel = new ActualiteModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                // On réutilise ta fonction existante
                $news = $newsModel->get_actualite_by_id($id);
                if (!$news) throw new Exception("Actualité introuvable.");
                echo json_encode($news);
            } else {
                echo json_encode($newsModel->getAllNewsAdmin());
            }
            break;

        // --- CRÉER ---
        case 'POST':
            $newId = $newsModel->createEmptyNews();
            echo json_encode(["id_actualite" => $newId]);
            break;

        // --- METTRE À JOUR (Données) ---
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            $newsModel->updateNews(
                $id,
                $input['name'],
                $input['description'],
                $input['date']
            );
            echo json_encode(["message" => "Actualité mise à jour avec succès"]);
            break;

        // --- METTRE À JOUR (Image) ---
        case 'PATCH':
            if (!$id) throw new Exception("ID manquant.");

            $fileData = file_get_contents('php://input');
            if (empty($fileData)) throw new Exception("Aucune image reçue.");

            $filename = uniqid('news_') . '.png';
            $filepath = __DIR__ . '/../api/files/' . $filename;
            file_put_contents($filepath, $fileData);

            $newsModel->updateNewsImage($id, $filename);
            echo json_encode(["message" => "Image mise à jour"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");
            $newsModel->deleteNews($id);
            echo json_encode(["message" => "Actualité supprimée"]);
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
