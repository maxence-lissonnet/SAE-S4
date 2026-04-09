<head>
    <title>Mot de passe oublie</title>
    <link rel="stylesheet" href="Asset/Style/login_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <form method="POST" action="index.php?page=forgot_password" class="login-form">
        <h1>Mot de passe oublie</h1>

        <?php if ($error) : ?>
            <h3 class="login-message error"><?= htmlspecialchars($error) ?></h3>
        <?php endif; ?>

        <label for="mail">Adresse mail :</label>
        <input type="email" name="mail" id="mail" required>

        <label for="newMdp">Nouveau mot de passe :</label>
        <input type="password" name="newMdp" id="newMdp" required>

        <label for="newMdpVerif">Confirmation du nouveau mot de passe :</label>
        <input type="password" name="newMdpVerif" id="newMdpVerif" required>

        <button type="submit">Reinitialiser</button>
        <a href="index.php?page=login" class="login-secondary-link">Retour a la connexion</a>
    </form>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>