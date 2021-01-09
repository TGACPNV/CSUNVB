<?php
ob_start();
$title = "CSU-NVB - Drogues hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
</div>
<div>
    <form>
        <input type="hidden" name="action" value="listDrugSheets">
            <select onchange="this.form.submit()" name="id" size="1">
                <?php foreach ($baseList as $base) : ?>
                    <option value="<?= $base['id'] ?>" <?= ($selectedBaseID == $base['id']) ? 'selected' : '' ?>
                            name="site"><?= $base['name'] ?></option>
                <?php endforeach; ?>
            </select>
    </form>
    <div class="newSheetZone"> <!-- Bouton de nouvelle semaine -->
        <?php if (ican('createsheet') && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
            <form method="POST" action="?action=newDrugSheet" style="margin-block-end: 0;" class="float-right">
                <button type="submit" class="btn btn-primary m-1">Nouvelle semaine</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div> <!-- Sections d'affichage des différentes feuilles -->
    <?php foreach ($slugs as $slug) : ?>
    <div> <!-- Feuilles ouvertes -->
        <?= showDrugSheetsByStatus($slug['slug'], $drugSheetList[$slug['slug']]) ?>
    </div>
    <br>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>