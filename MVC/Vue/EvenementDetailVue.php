<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($event['nom_evenement']); ?></title>
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/event_details_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <section class="event-details">
        <img src="<?php echo $event['image_evenement'] ? '../API/files/' . $event['image_evenement'] : 'admin/ressources/default_images/event.jpg'; ?>" alt="Image">

        <h1><?php echo strtoupper(htmlspecialchars($event['nom_evenement'])); ?></h1>

        <div>
            <h2><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></h2>

            <?php if ($event_date < $current_date) : ?>
                <button class="subscription" id="passed_subscription">Passé</button>
            <?php elseif ($isSubscribed) : ?>
                <button class="subscription" id="passed_subscription">Inscrit</button>
            <?php else : ?>
                <form class="subscription" action="index.php?page=event_inscription" method="post">
                    <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
                    <button type="submit">Inscription</button>
                </form>
            <?php endif; ?>
        </div>

        <ul>
            <li>
                <h3>📍 <?php echo htmlspecialchars($event['lieu_evenement']); ?></h3>
            </li>
            <li>
                <h3>💸 <?php echo $event['prix_evenement']; ?>€ par personne</h3>
            </li>
            <?php if ($event['reductions_evenement']) : ?>
                <li>
                    <h3>💎 -10% pour les membres Diamants</h3>
                </li>
            <?php endif; ?>
        </ul>

        <p><?php echo nl2br(htmlspecialchars($event['description_evenement'])); ?></p>
    </section>

    <section class="gallery">
        <h2>GALERIE</h2>
        <?php if ($isLoggedIn) : ?>
            <h3>Mes photos</h3>
            <div class="my-medias">
                <?php foreach ($my_medias as $img) : ?>
                    <img src="../API/files/<?php echo trim($img['url_media']); ?>" alt="Média perso">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h3>Collection Generale</h3>
        <div class="general-medias">
            <?php foreach ($general_medias as $img) : ?>
                <img src="../API/files/<?php echo trim($img['url_media']); ?>" alt="Média">
            <?php endforeach; ?>
        </div>

        <div class="show-more">
            <a href="?id=<?php echo $eventid; ?>&show=<?php echo $show + 8; ?>#gallery">Voir plus</a>
            <?php if ($show > 8) : ?>
                <a href="?id=<?php echo $eventid; ?>&show=<?php echo max(8, $show - 8); ?>#gallery">Voir moins</a>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once 'footerVue.php'; ?>
    <script src="/scripts/open_media.js"></script>
    <script src="/scripts/add_media.js"></script>
    <script src="/scripts/open_gallery.js"></script>
</body>

</html>
