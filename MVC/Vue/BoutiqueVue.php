<head>
    <title>Boutique</title>
    <link rel="stylesheet" href="Asset/Style/shop_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <h1>LA BOUTIQUE</h1>

    <div id="principal-section">
        <form method="post" id="filter-form" action="index.php?page=boutique">

            <input id="search-input" type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($searchTerm) ?>">

            <details <?= !empty($filters) ? 'open' : '' ?>>
                <summary>Cat&eacute;gories</summary>
                <fieldset>
                    <?php foreach ($categories_disponibles as $cat) : ?>
                        <label>
                            <input type="checkbox" name="category[]" value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $filters, true) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            </details>

            <select name="sort" id="selector">
                <option value="name_asc" <?= $orderBy === 'name_asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="price_asc" <?= $orderBy === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
            </select>

            <button type="submit" name="filter" class="btn-filter">Filtrer</button>
            <button type="submit" name="reset" class="btn-filter">R&eacute;initialiser</button>
        </form>

        <div id="cart-info">
            <a href="index.php?page=cart">
                <img src="Asset/image/logo_caddie.png" alt="Logo du panier">
                <p>Panier (<span id="count"><?= $cart->count(); ?></span>)</p>
            </a>
        </div>
    </div>

    <div id="product-list">
        <?php if (!empty($products)) : ?>
            <?php foreach ($products as $product) : ?>
                <div id="one-product">
                    <div>
                        <img src="<?= htmlspecialchars($product['resolved_image_article']) ?>" alt="<?= htmlspecialchars($product['nom_article']) ?>">
                        <h3 title="<?= htmlspecialchars($product['nom_article']) ?>">
                            <?= htmlspecialchars($product['nom_article']) ?>
                        </h3>
                        <p><?= number_format((float) $product['prix_article'], 2, ',', ' ') ?> &euro;</p>
                        <p>
                            <?= htmlspecialchars((string) $product['xp_article']) ?> XP
                            <?php if (!(int) $product['reduction_article']): ?>
                                <span>*</span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($product['stock_article'] != 0): ?>
                        <a class="addCart" id="add-to-cart-button" href="index.php?page=add_cart&id=<?= $product['id_article'] ?>">Ajouter au panier</a>
                    <?php else: ?>
                        <button disabled>Épuisé</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </div>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>