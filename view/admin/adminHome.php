<?php
ob_start();
$title = "CSU-NVB - Administration";
?>
<div class="row m-4 d-flex justify-content-center">
    <a class="col-4 text-decoration-none text-black-50 text-center m-5 p-5" href="?action=adminCrew"><i class="fas fa-users fa-8x"></i><br>Secouristes</a>
    <a class="col-4 text-decoration-none text-black-50 text-center m-5 p-5" href="?action=adminBases"><i class="fas fa-hospital fa-8x"></i><br>Bases</a>
    <a class="col-4 text-decoration-none text-black-50 text-center m-5 p-5" href="?action=adminNovas"><i class="fas fa-ambulance fa-8x"></i><br>Ambulances</a>
    <a class="col-4 text-decoration-none text-black-50 text-center m-5 p-5" href="?action=adminDrugs"><i class="fas fa-syringe fa-8x"></i><br>Médicaments</a>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
