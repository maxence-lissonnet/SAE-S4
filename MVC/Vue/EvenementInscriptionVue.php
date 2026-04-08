<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription - <?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="Asset/Style/general_style.css">
    <link rel="stylesheet" href="Asset/Style/event_subscription_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <h1>RÉCAPITULATIF DE L'INSCRIPTION</h1>

    <div class="cart-container">
        <a href="index.php?page=evenementDetail&id=<?= $eventid ?>" class="back-link">
            <img src="/assets/fleche_retour.png" alt="Retour"> Retourner à l'évènement
        </a>

        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= strtoupper(htmlspecialchars($title)) ?></td>
                    <td>1</td>
                    <td><?= number_format($base_price, 2) ?> €</td>
                    <td><?= number_format($base_price, 2) ?> €</td>
                </tr>
            </tbody>
        </table>

        <div class="totals">
            <p>Total : <?= number_format($base_price, 2) ?> €</p>
            <h3>Total après réductions : <?= number_format($final_price, 2) ?> €</h3>
        </div>

        <div class="payment-section">
            <h3>Paiement</h3>
            <select id="mode_paiement">
                <option value="carte_credit">Carte de Crédit</option>
                <option value="paypal">PayPal</option>
            </select>

            <form method="POST" action="index.php?page=event_inscription">
                <input type="hidden" name="eventid" value="<?= $eventid ?>">
                <input type="hidden" name="final_validation" value="1">

                <div id="fields_cc">
                    <input type="text" placeholder="XXXX XXXX XXXX XXXX" required>
                    <input type="text" placeholder="MM/AA" required>
                    <input type="text" placeholder="CVV" required>
                </div>

                <button type="submit" class="btn-validate">Valider la commande</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('mode_paiement').addEventListener('change', function() {
            document.getElementById('fields_cc').style.display = (this.value === 'carte_credit') ? 'block' : 'none';
        });
    </script>

    <?php require_once 'footerVue.php'; ?>
</body>

</html>
