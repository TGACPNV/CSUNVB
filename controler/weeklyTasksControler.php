<!--
COMMENTAIRES
-->
<?php

/** Fonction qui permet l'affichage des semaines de tâches pour une base spécifique
 * @param $selectedBaseID : l'ID de la base dont les semaines sont à afficher
 */
function homeWeeklyTasks($selectedBaseID){
    $weeksNbrList = getClosedWeeks($selectedBaseID); // La liste des numéros de semaines qui sont fermées
    $activeWeek = getOpenedWeeks($selectedBaseID);  // Le numero de la semaine active

    $baseList = getbases();
    require_once VIEW . 'todo/homeWeeklyTasks.php';
}

/**
 * Fonction qui affiche les tâches d'une semaine spécifique
 * @param $weekID : l'ID de la semaine à afficher
 * @param $weekNbr : Le numéro de la semaine à afficher
 */
function showWeeklyTasks($weekID, $weekNbr){
    $dates = getDatesFromWeekNumber($weekNbr);
    // Récupération des dates par rapport à la semaine
    require_once VIEW . 'todo/detailsWeeklyTasks.php';
}

/**
 * Fonction qui retourne les dates des jours de la semaine, à partir d'un numéro de semaine
 * @param $weekNumber : Le numéro de la semaine dont on veut connaitre les dates
 * @return array : les dates de la semaine
 */
function getDatesFromWeekNumber($weekNumber){
    $year = 2000 + intdiv($weekNumber,100);
    $week = $weekNumber%100;

    $dates = Array();
    setlocale(LC_TIME, 'fr'); // Besoin de vérifier que cette ligne ne posera aucun problème !
    $time = strtotime(sprintf("%4dW%02d", $year, $week));

    for($i = 0; $i < 7; $i++){
        $day = date(strtotime("+".$i." day", $time));
        $fullDate = strftime('%A %e %b %Y', $day);
        $dates[] = $fullDate;
    }

    return $dates;
}