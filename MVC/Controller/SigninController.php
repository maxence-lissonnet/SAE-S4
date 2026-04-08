<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../Model/UserModel.php';

$userModel = new UserModel($db);

$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mail = htmlspecialchars(trim($_POST['mail']));
    $password = htmlspecialchars(trim($_POST['password']));
    $password_verif = htmlspecialchars(trim($_POST['password_verif']));

    $fname = !empty($_POST['fname']) ? htmlspecialchars(trim($_POST['fname'])) : "N/A";
    $lname = !empty($_POST['lname']) ? htmlspecialchars(trim($_POST['lname'])) : "N/A";

    if ($password !== $password_verif) {
        $errorMessage = "Les mots de passe ne correspondent pas.";
    } elseif ($userModel->emailExists($mail)) {
        $errorMessage = "Utilisateur déjà présent.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userModel->createUser($lname, $fname, $mail, $hashedPassword, 'defaultPP.png');

        header("Location: index.php?page=login");
        exit();
    }
}

require_once __DIR__ . '/../Vue/SigninVue.php';
