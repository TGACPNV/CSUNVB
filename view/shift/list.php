<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
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
        <?php if (ican('createsheet')) : ?>
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

</div>


<div id="tableContent">
    <?= listSheet("shift", $sheets) ?>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
