<?php

/** Display admin page */
function adminHome()
{
    require VIEW . 'admin/adminHome.php';
}

/** Users Administration */
function adminCrew()
{
    $users = getUsers();
    require_once VIEW . 'admin/adminCrew.php';
}

function newUser()      //Pointe sur la page d'ajout d'un user
{
    require_once VIEW . 'admin/newUser.php';
}

function saveNewUser()         //Crée un utilisateur
{

    $prenomUser = $_POST['prenomUser'];
    $nomUser = $_POST['nomUser'];
    $initialesUser = $_POST['initialesUser'];
    $startPassword = $_POST['startPassword'];
    $hash = password_hash($startPassword, PASSWORD_DEFAULT);

    if($prenomUser == " " || $initialesUser == " " || $nomUser == " "){
        setFlashMessage("Ni le prénom, ni le nom, ni les initiales ne peut être un champ vide.");
    }
    else {
        $result = addNewUser($prenomUser, $nomUser, $initialesUser, $hash, 0, 1);
        if ($result == 0) {
            setFlashMessage("Une erreur est survenue. Impossible d'ajouter l'utilisateur.");
        } else {
            setFlashMessage("L'utilisateur a bien été ajouté !");
        }
    }
    adminCrew();
}

function changeUserAdmin()       //Change un user en admin (et inversément)
{
    $changeUser = $_GET['idUser'];
    $user = getUser($changeUser);
    if ($user['admin']) {
        $user['admin'] = 0;
        setFlashMessage($user['initials'] . " est désormais un utilisateur.");
    } else {
        $user['admin'] = 1;
        setFlashMessage($user['initials'] . " est désormais un administrateur.");
    }
    SaveUser($user);
    adminCrew();
}

function resetUserPassword()
{
    $newpassword = changePwdState($_GET['idUser']);
    setFlashMessage("Le nouveau mot de passe est: $newpassword");
    adminCrew();
}

/** drugs Administration */

function adminDrugs()
{
    $drugs = getDrugs();
    require_once VIEW . 'admin/adminDrugs.php';
}

function newDrug(){
    if(isset($_POST['nameDrug'])){
        if($_POST['nameDrug']==" " || $_POST['nameDrug']==""){
            setFlashMessage("Le nom de la base ne peut pas être vide.");
        }
        else {
            $res = addNewDrug($_POST['nameDrug']);
            if ($res == false) {
                setFlashMessage("Une erreur est survenue. Impossible d'ajouter le médicament.");
            } else {
                setFlashMessage("Le médicament " . $_POST['nameDrug'] . " a été correctement ajouté.");
            }
        }
        adminDrugs();
    }
    else {
        require_once VIEW . 'admin/newDrug.php';
    }
}

function updateDrug(){
    $idDrug = $_GET['idDrug'];
    if(isset($_POST['updateNameDrug'])){
        $res =updateDrugName($_POST['updateNameDrug'], $idDrug);
        if ($res == false) {
            setFlashMessage("Une erreur est survenue. Impossible de renommer le médicament.");
        } else {
            setFlashMessage("Le médicament a été correctement renommé.");
        }
        adminDrugs();
    }
    else {
        require_once VIEW . 'admin/updateDrug.php';
    }
}

/** Bases Administration */

function adminBases()
{
    $bases = getbases();
    require_once VIEW . 'admin/adminBases.php';
}

function newBase(){
    if(isset($_POST['nameBase'])){
        if($_POST['nameBase']==" " || $_POST['nameBase']==""){
            setFlashMessage("Le nom de la base ne peut pas être vide.");
        }
        else {
            $res = addNewBase($_POST['nameBase']);
            if ($res == false) {
                setFlashMessage("Une erreur est survenue. Impossible d'ajouter la base.");
            } else {
                setFlashMessage("La base a été correctement ajoutée.");
            }
        }
        adminBases();
    }
    else {
        require_once VIEW . 'admin/newBase.php';
    }
}

function editbase($id)
{
    $base = getbasebyid($id);
    require_once VIEW . 'admin/updateBase.php';
}

function updateBase()
{
    extract($_POST); // crée les variables $id et $updateNameBase qui sont les clés du POST
    $res = renameBase($id, $updateNameBase);
    if ($res == false) {
        setFlashMessage("Une erreur est survenue. Impossible de renommer la base.");
    } else {
        setFlashMessage("La base a été correctement renommée.");
    }
    redirect('adminBases');
}

/** Nova Administration */

function adminNovas()
{
    $novas = getNovas();
    require_once VIEW . 'admin/adminNovas.php';
}

function newNova(){
    if(isset($_POST['nameNova'])){
            $res = addNewNova($_POST['nameNova']);
            if ($res == false) {
                setFlashMessage("Une erreur est survenue. Impossible d'ajouter la nova.");
            } else {
                setFlashMessage("La nova a été correctement ajoutée.");
            }
            adminNovas();
    }
    else {
        require_once VIEW . 'admin/newNova.php';
    }
}

function updateNova()
{
    $idNova = $_GET['idNova'];
    if(isset($_POST['updateNameNova'])){
        $res = updateNameNova($_POST['updateNameNova'], $idNova);
        if ($res == false) {
            setFlashMessage("Une erreur est survenue. Impossible de renommer la nova.");
        } else {
            setFlashMessage("La nova a été correctement renommée.");
        }
        adminNovas();
    }
    else {
        require_once VIEW . 'admin/updateNova.php';
    }
}
