<head>
    <title>Boutique</title>
    <link rel="stylesheet" href="Asset/Style/shop_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <h1>LA BOUTIQUE</h1>

    <div id="principal-section">
        <form method="post" id="filter-form" action="index.php?page=boutique">
            <fieldset>
                <input id="search-input" type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($searchTerm) ?>">
            </fieldset>

            <details <?= !empty($filters) ? 'open' : '' ?>>
                <summary>Catégories</summary>
                <fieldset>
                    <?php foreach (['Sucré', 'Salé', 'Boisson', 'Merch'] as $cat): ?>
                        <label>
                            <input type="checkbox" name="category[]" value="<?= $cat ?>" <?= in_array($cat, $filters) ? 'checked' : '' ?>> <?= $cat ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            </details>

            <select name="sort">
                <option value="name_asc" <?= $orderBy === 'name_asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="price_asc" <?= $orderBy === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
            </select>
            <button type="submit" name="reset">Réinitialiser</button>
        </form>

        <div id='cart-info'>
            <a href="index.php?page=cart">
                <img src="assets/logo_caddie.png" alt="Panier">
                <p>Panier (<span id="count"><?= $cart->count(); ?></span>)</p>
            </a>
        </div>
    </div>

    <div id="product-list">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div id="one-product">
                    <img src="<?= $product['image_article'] ? 'api/files/' . $product['image_article'] : 'admin/ressources/default_images/boutique.png' ?>" alt="Article">
                    <h3><?= htmlspecialchars($product['nom_article']) ?></h3>
                    <p><?= number_format($product['prix_article'], 2, ',', ' ') ?> €</p>

                    <?php if ($product['stock_article'] != 0): ?>
                        <a class="addCart" href="index.php?page=add_cart&id=<?= $product['id_article'] ?>">Ajouter au panier</a>
                    <?php else: ?>
                        <button disabled>Épuisé</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </div>

    <?php require_once "footerVue.php"; ?>
    <script src="scripts/add_cart.js"></script>
</body>

</html>