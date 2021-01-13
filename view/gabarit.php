<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>
        <?= (isset($title)) ? $title : "Page sans nom" ?>
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- paths are from root ( where there is index.php ) -->
    <link href="assets/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/shift.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/@fortawesome/fontawesome-free/css/all.css" rel="stylesheet">



    <script src="assets/jquery/dist/jquery.js"></script>
    <script src="assets/bootstrap/dist/js/bootstrap.js"></script>
    <script src="js/global.js" defer></script>
</head>
<body>
<div class="container">
    <header>
        <div class="row banner">
            <a href="?action=home" class="col-auto">
                <img class="logo" src="assets/images/logo.png">
            </a>
            <div class="title col text-center">
                Gestion des rapports
                <?= gitBranchTag() ?>
            </div>
        </div>
        <div>
            <a href="?action=home" class="btn btn-primary m-1 float-right">Accueil</a>
            <?php if (isset($_SESSION['user'])) : ?>
                <a href="?action=disconnect" class="btn btn-primary m-1 float-right">Se déconnecter</a>
                <p>Connecté en tant que : <strong><?= $_SESSION['user']['initials'] ?></strong> à
                    <strong><?= $_SESSION['base']['name'] ?></strong>
                </p>
            <?php endif; ?>
        </div>
    </header>
</div>

<div class="container">
    <?= getFlashMessage() ?>
    <?= (isset($content)) ? $content : "page vide" ?>
</div>
</body>
</html>
