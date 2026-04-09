<?php
require_once __DIR__ . '/../Controller/HeaderController.php';
?>

<head>
    <title>Nous contacter</title>
    <link rel="stylesheet" href="Asset/Style/info_page_style.css">
</head>

<body class="body_margin">
    <main class="info-page">
        <h1>NOUS CONTACTER</h1>

        <section class="info-card">
            <h2>Association ADIIL</h2>
            <p>
                Pour toute question à propos de l'association, des événements ou de la boutique,
                vous pouvez nous contacter directement par mail.
            </p>
        </section>

        <section class="info-card">
            <h2>Coordonnées</h2>
            <p><strong>Email :</strong> adiil.bde@gmail.com</p>
            <p><strong>Lieu :</strong> IUT de Laval, Département Informatique</p>
        </section>

        <section class="info-card">
            <h2>Réseaux</h2>
            <p>
                Vous pouvez aussi nous retrouver pendant les permanences de l'association
                ou via les canaux de communication du BDE.
            </p>
        </section>
    </main>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>