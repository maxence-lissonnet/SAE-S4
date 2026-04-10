<?php
// On indique au navigateur qu'on ne renvoie pas du HTML, mais du JSON
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Model/ItemModel.php';

// Initialisation (assure-toi que $db existe depuis ton index.php)
$itemModel = new ItemModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE (Un ou tous les articles) ---
        case 'GET':
            if ($id) {
                $item = $itemModel->getItemById($id);
                if (!$item) throw new Exception("Article introuvable.");
                echo json_encode($item);
            } else {
                echo json_encode($itemModel->getAllItems());
            }
            break;

        // --- CRÉER (Un article vide) ---
        case 'POST':
            $newId = $itemModel->createEmptyItem();
            // Le JS (boutique.js ligne 129) attend spécifiquement l'ID en retour
            echo json_encode(["id_article" => $newId]);
            break;

        // --- METTRE À JOUR (Les infos) ---
        case 'PUT':
            // Avec PUT, PHP ne remplit pas $_POST, on doit lire le flux directement
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            // Le JS envoie un booléen (true/false) pour la réduction, on le convertit en 1/0 pour SQL
            $reduction = $input['reduction'] ? 1 : 0;

            $itemModel->updateItem($id, $input['name'], $input['price'], $input['categorie'], $input['stocks'], $input['xp'], $reduction);
            echo json_encode(["message" => "Article mis à jour avec succès"]);
            break;

        // --- METTRE À JOUR L'IMAGE ---
        case 'PATCH':
            if (!$id) throw new Exception("ID manquant.");

            // Le JS envoie le fichier brut
            $fileData = file_get_contents('php://input');
            if (empty($fileData)) throw new Exception("Aucune image reçue.");

            // Sauvegarde de l'image (tu peux adapter le chemin selon ton projet)
            $filename = uniqid('item_') . '.png';
            $filepath = __DIR__ . '/../api/files/' . $filename;
            file_put_contents($filepath, $fileData);

            $itemModel->updateItemImage($id, $filename);
            echo json_encode(["message" => "Image mise à jour"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");
            $itemModel->deleteItem($id);
            echo json_encode(["message" => "Article supprimé"]);
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
