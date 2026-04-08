<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Model/GradeModel.php';

$gradeModel = new GradeModel($db);

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        // --- LIRE ---
        case 'GET':
            if ($id) {
                $grade = $gradeModel->getGradeById($id); // On réutilise ta fonction existante !
                if (!$grade) throw new Exception("Grade introuvable.");
                echo json_encode($grade);
            } else {
                echo json_encode($gradeModel->getAllGradesAdmin());
            }
            break;

        // --- CRÉER ---
        case 'POST':
            $newId = $gradeModel->createEmptyGrade();
            echo json_encode(["id_grade" => $newId]);
            break;

        // --- METTRE À JOUR (Données) ---
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$id || !$input) throw new Exception("Données invalides.");

            $gradeModel->updateGrade(
                $id,
                $input['name'],
                $input['description'],
                $input['price'],
                $input['reduction']
            );
            echo json_encode(["message" => "Grade mis à jour avec succès"]);
            break;

        // --- METTRE À JOUR (Image) ---
        case 'PATCH':
            if (!$id) throw new Exception("ID manquant.");

            $fileData = file_get_contents('php://input');
            if (empty($fileData)) throw new Exception("Aucune image reçue.");

            $filename = uniqid('grade_') . '.webp';
            $filepath = __DIR__ . '/../api/files/' . $filename;
            file_put_contents($filepath, $fileData);

            $gradeModel->updateGradeImage($id, $filename);
            echo json_encode(["message" => "Image mise à jour"]);
            break;

        // --- SUPPRIMER ---
        case 'DELETE':
            if (!$id) throw new Exception("ID manquant.");
            $gradeModel->deleteGrade($id);
            echo json_encode(["message" => "Grade supprimé"]);
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
