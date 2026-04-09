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
    <?php
    $resolveImage = static function ($path, $fallback = 'Asset/image/event.jpg') {
        if (empty($path)) {
            return $fallback;
        }

        return preg_match('#^https?://#i', $path) ? $path : 'API/files/' . ltrim($path, '/');
    };
    ?>

    <section class="event-details">
        <img src="<?php echo htmlspecialchars($resolveImage($event['image_evenement'] ?? null)); ?>" alt="Image de l'evenement">

        <h1><?php echo strtoupper(htmlspecialchars($event['nom_evenement'])); ?></h1>

        <?php if ($inscriptionNotice !== null) : ?>
            <p class="event-notice <?php echo htmlspecialchars($inscriptionNotice['type']); ?>">
                <?php echo htmlspecialchars($inscriptionNotice['message']); ?>
            </p>
        <?php endif; ?>

        <div>
            <h2><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></h2>

            <?php if ($event_date < $current_date) : ?>
                <button class="subscription subscription-disabled">Passe</button>
            <?php elseif ($isSubscribed) : ?>
                <form class="subscription" action="index.php?page=event_desinscription" method="post">
                    <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
                    <button type="submit" class="subscription-cancel">Se desinscrire</button>
                </form>
            <?php elseif (!$isPlaceDisponible) : ?>
                <button class="subscription subscription-disabled">Complet</button>
            <?php else : ?>
                <form class="subscription" action="index.php?page=event_inscription" method="post">
                    <input type="hidden" name="eventid" value="<?php echo $eventid; ?>">
                    <button type="submit">Inscription</button>
                </form>
            <?php endif; ?>
        </div>

        <ul>
            <li>
                <h3>Lieu : <?php echo htmlspecialchars($event['lieu_evenement']); ?></h3>
            </li>
            <li>
                <h3>Prix : <?php echo htmlspecialchars((string) $event['prix_evenement']); ?> EUR par personne</h3>
            </li>
            <?php if (!empty($event['reductions_evenement'])) : ?>
                <li>
                    <h3>Reduction : -10% pour les membres Diamant</h3>
                </li>
            <?php endif; ?>
        </ul>

        <p><?php echo nl2br(htmlspecialchars($event['description_evenement'] ?? '')); ?></p>
    </section>

    <section class="gallery" id="gallery">
        <h2>GALERIE</h2>

        <?php if ($isLoggedIn) : ?>
            <div class="media-section-head">
                <h3>Mes photos</h3>
                <form id="add-media" action="index.php?page=add_media" method="post" enctype="multipart/form-data">
                    <label for="file-picker" class="add-media-trigger">
                        <img src="Asset/image/add_media.png" alt="Ajouter une photo">
                        <span>Ajouter une photo</span>
                    </label>
                    <input type="hidden" name="eventid" value="<?php echo htmlspecialchars((string) $eventid); ?>">
                    <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                    <button type="submit" hidden>Envoyer</button>
                </form>
            </div>

            <?php if (!empty($my_medias)) : ?>
                <div class="my-medias">
                    <?php foreach ($my_medias as $img) : ?>
                        <div class="media-container">
                            <img src="<?php echo htmlspecialchars($resolveImage(trim($img['url_media']))); ?>" alt="Photo personnelle de l'evenement">
                            <div class="delete-icon">
                                <form class="delete-media" action="index.php?page=delete_media" method="post">
                                    <label for="delete-media-<?php echo (int) $img['id_media']; ?>">
                                        <img src="Asset/image/delete_icon.png" alt="Supprimer">
                                    </label>
                                    <input type="hidden" name="mediaid" value="<?php echo htmlspecialchars((string) $img['id_media']); ?>">
                                    <input type="hidden" name="eventid" value="<?php echo htmlspecialchars((string) $eventid); ?>">
                                    <button type="submit" id="delete-media-<?php echo (int) $img['id_media']; ?>" hidden>Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="media-empty">Vous n'avez pas encore ajoute de photo a cet evenement.</p>
            <?php endif; ?>
        <?php else : ?>
            <p class="media-empty">Connectez-vous pour ajouter et retrouver vos photos de l'evenement.</p>
        <?php endif; ?>

        <h3>Toutes les photos de l'evenement</h3>
        <?php if (!empty($general_medias)) : ?>
            <div class="general-medias">
                <?php foreach ($general_medias as $img) : ?>
                    <img src="<?php echo htmlspecialchars($resolveImage(trim($img['url_media']))); ?>" alt="Photo de l'evenement">
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="media-empty">Aucune photo n'a encore ete ajoutee pour cet evenement.</p>
        <?php endif; ?>

        <div class="show-more">
            <?php if ($hasMoreGeneralMedias) : ?>
                <a href="index.php?page=event_detail&id=<?php echo $eventid; ?>&show=<?php echo $show + 8; ?>#gallery">Voir plus</a>
            <?php endif; ?>
            <?php if ($show > 8) : ?>
                <a href="index.php?page=event_detail&id=<?php echo $eventid; ?>&show=<?php echo max(8, $show - 8); ?>#gallery">Voir moins</a>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
    <script src="Asset/Script/open_media.js"></script>
    <script src="Asset/Script/add_media.js"></script>
    <script src="Asset/Script/delete_media.js"></script>
</body>

</html>
