<?php
/**
 * Created by PhpStorm.
 * User: Pascal.BENZONANA
 * Date: 08.05.2017
 * Time: 08:54
 * Update : 11-NOV-2020 - michael.gogniat
 * Simplify index. Remove all pages references.
 */

session_start();

require "../path.php";

require "../helpers.php";

require CONTROLER . "adminControler.php";
require CONTROLER . "drugControler.php";
require CONTROLER . "mainControler.php";
require CONTROLER . "shiftEndControler.php";
require CONTROLER . "weeklyTasksControler.php";


require MODEL . ".const.php";
require MODEL . "databaseModel.php";

require MODEL . "baseModel.php";
require MODEL . "userModel.php";
require MODEL . "drugModel.php";
require MODEL . "novaModel.php";
require MODEL . "shiftEndModel.php";
require MODEL . "weeklyTasksModel.php";


if (isset($_SESSION["username"])) {
    if ($_SESSION["username"]['firstconnect'] == false) {
        if (isset($_GET['action'])) {
            if (($_SESSION['username']['admin'] == true)) {
                switcherAdmin();
            } else {
                switcherUser();
            }
        } else {
            /** Using mainControler */
            home();
        }
    } else {
        /** Using mainControler */
        switcherFirstLogin();
    }
} else {
    /** Using mainControler */
    login();
}


function switcherAdmin()
{
    switch ($_GET['action']) {
        //---- Using adminControler ----
        //---- Users ----
        case 'adminHome':
            adminHome();
            break;
        case 'adminCrew' :
            adminCrew();
            break;
        case 'newUser' :
            newUser();
            break;
        case 'saveNewUser' :
            saveNewUser();
            break;
        case 'changeUserAdmin' :
            changeUserAdmin();
            break;
        case 'resetUserPassword':
            resetUserPassword();
            break;
        //---- Drugs ----
        case 'adminDrugs':
            adminDrugs();
            break;
        case 'newDrug' :
            newDrug();
            break;
        case 'updateDrug' :
            updateDrug();
            break;
        //---- Bases ----
        case 'adminBases':
            adminBases();
            break;
        case 'newBase' :
            newBase();
            break;
        case 'updateBase' :
            updateBase();
            break;
        //---- Novas ----
        case 'adminNovas':
            adminNovas();
            break;
        case 'newNova' :
            newNova();
            break;
        case 'updateNova' :
            updateNova();
            break;
        //---- ShiftSheet ----
        case 'newSheet':
            newShiftSheet($_POST['baseID']);
            break;
        default :
            switcherUser();
            break;
    }
}


function switcherFirstLogin()
{
    switch ($_GET['action']) {
        /** Using mainControler */
        case 'disconnect' :
            disconnect();
            break;
        default :
            firstLogin();
            break;
    }
}

function switcherUser()
{
    switch ($_GET['action']) {
        /** Using mainControler */
        case 'home' :
            home();
            break;
        case 'disconnect' :
            disconnect();
            break;

        /** Using drugControler */

        case 'closeStupFromTable' :
            $baseId = $_GET['stupBaseId'];
            $week = $_GET['stupPageWeek'];
            closedStupFromTable($baseId, $week);
            break;
        case 'addNewStup':
            ;
            createSheetStup();
            break;
        case 'activateStup' :
            activateStup();
            break;
        case 'activateStupFromTable' :
            activateStupFromTable();
            break;
        case "drugSiteTable":
            drugSiteTable();
            break;
        case 'updatePharmaCheck':
            pharmacheck();
            break;
        case "PharmaUpdate":
            PharmaUpdate();
            break;
        case 'LogStup':
            logStup();
            break;
        case 'drugs':
            if (isset($_POST["site"])) {
                $base_id = $_POST["site"];
            } else {
                $base_id = $_SESSION['base']['id'];
            }
            drugHomePage($base_id);
            break;
        case 'reopenStup':
            reopenStup();
            break;
        case 'closedStup':
            closedStup();
            break;

        /** Using shiftEndControler */

        case 'listShiftEnd':
            if (isset($_POST["site"])) {
                $baseID = $_POST["site"];
            } else {
                $baseID = $_SESSION['base']['id'];
            }
            listShiftEnd($baseID);
            break;
        case 'showGuardSheet':
            $sheetid = $_GET['id'];
            showShiftEnd($sheetid);
            break;
        case 'reOpenShift':
            reOpenShift();
            break;
        case 'closedShift':
            closeShift();
            break;

        /** Using weeklyTasksControler */

        case 'homeWeeklyTasks':
            if(isset($_POST['selectBaseID'])){
                $selectedBase = $_POST['selectBaseID'];
            } else {
                $selectedBase = $_SESSION['base']['id'];
            }
            homeWeeklyTasks($selectedBase);
            break;
        case 'toDoDetails':
            showWeeklyTasks($_POST['weekID'], $_POST['weekNbr']);
            break;
        case 'addWeek':
            // ToDo
            break;
        case 'closeWeek':
            // ToDo
            break;
        case 'reOpenToDo':
            reOpenToDo();
            break;
        default :
            unknownPage();
            break;
    }
}

