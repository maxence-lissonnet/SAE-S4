<?php
// Controller/ActualiteController.php
$actualiteModel = new ActualiteModel($db);

// Gestion du nombre d'éléments à afficher via l'URL
$show = (isset($_GET['show']) && is_numeric($_GET['show'])) ? (int)$_GET['show'] : 5;

// Récupération de la liste brute
$news_list = $actualiteModel->get_actualites($show);

$current_date = date("Y-m-d");
$closest_found = false;
$oldest_found = false;

// Tableaux de traduction pour les dates
$joursFr = [0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
$moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];

foreach ($news_list as $key => $news) {
    $news_date_raw = substr($news['date_actualite'], 0, 10);
    $timestamp = strtotime($news_date_raw);
    $date_info = getdate($timestamp);

    // Préparation de la date lisible pour la Vue
    $news_list[$key]['date_affiche'] = $joursFr[$date_info['wday']] . " " . $date_info["mday"] . " " . $moisFr[$date_info['mon']];

    // Détermination de l'élément cible pour le scroll automatique
    $news_list[$key]['is_closest'] = false;
    $news_list[$key]['is_oldest'] = false;
    if (!$closest_found) {
        if ($key == 0) {
            $news_list[$key]['is_closest'] = true;
            $closest_found = true;
        }
    }
    if (!$oldest_found) {
        if ($key == count($news_list) - 2) { // -2 car sinon scroll trop bas
            $news_list[$key]['is_oldest'] = true;
            $oldest_found = true;
        }
    }
}

function is_special($news)
{
    if ($news['is_closest']) {
        return 'closest-event';
    } else if ($news['is_oldest']) {
        return 'oldest-event';
    } else {
        return "";
    }
}

require_once __DIR__ . "/../Vue/ActualiteVue.php";
