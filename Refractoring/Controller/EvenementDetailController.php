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
$isPlaceDisponible = $eventModel->get_place_disponible($eventid);
$inscriptionNotice = null;

if (isset($_GET['inscription'])) {
    $inscriptionNotice = match ($_GET['inscription']) {
        'success' => ['type' => 'success', 'message' => 'Votre inscription a bien ete enregistree.'],
        'removed' => ['type' => 'success', 'message' => 'Votre inscription a bien ete annulee.'],
        'already' => ['type' => 'error', 'message' => 'Vous etes deja inscrit a cet evenement.'],
        'not_subscribed' => ['type' => 'error', 'message' => 'Vous n etes pas inscrit a cet evenement.'],
        'full' => ['type' => 'error', 'message' => 'Cet evenement est complet.'],
        'passed' => ['type' => 'error', 'message' => 'Cet evenement est deja passe.'],
        'remove_error' => ['type' => 'error', 'message' => 'Impossible d annuler cette inscription pour le moment.'],
        default => null,
    };
}

$isSubscribed = false;
if ($isLoggedIn) {
    $isSubscribed = $eventModel->get_inscription_status($eventid, $_SESSION["userid"]);
}

$my_medias = $isLoggedIn ? $galleryModel->getUserEventMedia((int) $_SESSION["userid"], $eventid) : [];
$general_medias = $galleryModel->getEventMedia($eventid, $show);
$hasMoreGeneralMedias = $galleryModel->countEventMedia($eventid) > $show;

require_once __DIR__ . "/../Vue/EvenementDetailVue.php";
