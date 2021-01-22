<?php

/** Fonction qui permet l'affichage des semaines de tâches pour la base par défaut (où on est loggé) todo:translate
 */
function listtodo()
{
    listtodoforbase($_SESSION['base']['id']);
}

/** Fonction qui permet l'affichage des semaines de tâches pour une base spécifique todo:translate
 * @param $selectedBaseID : l'ID de la base dont les semaines sont à afficher
 */
function listtodoforbase($selectedBaseID)
{

    // Récupération des semaines en fonction de leur état (slug) et de la base choisie
    $sheets = getAllTodoSheetsForBase($selectedBaseID);

    $baseList = getbases();
    $templates = getAllTemplateNames();
    $lastClosedWeek = getLastWeekClosed($selectedBaseID);

    require_once VIEW . 'todo/list.php';
}

/**
 * Fonction qui affiche les tâches d'une semaine spécifique todo:translate
 * @param $sheetID : l'ID de la feuille de tâche à afficher
 */
function showtodo($sheetID, $edition = false)
{
    $week = getTodosheetByID($sheetID);
    $base = getbasebyid($week['base_id']);
    $dates = getDaysForWeekNumber($week['week']);
    $template = getTemplateName($sheetID);

    $allTodoTasks[0] = getTasksByTime(0);
    $allTodoTasks[1] = getTasksByTime(1);

    $missingTasks = array();

    for ($daynight = 0; $daynight <= 1; $daynight++) {
        for ($dayofweek = 1; $dayofweek <= 7; $dayofweek++) {

            $todoThings[$daynight][$dayofweek] = readTodoThingsForDay($sheetID, $daynight, $dayofweek);
            $missingTasks[$daynight][$dayofweek] = findMissingTasks($allTodoTasks[$daynight], $todoThings[$daynight][$dayofweek]);

            foreach ($todoThings[$daynight][$dayofweek] as $key => $todoThing) {
                if ($todoThing['type'] == "2" && !is_null($todoThing['value'])) {
                    $todoThings[$daynight][$dayofweek][$key]['description'] = str_replace("....", "" . $todoThing['value'] . "", "" . $todoThing['description'] . "");
                }
            }

        }
    }

    $days = [1 => "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];

    require_once VIEW . 'todo/show.php';
}


/**
 * Fonction qui ajoute à la bbd dans todosheets les données relative à base_id et week todo:translate
 * @param $base : id de la base
 */
function addWeek()
{
    $baseID = $_SESSION['base']['id']; // On ne peut ajouter une feuille que dans la base où l'on se trouve

    $week = getLastWeek($baseID); // Récupère la dernière semaine

    if ($_POST['selectModel'] == 'lastValue') {
        $template = getLastWeekClosed($baseID);
    } else {
        $template = getTemplateSheet($_POST['selectModel']);
    }

    if(isset($week['week'])){
        $newWeekNumber = nextWeekNumber($week['week']);
    } else{
        $newWeekNumber = date("yW");
    }

    $todos = readTodoForASheet($template['id']);

    $newWeekID = createNewSheet($baseID, $newWeekNumber);

    foreach ($todos as $todo) {
        addTodoThing($todo['id'], $newWeekID, $todo['day']);
    }

    setFlashMessage("La semaine " . $newWeekNumber . " a été créée.");
    header('Location: ?action=listtodoforbase&id=' . $baseID);
}

/**
 * Fonction qui retourne le numéro de semaine de la semaine suivante todo:translate
 * @param $weekNbr : le numéro de la semaine
 * @return false|string
 */
function nextWeekNumber($weekNbr)
{
    $year = 2000 + intdiv($weekNbr, 100);
    $week = $weekNbr % 100;

    $time = strtotime(sprintf("%4dW%02d", $year, $week));
    $nextWeek = date(strtotime("+ 1 week", $time));

    return date("yW", $nextWeek);
}

/**todo:complete
 *
 */
function modelWeek()
{
    $todosheetID = $_POST['todosheetID'];

    updateTemplateName($todosheetID, $_POST['template_name']);
    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**todo:complete
 *
 */
function deleteTemplate()
{
    $todosheetID = $_POST['todosheetID'];

    deleteTemplateName($todosheetID);
    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**todo:complete
 *
 */
function todoEditionMode()
{
    $edition = $_POST['edition'];
    $todosheetID = $_POST['todosheetID'];

    if (!$edition) {
        $edition = true;
        showtodo($todosheetID, $edition);
    } else {
        $edition = false;
        header('Location: ?action=showtodo&id=' . $todosheetID);
    }
}

/**todo:complete
 *
 */
function destroyTaskTodo()
{
    $todosheetID = $_POST['todosheetID'];
    $todoTaskID = $_POST['taskID'];
    $todoTaskName = getTaskName($_POST['taskID']);
    $message = 'La tâche "'.$todoTaskName.'" a été supprimée !';
    deletethingsID($todoTaskID);

    setFlashMessage($message);
    showtodo($todosheetID,true);
}

/**todo:complete
 *
 */
function switchTodoStatus()
{
    $status = $_POST['modal-todoStatus'];
    $todoID = $_POST['modal-todoID'];
    $todoType = $_POST['modal-todoType'];
    $todoValue = $_POST['modal-todoValue'];
    $todosheetID = $_POST['todosheetID'];

    if ($status == 'unvalidate') {
        invalidateTodo($todoID, $todoType);
    } else {
        validateTodo($todoID, $todoValue);
    }

    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**
 * Fonction qui permet de changer l'état d'une feuille todo:translate
 */
function todoSheetSwitchState()
{
    $sheetID = $_POST['id'];
    $newSlug = $_POST['newSlug'];

    $sheet = getTodosheetByID($sheetID);

    changeSheetState($sheetID, $newSlug);
    $message = "La semaine " . $sheet['week'] . " a été ";

    switch ($newSlug) {  /* todo : utiliser displayname */
        case "open":
            $message = $message . "ouverte.";
            break;
        case "reopen":
            $message = $message . "ré-ouverte.";
            break;
        case "close":
            $message = $message . "fermée.";
            break;
        case "archive":
            $message = $message . "archivée.";
            break;
        default:
            break;
    }

    setFlashMessage($message);
    header('Location: ?action=listtodoforbase&id=' . $sheet['base_id']);
}

/**todo:complete
 *
 */
function todoDeleteSheet()
{
    $sheetID = $_POST['id'];
    $sheet = getTodosheetByID($sheetID);

    deleteTodoSheet($sheetID);

    setFlashMessage("La semaine " . $sheet['week'] . " a correctement été supprimée.");
    header('Location: ?action=listtodoforbase&id=' . $sheet['base_id']);
}

/**
 * Fonction qui cherche les tâches manquantes d'une liste, à partir d'une liste de référence todo : translate
 * @param array $allTasksList : la liste de référence
 * @param array $taskList : la liste dont on cherche les tâches manquantes
 * @return array
 */
function findMissingTasks($allTasksList, $taskList)
{
    $missingTask = array();

    for ($i = 0; $i < count($allTasksList); $i++) {
        $found = false;
        for ($j = 0; $j < count($taskList); $j++) {
            if ($allTasksList[$i]['id'] == $taskList[$j]['todothingID']) {
                $found = true;
                $j = count($taskList);
            }
        }
        if ($found == false) {
            $missingTask[] = $allTasksList[$i];
        }
    }
    return $missingTask;
}

function addTodoTask(){
    $todoSheetID = $_POST['todosheetID'];
    $time = $_POST['dayTime'];
    $day = $_POST['day'];
    $selectedList = "task".$day."time".$time;
    $taskID = $_POST[$selectedList];

    var_dump($todoSheetID);
    var_dump($day);
    var_dump($taskID);
    $taskDescription = getTaskDescription($taskID);

    $isAdded = addTodoThing($taskID, $todoSheetID, $day);

    if( isset($isAdded) ){
        $message = 'La tâche "'.$taskDescription.'" a été ajoutée.'; // todo : Message plus parlant pour l'utilisateur
    }else{
        $message = "Erreur lors de l'ajout de tâche.";
    }


    setFlashMessage($message);
    showtodo($todoSheetID,true);
}