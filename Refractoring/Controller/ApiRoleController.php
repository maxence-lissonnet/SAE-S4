<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/RoleModel.php';

$roleModel = new RoleModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                $role = $roleModel->getRoleById($id);
                if (!$role) throw new Exception("Rôle introuvable.");
                echo json_encode($role);
            } else {
                echo json_encode($roleModel->getAllRoles());
            }
            break;

        // --- CRÉER ---
        case 'POST':
            $newId = $roleModel->createEmptyRole();
            echo json_encode(["id_role" => $newId]);
            break;

        // --- METTRE À JOUR ---
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            // Le JS envoie des booleans (true/false). On les convertit tous en 1 ou 0 pour MySQL.
            $permsSQL = [];
            foreach ($input['permissions'] as $key => $value) {
                $permsSQL[$key] = $value ? 1 : 0;
            }

            $roleModel->updateRole($id, $input['name'], $permsSQL);
            echo json_encode(["message" => "Rôle mis à jour avec succès"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");
            $roleModel->deleteRole($id);
            echo json_encode(["message" => "Rôle supprimé"]);
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
