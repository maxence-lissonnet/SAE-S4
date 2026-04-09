<head>
    <title>Mon compte</title>
    <link rel="stylesheet" href="Asset/Style/account_style.css">
</head>

<body class="body_margin">

    <?php require_once __DIR__ . "/../Controller/HeaderController.php"; ?>
    <?php
    $memberPhotoMap = [
        'axel' => 'Asset/image/photo_axel.png',
        'axelle' => 'Asset/image/photo_axel.png',
        'enzo' => 'Asset/image/photo_enzo.png',
        'gemino' => 'Asset/image/photo_gemino.png',
        'julien' => 'Asset/image/photo_julien.png',
        'mathis' => 'Asset/image/photo_mathis.png',
        'tom' => 'Asset/image/photo_tom.png',
    ];

    $resolveAccountFile = static function ($path, $fallback, $legacyMap = []) {
        if (empty($path)) {
            return $fallback;
        }

        $path = trim((string) $path);
        if (preg_match('#^https?://#i', $path)) {
            $basename = basename((string) parse_url($path, PHP_URL_PATH));
            return $legacyMap[$basename] ?? $fallback;
        }

        $relativePath = ltrim(str_replace('\\', '/', $path), '/');
        $fullPath = __DIR__ . '/../API/files/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        if (is_file($fullPath)) {
            return 'API/files/' . $relativePath;
        }

        return $legacyMap[basename($relativePath)] ?? $fallback;
    };

    $defaultProfilePicture = $memberPhotoMap[strtolower(trim((string) ($infoUser['prenom_membre'] ?? '')))] ?? 'Asset/image/user.jpg';
    $profilePictureUrl = $resolveAccountFile($infoUser['pp_membre'] ?? null, $defaultProfilePicture);
    $gradeImageUrl = $resolveAccountFile(
        $infoUser['image_grade'] ?? null,
        'Asset/image/grade.webp',
        [
            'grade_diamand.jpg' => 'API/files/grade_diamant.png',
            'grade_fer.jpg' => 'API/files/grade_fer.webp',
            'grade_or.jpg' => 'API/files/grade_or.png',
        ]
    );
    ?>

    <h2>MON COMPTE</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <?php $messageStyle = ($_SESSION['message_type'] ?? '') === "error" ? "error-message" : "success-message"; ?>
        <div id="<?= $messageStyle ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>

    <section>
        <div id="account-generalInfo">
            <div>
                <form method="POST" action="index.php?page=account" enctype="multipart/form-data" id="pp-form">
                    <label id="cadre-pp" for="profilePictureInput">
                        <img src="<?= htmlspecialchars($profilePictureUrl) ?>" alt="Photo de profil de l'utilisateur" />
                    </label>

                    <input type="file" id="profilePictureInput" name="file" accept="image/jpeg, image/png, image/webp" style="display: none;" onchange="this.form.submit()">

                    <button type="button" id="edit-icon" onclick="document.getElementById('profilePictureInput').click()">
                        <img src="Asset/image/edit_logo.png" alt="Icone éditer la photo de profil" />
                    </button>
                </form>
            </div>
            <div>
                <p><?= htmlspecialchars((string) $infoUser['xp_membre']) ?></p>
                <p>XP</p>
            </div>
            <div id="cadre-grade">
                <?php if (empty($infoUser['nom_grade'])): ?>
                    <p>Vous n'avez pas de grade</p>
                <?php else: ?>
                    <p><?= htmlspecialchars($infoUser['nom_grade']) ?></p>
                    <img src="<?= htmlspecialchars($gradeImageUrl) ?>" alt="Illustration du grade" />
                <?php endif; ?>
            </div>
        </div>

        <form method="POST" action="index.php?page=account" id="account-personalInfo-form">
            <div>
                <div>
                    <input type="text" id="name" name="name" placeholder="Prénom" value="<?= htmlspecialchars($infoUser['prenom_membre'] ?? '') ?>" required>
                    <input type="text" id="lastName" name="lastName" placeholder="Nom de famille" value="<?= htmlspecialchars($infoUser['nom_membre'] ?? '') ?>" required>
                </div>
                <div>
                    <input type="email" id="mail" name="mail" placeholder="Adresse mail" value="<?= htmlspecialchars($infoUser['email_membre'] ?? '') ?>" required>

                    <?php if (!empty($infoUser['tp_membre'])): ?>
                        <select id="tp" name="tp">
                            <?php foreach (['11A', '11B', '12C', '12D', '21A', '21B', '22C', '22D', '31A', '31B', '32C', '32D'] as $tpOption): ?>
                                <option value="<?= $tpOption ?>" <?= ($infoUser['tp_membre'] === $tpOption) ? 'selected' : '' ?>>TP <?= $tpOption ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit">
                <img src="Asset/image/save_logo.png" alt="Logo enregistrer les modifications" />
            </button>
        </form>

        <form method="POST" action="index.php?page=account" id="account-editPass-form">
            <div>
                <div>
                    <p>Modifier mon mot de passe :</p>
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe actuel">
                </div>
                <div>
                    <input type="password" id="newMdp" name="newMdp" placeholder="Nouveau mot de passe" required>
                    <input type="password" id="newMdpVerif" name="newMdpVerif" placeholder="Confirmation du nouveau mot de passe" required>
                </div>
            </div>
            <button type="submit"><img src="Asset/image/save_logo.png" alt="Logo enregistrer" /></button>
        </form>
    </section>

    <section>
        <div id="buttons-section">
            <button type="button">
                <a href="https://discord.com/login" target="_blank">
                    <img src="Asset/image/logo_discord.png" alt="Logo de Discord"> Associer mon compte à Discord
                </a>
            </button>

            <form action="index.php?page=account" method="post">
                <input type="hidden" name="deconnexion" value="true">
                <button type="submit">
                    <img src="Asset/image/logOut_icon.png" alt="icone de deconnexion"> Déconnexion
                </button>
            </form>

            <form action="index.php?page=delete_account" method="post">
                <input type="hidden" name="delete_account" value="true">
                <button type="submit">
                    <img src="Asset/image/delete_icon.png" alt="icone de suppression"> Supprimer mon compte
                </button>
            </form>
        </div>
    </section>

    <section id="section-mesAchats">
        <h2>MES ACHATS</h2>
        <div id="historique-achats">
            <form method="GET" action="index.php" id="viewAll-form">
                <input type="hidden" name="page" value="account">
                <?php if ($viewAll): ?>
                    <button type="submit" name="viewAll" value="0">Afficher moins</button>
                <?php else: ?>
                    <button type="submit" name="viewAll" value="1">Afficher tout</button>
                <?php endif; ?>
            </form>

            <?php if (!empty($historiqueAchats)): ?>
                <table id="tab-historique-achats">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Mode de paiement</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historiqueAchats as $achat): ?>
                            <tr>
                                <td><?= htmlspecialchars($achat['date_transaction']) ?></td>
                                <td><?= htmlspecialchars($achat['type_transaction']) ?></td>
                                <td><?= htmlspecialchars($achat['element']) ?></td>
                                <td><?= htmlspecialchars((string) $achat['quantite']) ?></td>
                                <td><?= number_format((float) $achat['montant'], 2, ',', ' ') ?> &euro;</td>
                                <td><?= htmlspecialchars($achat['mode_paiement']) ?></td>
                                <td><?= htmlspecialchars($achat['statut']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Vous n'avez effectué aucun achat pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once __DIR__ . "/../Controller/FooterController.php"; ?>
</body>

</html>
