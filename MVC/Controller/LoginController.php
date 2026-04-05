<?php
// LoginController.php
require_once __DIR__ . "/../Model/MembreModel.php";
$membreModel = new MembreModel($db);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = trim($_POST['mail']);
    $password = trim($_POST['password']);

    $user = $membreModel->get_membre_by_email($mail);

    if ($user) {
        // Vérification du mot de passe (gestion du NULL pour les anciens comptes si besoin)
        $password_ok = ($user['password_membre'] === null && $password === "")
            ? true
            : password_verify($password, $user['password_membre']);

        if ($password_ok) {
            // Initialisation de la session
            $_SESSION['userid'] = $user["id_membre"];

            // Vérification Admin
            if ($membreModel->check_admin_permission($user["id_membre"])) {
                $_SESSION["isAdmin"] = true;
            }

            header("Location: index.php");
            exit;
        }
    }
    $error = "Erreur dans les informations de connexion.";
}

require_once __DIR__ . "/../Vue/LoginVue.php";
