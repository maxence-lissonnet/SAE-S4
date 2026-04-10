<?php
require_once __DIR__ . '/../Model/GradeModel.php';

$gradeModel = new GradeModel($db);
$products = $gradeModel->getAllGrades();

$defaultGradeImage = 'Asset/image/grade.webp';
$apiFilesDirectory = __DIR__ . '/../API/files/';
$legacyGradeImageMap = [
    'grade_diamand.jpg' => 'API/files/grade_diamant.png',
    'grade_fer.jpg' => 'API/files/grade_fer.webp',
    'grade_or.jpg' => 'API/files/grade_or.png',
];

$resolveGradeImage = static function ($imagePath) use ($defaultGradeImage, $apiFilesDirectory, $legacyGradeImageMap) {
    $imagePath = trim((string) $imagePath);

    if ($imagePath === '') {
        return $defaultGradeImage;
    }

    if (preg_match('#^https?://#i', $imagePath)) {
        $basename = basename((string) parse_url($imagePath, PHP_URL_PATH));
        return $legacyGradeImageMap[$basename] ?? $defaultGradeImage;
    }

    $relativePath = ltrim(str_replace('\\', '/', $imagePath), '/');
    $fullPath = $apiFilesDirectory . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (is_file($fullPath)) {
        return 'API/files/' . $relativePath;
    }

    return $legacyGradeImageMap[basename($relativePath)] ?? $defaultGradeImage;
};

if (!empty($products)) {
    foreach ($products as &$product) {
        $product['owned'] = false;
        if (!empty($_SESSION['userid'])) {
            $product['owned'] = $gradeModel->checkUserOwnership($product['id_grade'], $_SESSION['userid']);
        }
        $product['resolved_image_grade'] = $resolveGradeImage($product['image_grade'] ?? null);
    }
    unset($product);
}

require_once __DIR__ . '/../Vue/GradeVue.php';
