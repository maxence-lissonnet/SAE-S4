<?php
require_once __DIR__ . "/../Model/Database.php";
require_once __DIR__ . "/../Model/AccueilModel.php";

$accueilModel = new AccueilModel($db);
$eventModel = new EvenementModel($db);

$podium = $accueilModel->get_podium_xp();
$events_to_display = $eventModel->get_derniers_evenements();

foreach ($events_to_display as $key => $event) {
    $eventid = $event['id_evenement'];

    $event['date_affiche'] = formatage_date($event['date_evenement']);

    $isPlaceDisponible = $eventModel->get_place_disponible($eventid);

    $event['class'] = $isPlaceDisponible ? "event-not-subscribed hover_effect" : "event-full";
    $event['label'] = $isPlaceDisponible ? "S'inscrire" : "Complet";

    if (isset($_SESSION["userid"])) {
        if ($eventModel->get_inscription_status($eventid, $_SESSION["userid"])) {
            $event['class'] = "event-subscribed";
            $event['label'] = "Inscrit";
        }
    }

    $events_to_display[$key] = $event;
}

function formatage_date($date_str)
{
    $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];

    $date_info = getdate(strtotime($date_str));
    return ucwords($date_info["mday"] . " " . $moisFr[$date_info['mon']] . ", " . $date_info["year"]);
}

require_once __DIR__ . "/../Vue/AcceuilVue.php";
