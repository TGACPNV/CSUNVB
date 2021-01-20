<?php
/**
 * Auteur: Thomas Grossmann
 * Date: Mars 2020
 **/

function getFlashMessage()
{
    if (isset($_SESSION['flashmessage'])) {
        $message = $_SESSION['flashmessage'];
        unset($_SESSION['flashmessage']);
        return '<div class="alert alert-info">' . $message . '</div>';
    } else {
        return null;
    }
}

function setFlashMessage($message)
{
    $_SESSION['flashmessage'] = $message;
}

// todo (VB) : supprimer dès que les vues 'list' sont homogènes
function getDrugSheetStateButton($state)
{
    switch ($state) {
        case "closed":
            return "reopen";
        case "open":
        case "reopened":
            return "close";
        default:
            return "open";
    }
}



function buttonTask($initials, $desription, $taskID, $type, $slug, $edition, $day)
{
    if ($slug == 'open' || $slug == 'reopen') {
        if (empty($initials)) {
            $messageQuittance = 'Vous êtes sur le point de quittancer la tâche suivante : <br> "' . $desription . '".';
            return "<button type='button' class='btn btn-secondary toggleTodoModal btn-block m-1 tasks' data-title='Quittancer une tâche' data-id='" . $taskID . "' data-status='validate' data-type='" . $type . "' data-content='" . $messageQuittance . "'>" . $desription . "<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            $messageQuittance = 'Vous êtes sur le point de retirer la quittance de la tâche suivante : <br> "' . $desription . '".';
            return "<button type='button' class='btn btn-success toggleTodoModal btn-block m-1 tasks' data-title='Retirer une quittance' data-id='" . $taskID . "' data-status='unvalidate' data-type='" . $type . "' data-content='" . $messageQuittance . "'>" . $desription . "<div class='text-dark bg-white rounded mt-1'>" . $initials . "</div></button>";
        }
    }elseif($slug == 'blank' && $edition)
    {
        $date = displayDate($day, 0);
        $messageSuppression = 'Êtes-vous sûr(e) de vouloir supprimer la tâche  <br> "' . $desription . '" du '.$date.'?';
        return "<button type='button' class='btn btn-secondary btn-block m-1 tasks' disabled >" . $desription . "<div class='rounded mt-1 trashButtons' data-title='Suppression de une tâche' data-id='" . $taskID . "' data-content='" . $messageSuppression . "'><i class='fas fa-trash'></i><br></div></button>";
    }
    else {
        if (empty($initials)) {
            return "<button type='button' class='btn btn-secondary btn-block m-1 tasks' disabled >" . $desription . "<div class='bg-white rounded mt-1'><br></div></button>";
        } else {
            return "<button type='button' class='btn btn-success btn-block m-1 tasks' disabled >" . $desription . "<div class='text-dark bg-white rounded mt-1'>" . $initials . "</div></button>";
        }
    }
}

/**
 * Retourne la date formatée pour l'affichage
 * @param $date au format standard YYYY-MM-DD HH:ii:ss
 * @param $format un de quelques formats prédéfinis que l'on utilise dans l'appli
 */
function displayDate($date, $format)
{
    switch ($format) {
        case 1:
            return strftime('%A %e %b %Y', strtotime($date)); // Complet avec le jour
        default:
            return strftime('%e %b %Y', strtotime($date)); // Complet sans le jour
    }
}


function showState($slug, $plural = 0)
{
    // todo (VB) : Utilisation de la base de données (displayname)
    switch ($slug) {
        case "blank":
            $result = "en préparation";
            break;
        case "open":
            $result = "active";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        case "reopen":
            $result = "en correction";
            break;
        case "close":
            $result = "clôturée";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        case "archive":
            $result = "archivée";
            if ($plural) {
                $result = $result . "(s)";
            }
            break;
        default:
            $result = "[Non défini]";
            break;
    }

    return $result;
}


function listSheet($page, $sheets)
{
    switch ($page) {
        case "drugs":
        case "todo":
            $function = "listTodoOrDrugsSheet";
            break;
        case "shift":
            $function = "listShiftSheet";
            break;
        default:
            break;
    }

    $html = "<div> <!-- Sections d'affichage des différentes feuilles -->";
    $html .= "<div> <!-- Feuilles ouvertes -->
        <div class='slugBlank'>" . $function("open", $sheets["open"], $page) . "</div><br>";
    $html .= "<div> <!-- Feuilles en préparation -->
        <div class='slugOpen'> " . $function("blank", $sheets["blank"], $page) . "</div><br>";
    $html .= "<div> <!-- Feuilles en correction -->
        <div class='slugReopen'>" . $function("reopen", $sheets["reopen"], $page) . "</div><br>";
    $html .= "<div> <!-- Feuilles fermées -->
        <div class='slugClose'>" . $function("close", $sheets["close"], $page) . "</div>";

    return $html;
}

