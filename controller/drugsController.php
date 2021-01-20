<?php
/**
 * Auteur: David Roulet / Fabien Masson + h
 * Date: Avril 2020 + 2020/11
 **/

//Affiche la page de selection de la semaine pour une base choisie
function listDrugSheets($selectedBaseID = null) {
    if (is_null($selectedBaseID))
        $selectedBaseID = $_SESSION["base"]["id"];
    $baseList = getbases();
    $slugs = getSlugs();
    foreach($slugs as $slug) {
        $drugSheetList[$slug['slug']] = getDrugSheetsByState($selectedBaseID, $slug['slug']);
    }
    require_once VIEW . 'drugs/list.php';
}

// Affichage de la page finale
function showDrugSheet($drugSheetID) {
    $drugsheet = getDrugSheetById($drugSheetID);
    $dates = getDaysForWeekNumber($drugsheet["week"]);
    $novas = getNovasForSheet($drugSheetID);
    $BatchesForSheet = getBatchesForSheet($drugSheetID); // Obtient la liste des batches utilisées par cette feuille
    foreach ($BatchesForSheet as $p) {
        $batchesByDrugId[$p["drug_id"]][] = $p;
    }
    $drugs = getDrugsInDrugSheet($drugSheetID);
    $site = getbasebyid($drugsheet['base_id']);
    require_once VIEW . 'drugs/show.php';
}

function newDrugSheet($baseID = null) {
    if (is_null($baseID))
        $baseID = $_SESSION["base"]["id"];
    $oldSheet = getLatestDrugSheetWeekNb($baseID);
    cloneLatestDrugSheet(insertDrugSheet($baseID, $oldSheet['week']), $oldSheet['id']);
    redirect("listDrugSheets", $baseID);
}

function hasOpenDrugSheet($baseID) {
    return boolval(getOpenDrugSheet($baseID));
}

function drugsDeleteSheet($baseID = null) {
    if (is_null($baseID))
        $baseID = $_SESSION["base"]["id"];
    removeDrugSheet($_POST['id']);
    redirect("listDrugSheets", $baseID);
}
function drugSheetSwitchState() {
    updateSheetState($_POST["id"], getStatusID($_POST['newSlug']));
    redirect("listDrugSheets", $_SESSION["base"]["id"]);
}