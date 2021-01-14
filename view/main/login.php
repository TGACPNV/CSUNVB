<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

ob_start();
$title = "CSU-NVB - Login";
?>

<div class="row">
    <form action="?action=login" method="post" class="form form-group">
        <label for="initales">Initiales</label>
        <input id="initiales" type="text" class="form-group form-control" <?= (isset($_POST['username'])) ? "value ='".$_POST['username']."'" : "" ?> name="username" required>
        <label for="password">Mot de passe</label>
        <input id="password" type="password" class="form-group form-control" name="password" required>
        <label>Quel site ?</label><br>
        <div class="form-check-inline">
            <?php foreach ($bases as $base): ?>
                <div class="form-check">
                    <input type="radio" name="base" value="<?= $base['id'] ?>" required> <?= $base['name'] ?>
                </div>
            <?php endforeach; ?>
        </div><br><br>
        <button type="submit" id="btnLogin" class="btn btn-primary">Connecter</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require GABARIT;
?>
