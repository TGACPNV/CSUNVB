<?php
ob_start();
$title = "CSU-NVB - Tâches hebdomadaires";
?>
<div>
    <h1>Tâches hebdomadaires</h1>
    <h2>Semaine <?= $weekNbr?></h2>
    <div>
        <!-- A implémenter -->
        <a href='index.php?action=openWeek&weekID=<?=$weekID?>' class="btn btn-primary">Ouvrir</a>
        <a href='index.php?action=closeWeek&weekID=<?=$weekID?>' class="btn btn-primary">Cloturer</a>
    </div>
</div>
<div>
    <table class="table">
        <thead class="thead-dark">
        <?php foreach($dates as $date){
            echo "<th>".$date."</th>";
        } ?>
        </thead>
        <tbody>
            <tr>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require GABARIT;
?>

