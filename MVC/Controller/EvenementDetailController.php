<?php
$eventModel = new EvenementModel($db);

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

$isLoggedIn = isset($_SESSION["userid"]);
$current_date = new DateTime(date("Y-m-d"));
$event_date = new DateTime(substr($event['date_evenement'], 0, 10));

$isSubscribed = false;
if ($isLoggedIn) {
    $isSubscribed = $eventModel->get_inscription_status($eventid, $_SESSION["userid"]);
}

$my_medias = $isLoggedIn ? $eventModel->get_event_medias($eventid, 4, $_SESSION["userid"]) : [];
$general_medias = $eventModel->get_event_medias($eventid, $show);

require_once __DIR__ . "/../Vue/EvenementDetailVue.php";
