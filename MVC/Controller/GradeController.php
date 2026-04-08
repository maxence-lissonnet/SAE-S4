<?php
// Importer les fichiers
require_once __DIR__ . "/../Model/GradeModel.php";

$gradeModel = new GradeModel($db);
$products = $gradeModel->getAllGrades();

$defaultGradeImage = 'Asset/image/grade.webp';
$apiFilesDirectory = __DIR__ . '/../API/files/';

$resolveGradeImage = static function ($imagePath) use ($defaultGradeImage, $apiFilesDirectory) {
    $imagePath = trim((string) $imagePath);

    if ($imagePath === '') {
        return $defaultGradeImage;
    }

    if (preg_match('#^https?://#i', $imagePath)) {
        return $imagePath;
    }

    $relativePath = ltrim(str_replace('\\', '/', $imagePath), '/');
    $fullPath = $apiFilesDirectory . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    return is_file($fullPath) ? 'API/files/' . $relativePath : $defaultGradeImage;
};

if (!empty($products)) {
    foreach ($products as &$product) {
        $product['owned'] = false;
        if (!empty($_SESSION['userid'])) {
            $product['owned'] = $gradeModel->checkUserOwnership($product['id_grade'], $_SESSION['userid']);
        }
        $product['resolved_image_grade'] = $resolveGradeImage($product['image_grade'] ?? null);
    }
}

require_once __DIR__ . "/../Vue/GradeVue.php";
