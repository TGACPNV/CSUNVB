<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
    <h2>Semaine <?= $week['week'] ?> - Base de <?= $base['name']?> <?= showSheetState($week['id'], "todo") ?></h2>
    <div class="d-flex justify-content-end">
        <form  method='POST' action='?action=todoSheetToPDF&id=<?=$week['id']?>'>
            <button type='submit' class='btn btn-primary m-1 float-right'>Télécharger en PDF</button>
        </form>
        <form>
            <input type="hidden" name="action" value="listtodoforbase">
            <input type="hidden" name="id" value="<?= $base['id'] ?>">
            <button type="submit" class='btn btn-primary m-1 float-right'>Retour à la liste</button>
        </form>
    </div>
</div>
<div class="d-flex justify-content-between">
    <div class="d-flex flex-row"> <!-- Boutons relatifs aux modèles -->
        <?php if(ican ("createTemplate") && is_null($template['template_name'])) : ?>
            <form action="?action=modelWeek" method="POST">
                <button type="submit" class='btn btn-primary m-1'>Retenir comme modèle</button>
                <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                <input type="hidden" name="baseID" value="<?= $base['id'] ?>">
                <input type="text" name="template_name" value="" placeholder="Nom du modèle" required>
            </form>
        <?php elseif(ican ("deleteTemplate") && !is_null($template['template_name'])): ?>
            <form action="?action=deleteTemplate" method="POST">
                <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                <button type="submit" class='btn btn-primary m-1'>Oublier le modèle</button>
            </form>
            <div style="padding: 5px"> Nom du modèle : <?= $template['template_name'] ?></div>
        <?php endif; ?>

    </div>
    <div class="d-flex flex-row"> <!-- Boutons relatifs à l'état de la feuille -->
        <?php if(ican ("modifySheet") && $week['slug'] == "blank") : ?>
            <?php if($edition == false) : ?>
                <form action="?action=modTemplate" method="POST">
                    <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                    <input type="hidden" name="edition" value="<?= $edition ?>">
                    <button type="submit" class='btn btn-primary m-1 float-right'>Mode édition</button>
                </form>
            <?php else:?>
                <form action="?action=modTemplate" method="POST">
                    <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                    <input type="hidden" name="edition" value="<?= $edition ?>">
                    <button type="submit" class='btn btn-primary m-1 float-right'>Quitter édition</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
        <?=  slugsButtonTodo($week['slug'], $week['id'])?>
    </div>
</div>
<div>
    <div class="week text-center p-0">
        <?php foreach ($dates as $index => $date) : ?>
            <div class='bg-dark text-white col-md font-weight-bold'><?= $days[$index + 1] ?>
                <br><?= displayDate($date, 0) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="week text-center bg-secondary">
        <div class="col-md font-weight-bold text-white">Jour</div>
    </div>
    <div class="row week hour">
        <?php foreach ($dates as $index => $date) : ?>
            <div class="col p-1">
                <?php foreach ($todoThings[1][$index + 1] as $todothing): ?>
                    <?= buttonTask($todothing['initials'], $todothing['description'], $todothing['id'], $todothing['type'], $week['slug'], $edition) ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <div class="week text-center bg-secondary">
        <div class="col-md font-weight-bold  text-white">Nuit</div>
    </div>
    <div class="row week hour">
        <?php foreach ($dates as $index => $date) : ?>
            <div class="col p-1">
                <?php foreach ($todoThings[0][$index + 1] as $todothing): ?>
                    <?= buttonTask($todothing['initials'], $todothing['description'], $todothing['id'], $todothing['type'], $week['slug'], $edition) ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
</div>
<!-- Affichage de la pop-up pour les quittances -->
<div class="modal fade" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="modal-taskValidation"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="?action=switchTodoStatus">
                <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                <input type="hidden" id="modal-todoType" name="modal-todoType" value="">
                <input type="hidden" id="modal-todoID" name="modal-todoID" value="">
                <input type="hidden" id="modal-todoStatus" name="modal-todoStatus" value="">
                <div class="modal-body" >
                    <div id="modal-content"></div>
                    <input type="hidden" id="modal-todoValue" name="modal-todoValue">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Valider</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/todo.js"></script>
<?php
$content = ob_get_clean();
require GABARIT;
?>

