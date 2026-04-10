<head>
    <title>Boutique</title>
    <link rel="stylesheet" href="Asset/Style/shop_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>
    <div
        id="cart-toast"
        aria-live="polite"
        aria-atomic="true"
        style="display:none; position:fixed; top:118px; right:32px; max-width:420px; min-width:240px; padding:12px 16px; border-radius:12px; background-color:rgba(64, 180, 141, 0.2); border:1px solid rgba(64, 180, 141, 0.8); color:rgb(182, 248, 224); font-weight:600; font-size:0.95rem; line-height:1.5; text-align:left; opacity:0; transform:translateY(-6px); pointer-events:none; transition:opacity 0.2s ease, transform 0.2s ease; z-index:1201;"
    ></div>

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
                        <?php if ($product['is_available'] && !empty($product['stock_label'])): ?>
                            <p class="stock-label available" style="font-size: 0.68em; text-align: center; width: 100%; display: block; line-height: 1.2; margin: 10px auto 12px;">
                                <?= htmlspecialchars($product['stock_label']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if ($product['is_available']): ?>
                        <a class="addCart" id="add-to-cart-button" href="index.php?page=add_cart&id=<?= $product['id_article'] ?>">Ajouter au panier</a>
                    <?php else: ?>
                        <button id="out-of-stock" disabled>&Eacute;puis&eacute;</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun produit trouv&eacute;.</p>
        <?php endif; ?>
    </div>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
    <script src="Asset/Script/add_cart.js"></script>
</body>

</html>
