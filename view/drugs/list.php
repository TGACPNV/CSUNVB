<?php
ob_start();
$title = "CSU-NVB - Drogues hebdomadaires";
?>
<div>
    <form><!-- Liste déroulante pour le choix de la base -->
        <input type="hidden" name="action" value="listDrugSheets">
        <div class="row">
            <h1 class="mr-3">Gestion des stupéfiants à </h1>
            <select onchange="this.form.submit()" name="id" size="1" class="bigfont mb-3">
                <?php foreach ($baseList as $base) : ?>
                    <option value="<?= $base['id'] ?>" <?= ($selectedBaseID == $base['id']) ? 'selected' : '' ?>
                            name="base"><?= $base['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <div class="newSheetZone"> <!-- Bouton de nouvelle semaine -->
        <?php if (ican('createsheet') && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
            <form method="POST" action="?action=newDrugSheet" style="margin-block-end: 0;" class="float-right">
                <button type="submit" class="btn btn-primary m-1">Nouvelle semaine</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?= listSheet('drug', $drugSheetList) ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
