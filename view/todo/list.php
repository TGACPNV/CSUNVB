<!--
 * Projet   : CSUNVB
 * Fichier  : homeToDo.php
 * Auteur   : Vicky BUTTY
 * Version  : 2.0
 * Description: Permet l'affichage sous forme de liste des différents rapports hebdomadaires.
 * Page basée sur le travail précédement réalisé par Marwan ALHELO [13.02.2020] & Gatien JAYME [27.08.2020]
-->
<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
</div>

<div>
    <form>
        <input type="hidden" name="action" value="listtodoforbase">
        <select onchange="this.form.submit()" name="id">
            <?php
                foreach ($baseList as $base) {
                    echo "<option value='".$base['id']."'";
                    if($base['id'] == $selectedBaseID){
                        echo "selected='selected'";
                    }
                    echo ">".$base['name'];
                }?>
        </select>
        <select onchange="this.form.submit()" name="selectModel">
            <!-- TODO (XCL) : faire une fonction getTemplates() qui retourne un tableau des noms de modèles connus -->
            <?php foreach ($templates as $template): ?>
                <option><?= $template['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($_SESSION['user']['admin'] == 1) { ?>
            <a href="?action=addWeek&base=<?= $selectedBaseID ?>" class="btn btn-primary m-1 pull-right">Nouvelle semaine</a>
        <?php } ?>
    </form>
</div>
<div>
    <h3>Semaine en cours</h3>
    <?php if(isset($activeWeek['week'])): ?>
        <table class="table">
            <thead class="thead-dark">
                <th>Semaine n°</th>
                <th>Statut</th>
                <th>Actions</th>
            </thead>
            <tbody>
            <tr>
                <td>Semaine <?=$activeWeek['week']?></td>
                <td>En cours</td>
                <td>
                    <form>
                        <input type="hidden" name="action" value="showtodo">
                        <input type="hidden" name="id" value="<?= $activeWeek['id'] ?>">
                        <button type="submit" class='btn btn-primary m-1 float-right'>Détails</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
    <?php else :?>
        <div>
            <p>Aucune feuille de tâche n'est actuellement ouverte.</p>
        </div>
    <?php endif; ?>
</div>
<div>
    <h3>Semaines précédentes</h3>
    <?php if(!empty($weeksNbrList)): ?>
        <table class="table">
            <thead class="thead-dark">
            <th>Semaine n°</th>
            <th>Statut</th>
            <th>Actions</th>
            </thead>
            <tbody>
            <?php foreach ($weeksNbrList as $week):?>
                <tr>
                    <td>Semaine <?=$week['week']?></td>
                    <td>Cloturé</td>
                    <td>
                        <form>
                            <input type="hidden" name="action" value="showtodo">
                            <input type="hidden" name="id" value="<?= $week['id'] ?>">
                            <button type="submit" class='btn btn-primary m-1 float-right'>Détails</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else :?>
        <div>
            <p>Aucune feuille de tâche n'est encore cloturée.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>