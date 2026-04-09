<?php
$eventModel = new EvenementModel($db);
require_once __DIR__ . '/../Model/GalleryModel.php';

$galleryModel = new GalleryModel($db);

$eventid = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;

if (!$eventid) {
    header("Location: index.php");
    exit;
}

$event = $eventModel->get_event_by_id($eventid);
if (!$event) {
    header("Location: index.php");
    exit;
}

$show = (isset($_GET['show']) && is_numeric($_GET['show'])) ? (int)$_GET['show'] : 8;
$show = max(8, $show);

$isLoggedIn = isset($_SESSION["userid"]);
$current_date = new DateTime(date("Y-m-d"));
$event_date = new DateTime(substr($event['date_evenement'], 0, 10));

$isSubscribed = false;
if ($isLoggedIn) {
    $isSubscribed = $eventModel->get_inscription_status($eventid, $_SESSION["userid"]);
}

$my_medias = $isLoggedIn ? $galleryModel->getUserEventMedia((int) $_SESSION["userid"], $eventid) : [];
$general_medias = $galleryModel->getEventMedia($eventid, $show);
$hasMoreGeneralMedias = $galleryModel->countEventMedia($eventid) > $show;

require_once __DIR__ . "/../Vue/EvenementDetailVue.php";
