<?php
$evenementModel = new EvenementModel($db);

$show = (isset($_GET['show']) && is_numeric($_GET['show'])) ? (int)$_GET['show'] : 2;
$events = array_merge(array_reverse($evenementModel->get_evenement_passe($show)), $evenementModel->get_evenement_a_venir());

foreach ($events as $key => $event) {
    $eventid = $event['id_evenement'];

    $event['date_affiche'] = formatage_date($event['date_evenement']);
    $event['statut'] = get_statut_evenement($event['date_evenement']);

    if ($event['statut'] === "passed") {
        $event['class'] = "event-full";
        $event['label'] = "Passé";
        $event['label_temp'] = "Passé";
    } else {
        $event['label_temp'] = ($event['statut'] == "today") ? "Aujourd'hui" : "À venir";

        $isPlaceDisponible = $evenementModel->get_place_disponible($eventid);
        $event['class'] = $isPlaceDisponible ? "event-not-subscribed hover_effect" : "event-full";
        $event['label'] = $isPlaceDisponible ? "S'inscrire" : "Complet";

        if (isset($_SESSION["userid"])) {
            if ($evenementModel->get_inscription_status($eventid, $_SESSION["userid"])) {
                $event['class'] = "event-subscribed";
                $event['label'] = "Inscrit";
            }
        }
    }
    $events[$key] = $event;
}

require_once __DIR__ . "/../Vue/EvenementVue.php";

function get_statut_evenement($date_evenement)
{
    $current_date = new DateTime(date("Y-m-d"));
    $event_date = new DateTime(substr($date_evenement, 0, 10));

    if ($event_date < $current_date) {
        return "passed";
    } elseif ($event_date == $current_date) {
        return "today";
    } else {
        return "upcoming";
    }
}
function formatage_date($date_str)
{
    $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];

    $date_info = getdate(strtotime($date_str));
    return ucwords($date_info["mday"] . " " . $moisFr[$date_info['mon']] . ", " . $date_info["year"]);
}
