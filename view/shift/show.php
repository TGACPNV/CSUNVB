<?php
ob_start();
$title = "CSU-NVB - Remise de garde";
?>
<div>
    <h1>Remise de Garde</h1>
    <h2>Jour : <?= date('d.m.Y', strtotime($shiftsheet['date'])) ?> - Base de <?= $shiftsheet['baseName'] ?>
        [<?= $shiftsheet['displayname'] ?>]</h2>
    <div class='d-flex justify-content-end d-print-none'>
        <?= slugButtons("shift", $shiftsheet, $shiftsheet["status"]) ?>
        <form method='POST'>
            <input type='hidden' name='id' value='" . $sheet["id"] . "'>
            <input type='hidden' name='newSlug' value='open'>
            <button type='submit' class='btn btn-primary m-1' onclick="print_page()">Télécharger en PDF</button>
        </form>

        <form>
            <input type="hidden" name="action" value="listshift">
            <input type="hidden" name="id" value="<?= $shiftsheet["base_id"] ?>">
            <button type="submit" class='btn btn-primary m-1 float-right'>Retour à la liste</button>
        </form>
    </div>
</div>

<div class="container">
    <form action="?action=updateShift&id=<?= $shiftsheet['id'] ?>" method="POST">
        <input type=hidden name="id" value= <?= $shiftsheet['id'] ?>>
        <div class="row">
            <div class="col-auto">
                <table>
                    <tr>
                        <td class=""></td>
                        <td class=" text-center">Jour</td>
                        <td class=" text-center">Nuit</td>
                    </tr>
                    <tr>
                        <td class=" text-right">Novas</td>
                        <td class=" text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="novaDay" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['novaDay'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($novas as $nova): ?>
                                        <option value="<?= $nova['id'] ?>" <?= ($shiftsheet['novaDay'] == $nova['number']) ? 'selected' : '' ?>><?= $nova['number'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['novaDay'] ?>
                            <?php endif; ?>
                        </td>
                        <td class=" text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="novaNight" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['novaNight'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($novas as $nova): ?>
                                        <option value="<?= $nova['id'] ?>" <?= ($shiftsheet['novaNight'] == $nova['number']) ? 'selected' : '' ?>><?= $nova['number'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['novaNight'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class=" text-right">Responsable</td>
                        <td class="text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="bossDay" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['bossDay'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= ($shiftsheet['bossDay'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['bossDay'] ?>
                            <?php endif; ?>
                        </td>
                        <td class=" text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="bossNight" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['bossNight'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= ($shiftsheet['bossNight'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['bossNight'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class=" text-right">Équipier</td>
                        <td class=" text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="teammateDay" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['teammateDay'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= ($shiftsheet['teammateDay'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['teammateDay'] ?>
                            <?php endif; ?>
                        </td>
                        <td class=" text-center">
                            <?php if ($enableshiftsheetUpdate) : ?>
                                <select name="teammateNight" class="SH_dropdownInfo">
                                    <?= ($shiftsheet['teammateNight'] == NULL) ? '<option value="NULL" selected></option>' : '' ?>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" <?= ($shiftsheet['teammateNight'] == $user['initials']) ? 'selected' : '' ?>><?= $user['initials'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else : ?>
                                <?= $shiftsheet['teammateNight'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col" id="SH_updateInfoBtn">
                <button type="submit" class="btn btn-primary m-1 pull-right">Valider</button>
            </div>
        </div>
    </form>
</div>
<div class="container">
<?php foreach ($sections as $section): ?>
    <div class="row SH_sectionName"><?= $section["title"] ?></div>
    <table class="table table-bordered float-left">
        <thead class="thead-dark">
        <th></th>
        <th class="SH_checkCase">Jour</th>
        <th class="">Nuit</th>
        <th>Remarques</th>
        </thead>
        <tbody>
        <?php
        foreach ($section["actions"] as $action): ?>
            <tr>
                <td class="SH_actionCase">
                    <form action="?action=removeActionForShift&id=<?= $shiftsheet['id'] ?>" method="post">
                        <?php if ($shiftsheet['status'] == "blank" && $_SESSION['user']['admin'] == true): ?>

                            <input type="hidden" name="model" value="<?= $shiftsheet['model'] ?>">
                            <input type="hidden" name="action" value="<?= $action['id'] ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        <?php endif; ?>
                        <div class="SH_actionName"><?= $action['text'] ?></div>
                    </form>
                </td>
                <?php if ($enableshiftsheetFilling): ?>
                    <td class="SH_checkCase">
                        <button type="submit"
                                class="btn <?= (count($action["checksDay"]) == 0) ? 'btn-warning' : 'btn-success' ?> toggleShiftModal"
                                data-content="Valider <?= $action['text'] ?> : Jour"
                                data-action_id="<?= $action['id'] ?>" data-day="1" data-action="?action=checkShift"
                                data-comment="hidden">
                            <?php if (count($action["checksDay"]) == 0): ?>
                                A Valider
                            <?php else: ?>
                                Validé Par
                                <div class="text-success bg-white rounded mt-1">
                                    <?php foreach ($action["checksDay"] as $check): ?>
                                        <?= $check["initials"] ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </button>
                    </td>

                    <td class="SH_checkCase">
                        <button type="submit"
                                class="btn <?= (count($action["checksNight"]) == 0) ? 'btn-warning' : 'btn-success' ?> toggleShiftModal"
                                data-content="Valider <?= $action['text'] ?> : Nuit"
                                data-action_id="<?= $action['id'] ?>" data-day="0" data-action="?action=checkShift"
                                data-comment="hidden"
                        ">
                        <?php if (count($action["checksNight"]) == 0): ?>
                            A Valider
                        <?php else: ?>
                            Validé Par
                            <div class="text-success bg-white rounded mt-1">
                                <?php foreach ($action["checksNight"] as $check): ?>
                                    <?= $check["initials"] ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        </button>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment): ?>
                            <div class="<?= ($comment['carryOn'] == 1 and $comment['endOfCarryOn'] == null) ? 'carry' : 'notCarry' ?>"
                                 id="comment-<?= $comment['id'] ?>">

                                <button class="removeCarryOnBtn carried" value=<?= $comment['id'] ?>>
                                    <i class="fas fa-thumbtack fa-lg" style="color:#000000"></i>
                                </button>

                                <button class="addCarryOnBtn addCarry" value=<?= $comment['id'] ?>>
                                    <i class="fas fa-thumbtack fa-rotate-90 fa-lg" style="color:#777777"></i>
                                </button>

                                <strong>[ <?= $comment['initials'] ?>
                                    - <?= date('H:i', strtotime($comment['time'])) ?> <?= ($comment['carryOn'] == 1) ? date('/  d.m.Y ', strtotime($comment['time'])) : "" ?>
                                    ] :</strong>
                                <?= $comment['message'] ?>
                                <hr>
                            </div>

                        <?php endforeach; ?>


                        <button type="submit" class="btn bg-white btn-block m-1 toggleShiftModal d-print-none"
                                data-content="Ajouter un commentaire  à <?= $action['text'] ?>"
                                data-action_id="<?= $action['id'] ?>" data-action="?action=commentShift"
                                data-comment="text" style="width:200px;">
                            Nouveau commentaire
                        </button>


                    </td>
                <?php else: ?>
                    <td>
                        <?php foreach ($action["checksDay"] as $check): ?>
                            <?= $check["initials"] ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($action["checksNight"] as $check): ?>
                            <?= $check["initials"] ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($action["comments"] as $comment): ?>
                            [ <?= $comment['initials'] ?>, <?= $comment['time'] ?> ] : <?= $comment['message'] ?>
                            <br>
                        <?php endforeach; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        <?php if ($shiftsheet['status'] == "blank" && $_SESSION['user']['admin'] == true): ?>
            <tr>
                <td colspan="4" style="padding: 0px;">
                    <div>
                        <div class="float-left">
                            <form action="?action=addActionForShift&id=<?= $shiftsheet['id'] ?>" method="post">
                                <input type="hidden" name="model" value="<?= $shiftsheet['model'] ?>">
                                <button type="submit" class='btn btn-success m-1'
                                ">Ajouter</button>
                                <select name="actionID">
                                    <?php foreach ($section["unusedActions"] as $action): ?>
                                        <option value="<?= $action["id"] ?>"><?= $action["text"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                        <div class="float-left" style="margin-left: 50px">
                            <form action="?action=creatActionForShift&id=<?= $shiftsheet['id'] ?>" method="post">
                                <input type="hidden" name="model" value="<?= $shiftsheet['model'] ?>">
                                <input type="hidden" name="section" value="<?= $section['id'] ?>">
                                <button type="submit" class='btn btn-success m-1'
                                ">Créer</button>
                                <input type="text" name="actionToAdd" value="" style="margin : 6px;">
                            </form>
                        </div class="float-left">
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php endforeach; ?>
</div>
<div style='clear: both;  font-size: 14px; font-family: Helvetica; color: #8d8d8d; background: transparent;'>
    Modèle utilisé :
    <?php if ($model["name"] == ""): ?>
        Aucun
    <?php else: ?>
        <?= $model["name"] ?>
    <?php endif; ?>
    <div class="float-right d-print-none">
        <div class="d-flex flex-row">
            <?php if ($model["suggested"] == 0) : ?>
                <?php if ($model["name"] != "") : ?>
                    <button type="submit"
                            class="btn btn-primary toggleShiftModal m-1"
                            data-content="Voulez-vous ré-activer le modèle ?<br><strong><?= $model["name"] ?></strong><br>Il sera proposé à la création de nouveaux rapports"
                            data-action_id="<?= $shiftsheet["model"] ?>" data-action="?action=reAddShiftModel"
                            data-comment="hidden">
                        Ré-activer le modèle
                    </button>
                <?php else : ?>
                    <button type="submit"
                            class="btn btn-primary toggleShiftModal m-1"
                            data-content="Enregistrer comme nouveau modèle :<br><br><strong>Nom :</strong>"
                            data-action_id="<?= $shiftsheet["model"] ?>" data-day="1"
                            data-action="?action=addShiftModel"
                            data-comment="text" style="size:60%">
                        Enregistrer comme modèle
                    </button>
                <?php endif; ?>
            <?php else : ?>
                <?php if ($model["name"] != "Vide") : ?>
                    <button type="submit"
                            class="btn btn-primary toggleShiftModal m-1"
                            data-content="Voulez-vous vraiment retirer le modèle ?<br><strong><?= $model["name"] ?></strong><br>Il ne sera plus proposé à la création de nouveaux rapports"
                            data-action_id="<?= $shiftsheet["model"] ?>" data-action="?action=removeShiftModel"
                            data-comment="hidden">
                        Retirer le modèle
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" id="shiftSheetinfo" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">
                        Garde du <?= date('d.m.Y', strtotime($shiftsheet['date'])) ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-content">
                </div>
                <input type="hidden" name="action_id" id="action_id" value="0">
                <input type="hidden" name="shiftSheet_id" value="<?= $shiftsheet['id'] ?>">
                <input type="hidden" name="day" id="day" value="0">
                <input type="hidden" name='comment' id="comment" style='margin:0px 0px 10px 10px; width:400px;'>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <input type="submit" class="btn btn-primary" value="Valider">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="js/shift.js"></script>
<?php
$content = ob_get_clean();
require GABARIT;
?>
