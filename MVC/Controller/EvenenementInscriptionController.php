<?php
require_once __DIR__ . '/../Model/EvenementModel.php';

$eventModel = new EvenementModel($db);

if (!isset($_SESSION['userid'])) {
    header('Location: index.php?page=login');
    exit;
}

$userid = (int) $_SESSION['userid'];
$eventid = 0;

if (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) {
    $eventid = (int) $_POST['eventid'];
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $eventid = (int) $_GET['id'];
}

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
$isSubscribed = $eventModel->get_inscription_status($eventid, $userid);
$isPlaceDisponible = $eventModel->get_place_disponible($eventid);

if ($eventDate < $currentDate) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=passed');
    exit;
}

if ($isSubscribed) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=already');
    exit;
}

if (!$isPlaceDisponible) {
    header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=full');
    exit;
}

$title = $event['nom_evenement'];
$base_price = (float) $event['prix_evenement'];
$reduction = !empty($event['reductions_evenement']) ? $eventModel->get_user_reduction($userid) : 1;
$final_price = round($base_price * $reduction, 2);
$hasDiscount = abs($final_price - $base_price) > 0.009;
$errorMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_validation'])) {
    $mode_paiement = $_POST['mode_paiement'] ?? '';
    $allowedModes = ['carte_credit', 'paypal', 'especes'];

    if (!in_array($mode_paiement, $allowedModes, true)) {
        $errorMessage = 'Mode de paiement invalide.';
    } else {
        try {
            $eventModel->register_user_to_event($userid, $eventid, $final_price, $mode_paiement);
            header('Location: index.php?page=event_detail&id=' . $eventid . '&inscription=success');
            exit;
        } catch (Throwable $e) {
            $errorMessage = 'Impossible de finaliser l inscription pour le moment.';
        }
    }
}

require_once __DIR__ . '/../Vue/EvenementInscriptionVue.php';
