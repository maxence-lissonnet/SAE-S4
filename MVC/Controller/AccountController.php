<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}

require_once __DIR__ . '/files_save.php';
require_once __DIR__ . '/../Model/UserModel.php';

$accountModel = new UserModel($db);

$userId = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deconnexion']) && $_POST['deconnexion'] === 'true') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $infoUser = $accountModel->getUserInfo($userId);
    $fileName = saveImage();

    if ($fileName !== null) {
        if (!empty($infoUser['pp_membre'])) {
            deleteFile($infoUser['pp_membre']); // Ta fonction dans files_save.php
        }
        $accountModel->updateProfilePicture($fileName, $userId);
        $_SESSION['message'] = "Mise à jour de la photo de profil réussie !";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Erreur : veuillez vérifier le fichier envoyé.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: index.php?page=account");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['lastName'], $_POST['mail'])) {
    $currentUserData = $accountModel->getUserInfo($userId);

    if (!empty($currentUserData)) {
        $name = empty($_POST['name']) ? $currentUserData['prenom_membre'] : htmlspecialchars($_POST['name']);
        $lastName = empty($_POST['lastName']) ? $currentUserData['nom_membre'] : htmlspecialchars($_POST['lastName']);
        $mail = empty($_POST['mail']) ? $currentUserData['email_membre'] : htmlspecialchars($_POST['mail']);
        $tp = isset($_POST['tp']) && !empty($_POST['tp']) ? htmlspecialchars($_POST['tp']) : $currentUserData['tp_membre'];

        if ($accountModel->checkEmailExistsForOtherUser($mail, $userId)) {
            $_SESSION['message'] = "Les modifications n'ont pas pu être effectuées car l'adresse e-mail est déjà utilisée par un autre compte.";
            $_SESSION['message_type'] = "error";
        } else {
            $accountModel->updatePersonalInfo($name, $lastName, $mail, $tp, $userId);
            $_SESSION['message'] = "Vos informations ont été mises à jour avec succès !";
            $_SESSION['message_type'] = "success";
        }
    }
    header("Location: index.php?page=account");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mdp'], $_POST['newMdp'], $_POST['newMdpVerif'])) {
    $currentPassword = htmlspecialchars(trim($_POST['mdp']));
    $newPassword = htmlspecialchars(trim($_POST['newMdp']));
    $newPasswordVerif = htmlspecialchars(trim($_POST['newMdpVerif']));

    $dbPassword = $accountModel->getUserPassword($userId);

    $password_ok = ($dbPassword == NULL && $currentPassword == "") ? true : password_verify($currentPassword, $dbPassword);

    if ($password_ok) {
        if ($newPassword === $newPasswordVerif) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $accountModel->updatePassword($hashedPassword, $userId);
            $_SESSION['message'] = "Mot de passe mis à jour avec succès !";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Les nouveaux mots de passe ne correspondent pas.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Mot de passe actuel incorrect.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: index.php?page=account");
    exit();
}

$infoUser = $accountModel->getUserInfo($userId);
$viewAll = isset($_GET['viewAll']) && $_GET['viewAll'] === '1';
$historiqueAchats = $accountModel->getPurchaseHistory($userId, $viewAll);

require_once __DIR__ . '/../Vue/AccountVue.php';