function listTodoOrDrugsSheet($slug, $sheets, $zone)
{
    if($zone == 'drugs'){
        $detailAction = "showDrugSheet";
    }else{
        $detailAction = "showtodo";
    }


    $html = "<h3>Semaine(s) " . showState($slug, 1) . "</h3>
                        <button class='btn dropdownButton'><i class='fas fa-caret-square-down' data-list='" . $slug . "' ></i></button>
                    </div>";

    if (!empty($sheets)) {
        $html = $html . "<div class='" . $slug . "Sheets' style='margin-top: 0px;'><table class='table table-bordered' style='margin-top: 0px;'>
                            <thead class='thead-dark'><th>Semaine n°</th><th class='actions'>Actions</th></thead>
                            <tbody>";

        foreach ($sheets as $sheet) {

            $html = $html . "<tr> <td>Semaine " . $sheet['week'];

            if (ican('createsheet') && (isset($sheet['template_name']))) {
                $html = $html . "<i class='fas fa-file-alt template' title='" . $sheet['template_name'] . "'></i>";
            }

            $html = $html . "<td><div class='d-flex justify-content-around'>
                                        <form>
                                            <input type='hidden' name='action' value='".$detailAction."'>
                                            <input type='hidden' name='id' value='" . $sheet['id'] . "'>
                                            <button type='submit' class='btn btn-primary m-1'>Détails</button>
                                        </form>
                                        " . slugButtons($zone, $sheet, $slug) . "</div></td>";
        }

        $html = $html . "</tr> </tbody> </table></div>";

    } else {
        $html = $html . "<div class='" . $slug . "Sheets'><p>Aucune feuille de tâche n'est actuellement " . showState($slug) . ".</p></div>";
    }

    return $html;
}

function listShiftSheet($slug, $shiftList, $zone)
{
    $html = "<h3>Semaine(s) " . showState($slug, 1) . "</h3>
                    <button class='btn dropdownButton'><i class='fas fa-caret-square-down' data-list='" . $slug . "' ></i></button>
                    </div>";
    if (count($shiftList) > 0) {
        $head = '<table class="table table-bordered ' . $slug . 'Sheets" style="margin-top:0px; text-align: center">
        <thead class="thead-dark">
        <th>Date</th>
        <th>Véhicule</th>
        <th>Responsable</th>
        <th>Équipage</th>
        <th class="actions">Action</th>
        </thead>';
        $body = "";
        foreach ($shiftList as $shift) {
            $body .= "<tr>
                <td>" . date('d.m.Y', strtotime($shift['date'])) . "</td>
                <td>Jour : " . $shift['novaDay'] . "<br>Nuit : " . $shift['novaNight'] . "</td>
                <td>Jour : " . $shift['bossDay'] . "<br>Nuit : " . $shift['bossNight'] . "</td>
                <td>Jour : " . $shift['teammateDay'] . "<br>Nuit : " . $shift['teammateNight'] . "</td>";
            $body .= "<td><div class='d-flex justify-content-around'>
                                <form>
                                    <input type='hidden' name='action' value='showShift'>
                                    <input type='hidden' name='id' value='" . $shift['id'] . "'>
                                    <button type='submit' class='btn btn-primary m-1'>Détails</button>
                                </form>
            " . slugButtons("shift", $shift, $slug) . "</div></td>";
            $body .= "</td></tr>";
        }
        $foot = "</table>";
        $table = $head . $body . $foot;
        $html .= $table;
    } else {
        $html .= "<div class='" . $slug . "Sheets'><p>Aucune feuille de tâche n'est actuellement " . showState($slug) . ".</p></div>";
    }
    return $html;
}

