<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/ComptabiliteModel.php';

$comptaModel = new ComptabiliteModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            echo json_encode($comptaModel->getAllFiles());
            break;

        // --- CRÉER (Upload d'un fichier) ---
        case 'POST':
            // Avec un FormData, PHP remplit $_POST et $_FILES automatiquement
            if (!isset($_POST['nom']) || !isset($_POST['date']) || !isset($_FILES['file'])) {
                throw new Exception("Données ou fichier manquants.");
            }

            $nom = $_POST['nom'];
            $date = $_POST['date'];
            $file = $_FILES['file'];

            // Récupère l'extension du fichier (ex: pdf, png)
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            // Génère un nom unique pour éviter d'écraser un fichier existant
            $filename = uniqid('compta_') . '.' . $ext;
            $filepath = __DIR__ . '/../api/files/' . $filename;

            // Déplace le fichier temporaire vers le dossier final
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception("Erreur lors de l'enregistrement du fichier sur le serveur.");
            }

            $comptaModel->addFile($nom, $date, $filename);
            echo json_encode(["message" => "Fichier uploadé avec succès"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");

            // 1. On trouve le nom du fichier pour le supprimer du serveur
            $fileData = $comptaModel->getFileById($id);
            if ($fileData && !empty($fileData['url_comptabilite'])) {
                $physPath = __DIR__ . '/../api/files/' . $fileData['url_comptabilite'];
                if (file_exists($physPath)) {
                    unlink($physPath); // Supprime physiquement le fichier
                }
            }

            // 2. On supprime la ligne dans la base de données
            $comptaModel->deleteFile($id);
            echo json_encode(["message" => "Fichier supprimé"]);
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
