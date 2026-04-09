<head>
    <title>Supprimer le compte</title>
    <link rel="stylesheet" href="Asset/Style/delete_account_style.css">
</head>

<body>
    <div id="deleteAccountAlert" class="alert-container">
        <div class="alert-content">
            <p>
                ⚠️ Vous êtes sur le point de supprimer votre compte. Cette action est irréversible.
                Toutes vos données seront perdues. Veuillez cocher la case ci-dessous pour confirmer que vous comprenez les conséquences.
            </p>

            <input type="checkbox" id="confirmCheckbox">
            <label for="confirmCheckbox">J'ai compris</label>
            <br><br>

            <ul>
                <li>
                    <form action="index.php?page=delete_account" method="POST">
                        <button id="confirmDelete" name="delete_account_valid" value="true" disabled>Valider</button>
                    </form>
                </li>
                <li>
                    <button type="button" id="cancelDelete" onclick="window.location.href='index.php?page=account'">
                        Revenir en arrière
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <script src="Asset/Script/confirm_account_supression.js"></script>
</body>

</html>