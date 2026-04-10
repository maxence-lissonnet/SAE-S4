<?php
require_once __DIR__ . '/../Model/EvenementModel.php';

$eventModel = new EvenementModel($db);

if (!isset($_SESSION['userid'])) {
    header('Location: index.php?page=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=events');
    exit;
}

$userid = (int) $_SESSION['userid'];
$eventid = (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) ? (int) $_POST['eventid'] : 0;

if ($eventid <= 0) {
    header('Location: index.php?page=events');
    exit;
}

$event = $eventModel->get_event_by_id($eventid);
if (!$event) {
    header('Location: index.php?page=events');
    exit;
}

$eventDate = new DateTime(substr($event['date_evenement'], 0, 10));
$currentDate = new DateTime(date('Y-m-d'));

if ($eventDate < $currentDate) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=passed');
    exit;
}

if (!$eventModel->get_inscription_status($eventid, $userid)) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=not_subscribed');
    exit;
}

try {
    $eventModel->unregister_user_from_event($userid, $eventid);
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=removed');
    exit;
} catch (Throwable $e) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=remove_error');
    exit;
}
