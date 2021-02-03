<?php
/**
 *
 */

function getUsers()     //Récupère tous les utilisateurs
{
    return selectMany("SELECT * FROM users order by  initials ASC", []);
}

function addNewUser($prenomUser, $nomUser, $initialesUser, $hash, $admin, $firstconnect)
{
    return intval(insert("INSERT INTO users (firstname, lastname, initials, password, admin, firstconnect) VALUES (:firstname, :lastname, :initials, :password, :admin, :firstconnect)", ['firstname' => $prenomUser, 'lastname' => $nomUser, 'initials' => $initialesUser, 'password' => $hash, 'admin' => $admin, 'firstconnect' => $firstconnect]));       //à optimiser/simplifier avec un tableau
}

function SaveUserPassword($hash, $id)       //Met à jour le mdp d'un utilisateur
{
    return execute("UPDATE users SET password= :password, firstconnect= :firstconnect where id = :id", ['password' => $hash, 'firstconnect' => 0, 'id' => $id]);
}

/** update the user
 * @return bool|null
 */
function SaveUser($user)
{
    unset($user['password']);
    unset($user['firstconnect']);
    return execute("UPDATE users SET firstname= :firstname, lastname= :lastname, initials = :initials, admin = :admin, email = :email, mobileNumber = :mobileNumber where id = :id", $user);
}

/** return the user from database
 * @param $id id of the user
 * @return array|mixed|null
 */
function getUser($id)
{
    return selectOne("SELECT * FROM users where id=:id", ['id' => $id]);
}

/** return the user from database
 * @param $initials initials of the user
 * @return array|mixed|null
 */
function getUserByInitials($initials)       //Récupère un utilisateur en fonction de ses initiales
{
    return selectOne("SELECT * FROM users where initials =:initials", ['initials' => $initials]);
}

/** change password of a user ( a radom one )
 * @param $changeUser id of the user
 * @return false|string ( string of the new password )
 */
function changePwdState($changeUser)
{
    $newpassw = substr(md5(rand()),0,6);
    $hash = password_hash($newpassw, PASSWORD_DEFAULT);
    execute("UPDATE users SET firstconnect= :firstconnect, password = :hash WHERE id= :id", ['firstconnect' => 1, 'id' => $changeUser, 'hash' => $hash]);
    return $newpassw;
}
