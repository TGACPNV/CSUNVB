<!--
 * Projet   : CSUNVB
 * Fichier  : homeToDo.php
 * Auteur   : Vicky BUTTY
 * Version  : 2.0
 * Description: Permet l'affichage sous forme de liste des différents rapports hebdomadaires.
 * Page basée, en partie, sur le travail précédement réalisé par Marwan ALHELO [13.02.2020] & Gatien JAYME [27.08.2020]
-->
<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
</div>
<div>
    <div> <!-- Liste déroulante pour le choix de la base -->
        <form>
            <input type="hidden" name="action" value="listtodoforbase">
            <select onchange="this.form.submit()" name="id" size="1">
                <?php foreach ($baseList as $base) : ?>
                    <option value="<?= $base['id'] ?>" <?= ($selectedBaseID == $base['id']) ? 'selected' : '' ?>
                            name="site"><?= $base['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="newSheetZone"> <!-- Liste déroulante pour le choix du modèle et bouton de nouvelle semaine -->
        <?php if (ican('createsheet') && ($_SESSION['base']['id'] == $selectedBaseID)) : ?>
            <form method="POST" action="?action=addWeek" class="float-right">
                <select name="selectModel">
                    <?php if (!is_null($maxID['id'])): ?>
                        <option value='lastValue' selected=selected>Dernière semaine en date</option>
                    <?php endif; ?>
                    <?php foreach ($templates as $template) : ?>
                        <option value='<?= $template['template_name'] ?>'><?= $template['template_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary m-1 pull-right">Nouvelle semaine</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<div> <!-- Sections d'affichage des différentes feuilles -->
    <div> <!-- Feuilles ouvertes -->
        <?= showSheetsTodoByStatus("open", $openWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles en préparation -->
        <?= showSheetsTodoByStatus("blank", $blankWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles en correction -->
        <?= showSheetsTodoByStatus("reopen", $reopenWeeks) ?>
    </div>
    <br>
    <div> <!-- Feuilles fermées -->
        <?= showSheetsTodoByStatus("close", $closeWeeks) ?>
    </div>
    <br>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
