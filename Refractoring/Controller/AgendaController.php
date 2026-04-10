<?php
// On s'assure que la session est démarrée si ton header en a besoin
if (session_status() === PHP_SESSION_NONE) session_start();

// Pas de Modèle nécessaire car aucune requête SQL n'est effectuée ici !

// On appelle simplement la vue
require_once __DIR__ . '/../Vue/AgendaVue.php';
