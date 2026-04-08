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
    <h1>LES EVENEMENTS</h1>

    <section>
        <div class="events-display">
            <?php
            if (!isset($GLOBALS['check_loop'])) {
                $GLOBALS['check_loop'] = 1;
            } else {
                echo "<h1 style='color:red;'>ALERTE : La vue est chargée une DEUXIÈME fois !</h1>";
            }
            ?>
            <?php foreach ($events as $event) : ?>
                <div class="event-box <?php echo $event['statut']; ?>" id="<?php echo (htmlspecialchars(is_special($event))) ?>">

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

                            <?php if ($event['class'] !== 'event-full') : ?>
                                <h4 class="<?php echo $event['class']; ?>">
                                    <?php echo $event['label']; ?>
                                </h4>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="show-more" href="index.php?page=events&show=<?php echo $show + 2; ?>#oldest-event">
            Voir + d'événements passés
        </a>
        <a id="back-to-top-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
            </svg>
        </a>
    </section>

    <?php require_once 'footerVue.php'; ?>

    <script src="Asset/Script/scroll_to_closest_event.js"></script>
</body>

</html>
