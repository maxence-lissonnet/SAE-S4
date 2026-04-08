<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Sécurité : On s'assure que l'utilisateur est bien connecté
if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}

require_once __DIR__ . '/../Model/UserModel.php';

$userModel = new UserModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account_valid']) && $_POST['delete_account_valid'] === 'true') {

        $userModel->deleteAccount($_SESSION["userid"]);

        session_destroy();
        header("Location: index.php");
        exit();
    }
}

require_once __DIR__ . '/../Vue/DeleteAccountVue.php';
