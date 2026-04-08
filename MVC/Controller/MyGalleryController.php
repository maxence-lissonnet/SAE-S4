<?php
require_once __DIR__ . '/../Model/GalleryModel.php';

$galleryModel = new GalleryModel($db);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}

$galleryModel = new GalleryModel($db);
$userId = $_SESSION["userid"];
$limit = 10;

if (isset($_GET["show"]) && ctype_digit($_GET["show"])) {
    $limit = (int)$_GET["show"];
}

$isGlobalGallery = !isset($_GET['eventid']);

if (!$isGlobalGallery) {
    $eventId = (int)$_GET['eventid']; // On le lit uniquement ici !
    $eventName = $galleryModel->getEventName($eventId);
    $medias = $galleryModel->getUserEventMedia($userId, $eventId, $limit);
} else {
    $eventId = null;
    $eventName = "Toutes mes photos";
    $medias = $galleryModel->getUserAllMedia($userId, $limit);
}

// Appel de la vue
require_once __DIR__ . '/../Vue/MyGalleryVue.php';
