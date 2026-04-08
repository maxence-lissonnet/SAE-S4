<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($actualite['titre_actualite']); ?></title>

    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/header_style.css">
    <link rel="stylesheet" href="Asset/Style/footer_style.css">
    <link rel="stylesheet" href="Asset/Style/event_details_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>
    <?php
    $resolveImage = static function ($path, $fallback = 'API/files/default-event.png') {
        if (empty($path)) {
            return $fallback;
        }

        return preg_match('#^https?://#i', $path) ? $path : 'API/files/' . ltrim($path, '/');
    };
    ?>

    <section class="event-details">
        <?php if (empty($actualite['image_actualite'])) : ?>
            <img src="admin/ressources/default_images/event.jpg" alt="Image par défaut">
        <?php else : ?>
            <img src="api/files/<?php echo htmlspecialchars($actualite['image_actualite']); ?>" alt="Image de l'actualité">
        <?php endif; ?>

        <h1><?php echo strtoupper(htmlspecialchars($actualite['titre_actualite'])); ?></h1>

        <div>
            <h2><?php echo $date_formattee; ?></h2>
        </div>

        <p>
            <?php echo nl2br(htmlspecialchars($actualite['contenu_actualite'])); ?>
        </p>
    </section>

    <?php require_once 'footerVue.php'; ?>
</body>

</html>