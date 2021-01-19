<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 */

/**
 * newShiftSheet : create a new sheet for a shift. It is created on the base we are currently watching the list, with the model selected
 * @param int $baseID
 * show a message if it has been done correctly
 */
function newShiftSheet($baseID)
{
    if ($_POST["selectedModel"] == "lastModel") {
        $modelID = getLastShiftModel($baseID);
    } else {
        $modelID = $_POST["selectedModel"];
    }
    
    $result = addNewShiftSheet($baseID, $modelID);
    if ($result == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter la feuille de garde.");
    } else {
        setFlashMessage("La feuille de garde a bien été créée !");
    }
    redirect("listshift", $baseID);
}

/**
 * listshift : show a list of all existing shiftsheet for a certain base
 * @param int $selectedBaseID : id of the base we want to show  the shiftsheets. By default : correspond to the one we are logged on.
 */
function listshift($selectedBaseID = null)
{
    if ($selectedBaseID == null) $selectedBaseID = $_SESSION['base']['id'];
    $bases = getbases();
    $sheets = getAllShiftForBase($selectedBaseID);
    $models = getShiftModels();
    if(count($sheets["close"])==0 and count($sheets["reopen"])==0){
        $emptyBase = true;
    }else{
        $emptyBase = false;
    }
    require_once VIEW . 'shift/list.php';
}

/**
 * showshift : show the detailed view of a shiftsheet
 * @param int $shiftid : id of the sheet we want to visualize
 */
function showshift($shiftid)
{
    $shiftsheet = getshiftsheetByID($shiftid);
    $sections = getshiftsections($shiftid, $shiftsheet["baseID"]);
    $enableshiftsheetUpdate = ($shiftsheet['status'] == "open" || ($shiftsheet['status'] == "blank" && $_SESSION['user']['admin'] == true));
    $enableshiftsheetFilling = ($shiftsheet['status'] == "open" || $shiftsheet['status'] == "reopen" && $_SESSION['user']['admin'] == true);
    $model = getModelByID($shiftsheet['model']);
    $novas = getNovas();
    $users = getUsers();
    require_once VIEW . 'shift/show.php';
}

/**
 * checkShift : validate if a task has been done and who has done it
 * @param none
 * show a message if it has been done correctly
 */
