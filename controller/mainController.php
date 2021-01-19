<?php
/**
 * file with the main action -> the one used on every branch and common to every groups
 */


/** Display Home page */
function home()
{
    require VIEW . 'main/home.php';
}

/**
 * disconnect the user
 */
function disconnect()
{
    $_SESSION['user'] =  null;
    $_SESSION['action'] = 'login';
    login();
}

/**
 * verify presence of every parameter for the connexion
 */
function login()
{
    if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['base']))
    {
        trylogin();
    }else{
        displayLoginPage();
    }
}

/**
 * show the login page
 */
function displayLoginPage(){
    $bases = getbases();
    require VIEW . 'main/login.php';
}

/**
 * attempt to connect the user
 */
function tryLogin()
{
    $initials = $_POST['username'];
    $password = $_POST['password'];
    $baseLogin = $_POST['base'];
    $user = getUserByInitials($initials);
    if (password_verify($password, $user['password'])) {
        unset($user['password']); // don't store password in the session
        $_SESSION['user'] =  $user;
        $_SESSION['base'] = getbasebyid($baseLogin);        //Met la base dans la session
        if ($user['firstconnect'] == true) {
            firstLogin();
        } else {
            setFlashMessage('Bienvenue ' . $user['firstname'] . ' ' . $user['lastname'] . ' !');
            home();
        }
    } else {
        setFlashMessage('Identifiants incorrects ...');
        displayLoginPage();
    }
}

/**
 * send to a specific page if it is the first login of a person
 */
function firstLogin(){
    if(isset($_POST['passwordchange'])&&isset($_POST['confirmpassword']))
    {
        changeFirstPassword();
    }else{
        firstLoginPage();
    }
}

/**
 * change the password of a new user -> it is mandatory and everyone arrives here the first time
 */
function changeFirstPassword()
{
    $passwordChange = $_POST['passwordchange'];
    $confirmPassword = $_POST['confirmpassword'];
    //todo : confirmation plantée -> à regarder
    if ($passwordChange != $_SESSION['user']['password']) {
        if ($confirmPassword != $passwordChange) {
            setFlashMessage("Erreur lors de la confirmation du mot de passe");
            firstLoginPage();
        } else {
            setFlashMessage("Mot de passe modifié");
            $id = $_SESSION['user']['id'];
            $hash = password_hash($confirmPassword, PASSWORD_DEFAULT);
            SaveUserPassword($hash, $id);
            disconnect();
        }
    } else {
        setFlashMessage("Le nouveau mot de passe doit être différent de l'ancien !");
        firstLoginPage();
    }
}

/**
 * send to the view of the page of the first login
 */
function firstLoginPage(){
    require VIEW . 'main/firstLogin.php';
}

/**
 * send to the view of an unknown page
 */
function unknownPage(){
    require VIEW . 'main/unknownPage.php';
}

