<?php
/**
 * Shift.php : all funtion pertaining to database request -> for shift
 * Auteur: Gogniat Michael / Paola Costa
 * Date: Decembre 2020
 **/


function getshiftchecksForAction($action_id, $shiftsheet_id, $day)
{
    $checks = selectMany('SELECT shiftchecks.time, users.initials as initials FROM shiftchecks inner join users on users.id = shiftchecks.user_id where shiftaction_id =:action_id and shiftsheet_id =:shiftsheet_id and day=:day', ['action_id' => $action_id, 'shiftsheet_id' => $shiftsheet_id, 'day' => $day]);
    return $checks;
}

function getShiftCommentsForAction($action_id, $shiftsheet_id, $base_id)
{
    $comments = selectMany('SELECT shiftcomments.message, shiftcomments.carryOn, shiftcomments.id, shiftcomments.time, users.initials ,shiftsheets.date,shiftcomments.endOfCarryOn
FROM shiftcomments 
inner join users on users.id = shiftcomments.user_id
inner join shiftsheets on shiftsheets.id = shiftcomments.shiftsheet_id
WHERE shiftaction_id = :action_id AND (shiftsheets.id = :shiftsheet_id or ((carryOn = 1  AND ( (endOfCarryOn IS NULL AND :date > shiftsheets.date) OR :date BETWEEN shiftsheets.date AND endOfCarryOn)) and shiftsheets.base_id = :base_id))', ['action_id' => $action_id, 'shiftsheet_id' => $shiftsheet_id, 'base_id' => $base_id, 'date' => getshiftsheetByID($shiftsheet_id)["date"]]);
    return $comments;
}

function getSelectedActions($sectionID, $model_id)
{
    $actions = selectMany('SELECT shiftactions.* FROM shiftmodel_has_shiftaction
INNER JOIN shiftactions
ON shiftactions.id = shiftmodel_has_shiftaction.shiftaction_id
WHERE shiftmodel_id = :model_id AND shiftsection_id = :sectionID', ['sectionID' => $sectionID, 'model_id' => $model_id]);
    return $actions;
}

function getNotSelectedActions($sectionID, $model_id)
{
    $actions = selectMany('SELECT * FROM shiftactions WHERE id NOT IN
(SELECT shiftactions.id FROM shiftmodel_has_shiftaction
INNER JOIN shiftactions
ON shiftactions.id = shiftmodel_has_shiftaction.shiftaction_id
WHERE shiftmodel_id = :model_id)
AND shiftsection_id = :sectionID', ['sectionID' => $sectionID, 'model_id' => $model_id]);
    return $actions;
}

function getshiftsections($shiftSheetID, $baseID)
{
    $shiftsections = selectMany('SELECT * FROM shiftsections', []);
    foreach ($shiftsections as &$section) {
        $section["actions"] = getSelectedActions($section["id"], getshiftsheetByID($shiftSheetID)["model"]);
        $section["unusedActions"] = getNotSelectedActions($section["id"], getshiftsheetByID($shiftSheetID)["model"]);
        foreach ($section["actions"] as &$action) {
            $action['checksDay'] = getshiftchecksForAction($action["id"], $shiftSheetID, 1);
            $action['checksNight'] = getshiftchecksForAction($action["id"], $shiftSheetID, 0);
            $action["comments"] = getShiftCommentsForAction($action["id"], $shiftSheetID, $baseID);
        }
    }
    return $shiftsections;
}

function getAllShiftForBase($baseID)
{
    $slugs = selectMany("SELECT id,slug as name FROM status", []);
    foreach ($slugs as $slug) {
        $sheets[$slug["name"]] = getShiftWithStatus($baseID, $slug["id"]);
    }
    return $sheets;
}

function getShiftWithStatus($baseID, $slugID)
{
    return selectMany('SELECT shiftsheets.id, shiftsheets.date, shiftsheets.base_id, status.displayname AS status, status.slug AS statusslug,novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM shiftsheets
INNER JOIN status ON status.id = shiftsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = shiftsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = shiftsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = shiftsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = shiftsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = shiftsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = shiftsheets.nightteammate_id
WHERE shiftsheets.base_id =:base_id and status.id =:slugID order by date DESC;', ["base_id" => $baseID, "slugID" => $slugID]);
}

function getshiftsheetByID($id)
{
    return selectOne('SELECT bases.name as baseName,bases.id as baseID, shiftsheets.id, shiftsheets.date, shiftsheets.base_id,shiftsheets.shiftmodel_id as model, status.slug AS status, status.displayname AS displayname, novaDay.number AS novaDay, novaNight.number AS novaNight, bossDay.initials AS bossDay, bossNight.initials AS bossNight,teammateDay.initials AS teammateDay, teammateNight.initials AS teammateNight
FROM shiftsheets
INNER JOIN bases ON bases.id = shiftsheets.base_id
INNER JOIN status ON status.id = shiftsheets.status_id
LEFT JOIN novas novaDay ON novaDay.id = shiftsheets.daynova_id
LEFT JOIN novas novaNight ON novaNight.id = shiftsheets.nightnova_id
LEFT JOIN users bossDay ON bossDay.id = shiftsheets.dayboss_id
LEFT JOIN users bossNight ON bossNight.id = shiftsheets.nightboss_id
LEFT JOIN users teammateDay ON teammateDay.id = shiftsheets.dayteammate_id
LEFT JOIN users teammateNight ON teammateNight.id = shiftsheets.nightteammate_id
WHERE shiftsheets.id =:id;', ["id" => $id]);
}


function addNewShiftSheet($baseID, $modelID, $date)
{
    try {
        $insertshiftsheet = execute("INSERT INTO shiftsheets (date,shiftmodel_id,status_id,base_id) VALUES (:date,:modelID,1,:base)", ['date' => $date, 'base' => $baseID, 'modelID' => $modelID]);
        if ($insertshiftsheet == false) {
            throw new Exception("L'enregistrement ne s'est pas effectué correctement");
        }
        $dbh = null;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

function getDateOfLastSheet($baseID)
{
    $lastDate = selectOne("SELECT MAX(date) FROM shiftsheets where base_id = :baseID", ['baseID' => $baseID])["MAX(date)"];
    return $lastDate;
}

function getNextDateForShift($baseID)
{
    $newDate = selectOne("SELECT DATE_ADD( :lastDate, INTERVAL 1 DAY) as newDate", ['lastDate' => getDateOfLastSheet($baseID)])["newDate"];
    if($newDate == NULL){
        $now = selectOne("select NOW()",[])["NOW()"];
        $newDate = selectOne("SELECT DATE_FORMAT('".$now."', '%Y-%m-%d') as date",[])["date"];
    }
    return $newDate;
}

function getOpenShiftSheet($base_id){
    return selectOne("SELECT COUNT(shiftsheets.id) as number FROM  shiftsheets inner join status on status.id = shiftsheets.status_id where status.slug = 'open' and shiftsheets.base_id =:base_id", ['base_id' => $base_id])["number"];
}


function getNbshiftsheet($status,$base_id){
    return selectOne("SELECT COUNT(shiftsheets.id) as number FROM  shiftsheets inner join status on status.id = shiftsheets.status_id where status.slug = :status and shiftsheets.base_id =:base_id", ['status' => $status, 'base_id' => $base_id])["number"];
}

function checkActionForShift($action_id, $shiftSheet_id, $day)
{
    return execute("Insert into shiftchecks(day,shiftsheet_id,shiftaction_id,user_id)values(:day,:shiftSheet_id,:action_id,:user_id)", ["day" => $day, "user_id" => $_SESSION['user']['id'], "shiftSheet_id" => $shiftSheet_id, "action_id" => $action_id]);
}

function commentActionForShift($action_id, $shiftSheet_id, $message)
{
    return execute("Insert into shiftcomments(shiftsheet_id,shiftaction_id,user_id,message)values(:shiftSheet_id,:action_id,:user_id,:message)", ["user_id" => $_SESSION['user']['id'], "shiftSheet_id" => $shiftSheet_id, "action_id" => $action_id, "message" => $message]);
}

function getStateFromSheet($id)
{
    return selectOne("SELECT status.slug, status.displayname FROM status LEFT JOIN shiftsheets ON shiftsheets.status_id = status.id WHERE shiftsheets.id =:sheetID", ["sheetID" => $id]);
}


function getBaseIDForShift($id)
{
    return selectOne("SELECT base_id FROM shiftsheets where id =:id", ["id" => $id])["base_id"];
}

function addCarryOnComment($commentID)
{
    return execute("update shiftcomments set carryON = 1, endOfCarryOn = null where id=:commentID", ["commentID" => $commentID]);
}

function carryOffComment()
{
    return execute("update shiftcomments set endOfCarryOn = :carryOff where id= :commentID", ["commentID" => $_POST["commentID"], "carryOff" => $_POST["carryOff"]]);
}

function getModelByID($id)
{
    return selectOne("select * from shiftmodels where id=:id", ["id" => $id]);
}


function addShiftAction($modelID, $actionID)
{
    return execute("INSERT INTO `shiftmodel_has_shiftaction` (shiftaction_id,shiftmodel_id) VALUES (:actionID,:modelID)", ["modelID" => $modelID, "actionID" => $actionID]);
}

function creatShiftAction($action, $section)
{
    execute("INSERT INTO `shiftactions` (text,shiftsection_id) VALUES (:action,:section)", ["action" => $action, "section" => $section]);
    return selectOne("SELECT MAX(id) AS max FROM shiftactions", [])["max"];
}

function removeShiftAction($modelID, $actionID)
{
    return execute("DELETE FROM `shiftmodel_has_shiftaction` WHERE shiftaction_id=:actionID and shiftmodel_id=:modelID;", ["actionID" => $actionID, "modelID" => $modelID]);
}

function getShiftActionID($actionName, $sectionName)
{
    return selectOne("SELECT shiftactions.id from shiftactions 
INNER JOIN shiftsections
ON shiftsections.id = shiftactions.shiftsection_id
where TEXT= :actionName AND shiftsections.id = :sectionName", ["actionName" => $actionName, "sectionName" => $sectionName])["id"];
}

function getShiftActionName($actionID)
{
    return selectOne("SELECT text from shiftactions where id=:actionID", ["actionID" => $actionID])["text"];
}


/**
 * setSlugForShift : update the status for a shiftsheet
 * @param int $id : id of the shiftsheet
 * @param string $slug : status's slug, values possible values ("blank", "open" , "close" , "reopen", "archive")
 * @return bool : true = ok / false = fail
 */
function setSlugForShift($id, $slug)
{
    return execute("update shiftsheets set status_id= (select id from status where slug =:slug) WHERE id=:id", ["slug" => $slug, "id" => $id]);
}


/**
 * shiftSheetDelete : delete a shiftsheet ( currently only used for deleting "blank", to delete "archive you need to add some "delete on cascade" in the database )
 * @param int $id : id of the shiftsheet
 * @return bool : true = ok / false = fail
 */
function shiftSheetDelete($id)
{
    return execute("DELETE FROM shiftsheets WHERE id=:id", ["id" => $id]);
}

/**
 * getShiftModels : get the list of models where name si not null ( = not a hidden model ) and suggested = 1 ( suggested in the list for shiftsheet creation )
 * @return array : array of models with id and name
 */
function getShiftModels()
{
    $models = selectMany("SELECT id,name FROM shiftModels where name <> '' and suggested = 1", []);
    return $models;
}

/**
 * getLastShiftModel : get id of most recent closed or reopened ( in correction ) shiftsheet for the selected base
 * @param int $baseID : id of the selected base
 * @return int : id of the model
 */
function getLastShiftModel($baseID)
{
    $modelID = selectOne("SELECT shiftmodel_id from shiftsheets where DATE = ( SELECT MAX(DATE) FROM shiftsheets WHERE base_id = :baseID and (status_id = (SELECT id FROM status WHERE slug = 'close') or status_id = (SELECT id FROM status WHERE slug = 'reopen'))) AND base_id = :baseID", ["baseID" => $baseID])["shiftmodel_id"];
    return $modelID;
}

/**
 * enableShiftModel : remove the model to the suggested list for the creation of sheet
 * @param int $modelID : id of the model
 * @return bool : true = ok / false = fail
 */
function disableShiftModel($modelID)
{
    return execute("UPDATE shiftmodels SET suggested = 0 WHERE id = :id", ["id" => $modelID]);
}

/**
 * enableShiftModel : add the model to the suggested list for the creation of sheet and give him a name
 * @param int $modelID : id of the model
 * @param int $modelName : name for the model to display
 * @return bool : true = ok / false = fail
 */
function enableShiftModel($modelID, $modelName)
{
    return execute("UPDATE shiftmodels SET suggested = 1, name =:name WHERE id = :id", ["id" => $modelID, "name" => $modelName]);
}

/**
 * updateModelID : change the model of the shiftsheet
 * @param int $shiftSheetID : id of the shiftsheet
 * @param int $newModelID : id for the new model to use
 * @return bool : true = ok / false = fail
 */
function updateModelID($shiftSheetID, $newModelID)
{
    return execute("update shiftsheets set shiftmodel_id = :newModelID where id= :shiftSheetID", ["shiftSheetID" => $shiftSheetID, "newModelID" => $newModelID]);
}

/**
 * copyModel : create a copy of the model
 * @param int $modelID : id of the model to copy
 * @return int : id of the copy
 */
function copyModel($modelID)
{
    execute("INSERT INTO `shiftmodels` (NAME) VALUES (null)", []);
    $newID = selectOne("SELECT MAX(id) AS max FROM shiftmodels", [])["max"];
    $actionToCopy = selectMany('SELECT shiftactions.id FROM shiftmodel_has_shiftaction
INNER JOIN shiftactions
ON shiftactions.id = shiftmodel_has_shiftaction.shiftaction_id
WHERE shiftmodel_id = :model_id ', ['model_id' => $modelID]);
    foreach ($actionToCopy as $action) {
        execute("INSERT INTO `shiftmodel_has_shiftaction` (shiftaction_id,shiftmodel_id) VALUES (:actionID,:modelID)", ["modelID" => $newID, "actionID" => $action["id"]]);
    }
    return $newID;
}

/**
 * updateDataShift : update the informations for the shiftsheet
 * @param int $id : id of the shiftsheet
 * @param int $novaDay : id of the nova used for the day
 * @param int $novaNight : id of the nova used for the night
 * @param int $bossDay : id of the person for the day boss
 * @param int $bossNight : id of the person for the night boss
 * @param int $teammateDay : id of the person for the day teammate
 * @param int $teammateNight : id of the person for the night teammate
 * @return bool : true = ok / false = fail
 */
function updateDataShift($id, $novaDay, $novaNight, $bossDay, $bossNight, $teammateDay, $teammateNight)
{
    if ($novaDay == "NULL") $novaDay = null;
    if ($novaNight == "NULL") $novaNight = null;
    if ($bossDay == "NULL") $bossDay = null;
    if ($bossNight == "NULL") $bossNight = null;
    if ($teammateDay == "NULL") $teammateDay = null;
    if ($teammateNight == "NULL") $teammateNight = null;
    return execute("update shiftsheets set daynova_id =:novaDay, nightnova_id =:novaNight, dayboss_id =:bossDay, nightboss_id =:bossNight, dayteammate_id =:teammateDay, nightteammate_id =:teammateNight WHERE id=:id", ["id" => $id, "novaDay" => $novaDay, "novaNight" => $novaNight, "bossDay" => $bossDay, "bossNight" => $bossNight, "teammateDay" => $teammateDay, "teammateNight" => $teammateNight]);
}