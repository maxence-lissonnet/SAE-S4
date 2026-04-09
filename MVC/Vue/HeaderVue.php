<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhérer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="Asset/Admin/ressources/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="Asset/Style/header_style.css">
    <link rel="stylesheet" href="Asset/Style/general_style.css">
</head>

<body>

    <?php $isLoggedIn = isset($_SESSION["userid"]); ?>

    <header>
        <a id="accueil" href="index.php?page=accueil">
            <img src="Asset/image/logo.png" alt="Logo de l'ADIIL">
        </a>

        <button id="mobile-menu-btn" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="main-nav">
            <ul>
                <li><a href="index.php?page=events">Événements</a></li>
                <li><a href="index.php?page=actualite">Actualités</a></li>
                <li><a href="index.php?page=boutique">Boutique</a></li>
                <li><a href="index.php?page=grade">Grades</a></li>

                <?php if ($isUserLoggedIn) : ?>
                    <li><a href="index.php?page=agenda">Agenda</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=about">À propos</a></li>

                <?php if ($isUserLoggedIn) : ?>
                    <li><a href="index.php?page=account">Mon compte</a></li>
                    <?php if ($isAdmin) : ?>
                        <li><a id="header_admin" href="index.php?page=admin">Panel Admin</a></li>
                    <?php endif; ?>
                <?php else : ?>
                    <li><a href="index.php?page=login">Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mainNav = document.getElementById('main-nav');

            if (mobileBtn && mainNav) {
                mobileBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    mainNav.classList.toggle('active');
                });
            } else {
                console.error("Le bouton hamburger ou le menu est introuvable ! Vérifie les IDs.");
            }
        });
    </script>

</body>

</html>