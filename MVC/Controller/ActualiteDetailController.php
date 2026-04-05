<?php
$actualiteModel = new ActualiteModel($db);

// 1. Récupération et validation de l'ID
$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int)$_GET['id'] : null;

if (!$id) {
    header("Location: index.php?page=actualite");
    exit;
}

// 2. Récupération de l'actualité
$actualite = $actualiteModel->get_actualite_by_id($id);

if (!$actualite) {
    header("Location: index.php?page=actualite");
    exit;
}

// 3. Préparation des variables de date
$date_formattee = date('d/m/Y', strtotime($actualite['date_actualite']));

// 4. Chargement de la Vue
require_once __DIR__ . "/../Vue/ActualiteDetailVue.php";
