<?php
/**
 * Auteur: Thomas Grossmann / Mounir Fiaux
 * Date: Mars 2020
 **/

ob_start();
$title = "CSU-NVB - Administration - Secouristes";
?>
<a href="?action=newUser" class="btn btn-success">Créer un utilisateur</a>
<table class="table table-bordered" style="text-align: center">
    <thead>
    <th>Initiales</th>
    <th>Prénom</th>
    <th>Nom</th>
    <th>Mail</th>
    <th>Téléphone</th>
    <th>Admin</th>
    <th>Statut</th>
    </thead>
    <tbody>
    <?php foreach ($users as $user) { ?>
        <tr>
        <td><?= $user['initials'] ?></td>
        <td><?= $user['firstname'] ?></td>
        <td><?= $user['lastname'] ?></td>
        <td><?= $user['email'] ?></td>
        <td><?= $user['mobileNumber'] ?></td>
        <td><?php
            if ($user['id'] != $_SESSION['user']['id']) { if ($user['admin'] == 1) { ?>
        <a href="?action=changeUserAdmin&idUser=<?= $user['id'] ?>" class="btn btn-primary">Changer en utilisateur</a><?php } else { ?>
        <a href="?action=changeUserAdmin&idUser=<?= $user['id'] ?>" class="btn btn-primary">Changer en administrateur</a><?php } } else { ?>
        <p>Vous ne pouvez pas changer votre propre état</p><?php } ?>
        <td><?php if ($user['firstconnect'] == 1) { ?>Mot de passe expiré<br><?php } ?> <a href="?action=resetUserPassword&idUser=<?= $user['id'] ?>" class="btn btn-primary">Réinitialiser le mot de passe</a></td>
        </tr><?php } ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
require GABARIT;
?>
