<head>
    <meta charset="UTF-8">
    <title>Inscription - <?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="Asset/Style/event_subscription_style.css">
</head>

<body class="body_margin">
    <?php require_once __DIR__ . '/../Controller/HeaderController.php'; ?>

    <h1>RECAPITULATIF DE L'INSCRIPTION</h1>

    <section class="subscription-page">
        <a href="index.php?page=event_detail&id=<?= htmlspecialchars((string) $eventid) ?>" class="back-link">
            <img src="Asset/image/fleche_retour.png" alt="Retour">
            Retourner a l evenement
        </a>

        <?php if ($errorMessage !== null) : ?>
            <p class="subscription-error"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <div class="subscription-layout">
            <div class="subscription-summary">
                <table>
                    <thead>
                        <tr>
                            <th>Evenement</th>
                            <th>Quantite</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= strtoupper(htmlspecialchars($title)) ?></td>
                            <td>1</td>
                            <td><?= number_format($base_price, 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format($base_price, 2, ',', ' ') ?> EUR</td>
                        </tr>
                    </tbody>
                </table>

                <div class="totals">
                    <h3>Total : <?= number_format($base_price, 2, ',', ' ') ?> EUR</h3>
                    <?php if ($hasDiscount) : ?>
                        <h3>Total apres reductions : <?= number_format($final_price, 2, ',', ' ') ?> EUR</h3>
                    <?php endif; ?>
                </div>
            </div>

            <div class="payment-section">
                <h3>Paiement</h3>

                <label for="mode_paiement">Mode de paiement :</label>
                <select id="mode_paiement" name="mode_paiement" required>
                    <option value="carte_credit">Carte de credit</option>
                    <option value="paypal">PayPal</option>
                    <option value="especes">Especes</option>
                </select>

                <div id="carte_credit" class="mode_paiement_fields">
                    <form method="POST" action="index.php?page=event_inscription">
                        <input type="hidden" name="eventid" value="<?= htmlspecialchars((string) $eventid) ?>">
                        <input type="hidden" name="mode_paiement" value="carte_credit">
                        <input type="hidden" name="final_validation" value="1">

                        <label class="payment-field">
                            <span>Numero de carte</span>
                            <input type="text" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX" required>
                        </label>

                        <label class="payment-field">
                            <span>Date d expiration</span>
                            <input type="text" name="expiration" placeholder="MM/AA" required>
                        </label>

                        <label class="payment-field">
                            <span>CVV</span>
                            <input type="text" name="cvv" placeholder="XXX" required>
                        </label>

                        <button type="submit" class="validate-button">Valider l inscription</button>
                    </form>
                </div>

                <div id="paypal" class="mode_paiement_fields" style="display: none;">
                    <form method="POST" action="index.php?page=event_inscription">
                        <input type="hidden" name="eventid" value="<?= htmlspecialchars((string) $eventid) ?>">
                        <input type="hidden" name="mode_paiement" value="paypal">
                        <input type="hidden" name="final_validation" value="1">

                        <button type="button" class="paypal-button">Se connecter a PayPal</button>
                        <button type="submit" class="validate-button">Valider l inscription</button>
                    </form>
                </div>

                <div id="especes" class="mode_paiement_fields" style="display: none;">
                    <form method="POST" action="index.php?page=event_inscription">
                        <input type="hidden" name="eventid" value="<?= htmlspecialchars((string) $eventid) ?>">
                        <input type="hidden" name="mode_paiement" value="especes">
                        <input type="hidden" name="final_validation" value="1">

                        <p class="cash-note">Le paiement en especes sera a remettre lors de la permanence ou le jour de l evenement.</p>
                        <button type="submit" class="validate-button">Valider l inscription</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const modePaiementSelect = document.getElementById('mode_paiement');
        const paymentSections = document.querySelectorAll('.mode_paiement_fields');

        function updatePaymentMode(modePaiement) {
            paymentSections.forEach((section) => {
                section.style.display = section.id === modePaiement ? 'block' : 'none';
            });
        }

        modePaiementSelect.addEventListener('change', function() {
            updatePaymentMode(this.value);
        });

        updatePaymentMode(modePaiementSelect.value);
    </script>

    <?php require_once __DIR__ . '/../Controller/FooterController.php'; ?>
</body>

</html>