<?php
/**
 * Auteur: David Roulet / Fabien Masson
 * Date: Aril 2020
 **/

$title = "CSU-NVB - Stupéfiants";
ob_start();
?>
<div class="row m-2">
    <h1>Stupéfiants</h1>
</div>
<h2>Site de <?= $site ?>, Semaine N°<?= $drugsheet["week"] ?> <?= showSheetState($drugsheet["week"], "drugs") ?></h2>
<?php //TODO: a simplifier ?>
<a href='?action=<?= getDrugSheetStateButton(getDrugSheetState($drugsheet["base_id"], $drugsheet["week"])["state"]); ?>DrugSheet&id=<?= $drugsheet["base_id"] ?>&week=<?= $drugsheet["week"]?>'>
    <button class='btn btn-primary btn-sm ml-3'><?= getDrugSheetStateButton(getDrugSheetState($drugsheet["base_id"], $drugsheet["week"])["state"]) ?></button>
</a>
<?php foreach ($dates as $date): ?>
    <table border="1" class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th colspan="6" <?= ($date == date("Y-m-d")) ? "class='today'" : "" ?>>
                    <?= displayDate($date,1) ?>
                </th>
            </tr>
            <tr>
            <th>
                <?php //TODO: th a supprimer? ?>
            </th>
            <th>Pharmacie (matin)</th>
            <?php foreach ($novas as $nova): ?>
                <th><?= $nova["number"] ?></th>
            <?php endforeach; ?>
            <th>Pharmacie (soir)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($drugs as $drug): ?>
                <tr>
                    <td class="font-weight-bold"><?= $drug["name"] ?></td>
                    <td><?php //TODO: td a supprimer? ?></td>
                    <?php foreach ($novas as $nova): ?>
                        <?php $ncheck = getNovaCheckByDateAndBatch($date, $drug['id'], $nova['id'], $drugsheet['id']); // not great practice, but it spares repeated queries on the db ?>
                        <td>
                            <div class="text-center">
                                <?= $ncheck ? $ncheck["start"] : ''?> - <?= $ncheck ? $ncheck["end"] : ''?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                    <td><?php //TODO: td a supprimer? ?></td>
                </tr>
                <!-- Plusieurs lignes avec les batches nom de ce médicament, les restocks et les pharmachecks -->
                <?php foreach ($batchesByDrugId[$drug["id"]] as $batch): ?>
                    <?php $pcheck = getPharmaCheckByDateAndBatch($date, $batch['id'], $drugsheet['id']); // not great practice, but  it spares repeated queries on the db ?>
                    <tr>
                        <td class="text-right"><?= $batch['number'] ?></td>
                        <td class="text-center"><?= $pcheck ? $pcheck['start'] : '' ?></td>
                        <?php foreach ($novas as $nova): ?>
                            <td class="text-center"><?= getRestockByDateAndDrug($date,$batch['id'],$nova['id']) ?></td>
                        <?php endforeach; ?>
                        <td class="text-center"><?= $pcheck ? $pcheck['end'] : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr>
                <td>Signature</td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>
<?php endforeach; ?>

<?php
$content = ob_get_clean();
require GABARIT;
?>
