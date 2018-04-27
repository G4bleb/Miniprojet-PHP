<script>
function confirmer() {
  return confirm("Confirmer la suppression ?");
}
</script>

<?php
require_once 'dbconnect.php';
?>
<h2>Liste des enregistrements :</h2>
<?php
$records = $dbCnx->prepare("SELECT * FROM parametre");
$records->execute();
$recordsList = $records->fetchAll(PDO::FETCH_CLASS, 'Parametre');
?>
<table border=1 style = 'border-collapse: collapse'>
  <tr>
    <th>Nom</th>
    <th>Corde</th>
    <th>Tmax(%)</th>
    <th>Tmax(mm)</th>
    <th>Fmax(%)</th>
    <th>Fmax(mm)</th>
    <th>Nb Points</th>
    <th>Date</th>
    <tr/>
    <?php
    foreach ($recordsList as $value) {
      echo "<tr>";
      echo "<td>".$value->getLibelle()."</td>";
      echo "<td>".$value->getCorde()."</td>";
      echo "<td>".$value->getTmax_prc()."</td>";
      echo "<td>".$value->getTmax_mm()."</td>";
      echo "<td>".$value->getFmax_prc()."</td>";
      echo "<td>".$value->getFmax_mm()."</td>";
      echo "<td>".$value->getNb_points()."</td>";
      echo "<td>".$value->getDate()."</td>";
      echo "<td><form action='details.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' name='mode' value='Details' formtarget='_blank'></form>";
      echo "<td><form action='records.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' name='mode' value='Editer'></form>";
      echo "<td><form onsubmit='return confirmer()' action='records.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' name='mode' value='Supprimer'></form>";
    }
    echo "</table>";

    if (isset($_GET['mode'])) {

      /* EDITION */
      if($_GET['mode']=='Editer'){
        $selectedRecord = $dbCnx->prepare("SELECT * FROM parametre WHERE id=".$_GET['id']."");
        $selectedRecord->execute();
        $tabselectedRecord = $selectedRecord->fetchAll(PDO::FETCH_CLASS, 'Parametre');
        $selectedRecord = $tabselectedRecord[0];

        echo "<form action='records.php' method='GET'>
        <input type='hidden' name='id' value='".$_GET['id']."'>
        Nom ? <input type='text' name='libelle' value='".$selectedRecord->getLibelle()."'><br>
        Taille de la corde ?  <input type='text' name='corde' value='".$selectedRecord->getCorde()."'><br>
        Nombre de points ? <input type='text' name='nb_points' value='".$selectedRecord->getNb_Points()."'><br>
        Epaisseur max en % ?  <input type='text' name='tmax_prc' value='".$selectedRecord->getTmax_prc()."'><br>
        Cambrure max en % ? <input type='text' name='fmax_prc' value='".$selectedRecord->getFmax_prc()."'><br>
        <input type='submit' name='mode' value='Editer'>
        </form>";

        if(isset($_GET["libelle"]) && isset($_GET["corde"]) && isset($_GET["nb_points"]) && isset($_GET["tmax_prc"]) && isset($_GET["fmax_prc"])){

          $selectedRecord->setCorde($_GET["corde"]);
          $selectedRecord->createDate();
          $selectedRecord->setTmax_prc($_GET["tmax_prc"]);
          $selectedRecord->setFmax_prc($_GET["fmax_prc"]);
          $selectedRecord->tmax_prc_to_mm();
          $selectedRecord->fmax_prc_to_mm();

          $editRecord = $dbCnx->prepare("UPDATE parametre SET libelle=:libelle, corde=:corde, tmax_prc=:tmax_prc, tmax_mm=:tmax_mm, fmax_prc=:fmax_prc, fmax_mm=:fmax_mm, nb_points=:nb_points, date=:stringDate WHERE id=:id");

          $editRecord->execute(array(
            ':libelle'=>$_GET["libelle"],
            ':corde'=>$_GET["corde"],
            ':tmax_prc'=>$_GET["tmax_prc"],
            ':tmax_mm'=>$selectedRecord->getTmax_mm(),
            ':fmax_prc'=>$_GET["tmax_prc"],
            ':fmax_mm'=>$selectedRecord->getFmax_mm(),
            ':nb_points'=>$_GET["nb_points"],
            ':stringDate'=>$selectedRecord->getDate(),
            ':id'=>$selectedRecord->getId()));


            echo "<script>window.location = window.location.pathname;</script>";//refreshes the page
          }

          /* SUPPRESSION */
        } else if($_GET['mode']=='Supprimer'){
          $deleteCambrure = $dbCnx->prepare("DELETE FROM cambrure WHERE id_param=".$_GET['id']."");
          $deleteCambrure->execute();
          $deleteParam = $dbCnx->prepare("DELETE FROM parametre WHERE id=".$_GET['id']."");
          $deleteParam->execute();
          echo "<script>window.location = window.location.pathname;</script>";//refreshes the page
        }
      }
      ?>
