<?php
require_once __DIR__ . '/../Controller/HeaderController.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Accueil</title>

    <link rel="stylesheet" href="Asset/Style/index_style.css">
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/header_style.css">
    <link rel="stylesheet" href="Asset/Style/footer_style.css">
    <link rel="stylesheet" href="Asset/Style/bubble.css">

</head>

<body id="index" class="body_margin">

    <div id="page-container">
        <!--H1 A METTRE -->
        <section>
            <h2 class="titre_vertical"> ADIIL</h2>
            <div id="index_carrousel">
                <img src="assets/photo_accueil_BDE.png" alt="Carrousel ADIIL">
            </div>
        </section>

        <section>
            <div class="paragraphes">
                <p>
                    <b class="underline">L'ADIIL</b>, ou l'<b>Association</b> du <b>Département</b> <b>Informatique</b>
                    de l'<b>IUT</b> de <b>Laval</b>,
                    est une organisation étudiante dédiée à créer un environnement propice à l'épanouissement dans le
                    campus.
                    Participer a des évèvements, et plus globalement a la vie du département.
                </p>
                <p>
                    L'ADIIL, véritable moteur de la vie étudiante à l'IUT de Laval,
                    offre un cadre propice à l'épanouissement académique et social des étudiants en informatique.
                    En participant à ses événements variés, les étudiants enrichissent leur expérience universitaire,
                    tout en renforçant les liens au sein de la communauté.
                </p>
            </div>
            <h2 class="titre_vertical">L'ASSO</h2>
        </section>

        <section>
            <h2 class="titre_vertical">SCORES</h2>

            <div id="podium">
                <?php

                foreach ([2, 1, 3] as $member_number):
                    $pod = $podium[$member_number - 1];

                ?>
                    <div class="podium_unit">
                        <h3>#0<?php echo $member_number ?></h3>
                        <h4><?php echo $pod['prenom_membre']; ?></h4>
                        <div>
                            <?php if ($pod['pp_membre'] == null): ?>
                                <img src="admin/ressources/default_images/user.jpg" alt="Profile Picture"
                                    class="profile_picture">
                            <?php else: ?>
                                <img src="api/files/<?php echo $pod['pp_membre']; ?>" alt="Profile Picture"
                                    class="profile_picture">
                            <?php endif ?>
                            <?php echo $pod['xp_membre']; ?> xp
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <div class="events-display">
                <?php
                foreach ($events_to_display as $event):
                    $eventid = $event["id_evenement"]; ?>

                    <div class="event" event-id="<?php echo $eventid; ?>">
                        <div>
                            <h2><?php echo $event['nom_evenement']; ?></h2>
                            <?php echo $event['date_affiche']; ?>
                        </div>

                        <h4 class="<?php echo $event['class']; ?>">
                            <?php echo $event['label']; ?>
                        </h4>

                    </div>
                <?php endforeach; ?>
                <h3><a href="index.php?page=events">Voir tous les événements</a></h3>
            </div>
            <h2 class="titre_vertical">EVENT</h2>

        </section>
    </div>
    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
    <script src="scripts/event_details_redirect.js"></script>
    <script src="scripts/bubble.js"></script>
</body>

</html>