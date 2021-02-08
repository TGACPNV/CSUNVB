<?php

/**
 * Open a connection to the database
 * @return PDO
 */
function getPDO() {
    require_once ".const.php";
    $dbh = null;
    $dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);/*
    try {
        $dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }*/
    $dbh->exec("set names utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

//Fonction permettant de selectionner des données
function select($query, $params, $multirecord) {

    $dbh = getPDO();
    try {
        $statement = $dbh->prepare($query);     //Préparer la requête
        $statement->execute($params);       //Exécuter la requête
        if ($multirecord)       //Si on veut récuperer plusieurs données
        {
            $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);  //Alors on fait un fetchAll
        } else {
            $queryResult = $statement->fetch(PDO::FETCH_ASSOC);     //Sinon on fait un fetch simple
        }
        $dbh = null;
        return $queryResult;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

//Fonction permettant de récuperer plusieurs données
function selectMany($query, $params = []) {
    return select($query, $params, true);
}

//Fonction permettant de récuperer une donnée
function selectOne($query, $params = []) {
    return select($query, $params, false);
}

//Fonction permettant d'insérer des données
function insert($query, $params = []) {
    $dbh = getPDO();
    try {
        $statement = $dbh->prepare($query);     //Préparer la requête
        $statement->execute($params);       //Exécuter la requête
        return $dbh->lastInsertId();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}

//Fonction permettant de mettre à jour et d'effacer des données
function execute($query, $params = []) {
    $dbh = getPDO();
    try {
        $statement = $dbh->prepare($query);     //Préparer la requête
        $statement->execute($params);       //Exécuter la requête
        $dbh = null;
        return true;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return null;
    }
}
