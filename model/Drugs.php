<?php
//-------------------------------------- admin --------------------------------------------
/**
 * Retourne la liste des médicaments connus (table 'drugs')
 */
function getDrugs() {
    return selectMany("SELECT * FROM drugs");
}

function addNewDrug($drugName) {
    return insert("INSERT INTO drugs (name) values ('$drugName')");
}

function updateDrugName($updatedName, $drugID) {
    return execute("UPDATE drugs SET name='$updatedName' WHERE id='$drugID'");
}

//-------------------------------------- drugs --------------------------------------------
/**
 *  Retourne une sheet précise
 */
function getDrugSheetById($sheet_id) {
    return selectOne("SELECT * FROM drugsheets WHERE id = :id",['id' => $sheet_id]);
}

/**
 * Retourne la liste des drugsheets pour une base donnée.
 */
function getDrugSheets($base_id) {
    return selectMany("SELECT id, week, state FROM drugsheets WHERE base_id=:id", ['id' => $base_id]);
}

/**
 * Retourne la liste des novas 'utilisées' par cette feuille
 * Les données retournées sont dans un tableau indexé par id (i.e: [ 12 => [ "id" => 12, "value" => ...], 17 => [ "id" => 17, "value" => ...] ]
 */
function getNovasForSheet($drugSheetID) {
    return selectMany("SELECT novas.id as id, number FROM novas INNER JOIN drugsheet_use_nova ON nova_id = novas.id WHERE drugsheet_id ='$drugSheetID'");
}

/**
 * Retourne les batches de médicaments utilisés sur un rapport précis
 * @param $drugSheetID
 * @return array|mixed|null
 */
function getBatchesForSheet($drugSheetID) {
    return selectMany("SELECT batches.id AS id, number, drug_id FROM batches INNER JOIN drugsheet_use_batch ON batches.id = batch_id WHERE drugsheet_id='$drugSheetID'");
}

/**
 * Retourne le pharmacheck du jour donné pour un batch précis lors de son utilisation dans une drugsheet
 */
function getPharmaCheckByDateAndBatch($date, $batch, $drugSheetID) {
    $res = selectOne("SELECT start,end FROM pharmachecks WHERE date='$date' AND batch_id='$batch' AND drugsheet_id='$drugSheetID'");
    return $res;
}

/**
 * Retourne le novacheck du jour donné pour un médicament précis dans une nova lors de son utilisation dans une drugsheet
 */
function getNovaCheckByDateAndBatch($date, $drug, $nova, $drugSheetID) {
    return selectOne("SELECT start,end FROM novachecks WHERE date='$date' AND drug_id='$drug' AND nova_id='$nova' AND drugsheet_id='$drugSheetID'");
}

/**
 * Retourne le restock du jour donné pour un batch précis dans une nova lors de son utilisation dans une drugsheet
 */
function getRestockByDateAndDrug($date, $batch, $nova) {
    $res = selectOne("SELECT quantity FROM restocks WHERE date='$date' AND batch_id='$batch' AND nova_id='$nova'");
    return $res ? $res['quantity'] : ''; // chaîne vide si pas de restock
}
function readLastDrugSheet($base_id) {
    return selectOne("SELECT base_id, MAX(week) as 'lastWeek' FROM drugsheets WHERE base_id ='$base_id' GROUP BY base_id");
}

function insertDrugSheet($base_id, $lastWeek) {
    //magnifique, passe a la nouvelle annee grace a +48 si 52eme semaine
    if(date("D", $lastWeek / 100 * 100 . "-01-01") == "Wed" || date("D", $lastWeek / 100 * 100 . "-01-01") == "thu")
        echo "oui cette annee elle a au totale : 53 semaines";
    (($lastWeek % 100) == 52) ? $lastWeek += 49 : $lastWeek++;
    return insert("INSERT INTO drugsheets (base_id,state,week) VALUES ('$base_id', 'vierge', '$lastWeek')");
}

function updateSheetState($baseID, $week, $state) {
    return execute("UPDATE drugsheets SET state='$state' WHERE base_id='$baseID' AND week='$week'");
}

function getOpenDrugSheet($baseID) {
    return selectOne("SELECT state FROM drugsheets WHERE state = 'open'");
}

function getDrugSheetState($baseID, $week) {
    return selectOne("SELECT state FROM drugsheets WHERE base_id = '$baseID' AND week = '$week'");
}

function getStateFromDrugs($id){
    return selectOne("SELECT status.slug FROM status LEFT JOIN drugsheets ON drugsheets.status_id = status.id WHERE drugsheets.id =:sheetID", ["sheetID"=>$id]);
}