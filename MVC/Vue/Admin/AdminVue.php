<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDE - Administration</title>
    <link rel="shortcut icon" href="Asset/Admin/ressources/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="Asset/Admin/styles/general.css">
    <link rel="stylesheet" href="Asset/Admin/styles/admin.css">
</head>

<body id="main" data-initial-panel="<?= htmlspecialchars($currentPanel) ?>">
    <nav>
        <h1 onclick="window.location.href='index.php?page=accueil'" style="cursor: pointer;">ADIIL - Admin</h1>

        <ul>
            <?php foreach ($panels as $panel): ?>
                <li perm="<?= htmlspecialchars($panel['key']) ?>">
                    <img src="Asset/Admin/ressources/panels_icons/<?= htmlspecialchars($panel['icon']) ?>" alt="Icone <?= htmlspecialchars($panel['label']) ?>">
                    <p><?= htmlspecialchars($panel['label']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <main>
        <iframe frameborder="0" id="content" src="index.php?page=admin_panel&panel=<?= urlencode($currentPanel) ?>" title="Panel administration"></iframe>
    </main>

    <script type="module" src="Asset/Admin/scripts/admin.js"></script>
</body>

</html>
