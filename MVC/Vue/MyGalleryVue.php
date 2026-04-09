<head>
    <title>Ma Galerie</title>
    <link rel="stylesheet" href="Asset/Style/my_gallery_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <section class="user-gallery">

        <a href="index.php?page=event_detail&id=<?= htmlspecialchars((string) $eventId) ?>" class="back-arrow">
            &#8592;<span>Retour</span>
        </a>

        <h1>MA GALERIE</h1>
        <h2><?= htmlspecialchars($eventName) ?></h2>

        <div class="my-medias">
            <form id="add-media" action="index.php?page=add_media" method="post" enctype="multipart/form-data">
                <label for="file-picker">
                    <img src="Asset/image/add_media.png" alt="Ajouter un média">
                </label>
                <input type="hidden" name="eventid" value="<?= htmlspecialchars((string) $eventId) ?>">
                <input type="hidden" name="userid" value="<?= htmlspecialchars((string) $userId) ?>">

                <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                <button type="submit" style="display:none;">Envoyer</button>
            </form>

            <?php if (!empty($medias)): ?>
                <?php foreach ($medias as $img): ?>
                    <div class="media-container">
                        <img src="API/files/<?= htmlspecialchars(trim($img['url_media'])) ?>" alt="Image Personnelle de l'événement">

                        <div class="delete-icon">
                            <form class="delete-media" action="index.php?page=delete_media" method="post">
                                <label for="del-media-<?= $img['id_media'] ?>">
                                    <img src="Asset/image/delete_icon.png" alt="poubelle">
                                </label>
                                <input type="hidden" name="mediaid" value="<?= htmlspecialchars((string) $img['id_media']) ?>">
                                <input type="hidden" name="eventid" value="<?= htmlspecialchars((string) $eventId) ?>">

                                <button type="submit" id="del-media-<?= $img['id_media'] ?>" style="display:none;">Envoyer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="margin-left: 20px;">Vous n'avez pas encore ajouté de photos pour cet événement.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>

    <script src="Asset/Script/open_media.js"></script>
    <script src="Asset/Script/add_media.js"></script>
    <script src="Asset/Script/delete_media.js"></script>

</body>

</html>
