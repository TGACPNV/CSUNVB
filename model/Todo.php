<?php
/**Function for the daily task section**/


/**
 * Function that gets all data from a weekly todosheets specified by ID
 * @param int $sheetID : ID of specified todosheet
 * @return array|mixed|null
 */
function getTodosheetByID($sheetID)
{
    return selectOne("SELECT todosheets.id AS id, week, base_id, template_name, slug, displayname
                             FROM todosheets
                             LEFT JOIN status ON todosheets.status_id = status.id
                             WHERE todosheets.id =:sheetID", ['sheetID' => $sheetID]);
}


/** Function that returns all todosheets for a base specified by ID
 * @param  int $baseID : ID of specified base
 * @return array :  all todosheets for a specified base, odered by slug
 */
function getAllTodoSheetsForBase($baseID){
    $slugs = selectMany("SELECT id,slug as name FROM status",[]);
    foreach ($slugs as $slug){
        $sheets[$slug['name']]= getWeeksBySlugs($baseID,$slug['name']);
    }
    return  $sheets;
}

/**
 * Function that gets all weekly todosheets based on base ID and slug name
 * @param int $baseID : ID of the specified base
 * @param string $slug : Name of specified slug. Values: blank, open, close, reopen, archived
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

/**
 * Function that returns the the state (slug) for a todosheets by ID
 * @param int $sheetID
 * @return array|mixed|null
 */
function getStateFromTodo($sheetID){
    return selectOne("SELECT status.slug, status.displayname FROM status LEFT JOIN todosheets ON todosheets.status_id = status.id WHERE todosheets.id =:sheetID", ["sheetID"=>$sheetID]);
}


/**
 * Function that gets the latest week for a defined base
 * @param int $baseID : ID of the specified base
 * @return array|mixed|null
 */
function getLastWeek($baseID)
{
    return selectOne("SELECT MAX(week) as 'week', id
                            FROM todosheets
                            WHERE base_id =:baseID", ["baseID" => $baseID]);
}

/**
 * Function that gets the latest closed week for a defined base
 * @param int $baseID : ID of the specified base
 * @return array|mixed|null
 */
function getLastWeekClosed($baseID)
{
    return selectOne("SELECT MAX(week) as 'week', t.id
                            FROM todosheets t
                            JOIN status ON t.status_id = status.id
                            Where base_id =:baseID 
                            AND slug = 'close'", ["baseID" => $baseID]);
}


/**
 * Function that creates a new weekly sheet
 * @param int $baseID : ID of the specified base
 * @param int $weekNbr : Number of the specified week. format: yynn where yy is 2 digit year and nn is week number
 * @return string|null
 */
function createNewSheet($baseID, $weekNbr)
{
    return insert("INSERT INTO todosheets(base_id,status_id,week) VALUES(:baseID, 1, :weekNbr)", ["baseID" => $baseID, "weekNbr" => $weekNbr]); // 1 is value for blank
}

/**
 * Function that returns all tasks that match the 3 parameters
 * @param int $sheetID : ID of the specified todosheet
 * @param int $daynight : day or night (0 or 1)
 * @param int $dayOfWeek : day of the week (1-7)
 * @return array|mixed|null
 */
function readTodoThingsForDay($sheetID, $daynight, $dayOfWeek)
{
    $res = selectMany("SELECT description, type, value, u.initials AS 'initials', todos.id AS id, t.id AS todothingID
                             FROM todos 
                             INNER JOIN todothings t ON todos.todothing_id = t.id
                             LEFT JOIN users u ON todos.user_id = u.id
                             WHERE todosheet_id=:sid AND daything = :daything AND day_of_week = :dayofweek", ["sid" => $sheetID, "daything" => $daynight, "dayofweek" => $dayOfWeek]);
    return $res;
}

/**
 * Function that returns all tasks matching sheetID
 * @param int $sheetID
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
 *Function that adds a specified task to a todosheet on a specified day
 * @param int $taskID : specified task id
 * @param int $sheetID : specified sheet id
 * @param int $dayOfWeek : day of the week (1-7)
 * @return bool|null
 */
function addTodoThing($taskID, $sheetID, $dayOfWeek)
{
    $query = "INSERT INTO todos (todothing_id, todosheet_id, day_of_week) VALUE (:taskID, :sheetID, :day)";
    return execute($query, ['taskID' => $taskID, 'sheetID' => $sheetID, 'day' => $dayOfWeek]);
}

/**
 * Function to change name of a template
 * @param int $sheetID : specified sheet id
 * @param string $templateName : new name for template
 * @return bool|null
 */
function updateTemplateName($sheetID, $templateName)
{
    return execute(
        "UPDATE todosheets SET template_name=:templateName WHERE id =:id", ['templateName' => $templateName, 'id' => $sheetID]);
}

/**
 * Function to remove a template
 * @param int $sheetID
 * @return bool|null
 */
function deleteTemplateName($sheetID)
{
    return execute(
        "UPDATE todosheets SET template_name=NULL WHERE id =:id", ['id' => $sheetID]);
}


/**
 * Function to invalidate a task (mark as not completed)
 * @param int $todoTaskID : ID of task in sheet
 * @param int $type : if task has an addition "value" associated or not (1 or 2)
 * @return bool|null
 */
function invalidateTodo($todoTaskID, $type)
{
    if ($type == 1) {
        return execute("UPDATE todos SET user_id=NULL WHERE id=:id", ['id' => $todoTaskID]);
    } else {
        return execute("UPDATE todos SET user_id=NULL, value=NULL WHERE id=:id", ['id' => $todoTaskID]);
    }

}

/**
 * Function to validate a task (mark as completed)
 * @param int $todoTaskID : ID of task in sheet
 * @param int $value : value needed to be added for some tasks
 * @return bool|null
 */
function validateTodo($todoTaskID, $value)
{
    $initials = $_SESSION['user']['initials'];
    $user = getUserByInitials($initials);

    if (!empty($value)) {
        return execute("UPDATE todos SET user_id=:userID, value=:value WHERE id=:id;", ['userID' => $user['id'], 'value' => $value, 'id' => $todoTaskID]);
    } else {
        return execute("UPDATE todos SET user_id=:userID WHERE id=:id;", ['userID' => $user['id'], 'id' => $todoTaskID]);
    }
}

/**
 * Function to get template name for specified sheet id
 * @param int $sheetID
 * @return array|mixed|null
 */
function getTemplateName($sheetID)
{
    $query = "SELECT template_name 
             FROM todosheets
             WHERE id = :id";
    return selectOne($query, ['id' => $sheetID]);
}

/**
 * Function to get all template names and their IDs
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
 * Function to get sheetID for specified template name
 * @param string $templateName
 * @return array|mixed|null
 */
function getTemplateSheet($templateName)
{
    return selectOne("SELECT id, week AS last_week
                      FROM todosheets
                      Where template_name =:template", ["template" => $templateName]);
}

/**
 *Function to change to state of a todosheets specified by id and slug
 * @param int $sheetID
 * @param string $slug : Name of specified slug. Values: blank, open, close, reopen, archived
 * @return bool|null
 */
function changeSheetState($sheetID, $slug)
{
    return execute("UPDATE todosheets SET status_id= (SELECT id FROM status WHERE slug =:slug) WHERE id=:id", ['id' => $sheetID, 'slug' => $slug]);
}

/**
 * Function do delete a todosheets specified by id
 * @param int $sheetID
 * @return bool|null
 */
function deleteTodoSheet($sheetID){
    return execute("DELETE FROM todosheets WHERE id=:sheetID",['sheetID' => $sheetID]);
}

/**
 * Function do delete a task from a todosheets
 * @param int $todoTaskID
 * @return bool|null
 */
function deletethingsID($todoTaskID){
    return execute("DELETE FROM todos WHERE id =:task_id",['task_id' => $todoTaskID]);
}

/**
 * Function that returns number of open todosheets
 * @param int $baseID
 * @return mixed
 */
function getOpenTodoSheetNumber($baseID){
    return selectOne("SELECT COUNT(todosheets.id) as number FROM  todosheets inner join status on status.id = todosheets.status_id where status.slug = 'open' and todosheets.base_id =:base_id", ['base_id' => $baseID])['number'];
}

/**
 * Function that returns description of a task specified by id (FROM THE todos TABLE)
 * @param int $todoTaskID
 * @return mixed
 */
function getTaskName($todoTaskID){
    return selectOne("SELECT description
                          FROM todos
                          LEFT JOIN todothings on todos.todothing_id = todothings.id 
                          WHERE todos.id =:task_id",['task_id' => $todoTaskID])['description'];
}


/**
 * Function that returns all task by day or night
 *  @param int $daynight : 0 for night and 1 for day
 * @return array|mixed|null
 */
function getTasksByTime($daynight){
    return selectMany("SELECT id, description FROM todothings WHERE daything = :daynight",['daynight' => $daynight]);
}

/** Function that returns description of a task specified by id (FROM THE todothings TABLE)
 * @param int $taskID
 * @return mixed
 */
function getTaskDescription($taskID){
    return selectOne("SELECT description
                          FROM todothings
                          WHERE id =:task_id",['task_id' => $taskID])['description'];
}