function checkShift()
{
    $res = checkActionForShift($_POST["action_id"], $_POST["shiftSheet_id"], $_POST["day"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de valider la tâche.");
    } else {
        setFlashMessage("La tâche a bien été validée !");
    }
    redirect("showshift", $_POST["shiftSheet_id"]);
}

/**
 * commentShift : add a comment on a certain task
 * @param none
 * show a message if it has been added correctly
 */
function commentShift()
{
    $res = commentActionForShift($_POST["action_id"], $_POST["shiftSheet_id"], $_POST["comment"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter le commentaire.");
    } else {
        setFlashMessage("Le commentaire a bien été ajouté à la feuille !");
    }
    redirect("showshift", $_POST["shiftSheet_id"]);
}

/**
 * updateShift : update the data of the sheet -> vehicle, teammates ...
 * @param none
 * show a message if it has been done correctly
 */
function updateShift()
{
    $res = updateDataShift($_GET["id"], $_POST["novaDay"], $_POST["novaNight"], $_POST["bossDay"], $_POST["bossNight"], $_POST["teammateDay"], $_POST["teammateNight"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'enregistrer les données.");
    } else {
        setFlashMessage("Les données ont été correctement enregistrées.");
    }
    redirect("showshift", $_GET["id"]);
}
/**
 * addActionForShift : add an action to a shiftsheet
 * @param int $sheetID : id of the sheet where the action is added
 * show a message if it has been added correctly
 */
function addActionForShift($sheetID)
{
    $modelID = configureModel($sheetID, $_POST["model"]);
    $res = addShiftAction($modelID, $_POST["actionID"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'enregistrer les données.");
    } else {
        setFlashMessage("L'action <strong>" . getShiftActionName($_POST["actionID"]) . "</strong> à été ajoutée à la feuille");
    }
    redirect("showshift", $sheetID);
}

/**
 * creatActionForShift : create an action if it doesn't exist and add it to the shiftsheet
 * @param int $sheetID : id of the sheet the action is added to
 * show a message if it has been added correctly
 */
function creatActionForShift($sheetID)
{
    $actionID = getShiftActionID($_POST["actionToAdd"], $_POST["section"]);
    if ($actionID == null) {
        $actionID = creatShiftAction($_POST["actionToAdd"], $_POST["section"]);
        setFlashMessage("Nouvelle action <strong>" . $_POST["actionToAdd"] . "</strong> créée et ajoutée à la feuille");
    } else {
        setFlashMessage("L'action <strong>" . $_POST["actionToAdd"] . "</strong> à été ajoutée à la feuille");
    }
    $modelID = configureModel($sheetID, $_POST["model"]);
    $res = addShiftAction($modelID, $actionID);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter l'action.");
        }
    redirect("showshift", $sheetID);
}

/**
 * removeActionForShift : remove an action from the list of active action on a certain shiftsheet
 * @param int $sheetID : id of the sheet the action is removed of
 * show a message if it has been removed correctly
 */
function removeActionForShift($sheetID)
{
    $modelID = configureModel($sheetID, $_POST["model"]);
    $res = removeShiftAction($modelID, $_POST["action"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de supprimer l'action.");
    } else {
        setFlashMessage("l'action <strong>" . getShiftActionName($_POST["action"]) . "</strong> a été suprimée");
    }
    redirect("showshift", $sheetID);
}

/**
 * configureModel : duplicate shiftsheetmodel IF it is user on another sheet, so that those are not modified
 * @param int $sheetID : id of the shiftsheet
 * @param int $modelID : id of the shiftsheet's model
 * @return int : id of the model used (new or not, depending on uses)
 */
function configureModel($sheetID, $modelID)
{
    //si le modèle ne possède pas de nom, il n'est pas utilisé pour créer d'autre feuille, il n'y a donc pas besoin de le mofifier
    if (getModelBYID($modelID)["name"] != "") {
        $newID = copyModel($modelID);
        updateModelID($sheetID, $newID);
        return $newID;
    }
    return $modelID;
}

/**
 * shiftSheetSwitchState : change the state of a shiftsheet
 * show a message if it has been removed correctly
 */
function shiftSheetSwitchState()
{
    $res = setSlugForShift($_POST["id"], $_POST["newSlug"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de changer l'état de la feuille de garde.");
    } else {
        setFlashMessage("L'état de la feuille de garde a été correctement modifiée.");
    }
    redirect("listshift", getBaseIDForShift($_POST["id"]));
}

/**
 * shiftDeleteSheet : delete a shiftsheet
 * show a message if it has been removed correctly
 */
function shiftDeleteSheet()
{
    $res = shiftSheetDelete($_POST["id"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de supprimer la feuille de garde.");
    } else {
        setFlashMessage("La feuille de garde a été correctement supprimé.");
    }
    redirect("listshift", getBaseIDForShift($_POST["id"]));
}

/**
 * removeShiftModel : remove the model from the list of suggested model
 * show a message if it has been removed correctly
 */
function removeShiftModel(){
    $res = disableShiftModel($_POST["action_id"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de retirer le modèle.");
    } else {
        setFlashMessage("Le modèle a été correctement retiré de la liste des modèles disponibles.");
    }
    redirect("showShift",$_POST["shiftSheet_id"]);
}

/**
 * addShiftModel : add a model to the list of model
 * show a message if it has been removed correctly
 */
function addShiftModel(){
    $res = enableShiftModel($_POST["action_id"],$_POST["comment"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter le modèle.");
    } else {
        setFlashMessage("Le modèle a été correctement ajouté.");
    }
    redirect("showShift",$_POST["shiftSheet_id"]);
}