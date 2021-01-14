<?php

ob_start();
$title = "CSU-NVB - error 404";
?>

<h1>Erreur : la page n'existe pas ou vous n'êtes pas autorisé(e) à y accéder</h1>

<?php
$content = ob_get_clean();
require GABARIT;
?>
