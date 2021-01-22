<?php

/**
 * Function that creates the display of weekly tasks (todosheet) for the base selected at login
 */
function listtodo()
{
    listtodoforbase($_SESSION['base']['id']);
}

/**
 * Function that creates the display of weekly tasks (todosheet) specified by base ID
 * @param int $baseID : ID of specified base
 */
function listtodoforbase($baseID)
{

    // Récupération des semaines en fonction de leur état (slug) et de la base choisie
    $sheets = getAllTodoSheetsForBase($baseID);

    $baseList = getbases();
    $templates = getAllTemplateNames();
    $lastClosedWeek = getLastWeekClosed($baseID);

    require_once VIEW . 'todo/list.php';
}

/**
 * Function that creates the display of weekly tasks (todosheet) specified by todosheet ID
 * @param int $sheetID : ID of specified todosheet
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

            $todoThings[$daynight][$dayofweek] = readTodoThingsForDay($sheetID, $daynight, $dayofweek); //todo:add explanation
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
 * Function that creates a new todosheet in the database from a template or last closed todosheet
 * Shows a message if successful
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
 * Function that returns the next week number or specified week
 * @param int $weekNbr
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

/**
 *Function used to call updateTemplateName and pass needed data
 */
function modelWeek()
{
    $todosheetID = $_POST['todosheetID'];

    updateTemplateName($todosheetID, $_POST['template_name']);
    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**
 *Function used to call deleteTemplate and pass needed data
 */
function deleteTemplate()
{
    $todosheetID = $_POST['todosheetID'];

    deleteTemplateName($todosheetID);
    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**
 *Function used to activate amd deactivate editing mode
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

/**
 *Function to delete a task from a todosheet
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

/**
 *  Function to mark a task as done or not done
 */
function switchTodoStatus() //todo. change name
{
    $status = $_POST['modal-todoStatus']; //todo:check name
    $todoID = $_POST['modal-todoID'];
    $todoType = $_POST['modal-todoType'];
    $todoValue = $_POST['modal-todoValue'];
    $todosheetID = $_POST['todosheetID'];

    if ($status == 'unvalidate') { //todo: change name
        invalidateTodo($todoID, $todoType);
    } else {
        validateTodo($todoID, $todoValue);
    }

    header('Location: ?action=showtodo&id=' . $todosheetID);
}

/**
 * Function to change the active status of a sheet
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

/**
 * Function to delete a todosheet from databas
 * Shows a message if successful
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
 * Function that looks for missing tasks from a todosheet from a reference list
 * @param array $allTasksList : reference list
 * @param array $taskList : existing list to check
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

/**
 * Function to add a task to a todosheet
 * Shows a message if successful or an error
 */
function addTodoTask(){
    $todoSheetID = $_POST['todosheetID'];
    $time = $_POST['dayTime'];
    $day = $_POST['day'];
    $selectedList = "task".$day."time".$time;
    $taskID = $_POST[$selectedList];

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