<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit();
}

require_once __DIR__ . '/files_save.php';
require_once __DIR__ . '/../Model/GalleryModel.php';

$galleryModel = new GalleryModel($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mediaid'], $_POST['eventid'])) {

    $mediaId = (int)$_POST['mediaid'];
    $eventId = (int)$_POST['eventid'];

    $fileName = $galleryModel->getMediaUrl($mediaId, $eventId);

    if ($fileName !== null) {
        if (deleteFile($fileName)) {
            $galleryModel->deleteMedia($mediaId, $eventId);
        }
    }

    header("Location: index.php?page=my_gallery&eventid=" . $eventId);
    exit();
} else {
    header("Location: index.php");
    exit();
}
