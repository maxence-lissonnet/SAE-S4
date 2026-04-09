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
    $userId = (int) $_SESSION['userid'];

    $fileName = $galleryModel->getMediaUrl($mediaId, $eventId, $userId);

    if ($fileName !== null) {
        if (deleteFile($fileName)) {
            $galleryModel->deleteMedia($mediaId, $eventId, $userId);
        }
    }

    header("Location: index.php?page=event_detail&id=" . $eventId . "#gallery");
    exit();
} else {
    header("Location: index.php");
    exit();
}
