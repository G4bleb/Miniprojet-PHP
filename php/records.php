<!-- Ce code permet d'affichier la liste des Parametres -->

<script>
     function confirmer() {
          return confirm("Confirmer la suppression ?");
     }
</script>

<style>
     body {font-family: Sans-Serif;}
</style>


<?php
require_once 'dbconnect.php';
?>


<h2>Liste des enregistrements :</h2>
<a href="../">Retour à l'accueil</a>


<?php

//Récupération des données sur tous les parametres et passage en objet Parametre
$records = $dbCnx->prepare("SELECT * FROM parametre");
$records->execute();
$recordsList = $records->fetchAll(PDO::FETCH_CLASS, 'Parametre');

?>

<!-- Tableau affichant tous les parametres ainsi que les différentes actions possibles (Edition, Affichage des détails, Suppression) -->
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
          echo "<tr>
                    <td>".$value->getLibelle()."</td>
                    <td>".$value->getCorde()."</td>
                    <td>".$value->getTmax_prc()."</td>
                    <td>".$value->getTmax_mm()."</td>
                    <td>".$value->getFmax_prc()."</td>
                    <td>".$value->getFmax_mm()."</td>
                    <td>".$value->getNb_points()."</td>
                    <td>".$value->getDate()."</td>";

                    //Bouton Details qui appelle le fichier details.php
                    echo "<td><form action='details.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' value='Details' formtarget='_blank'></form>";

                    //Bouton Edition (effets plus bas)
                    echo "<td><form action='records.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' name='mode' value='Editer'></form>";

                    //Bouton Supprimer (effets plus bas)
                    echo "<td><form onsubmit='return confirmer()' action='records.php' method='get'><input type='hidden' name='id' value='".$value->getId()."'><input type='submit' name='mode' value='Supprimer'></form>
               </tr>";
     }

     echo "</table>";

     if (isset($_GET['mode'])) {

          /* EDITION */

          if($_GET['mode']=='Editer'){
               //Récupération des données sur le parametre selectionné et passage en objet Parametre
               $selectedRecord = $dbCnx->prepare("SELECT * FROM parametre WHERE id=".$_GET['id']."");
               $selectedRecord->execute();
               $tabselectedRecord = $selectedRecord->fetchAll(PDO::FETCH_CLASS, 'Parametre');
               $selectedRecord = $tabselectedRecord[0];

               //Affichage des champs permettants d'entrer les données
               echo "<form action='records.php' method='GET'>
                         <input type='hidden' name='id' value='".$_GET['id']."'>
                              Nom ? <input type='text' name='libelle' value='".$selectedRecord->getLibelle()."'><br>
                              Taille de la corde ?  <input type='text' name='corde' value='".$selectedRecord->getCorde()."'><br>
                              Nombre de points ? <input type='text' name='nb_points' value='".$selectedRecord->getNb_Points()."'><br>
                              Epaisseur max en % ?  <input type='text' name='tmax_prc' value='".$selectedRecord->getTmax_prc()."'><br>
                              Cambrure max en % ? <input type='text' name='fmax_prc' value='".$selectedRecord->getFmax_prc()."'><br>
                         <input type='submit' name='mode' value='Editer'>
                    </form>";

               //Si tout est bien entré :
               if(isset($_GET["libelle"]) && isset($_GET["corde"]) && isset($_GET["nb_points"]) && isset($_GET["tmax_prc"]) && isset($_GET["fmax_prc"])){

                    //On les associe au Parametre selectionné
                    $selectedRecord->setCorde($_GET["corde"]);
                    $selectedRecord->createDate();
                    $selectedRecord->setTmax_prc($_GET["tmax_prc"]);
                    $selectedRecord->setFmax_prc($_GET["fmax_prc"]);
                    $selectedRecord->tmax_prc_to_mm();
                    $selectedRecord->fmax_prc_to_mm();

                    //On actualise le parametre
                    $editRecord = $dbCnx->prepare("UPDATE parametre SET libelle=:libelle, corde=:corde, tmax_prc=:tmax_prc, tmax_mm=:tmax_mm, fmax_prc=:fmax_prc, fmax_mm=:fmax_mm, nb_points=:nb_points, date=:stringDate WHERE id=:id");

                    $editRecord->execute(array(
                         ':libelle'=>$_GET["libelle"],
                         ':corde'=>$_GET["corde"],
                         ':tmax_prc'=>$_GET["tmax_prc"],
                         ':tmax_mm'=>$selectedRecord->getTmax_mm(),
                         ':fmax_prc'=>$_GET["fmax_prc"],
                         ':fmax_mm'=>$selectedRecord->getFmax_mm(),
                         ':nb_points'=>$_GET["nb_points"],
                         ':stringDate'=>$selectedRecord->getDate(),
                         ':id'=>$selectedRecord->getId()));

                         //On supprime la cambrure associée
                         $deleteCambrure = $dbCnx->prepare("DELETE FROM cambrure WHERE id_param=".$_GET['id']."");
                         $deleteCambrure->execute();

                         //Récupération des données modifiées sur le parametre et passage en objet Parametre
                         $statement=$dbCnx->query("SELECT * FROM parametre WHERE id='".$_GET['id']."'");
                         $parameters=$statement->fetchAll(PDO::FETCH_CLASS,'Parametre');
                         $newParameter = $parameters[0];

                         //Et on la regénere pour qu'elle soit adaptée aux nouvelles données
                         $tabCambrures=$newParameter->generateCambrures();

                         //Insertion de la cambrure en BDD
                         $addCambrureQuery = $dbCnx->prepare("INSERT INTO cambrure (x, t, f, yintra, yextra, id_param, igx) VALUES (:x, :t, :f, :yintra, :yextra, :id_param, :igx)");
                         foreach ($tabCambrures as $key => $value) {
                              $addCambrureQuery->execute(array(':x'=>$value->getX(),':t'=>$value->getTX(),':f'=>$value->getFX(),':yintra'=>$value->getYintra(),':yextra'=>$value->getYextra(),':id_param'=>$newParameter->getId(),':igx'=>$value->getIgx()));
                         }

                         //Génération de nouveaux fichiers
                         $newParameter->generateFiles($tabCambrures);

                         echo "<script>window.location = window.location.pathname;</script>";//refreshes the page
                    }


                    /* SUPPRESSION */

               } else if($_GET['mode']=='Supprimer'){
                    //Suppression de la cambrure
                    $deleteCambrure = $dbCnx->prepare("DELETE FROM cambrure WHERE id_param=".$_GET['id']."");
                    $deleteCambrure->execute();

                    //Suppression du parametre
                    $deleteParam = $dbCnx->prepare("DELETE FROM parametre WHERE id=".$_GET['id']."");
                    $deleteParam->execute();

                    unlink("../graphs/".$_GET['id'].".png");
                    unlink("../exports/".$_GET['id'].".csv");
                    echo "<script>window.location = window.location.pathname;</script>";//refreshes the page
               }
          }
?>