function slugButtons($page, $sheet, $slug)
{
    $buttons = "";
    switch ($slug) {
        case "blank":
            if (ican('opensheet')) {
                // Test pour vérifier si une autre feuille est déjà ouverte
                if (!checkOpen($page, $sheet['base_id'])) {
                    $buttons .= "<form  method='POST' action='?action=" . $page . "SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet['id'] . "'>
                    <input type='hidden' name='newSlug' value='open'>
                    <button type='submit' class='btn btn-primary m-1'>Activer</button>
                    </form>";
                } else {
                    $buttons .= "<form><button type='submit' class='btn btn-primary m-1' disabled>Activer</button></form>";
                }
            }
        case "archive":
            if (ican('deletesheet')) { // TODO : ajouter une verification de la part de l'utilisateur (VB)
                $buttons .= "<form  method='POST' action='?action=" . $page . "DeleteSheet'>
                    <input type='hidden' name='id' value='" . $sheet['id'] . "'>
                    <button type='submit' class='btn btn-primary m-1'>Supprimer</button>
                    </form>";
            }
            break;
        case "open":
            if (ican('closesheet')) {
                $buttons .= "<form  method='POST' action='?action=" . $page . "SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet['id'] . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary m-1'>Clôturer</button>
                    </form>";
            }
            break;
        case "reopen":
            if (ican('closesheet')) {
                $buttons .= "<form  method='POST' action='?action=" . $page . "SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='close'>
                    <button type='submit' class='btn btn-primary m-1'>Refermer</button>
                    </form>";
            }
            break;
        case "close":
            if (ican('opensheet')) {
                $buttons .= "<form  method='POST' action='?action=" . $page . "SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='reopen'>
                    <button type='submit' class='btn btn-primary m-1'>Corriger</button>
                    </form>";
            }
            if (ican('archivesheet')) {
                $buttons .= "<form  method='POST' action='?action=" . $page . "SheetSwitchState'>
                    <input type='hidden' name='id' value='" . $sheet["id"] . "'>
                    <input type='hidden' name='newSlug' value='archive'>
                    <button type='submit' class='btn btn-primary m-1'>Archiver</button>
                    </form>";
            }
            break;
        default:
            break;
    }
    return $buttons;
}

function checkOpen($page, $baseID){
    $openSheets = 0;
    $count = 1;

    switch($page){
        case 'drugs':
            $openSheets = getOpenDrugsSheetNumber($baseID);
            break;
        case 'todo':
            $openSheets = getOpenTodoSheetNumber($baseID);
            break;
        case 'shift':
            $openSheets = getOpenShiftSheet($baseID);
            break;
    }

    if(!isset($openSheets) || $openSheets < $count){
        return false; // A sheet can be open
    }else{
        return true; // Max number of sheets already open
    }
}


/**
 * @param $page nom de la page ex. "shift"
 * @param $bases liste des bases, avec leur id ("id") et noms ("name")
 * @param $selectedBaseID identifiant de la base selectionnée
 * @param $models liste des modèles, avec leur id ("id") et noms ("name")
 * @return string code html pour créer le header
 */
function headerForList($page, $bases, $selectedBaseID, $models, $emptyBase)
{
    switch ($page) {
        case "shift":
            $title = "Remise de Garde";
            $switchBaseAction = "listshift";
            $newSheetAction = "?action=newShiftSheet&id=" . $selectedBaseID;
            $newSheetBtnName = "Nouvelle Feuille de garde";
            $dateInput = "<input type='date' name='date' value='".getNextDateForShift($selectedBaseID)."'>";
            // <input type="week" name="week" value="2017-W01"> exemple for week
            break;
        default:
            return "<h1>Header pour la page non défini</h1>";
    }
    $header = "<div class='row'><h1 class='mr-3'>".$title."</h1>";
    //Liste déroulante pour le choix de la base
    $header .= "<form><input type='hidden' name='action' value='" . $switchBaseAction . "'><select onchange='this.form.submit()' name='id' size='1' class='bigfont mb-3'>";
    foreach ($bases as $base) {
        $header .= "<option value='" . $base['id'] . "'";
        if ($selectedBaseID == $base['id']) {
            $header .= " selected ";
        }
        $header .= "name='base'>" . $base['name'] . "</option>";
    }
    $header .= "</select></form></div>";

    //Création d'une nouvelle feuille
    if (ican('createsheet') && $_SESSION['base']['id'] == $selectedBaseID) {
        $header .= "<div class='newSheetZone'><form method='POST' action='" . $newSheetAction . "' class='float-right'>Utiliser le modèle :<select name='selectedModel'>";
        if($emptyBase == false){
            $header .= "<option value='lastModel' selected=selected>Dernier rapport clôturé</option>";
        }
        foreach ($models as $model) {
            $header .= "<option value='" . $model['id'] . "'>" . $model['name'] . "</option>";
        }
        $header .= "</select> <button class='btn btn-primary m-1'>" . $newSheetBtnName . "</button>";
        $header .= $dateInput;
        $header .= "</form></div>";
    }
    return $header;
}

function dropdownTodoMissingTask($missingTasks){
    $html = "<label for='task' class='d-none' id='missingTaskLabel' style='padding-right: 15px'>Tâche</label>";

    for($i = 1; $i<=7; $i++){
        $html = $html."<select name='task' id='day".$i."time1' class='missingTodoTaskList d-none'>";

        foreach ($missingTasks as $task) {
            $html = $html."<option value'".$task['id']."'>".$task['description']."</option>";

        }
        $html = $html."</select>";
    }

    return $html;
}