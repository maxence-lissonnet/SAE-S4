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

    <h1>ACTUALITÉS</h1>

    <section>
        <a class="show-more" href="index.php?page=actualite&show=<?= $show + 5 ?>">Voir plus loin dans le passé</a>

        <div class="events-display">
            <?php foreach ($news_list as $news): ?>
                <div class="event-box" id="<?= $news['is_closest'] ? 'closest-event' : '' ?>">
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
    </section>

    <?php require_once "footerVue.php" ?>

    <script src="/scripts/news_details_redirect.js"></script>
    <script src="/scripts/scroll_to_closest_event.js"></script>
</body>

</html>