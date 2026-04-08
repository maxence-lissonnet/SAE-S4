<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/UserModel.php';

$userModel = new UserModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                $user = $userModel->getUserById($id);
                if (!$user) throw new Exception("Utilisateur introuvable.");
                echo json_encode($user);
            } else {
                echo json_encode($userModel->getAllUsers());
            }
            break;

        // --- CRÉER ---
        case 'POST':
            $newId = $userModel->createEmptyUser();
            // Le JS (users.js) attend `id_membre`
            echo json_encode(["id_membre" => $newId]);
            break;

        // --- METTRE À JOUR (Données textuelles) ---
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            // On utilise la nouvelle fonction spécifique à l'admin
            $userModel->updateUserAdmin(
                $id,
                $input['name'],
                $input['firstname'],
                $input['email'],
                $input['tp'],
                $input['xp'],
                $input['roles']
            );
            echo json_encode(["message" => "Utilisateur mis à jour avec succès"]);
            break;

        // --- METTRE À JOUR (Image) ---
        case 'PATCH':
            if (!$id) throw new Exception("ID manquant.");

            $fileData = file_get_contents('php://input');
            if (empty($fileData)) throw new Exception("Aucune image reçue.");

            $filename = uniqid('user_') . '.png';
            $filepath = __DIR__ . '/../api/files/' . $filename;
            file_put_contents($filepath, $fileData);

            // On RÉUTILISE ta fonction existante !
            $userModel->updateProfilePicture($filename, $id);
            echo json_encode(["message" => "Image mise à jour"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");

            // On RÉUTILISE ta fonction existante (qui gère déjà tout !)
            $userModel->deleteAccount($id);
            echo json_encode(["message" => "Utilisateur supprimé"]);
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
