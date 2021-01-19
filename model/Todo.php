<?php
/**Function for the daily task section**/


/**
 * Function that gets all data from a weekly sheet based on it's ID
 * @param $sheetID : ID of the desired sheet
 * @return array|mixed|null
 */
function getTodosheetByID($sheetID)
{
    return selectOne("SELECT todosheets.id AS id, week, base_id, template_name, slug, displayname
                             FROM todosheets
                             LEFT JOIN status ON todosheets.status_id = status.id
                             WHERE todosheets.id =:sheetID", ['sheetID' => $sheetID]);

}

/**
 * Function that gets all data from a weekly sheet based on week number and base id *
 * @param $baseID : ID of the desired base
 * @param $weekNbr : Number of the desired week. format: yynn where yy is 2 digit year and nn is week number
 * @return array|mixed|null
 */
function getTodosheetByBaseAndWeek($baseID, $weekNbr)
{
    return selectOne("SELECT * FROM todosheets where base_id =:baseID and week = :weekNbr", ['baseID' => $baseID, 'weekNbr' => $weekNbr]);
}


function getAllTodoSheetsForBase($baseID){
    $slugs = selectMany("SELECT id,slug as name FROM status",[]);
    foreach ($slugs as $slug){
        $sheets[$slug['name']]= getWeeksBySlugs($baseID,$slug['name']);
    }
    return  $sheets;
}

/**
 * Function that gets all weekly sheets based on base ID and slug name
 * @param $baseID : ID of the desired base
 * @param $slug : Name of desired slug. Values: blank, open, close, reopen
 * @return array|mixed|null
 */
function getWeeksBySlugs($baseID, $slug)
{
    $query = "SELECT t.week, t.id, t.template_name, t.base_id
            FROM todosheets t
            JOIN bases b ON t.base_id = b.id
            JOIN status ON t.status_id = status.id
            WHERE b.id = :baseID AND status.slug =:slug
            ORDER BY t.week DESC;";
    return selectMany($query, ['baseID' => $baseID, 'slug' => $slug]);
}


function getStateFromTodo($id){
    return selectOne("SELECT status.slug, status.displayname FROM status LEFT JOIN todosheets ON todosheets.status_id = status.id WHERE todosheets.id =:sheetID", ["sheetID"=>$id]);
}


/**
 * Function that gets the latest week for a defined base
 * @param $baseID : ID of the desired base
 * @return array|mixed|null
 */
function getLastWeek($baseID)
{
    return selectOne("SELECT MAX(week) as 'last_week', MAX(id) AS 'id'
                            FROM todosheets
                            Where base_id =:baseID
                            GROUP BY base_id", ["baseID" => $baseID]);
}


/**
 * Function that creates a new weekly sheet
 * @param $baseID : ID of the desired base
 * @param $weekNbr : Number of the desired week. format: yynn where yy is 2 digit year and nn is week number
 * @return string|null
 */
function createNewSheet($baseID, $weekNbr)
{
    return insert("INSERT INTO todosheets(base_id,status_id,week) VALUES(:baseID, 1, :weekNbr)", ["baseID" => $baseID, "weekNbr" => $weekNbr]); // 1 is value for blank
}

/**
 * @param $sid
 * @param $day
 * @param $dayOfWeek
 * @return array|mixed|null
 */
function readTodoThingsForDay($sid, $day, $dayOfWeek)
{
    $res = selectMany("SELECT description, type, value, u.initials AS 'initials', todos.id AS id
                             FROM todos 
                             INNER JOIN todothings t ON todos.todothing_id = t.id
                             LEFT JOIN users u ON todos.user_id = u.id
                             WHERE todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sid, "daything" => $day, "dayofweek" => $dayOfWeek]);
    return $res;
}

/**
 * @param $sheetID
 * @return array|mixed|null
 */
function readTodoForASheet($sheetID)
{
    $query = "SELECT todothing_id AS id, daything, day_of_week AS 'day'
                FROM todos
                INNER JOIN todothings ON todos.todothing_id = todothings.id
                INNER JOIN todosheets ON todos.todosheet_id = todosheets.id
                WHERE todosheet_id = :sheetID";

    return selectMany($query, ['sheetID' => $sheetID]);
}

/**
 * @param $todoID
 * @param $weekID
 * @param $dayOfWeek
 */
function addTodoThing($todoID, $weekID, $dayOfWeek)
{
    $query = "INSERT INTO todos (todothing_id, todosheet_id, day_of_week) VALUE (:todoID, :sheetID, :day)";
    execute($query, ['todoID' => $todoID, 'sheetID' => $weekID, 'day' => $dayOfWeek]);
}

/**
 * @param $id
 * @param $template_name
 * @return bool|null
 */
function updateTemplateName($id, $template_name)
{
    return execute(
        "UPDATE todosheets SET template_name=:template_name WHERE id =:id", ['template_name' => $template_name, 'id' => $id]);
}

/**
 * @param $id
 * @return bool|null
 */
function deleteTemplateName($id)
{
    return execute(
        "UPDATE todosheets SET template_name=NULL WHERE id =:id", ['id' => $id]);
}


/**
 * @param $id
 * @param $type
 * @return bool|null
 */
function unvalidateTodo($id, $type)
{
    if ($type == 1) {
        return execute("UPDATE todos SET user_id=NULL WHERE id=:id", ['id' => $id]);
    } else {
        return execute("UPDATE todos SET user_id=NULL, value=NULL WHERE id=:id", ['id' => $id]);
    }

}

/**
 * @param $id
 * @param $value
 * @return bool|null
 */
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

/**
 * @param $id
 * @return array|mixed|null
 */
function getTemplateName($id)
{
    $query = "SELECT template_name 
             FROM todosheets
             WHERE id = :id";
    return selectOne($query, ['id' => $id]);
}

/**
 * @param $selectedBaseID
 * @return array|mixed|null
 */
function getTodosheetMaxID($selectedBaseID)
{
    $query = "SELECT MAX(id) AS id
              FROM todosheets
              WHERE base_id =:id";
    return selectOne($query, ['id' => $selectedBaseID]);
}

/**
 * @return array|mixed|null
 */
function getAllTemplateNames()
{
    $query = "SELECT template_name, id 
             FROM todosheets
             WHERE template_name is NOT NULL";
    return selectMany($query, []);
}

/**
 * @param $templateName
 * @return array|mixed|null
 */
function getTemplateSheet($templateName)
{
    return selectOne("SELECT id, week AS last_week
                      FROM todosheets
                      Where template_name =:template", ["template" => $templateName]);
}

/**
 * @param $id
 * @param $slug
 * @return bool|null
 */
function changeSheetState($id, $slug)
{
    return execute("UPDATE todosheets SET status_id= (SELECT id FROM status WHERE slug =:slug) WHERE id=:id", ['id' => $id, 'slug' => $slug]);
}

/**
 * @param $sheetID
 * @return bool|null
 */
function deleteTodoSheet($sheetID){
    return execute("DELETE FROM todosheets WHERE id=:sheetID",['sheetID' => $sheetID]);
}

function deletethingsID($todoTaskID){
    return execute("DELETE FROM todos WHERE id =:task_id",['task_id' => $todoTaskID]);
}

function getOpenTodoSheetNumber($baseID){
    return selectOne("SELECT COUNT(todosheets.id) as number FROM  todosheets inner join status on status.id = todosheets.status_id where status.slug = 'open' and todosheets.base_id =:base_id", ['base_id' => $baseID])['number'];
}

function getTaskName($todoTaskID){
    return execute("SELECT description
                          FROM todos
                          LEFT JOIN todothings on todos.todothing_id = todothings.id 
                          WHERE todos.id =:task_id",['task_id' => $todoTaskID]);
}

//SELECT description
//FROM todos
//LEFT JOIN todothings on todos.todothing_id = todothings.id
//WHERE todos.id = 120