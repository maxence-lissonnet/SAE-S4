<?php
// Importer les fichiers
require_once __DIR__ . "/../Model/GradeModel.php";

$gradeModel = new GradeModel($db);
$products = $gradeModel->getAllGrades();

if (!empty($products)) {
    foreach ($products as &$product) {
        $product['owned'] = false;
        if (!empty($_SESSION['userid'])) {
            $product['owned'] = $gradeModel->checkUserOwnership($product['id_grade'], $_SESSION['userid']);
        }
    }
}

require_once __DIR__ . "/../Vue/GradeVue.php";
