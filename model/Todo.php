<?php
/**Function for the daily task section**/


/**
 * Function that gets all data from a weekly sheet based on it's ID
 * @param $id : ID of the desired sheet
 * @return array|mixed|null
 */
function getTodosheetByID($id)
{
    return selectOne("SELECT todosheets.id AS id, week, base_id, template_name, slug, displayname
                             FROM todosheets
                             LEFT JOIN status ON todosheets.status_id = status.id
                             WHERE todosheets.id =:id", ['id' => $id]);

}



/**
 * Function that gets all data from a weekly sheet based on week number and base id *
 * @param $base_id : ID of the desired base
 * @param $weeknb : Number of the desired week. format: yynn where yy is 2 digit year and nn is week number
 * @return array|mixed|null
 */
function getTodosheetByBaseAndWeek($base_id, $weeknb)
{
    return selectOne("SELECT * FROM todosheets where base_id =:id and week = :weeknb", ['id' => $base_id, 'week' => $weeknb]);
}

/**
 * Function that gets all weekly sheets based on base ID and slug name
 * @param $baseID : ID of the desired base
 * @param $slug : Name of desired slug. Values: blank, open, close, reopen
 * @return array|mixed|null
 */
function getWeeksBySlugs($baseID, $slug)
{
    $query = "SELECT t.week, t.id, t.template_name
            FROM todosheets t
            JOIN bases b ON t.base_id = b.id
            JOIN status ON t.status_id = status.id
            WHERE b.id = :baseID AND status.slug =:slug
            ORDER BY t.week DESC;";
    return selectMany($query, ['baseID' => $baseID, 'slug' => $slug]);
}


function getStateFromTodo($id){
    return selectOne("SELECT status.slug FROM status LEFT JOIN todosheets ON todosheets.status_id = status.id WHERE todosheets.id =:sheetID", ["sheetID"=>$id]);
}





/**
 * Function that gets the latest week for a defined base
 * @param $base_id
 * @return array|mixed|null
 */
function GetLastWeek($base_id)
{
    return selectOne("SELECT MAX(week) as 'last_week', MAX(id) AS 'id'
                            FROM todosheets
                            Where base_id =:base_id
                            GROUP BY base_id", ["base_id" => $base_id]);
}


/**
 * @param $baseID
 * @param $weekNbr
 * @return string|null
 */
function createNewSheet($baseID, $weekNbr)
{
    return insert("INSERT INTO todosheets(base_id,status_id,week) VALUES(:base_id, 1, :week)", ["base_id" => $baseID, "week" => $weekNbr]); // 1 is value for blank
}

function readTodoThingsForDay($sid, $day, $dayOfWeek)
{
    $res = selectMany("SELECT description, type, value, u.initials AS 'initials', todos.id AS id
                             FROM todos 
                             INNER JOIN todothings t ON todos.todothing_id = t.id
                             LEFT JOIN users u ON todos.user_id = u.id
                             WHERE todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sid, "daything" => $day, "dayofweek" => $dayOfWeek]);
    return $res;
}

function readTodoForASheet($sheetID)
{
    $query = "SELECT todothing_id AS id, daything, day_of_week AS 'day'
                FROM todos
                INNER JOIN todothings ON todos.todothing_id = todothings.id
                INNER JOIN todosheets ON todos.todosheet_id = todosheets.id
                WHERE todosheet_id = :id";

    return selectMany($query, ['id' => $sheetID]);
}

function addTodoThing($todoID, $weekID, $dayOfWeek)
{
    $query = "INSERT INTO todos (todothing_id, todosheet_id, day_of_week) VALUE (:todoID, :sheetID, :day)";
    execute($query, ['todoID' => $todoID, 'sheetID' => $weekID, 'day' => $dayOfWeek]);
}

function updateTemplateName($id, $template_name)
{
    return execute(
        "UPDATE todosheets SET template_name=:template_name WHERE id =:id", ['template_name' => $template_name, 'id' => $id]);
}

function deleteTemplateName($id)
{
    return execute(
        "UPDATE todosheets SET template_name=NULL WHERE id =:id", ['id' => $id]);
}



function unvalidateTodo($id, $type)
{
    if ($type == 1) {
        return execute("UPDATE todos SET user_id=NULL WHERE id=:id", ['id' => $id]);
    } else {
        return execute("UPDATE todos SET user_id=NULL, value=NULL WHERE id=:id", ['id' => $id]);
    }

}

function validateTodo($id, $value)
{
    $initials = $_SESSION['user']['initials'];
    $user = getUserByInitials($initials);

    if (!empty($value)) {
        return execute("UPDATE todos SET user_id=:userID, value=:value WHERE id=:id;", ['userID' => $user['id'], 'value' => $value, 'id' => $id]);
    } else {
        return execute("UPDATE todos SET user_id=:userID WHERE id=:id;", ['userID' => $user['id'], 'id' => $id]);
    }
}

function getTemplateName($id)
{
    $query = "SELECT template_name 
             FROM todosheets
             WHERE id = :id";
    return selectOne($query, ['id' => $id]);
}

function getTodosheetMaxID($selectedBaseID)
{
    $query = "SELECT MAX(id) AS id
              FROM todosheets
              WHERE base_id =:id";
    return selectOne($query, ['id' => $selectedBaseID]);
}

function getAllTemplateNames()
{
    $query = "SELECT template_name, id 
             FROM todosheets
             WHERE template_name is NOT NULL";
    return selectMany($query, []);
}

function getTemplateSheet($templateName)
{
    return selectOne("SELECT id, week AS last_week
                            FROM todosheets
                            Where template_name =:template", ["template" => $templateName]);
}

/*
function closeStatus(){
    return selectOne("SELECT slug
                      FROM status
                      Where slug = 'close'", []);

}*/

/**
 * @param $id
 * @param $slug
 * @return bool|null
 */
function changeSheetState($id, $slug)
{
    $query = "UPDATE todosheets SET status_id=";

    switch($slug){
        case "open":
            $query = $query."2";
            break;
        case "reopen":
            $query = $query."4";
            break;
        case "close":
            $query = $query."3";
            break;
        case "archive":
            $query = $query."5";
            break;
        default:
            break;
    }

    $query = $query." WHERE id=:id";

    return execute($query,['id' => $id]);
}

/**
 * @param $id
 * @return bool|null
 */
function deleteTodoSheet($id){
    return execute("DELETE FROM todosheets WHERE id=:id",['id' => $id]);
}