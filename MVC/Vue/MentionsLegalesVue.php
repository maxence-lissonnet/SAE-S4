<?php
require_once __DIR__ . '/../Controller/HeaderController.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions légales</title>
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/header_style.css">
    <link rel="stylesheet" href="Asset/Style/footer_style.css">
    <link rel="stylesheet" href="Asset/Style/info_page_style.css">
</head>

<body class="body_margin">
    <main class="info-page">
        <h1>MENTIONS LEGALES</h1>

        <section class="info-card">
            <h2>Éditeur</h2>
            <p>Site réalisé dans le cadre du projet ADIIL.</p>
            <p>Association du Département Informatique de l'IUT de Laval.</p>
        </section>

        <section class="info-card">
            <h2>Hébergement</h2>
            <p>Environnement de développement local / hébergement interne au projet.</p>
        </section>

        <section class="info-card">
            <h2>Données</h2>
            <p>
                Les données affichées sur ce site sont utilisées dans le cadre de la vie de l'association,
                de la gestion des événements et de la boutique.
            </p>
        </section>

        <section class="info-card">
            <h2>Contact</h2>
            <p>Pour toute demande relative au site ou aux contenus, utilisez la page “Nous contacter”.</p>
        </section>
    </main>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>
