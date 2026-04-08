<?php

// Importer les fichiers
require_once __DIR__ . "/../Model/GradeModel.php";
require_once __DIR__ . '/files_save.php';

$gradeSubscriptionModel = new GradeModel($db);

// Sécurité
$isLoggedIn = isset($_SESSION["userid"]);
if (!$isLoggedIn) {
    header("Location: index.php?page=login"); // CORRECTION DU ROUTEUR
    exit;
}

$userid = $_SESSION["userid"];

// Vérification que l'ID du grade est fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?page=grade"); // CORRECTION DU ROUTEUR
    exit;
}
$id_grade = intval($_GET['id']);

// On récupère les informations du grade
$grade = $gradeSubscriptionModel->getGradeById($id_grade);

// Vérifie que le grade existe
if (empty($grade)) {
    $_SESSION['message'] = "Le grade sélectionné n'existe pas.";
    $_SESSION['message_type'] = "error";
    header("Location: index.php?page=grade"); // CORRECTION DU ROUTEUR
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

        // CORRECTION DU [0] ICI : On utilise $grade['prix_grade']
        $gradeSubscriptionModel->subscribeToGrade($userid, $id_grade, $grade['prix_grade'], $mode_paiement);

        $_SESSION['message'] = "Adhésion au grade réussie !";
        $_SESSION['message_type'] = "success";
        header("Location: index.php?page=grade"); // CORRECTION DU ROUTEUR
        exit;
    }
}

// Appel de la vue
require_once __DIR__ . '/../Vue/Grade_subscriptionVue.php';
