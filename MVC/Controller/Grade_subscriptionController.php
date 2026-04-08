<?php

// Importer les fichiers
require_once __DIR__ . "/../Model/GradeModel.php";

$gradeSubscriptionModel = new GradeModel($db);
require_once 'files_save.php';

// Connexion à la base de données

$isLoggedIn = isset($_SESSION["userid"]);
if (!$isLoggedIn) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION["userid"];


// Vérification que l'ID du grade est fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: grade.php");
    exit;
}
$id_grade = intval($_GET['id']);


// On récupère les informations du grade
$grade = $gradeSubscriptionModel->getGradeById($id_grade);


// Vérifie que le grade existe
if (empty($grade)) {
    $_SESSION['message'] = "Le grade sélectionné n'existe pas.";
    $_SESSION['message_type'] = "error";
    header("Location: grade.php");
    exit;
}

// Vérifie si l'utilisateur possède déjà un grade
$currentGrade = $gradeSubscriptionModel->getCurrentAdhesion($userid);

// Gestion de l'achat d'un grade
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
        $mode_paiement = $_POST['mode_paiement'];
        if (!empty($currentGrade)) {
            $gradeSubscriptionModel->unsubscribeFromGrade($userid);
        }
        $gradeSubscriptionModel->subscribeToGrade($userid, $id_grade, $grade[0]['prix_grade'], $mode_paiement);

        $_SESSION['message'] = "Adhésion au grade réussie !";
        $_SESSION['message_type'] = "success";
        header("Location: grade.php");
        exit;
    } else {
    }
}
