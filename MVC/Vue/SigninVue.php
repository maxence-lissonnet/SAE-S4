<head>
    <title>S'inscrire</title>
    <link rel="stylesheet" href="Asset/Style/login_style.css">
</head>

<body>
    <?php require_once __DIR__ . "/../Controller/HeaderController.php"; ?>

    <form method="POST" action="index.php?page=signin" class="login-form">
        <h1>S'inscrire</h1>

        <?php if ($errorMessage !== null): ?>
            <p class="error-message" style="color: red; font-weight: bold; text-align: center;">
                <?= htmlspecialchars($errorMessage) ?>
            </p>
        <?php endif; ?>

        <label for="fname">Prénom :</label>
        <input type="text" name="fname" id="fname" value="<?= isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : '' ?>">

        <label for="lname">Nom :</label>
        <input type="text" name="lname" id="lname" value="<?= isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : '' ?>">

        <label for="mail">Adresse Mail :*</label>
        <input type="email" name="mail" id="mail" required value="<?= isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : '' ?>">

        <label for="password">Mot de passe :*</label>
        <input type="password" name="password" id="password" required>

        <label for="password_verif">Confirmez le Mot de passe :*</label>
        <input type="password" name="password_verif" id="password_verif" required>

        <button type="submit">Confirmer</button>
    </form>

    <?php require_once __DIR__ . "/../Controller/FooterController.php"; ?>

</body>

</html>