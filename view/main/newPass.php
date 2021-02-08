<?php
/**
 * Auteur: Gogniat Michael
 * Date: Février 2021
 **/
ob_start();
$title = "CSU-NVB - Nouveau mot de passe";
?>
<div class="row">
    <h3>Veuillez entrer un nouveau mot de passe</h3>
</div>
<div class="row">
    <form class="form form-group" action="?action=setNewPass&id=<?=$userID?>" method="post">
        <input type="hidden" name="token" value="<?=$token?>">
        <label>Mot de passe</label>
        <input type="password" placeholder="Entrez votre nouveau mot de passe" name="newPassword" required class="form-group form-control" size="35">
        <label>Confirmation</label>
        <input type="password" placeholder="Répetez votre nouveau mot de passe" name="confirmPassword" required class="form-group form-control" size="35">
        <input type="submit" class="btn btn-primary" value="Confirmer">
    </form>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>
