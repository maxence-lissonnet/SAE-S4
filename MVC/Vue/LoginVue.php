<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/login_style.css">
</head>

<body>
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <form method="POST" action="index.php?page=login" class="login-form">
        <h1>Connexion</h1>

        <?php if ($flashMessage) : ?>
            <h3 class="login-message <?= htmlspecialchars($flashMessageType === 'error' ? 'error' : 'success') ?>">
                <?= htmlspecialchars($flashMessage) ?>
            </h3>
        <?php endif; ?>

        <?php if ($error) : ?>
            <h3 class="login-error"><?= htmlspecialchars($error) ?></h3>
        <?php endif; ?>

        <label for="mail">Adresse Mail :</label>
        <input type="email" name="mail" id="mail" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password">

        <a href="index.php?page=forgot_password" class="forgot-password-link">Mot de passe oublie ?</a>

        <button type="submit">Se connecter</button>
    </form>

    <div id="create-account">
        <h2>Pas encore de compte ?</h2>
        <a href="index.php?page=signin"><button>Créez en un</button></a>
    </div>
</body>

</html>
