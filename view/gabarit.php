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
<div class="d-print-none banner">
    <header>
        <div class="row">
            <a href="?action=home" class="col-auto">
                <img class="logo m-3 justify-content-center" src="assets/images/logo.png">
            </a>
            <div class="title col mt-4">
                Gestion des rapports
            </div>
            <?php if (isset($_SESSION['user'])) : ?>
                <a href="?action=disconnect" class="btn btn-primary mt-2 mr-5 float-right"><div class="font-weight-bold m-2">Déconnecter</div><div class="small"><?= $_SESSION['user']['initials'] ?>@<?= $_SESSION['base']['name'] ?></div></a><br>
                <?= gitBranchTag() ?>
            <?php endif; ?>
        </div>
    </header>
</div>

<div class="container p-4">
    <?= getFlashMessage() ?>
    <?= (isset($content)) ? $content : "page vide" ?>
</div>
</body>
</html>
