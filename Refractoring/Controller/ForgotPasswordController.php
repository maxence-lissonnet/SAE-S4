<?php
require_once __DIR__ . '/../Model/UserModel.php';

$userModel = new UserModel($db);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = trim($_POST['mail'] ?? '');
    $newPassword = trim($_POST['newMdp'] ?? '');
    $newPasswordVerif = trim($_POST['newMdpVerif'] ?? '');

    if ($mail === '' || $newPassword === '' || $newPasswordVerif === '') {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!$userModel->emailExists($mail)) {
        $error = 'Aucun compte n est associe a cette adresse mail.';
    } elseif ($newPassword !== $newPasswordVerif) {
        $error = 'Les nouveaux mots de passe ne correspondent pas.';
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $userModel->updatePasswordByEmail($mail, $hashedPassword);

        $_SESSION['message'] = 'Mot de passe reinitialise avec succes. Vous pouvez maintenant vous connecter.';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php?page=login');
        exit;
    }
}

require_once __DIR__ . '/../Vue/ForgotPasswordVue.php';
