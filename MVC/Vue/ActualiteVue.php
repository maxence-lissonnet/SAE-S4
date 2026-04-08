<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Actualités</title>
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/news_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <h1>ACTUALITES</h1>

    <section>
        <div class="events-display">
            <?php foreach ($news_list as $news) : ?>
                <div class="event-box" id="<?php echo (htmlspecialchars(is_special($news))) ?>">
                    <div class="timeline-event">
                        <h4><?= ucwords($news['date_affiche']) ?></h4>
                        <div class="vertical-line"></div>
                    </div>

                    <a href="index.php?page=actualiteDetail&id=<?= $news['id_actualite'] ?>" class="event-link">
                        <div class="event" event-id="<?= $news['id_actualite'] ?>">
                            <div>
                                <h2 style="margin-bottom: 0px;"><?= htmlspecialchars($news['titre_actualite']) ?></h2>
                            </div>
                            <h4 class="event-not-subscribed">Consulter</h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="show-more" href="index.php?page=actualite&show=<?= $show + 5 ?>#oldest-event" id="show-more-btn">Voir + d'événements passés</a>

        <a id="back-to-top-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrow-up-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0m-7.5 3.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z" />
            </svg>
        </a>
    </section>

    <?php require_once "footerVue.php" ?>
    <!-- <script src="/scripts/news_details_redirect.js"></script> -->
    <script src="Asset/Script/scroll_to_closest_event.js"></script>
</body>

</html>
