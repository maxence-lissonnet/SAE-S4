<?php
require_once __DIR__ . '/../Controller/HeaderController.php';
?>

<head>
    <title>Grades</title>
    <link rel="stylesheet" href="Asset/Style/grade_style.css">
</head>

<body class="body_margin">

    <h1>Les grades</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="<?= ($_SESSION['message_type'] ?? '') === 'error' ? 'error-message' : 'success-message' ?>">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <div id="product-list">
            <?php foreach ($products as $product): ?>
                <div id="one-product">
                    <div>
                    <img src="<?= htmlspecialchars($product['resolved_image_grade']) ?>" alt="Image du grade">
                    <h3><?= htmlspecialchars($product['nom_grade']) ?></h3>
                    <p><?= htmlspecialchars($product['description_grade'] ?? '') ?></p>
                    <p><?= number_format((float) $product['prix_grade'], 2, ',', ' ') ?> &euro;</p>
                    </div>

                    <?php if ($product['owned']): ?>
                        <button id="detention" disabled>Déjà détenu</button>
                    <?php else: ?>
                        <a id="buy-button" href="index.php?page=grade_subscription&id=<?= $product['id_grade'] ?>">Acheter</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun grade disponible.</p>
    <?php endif; ?>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>
