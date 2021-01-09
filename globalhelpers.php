<?php

setlocale(LC_ALL, 'fr_CH'); // pour les format de dates

function getVersion()
{
    return "2/Sprint 2";
}

/**
 * Permet de renvoyer le navigateur à une route précise, avec un id en paramètre ou non
 * @param $action l'action à laquelle renvoyer
 * @param int $id paramètre facultatif
 */
function redirect ($action, $id=0)
{
    if ($id > 0) {
        header('Location: ?action='.$action.'&id='.$id);
    } else {
        header('Location: ?action='.$action);
    }
}
/**
 * inspired by source https://stackoverflow.com/questions/7447472/how-could-i-display-the-current-git-branch-name-at-the-top-of-the-page-of-my-de
 * @author Kevin Ridgway
 */
function gitBranchTag()
{
    $stringfromfile = file('.git/HEAD', FILE_USE_INCLUDE_PATH);
    if ($stringfromfile) {
        $firstLine = $stringfromfile[0]; //get the string from the array
        $explodedstring = explode("/", $firstLine, 3); //seperate out by the "/" in the string
        $branchname = "la branche " . $explodedstring[2]; //get the one that is always the branch name
    } else {
        $branchname = "production";
    }
    return "<div style='clear: both; width: 100%; font-size: 14px; font-family: Helvetica; color: #8d8d8d; background: transparent; text-align: right;'>version " . getVersion() . " sur " . $branchname . "</div>"; //show it on the page
}

/**
 * Retourne un tableau des 7 dates de la semaine donnée
 * @param $weekNumber format AASS
 * @return array
 */
function getDaysForWeekNumber($weekNumber){
    $year = 2000 + intdiv($weekNumber,100);
    $week = $weekNumber%100;

    $dates = [];
    $time = strtotime(sprintf("%4dW%02d", $year, $week));

    for($i = 0; $i < 7; $i++){
        $dates[] = date("Y-m-d",strtotime("+".$i." day", $time));
    }

    return $dates;
}

/**
 * Return the state of the sheet on the screen
 * @param $id id of the pages on the screen
 * @param $zone name of the zone used (shift, drugs, todo)
 * @return $state
 */
function showSheetState($id, $zone){
    if($zone == "shift"){
        $slug = getStateFromSheet($id);
    }
    else if($zone == "todo"){
        $slug = getStateFromTodo($id);
    }
    else if ($zone == "drugs"){
        /** $slug = getStateFromDrugs($id); */
        $slug = "tsers";
    }
    else{

    }
    // todo (VB) : Utilisation de la base de données (displayname)
    switch($slug['slug']){
        case "blank":
            $state = "[En préparation]";
            break;
        case "open":
            $state = "[Active]";
            break;
        case "reopen":
            $state = "[En correction]";
            break;
        case "close":
            $state = "[Fermée]";
            break;
        case "archived":
            $state = "[Archivée]";
            break;
        default:
            $state = "[Non défini]";
            break;
    }

    return $state;
}

/**
 * Tells if the current user (logged in) can perform a certain action according to the policy
 * @param $action
 * @return bool
 */
function ican ($action)
{
    $policies = require('policies.php');
    return isset($policies[$_SESSION['user']['admin']][$action]);
}
