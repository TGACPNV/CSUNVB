<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
    <h2>Semaine <?= $week['week'] ?> - Base de <?= $base['name']?> [<?= $week['displayname'] ?>]</h2>
    <div class="d-flex justify-content-end d-print-none">

        <button type='submit' class='btn btn-primary m-1 float-right' onclick="window.print()" <?= !$edition ? '':'disabled'?> >Télécharger en PDF</button>
        <form>
            <input type="hidden" name="action" value="listtodoforbase">
            <input type="hidden" name="id" value="<?= $base['id'] ?>">
            <button type="submit" class='btn btn-primary m-1 float-right'>Retour à la liste</button>
        </form>
    </div>
</div>
<div class="d-flex justify-content-between d-print-none">
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
    <div class="d-flex flex-row"> <!-- If user is admin and sheet is "blank" then show modification button -->
        <?php if(ican ("modifySheet") && $week['slug'] == "blank") : ?>
            <?php if($edition) :
                $text = "Quitter édition";
            else:
                 $text = "Mode édition";
           endif; ?>
            <form action="?action=todoEditionMode" method="POST">
                <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                <input type="hidden" name="edition" value="<?= $edition ?>">
                <button type="submit" class='btn btn-warning m-1 float-right'><?= $text ?></button>
            </form>
        <?php endif; ?>
        <?=  slugButtons("todo", $week, $week['slug'])?>
    </div>
</div>

<?php if(ican ("modifySheet") && $edition) : ?> <!-- Zone d'ajout de nouvelle tâche -->
    <div class="d-print-none" style="border: solid; padding: 5px; margin: 2px; margin-top: 15px; margin-bottom: 15px">
        <form action="POST" action="?action=addTask" class="d-flex justify-content-between">
            <div class="d-flex">
                <div>
                    <label for="missingTaskDay" style="padding: 0 15px">Jour de la semaine </label>
                    <select name="day" id="missingTaskDay" class='missingTasksChoice' style="width: 100px;">
                        <option value="default"></option>
                        <?php foreach ($dates as $index => $date) : ?>
                            <option name="day" value="<?= $index + 1 ?>" ><?= $days[$index + 1] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="missingTaskTime" style="padding: 0 15px">Créneau </label>
                    <select name="dayTime" id="missingTaskTime" style="width: 100px;" class="missingTasksChoice float-right">
                        <option value="default"></option>
                        <option name="dayTime" value="1" >Jour</option>
                        <option name="dayTime" value="0" >Nuit</option>
                    </select>
                </div>
                <div style="padding: 20px 20px 0;" >
                        <?= dropdownTodoMissingTask($todoThings[1][1]) ?>
                </div>
            </div>

            <button type="submit" class='btn btn-primary m-1'>Ajouter la tâche</button>
        </form>
    </div>
<?php endif; ?>

<div> <!-- Affichage des tâches -->
    <div class="week text-center p-0" style="margin-top: 15px">
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
                    <?= buttonTask($todothing['initials'], $todothing['description'], $todothing['id'], $todothing['type'], $week['slug'], $edition, $date) ?>
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
                    <?= buttonTask($todothing['initials'], $todothing['description'], $todothing['id'], $todothing['type'], $week['slug'], $edition, $date) ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
</div>

<!-- Pop-up pour les quittances de tâches -->
<div class="modal fade" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="modal-taskValidation" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-validationTitle"></h5>
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
                    <div id="modal-validationContent"></div>
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
<!-- Pop up pour la suppression de tâches -->
<div class="modal fade" id="deletingTaskModal" tabindex="-1" role="dialog" aria-labelledby="modal-taskDelete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-deletingTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="?action=destroyTaskTodo">
                <input type="hidden" name="todosheetID" value="<?= $week['id'] ?>">
                <input type="hidden" id="modal-deletingTaskID" name="taskID" value="">
                <div class="modal-body" >
                    <div id="modal-deletingContent"></div>
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

