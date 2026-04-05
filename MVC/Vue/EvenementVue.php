<?php
require_once __DIR__ . '/../Controller/HeaderController.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Événements</title>
    <link rel="stylesheet" href="Asset/Style/events_style.css">
    <link rel="stylesheet" href="Asset/Style/general_style.css">
</head>

<body class="body_margin">
    <h1>LES ÉVÉNEMENTS</h1>

    <section>
        <a class="show-more" href="index.php?page=events&show=<?php echo $show + 2; ?>">
            Voir plus loin dans le passé
        </a>

        <div class="events-display">
            <?php
            if (!isset($GLOBALS['check_loop'])) {
                $GLOBALS['check_loop'] = 1;
            } else {
                echo "<h1 style='color:red;'>ALERTE : La vue est chargée une DEUXIÈME fois !</h1>";
            }
            ?>
            <?php foreach ($events as $event): ?>
                <div class="event-box <?php echo $event['statut']; ?>">

                    <div class="timeline-event">
                        <h4><?php echo ucwords($event['date_affiche']); ?></h4>
                        <div class="vertical-line"></div>
                        <p><?php echo $event['label_temp']; ?></p>

                        <div class="timeline-marker <?php echo $event['statut']; ?>">
                            <div class="time-line"></div>
                        </div>
                    </div>
                    <a href="index.php?page=event_detail&id=<?php echo $event['id_evenement']; ?>" class="event-link">
                        <div class="event" event-id="<?php echo $event['id_evenement']; ?>">
                            <div>
                                <h2><?php echo $event['nom_evenement']; ?></h2>
                                <p><?php echo ucwords($event["lieu_evenement"]); ?></p>
                            </div>

                            <h4 class="<?php echo $event['class']; ?>">
                                <?php echo $event['label']; ?>
                            </h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require_once 'footerVue.php'; ?>

</body>

</html>