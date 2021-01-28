<?php
/**
 * Title: CSUNVB
 * USER: marwan.alhelo
 * DATE: 13.02.2020
 * Time: 11:29
 **/

ob_start();
$title = "CSU-NVB - Accueil";
?>

<div class="container ">
    <div class="row m-4 d-flex justify-content-center">
        <a class="col-4 text-decoration-none text-black-50 text-center p-5" href="?action=listshift"><i class="fas fa-file-signature fa-8x"></i><br>Remise de garde</a>
        <a class="col-4 text-decoration-none text-black-50 text-center p-5" href="?action=listtodo"><i class="far fa-check-circle fa-8x"></i><br>Tâches hebdomadaires</a>
        <a class="col-4 text-decoration-none text-black-50 text-center p-5" href="?action=listDrugSheets"><i class="fas fa-syringe fa-8x"></i><br>Stupéfiants</a>
        <?php if ($_SESSION['user']['admin'] == true): ?>
            <a class="col-4 text-decoration-none text-black-50 text-center p-5" href="?action=adminHome"><i class="fas fa-users-cog fa-8x"></i><br>Administration</a>
        <?php endif; ?>
    </div>

</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
