<?php
require_once __DIR__ . "/../Model/EvenementModel.php";
$eventModel = new EvenementModel($db);

// 1. Sécurité : Vérifier connexion
if (!isset($_SESSION["userid"])) {
    header("Location: index.php?page=login");
    exit;
}

$userid = $_SESSION["userid"];
$eventid = $_POST["eventid"] ?? null;

if (!$eventid) {
    header("Location: index.php");
    exit;
}

// 2. Récupération des infos de l'événement
$event = $eventModel->get_event_by_id($eventid);
$title = $event["nom_evenement"];
$base_price = $event["prix_evenement"];

// 3. Calcul de la réduction
$reduction = 1;
if (boolval($event["reductions_evenement"])) {
    $reduction = $eventModel->get_user_reduction($userid);
}
$final_price = $base_price * $reduction;

// 4. Traitement de la validation finale (si le prix est envoyé)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['final_validation'])) {
    $eventModel->register_user_to_event($userid, $eventid, $final_price);
    header("Location: index.php?page=events&status=success");
    exit;
}

require_once __DIR__ . "/../Vue/EvenementInscriptionVue.php";
