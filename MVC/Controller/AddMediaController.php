<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) session_start();

// 1. Sécurité : Vérification de la connexion
if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}

require_once __DIR__ . '/../files_save.php';
require_once __DIR__ . '/../Model/GalleryModel.php';

// Si tu passes par ton routeur index.php, la variable $db (PDO) existe déjà.
$galleryModel = new GalleryModel($db);

// 2. Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'], $_POST['eventid'])) {

    // Par sécurité, on force l'utilisation de l'ID de la session plutôt que celui du formulaire POST
    // Cela empêche un utilisateur malveillant de poster une image sur le compte d'un autre !
    $userId = (int)$_SESSION['userid'];
    $eventId = (int)$_POST['eventid'];

    // Appel de ta fonction pour sauvegarder le fichier physiquement (dans files_save.php)
    $fileName = saveImage();

    if ($fileName !== null) {
        $date = new DateTime();
        $sqlDate = $date->format('Y-m-d H:i:s');

        // On enregistre dans la base de données via le modèle
        $galleryModel->addMedia($fileName, $sqlDate, $userId, $eventId);
    }

    // 3. Redirection vers la galerie de cet événement (via le routeur !)
    header("Location: index.php?page=my_gallery&eventid=" . $eventId);
    exit();
} else {
    // Si on accède à cette page sans poster de formulaire, on renvoie à l'accueil
    header("Location: index.php");
    exit();
}
