<head>
    <title><?= htmlspecialchars($actualite['titre_actualite']) ?></title>
    <link rel="stylesheet" href="Asset/Style/event_details_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <section class="event-details">
        <img src="<?= htmlspecialchars($resolvedImageActualite) ?>" alt="Image de l'actualité">

        <h1><?= strtoupper(htmlspecialchars($actualite['titre_actualite'])) ?></h1>

        <div>
            <h2><?= htmlspecialchars($date_formattee) ?></h2>
        </div>

        <p>
            <?= nl2br(htmlspecialchars($actualite['contenu_actualite'])) ?>
        </p>
    </section>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>