<?php
/**
 * file with the main action -> the one used on every branch and common to every groups, when it is an user action. The admin actions are on the adminController
 */


/** Display Home page */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

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

function sendmail(){
    $mail = new PHPMailer();
//Tell PHPMailer to use SMTP
    $mail->isSMTP();
//Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
//Set the encryption mechanism to use - STARTTLS or SMTPS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//Whether to use SMTP authentication
    $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = '*';
//Password to use for SMTP authentication
    $mail->Password = '*';

//Set who the message is to be sent from
    $mail->setFrom('*', 'CSU');

//Set who the message is to be sent to
    $mail->addAddress('*', 'Destinataire');

//Set the subject line
    $mail->Subject = 'Mail de Test';
    $mail->Body ="aaaaa";
//send the message, check for errors
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
}
