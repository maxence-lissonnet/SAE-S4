<link rel="shortcut icon" href="Asset/image/logo.png" type="image/x-icon">
<link rel="stylesheet" href="Asset/Style/header_style.css">
<?php $isLoggedIn = isset($_SESSION["userid"]); ?>
<!-- HEADER -->
<header>
    <a id="accueil" href="index.php?page=accueil">
        <img src="Asset/image/logo.png" alt="Logo de l'ADIIL">
    </a>
    <nav>
        <ul>
            <li>
                <a href="index.php?page=events">Événements</a>
            </li>
            <li>
                <a href="index.php?page=actualite">Actualités</a>
            </li>
            <li>
                <a href="index.php?page=boutique">Boutique</a>
            </li>
            <li>
                <a href="grade.php">Grades</a>
            </li>

            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="agenda.php">Agenda</a>
                </li>
            <?php endif; ?>

            <li>
                <a href="about.php">À propos</a>
            </li>

            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="account.php">Mon compte</a>
                </li>

                <?php if ($isAdmin): ?>
                    <li>
                        <a id="header_admin" href="admin/admin.php">Panel Admin</a>
                    </li>
                <?php endif; ?>

            <?php else: ?>
                <li>
                    <a href="login.php">Se connecter</a>
                </li>
            <?php endif; ?>


        </ul>
    </nav>
</header>
