<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<<<<<<< HEAD
<script src="js/shift.js"></script>
<div>
    <form><!-- Liste déroulante pour le choix de la base -->
        <input type="hidden" name="action" value="listshift">
        <div class="row">
            <h1 class="mr-3">Remise de Garde à </h1>
            <select onchange="this.form.submit()" name="id" size="1" class="bigfont mb-3">
                <?php foreach ($bases as $base) : ?>
                    <option value="<?= $base['id'] ?>" <?= ($baseID == $base['id']) ? 'selected' : '' ?>
                            name="base"><?= $base['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <div class="newSheetZone"> <!-- Liste déroulante pour le choix du modèle et bouton de nouvelle semaine -->
        <?php if (ican('createsheet') && ($_SESSION['base']['id'] == $baseID)) : ?>
            <form method="POST" action="?action=newShiftSheet&id=<?= $baseID ?>" class="float-right">
                <select name="selectModel">
                    <option value='lastValue' selected=selected>En développement</option>
                    <?php foreach ($templates as $template) : ?>
                        <option value='<?= $template['template_name'] ?>'><?= $template['template_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button class='btn btn-primary m-1'>Nouvelle Feuille de garde</button>
            </form>
        <?php endif; ?>
    </div>
=======
<div>
    <?= headerForList("shift",$bases,$selectedBaseID,$models) ?>
>>>>>>> 230c62154a507ab0c9c7cef6056e61692418bf3c
</div>

<div>
    <?= listSheet("shift", $sheets) ?>
</div>

<script src="js/shift.js"></script>
<?php
$content = ob_get_clean();
require GABARIT;
?>
