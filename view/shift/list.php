<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div>
    <?= headerForList("shift",$bases,$selectedBaseID,$suggestedModels,$emptyBase) ?>
</div>
<div>
    <?= listSheet("shift", $sheets) ?>
</div>

<script src="js/shift.js"></script>
<?php
$content = ob_get_clean();
require GABARIT;
?>
