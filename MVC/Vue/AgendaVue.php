<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Agenda</title>
    <link rel="stylesheet" href="Asset/Style/planner_style.css">
</head>

<body class="body_margin">

    <?php require_once __DIR__ . "/../Controller/HeaderController.php"; ?>

    <h1>Agenda</h1>

    <div>
        <iframe src="https://edt.gemino.dev" width="100%" height="600px" style="border:none;">
        </iframe>
    </div>

    <?php require_once __DIR__ . "/../Controller/FooterController.php"; ?>
</body>

</html>
