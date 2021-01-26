<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 */

/**
 * newShiftSheet : create a new sheet for a shift. It is created for the active base with the selected model
 * @param int $baseID
 * shows a message to confirm action or an error message
 */
function newShiftSheet($baseID)
{
    if ($_POST["selectedModel"] == "lastModel") {
        $modelID = getLastShiftModel($baseID);
    } else {
        $modelID = $_POST["selectedModel"];
    }

    $result = addNewShiftSheet($baseID, $modelID, $_POST["date"]);
    if ($result == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter le rapport de garde.");
    } else {
        setFlashMessage("le rapport de garde a bien été créé !");
    }
    redirect("listshift", $baseID);
}

/**
 * listshift : show a list of all existing shiftsheet for a selected base
 * @param int $selectedBaseID : id of the base we want to show the shiftsheets for. By default it is the based selected when logging in..
 */
function listshift($selectedBaseID = null)
{
    if ($selectedBaseID == null) $selectedBaseID = $_SESSION['base']['id'];
    $bases = getbases();
    $sheets = getAllShiftForBase($selectedBaseID);
    $models = getShiftModels();
    $suggestedModels = getSuggestedShiftModels();
    foreach ($models as $model){
        foreach ($sheets["close"] as &$sheet){
            if($model["id"] == $sheet["model_id"]){
                $sheet["modelImage"] = $model["name"];
                break;
            }
        }
    }
    if(count($sheets["close"])==0 and count($sheets["reopen"])==0){
        $emptyBase = true;
    }else{
        $emptyBase = false;
    }
    require_once VIEW . 'shift/list.php';
}

/**
 * showshift : show the detailed view of a shiftsheet
 * @param int $shiftid : id of the sheet we want to see
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
 * checkShift : Mark a task as completed and who did it
 * @param none
 * shows a message to confirm action or an error message
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
 * commentShift : add a comment to a task
 * @param none
 * shows a message to confirm action or an error message
 */
function commentShift()
{
    $res = commentActionForShift($_POST["action_id"], $_POST["shiftSheet_id"], $_POST["comment"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'ajouter le commentaire.");
    } else {
        setFlashMessage("Le commentaire a bien été ajouté au rapport !");
    }
    redirect("showshift", $_POST["shiftSheet_id"]);
}

/**
 * updateShift : update the data of the sheet -> vehicle, teammates ...
 * @param none
 * show a message to confirm action or an error message
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
 * show a message to confirm action or an error message
 */
function addActionForShift($sheetID)
{
    $modelID = configureModel($sheetID, $_POST["model"]);
    $res = addShiftAction($modelID, $_POST["actionID"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible d'enregistrer les données.");
    } else {
        setFlashMessage("L'action <strong>" . getShiftActionName($_POST["actionID"]) . "</strong> à été ajoutée au rapport");
    }
    redirect("showshift", $sheetID);
}

/**
 * creatActionForShift : create an action if it doesn't exist and add it to the shiftsheet
 * @param int $sheetID : id of the sheet the action is added to
 * show a message to confirm action or an error message
 */
function creatActionForShift($sheetID)
{
    $actionID = getShiftActionID($_POST["actionToAdd"], $_POST["section"]);
    if ($actionID == null) {
        $actionID = creatShiftAction($_POST["actionToAdd"], $_POST["section"]);
        setFlashMessage("Nouvelle action <strong>" . $_POST["actionToAdd"] . "</strong> créée et ajoutée au rapport");
    } else {
        setFlashMessage("L'action <strong>" . $_POST["actionToAdd"] . "</strong> à été ajoutée au rapport");
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
 * @param int $sheetID : id of the sheet the action is removed from
 * shows a message to confirm action or an error message
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
 * configureModel : create a model from an existing shiftsheet
 * @param int $sheetID : id of the shiftsheet
 * @param int $modelID : id of the shiftsheet's model
 * @return int : id of the model used (new or not, depending on uses)
 */
function configureModel($sheetID, $modelID)
{
    //If the model does not have a name it is not being used. no need to copy it
    if (getModelBYID($modelID)["name"] != "") {
        $newID = copyModel($modelID);
        updateModelID($sheetID, $newID);
        return $newID;
    }
    return $modelID;
}

/**
 * shiftSheetSwitchState : change the state of a shiftsheet
 * shows a message to confirm action or an error message
 */
function shiftSheetSwitchState()
{
    $res = setSlugForShift($_POST["id"], $_POST["newSlug"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de changer l'état du rapport de garde.");
    } else {
        setFlashMessage("L'état du rapport de garde a été correctement modifié.");
    }
    redirect("listshift", getBaseIDForShift($_POST["id"]));
}

/**
 * shiftDeleteSheet : delete a shiftsheet
 * shows a message to confirm action or an error message
 */
function shiftDeleteSheet()
{
    $res = shiftSheetDelete($_POST["id"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de supprimer le rapport de garde.");
    } else {
        setFlashMessage("le rapport de garde a été correctement supprimé.");
    }
    redirect("listshift", getBaseIDForShift($_POST["id"]));
}

/**
 * removeShiftModel : remove the model from the list of suggested models
 * shows a message to confirm action or an error message
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
 * addShiftModel : add a model to the list of models
 * shows a message to confirm action or an error message
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


function reAddShiftModel(){
    $res = reEnableShiftModel($_POST["action_id"],$_POST["comment"]);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de réactiver le modèle.");
    } else {
        setFlashMessage("Le modèle a été correctement réactivé.");
    }
    redirect("showShift",$_POST["shiftSheet_id"]);
}
