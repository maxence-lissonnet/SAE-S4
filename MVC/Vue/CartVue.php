<head>
    <title>Mon panier</title>
    <link rel="stylesheet" href="./Asset/Style/cart_style.css">

    <script>
        function pressEnter(event) {
            var code = event.which || event.keyCode;
            if (code == 13) {
                document.getElementById("form-quantity").submit();
            }
        }
    </script>
</head>

<body class="body_margin">
    <?php
    require_once __DIR__ . "/../Controller/HeaderController.php";
    ?>

    <div>
        <h1>MON PANIER</h1>
        <div>
            <?php if (isset($_SESSION['message'])): ?>
                <?php $messageStyle = (isset($_SESSION['message_type']) && $_SESSION['message_type'] === "error") ? "error-message" : "success-message"; ?>
                <div id="<?= $messageStyle ?>">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                </div>
                <?php
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            <?php endif; ?>
        </div>

        <div>
            <a href="index.php?page=boutique">
                <button id="shop-button">
                    <img src="./Asset/image/fleche_retour.png" alt="Fleche de retour">
                    Retourner à la boutique
                </button>
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['cart']) && !empty($products)) : ?>
        <div id='cart-container'>
            <form method="POST" action="index.php?page=cart" id="form-quantity">
                <table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <?php
                            $qty = $_SESSION['cart'][$product['id_article']];
                            $sousTotal = $product['prix_article'] * $qty;
                            ?>
                            <tr>
                                <td id='article-cell'>
                                    <img src="api/files/<?= htmlspecialchars($product['image_article']) ?>" alt="Image de l'article" />
                                    <p><?= htmlspecialchars($product['nom_article']) ?></p>
                                </td>
                                <td><?= number_format(htmlspecialchars($product['prix_article']), 2, ',', ' ') ?> €</td>
                                <td>
                                    <input type='text' name="cart[quantity][<?= $product['id_article'] ?>]" value="<?= htmlspecialchars($qty) ?>" onkeydown="pressEnter(event)">
                                </td>
                                <td><?= number_format($sousTotal, 2, ',', ' ') ?> €</td>
                                <td>
                                    <a href="index.php?page=cart&del=<?= $product['id_article'] ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre d'articles &nbsp;: </th>
                            <td><?= $cart->count() ?></td>
                        </tr>
                        <tr>
                            <th>Total &nbsp;: </th>
                            <td><?= number_format($cart->total(), 2, ',', ' ') ?> €</td>
                        </tr>

                        <?php if ($totalAvecReduc !== null) : ?>
                            <tr>
                                <th style="min-width: 400px">Total après réductions (-<?= $reductionPercent ?>%) &nbsp;: </th>
                                <td style="min-width: 50px"><?= number_format($totalAvecReduc, 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </form>
        </div>

        <div>
            <form class="subscription" action="index.php?page=order" method="post">
                <input type="hidden" name="cart" value="<?= htmlspecialchars(json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE)) ?>">

                <button type="submit" id='order-button'>
                    Commander
                </button>
            </form>
        </div>

    <?php else : ?>
        <p id="empty-cart">Votre panier est vide</p>
    <?php endif; ?>

    <?php
    require_once __DIR__ . "/../Controller/FooterController.php";
    ?>
</body>

</html>