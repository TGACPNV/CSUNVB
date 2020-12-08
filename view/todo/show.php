<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
    <h2>Semaine <?= $week['week'] ?> - Base de <?= $base['name'] ?></h2>
    <div>
        <div>
            <form>
                <input type="hidden" name="action" value="listtodoforbase">
                <input type="hidden" name="id" value="<?= $base['id'] ?>">
                <button type="submit" class='btn btn-primary m-1 float-right'>Retour à la liste</button>
            </form>
            <form action="?action=modelWeek" method="POST">
                <input type="hidden" name="weekID" value="<?= $week['id'] ?>">
                <input type="hidden" name="baseID" value="<?= $base['id'] ?>">
                <input type="hidden" name="template_name" value="<?= $template['template_name'] ?>">

                <button type="submit" class='btn btn-primary m-1 float-right'>Sauvegarder le nom</button>
            </form>
        </div>
        <?php if ($_SESSION['user']['admin'] == 1 && $alreadyOpen == false && $week['state'] == "close"): ?>
            <div>
                <form>
                    <input type="hidden" name="action" value="reopenweek">
                    <input type="hidden" name="id" value="<?= $week['id'] ?>">
                    <button type="submit" class='btn btn-primary m-1 float-right'>Réouvrir</button>
                </form>
            </div>
        <?php elseif ($_SESSION['user']['admin'] == 1 && $week['state'] == "open"): ?>
            <div>
                <form>
                    <input type="hidden" name="action" value="closeweek">
                    <input type="hidden" name="id" value="<?= $week['id'] ?>">
                    <button type="submit" class='btn btn-primary m-1 float-right'>Clôturer</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <br>
    <br>
</div>
<div>
    <div class="week text-center p-0">
        <?php foreach ($dates as $index => $date) : ?>
            <div class='bg-dark text-white col-md font-weight-bold'><?= $days[$index + 1] ?><br><?= $date ?></div>
        <?php endforeach; ?>
    </div>
    <div class="week text-center bg-secondary">
        <div class="col-md font-weight-bold text-white">Jour</div>
    </div>
    <div class="row week hour">
        <?php foreach ($dates as $index => $date) : ?>
            <div class="col p-1">
                <?php foreach ($todoThings[1][$index + 1] as $todothing): ?>
                    <?= buttonTask($todothing['initials'], $todothing['description'], $week['state']) ?>
                <?php endforeach;?>
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
                    <?= buttonTask($todothing['initials'], $todothing['description'], $week['state']) ?>
                <?php endforeach;?>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
</div>
<div>
    <?= popUpValidation("Quittance","sans valeur")?>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
