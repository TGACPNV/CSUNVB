<?php
/**
 * Auteur: Gogniat Michael
 * Date: Février 2020
 **/

ob_start();
$title = "CSU-NVB - Mot de passe oublié";
?>
<h2>Réinitialiser votre mot de passe</h2>
<div class="text-black-50">Veuillez indiquer votre adresse mail afin de vous envoyer le lien</div><br>
<form method="post" action ="?action=resetPassMail">
    <input type="email" name="mail">
    <input type="submit" value="Envoyer">
</form>


<?php
$content = ob_get_clean();
require GABARIT;
?>
