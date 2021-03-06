<?php
require_once 'dbconnect.php';

/* Ce code permet d'afficher les details d'un parametre */


//Récupération des données du paramètre sélectionné et passage en objet Parametre
$selectedParam = $dbCnx->prepare("SELECT * FROM parametre WHERE id=:id");
$selectedParam->execute(array(':id'=>$_GET['id']));
$selectedParamArray = $selectedParam->fetchAll(PDO::FETCH_CLASS, 'Parametre');

echo "<h2>Parametre \"".$selectedParamArray[0]->getLibelle()."\" :</h2>";

?>

<!-- Affichage de ce paramètre -->
<style>body {font-family: Sans-Serif;}</style>
<table border=1 style = 'border-collapse: collapse'>
  <tr>
    <th>Corde</th>
    <th>Tmax(%)</th>
    <th>Tmax(mm)</th>
    <th>Fmax(%)</th>
    <th>Fmax(mm)</th>
    <th>Nb Points</th>
    <th>Date</th>
    <tr/>


    <?php

    echo "<tr>
    <td>".$selectedParamArray[0]->getCorde()."</td>
    <td>".$selectedParamArray[0]->getTmax_prc()."</td>
    <td>".$selectedParamArray[0]->getTmax_mm()."</td>
    <td>".$selectedParamArray[0]->getFmax_prc()."</td>
    <td>".$selectedParamArray[0]->getFmax_mm()."</td>
    <td>".$selectedParamArray[0]->getNb_points()."</td>
    <td>".$selectedParamArray[0]->getDate()."</td>
    </tr>
    </table>";


    //Récupération des données sur la cambrure correspondant au paramètre choisi et passage en objet Cambrure
    $detailsList = $dbCnx->prepare("SELECT * FROM cambrure WHERE id_param=:id");
    $detailsList->execute(array(':id'=>$_GET['id']));
    $detailsListArray = $detailsList->fetchAll(PDO::FETCH_CLASS, 'Cambrure');

    //Affichage du graphique
    echo "<img src='../graphs/".$_GET['id'].".png' />";

    //Exportation en csv (appelle le fichier sendexport.php)
    echo "<form action='sendexport.php' method='get'>
    <input type='hidden' name='id' value='".$_GET['id']."'>
    <input type='submit' value='Exporter en CSV' target='_blank'>
    </form>";

    ?>

    <h2>Détails :</h2>


    <!-- Tableau affichant toutes les données sur la cambrure du paramètre -->
    <table border=1 style = 'border-collapse: collapse'>
      <tr>
        <th>x</th>
        <th>t</th>
        <th>f</th>
        <th>yintrados</th>
        <th>yextrados</th>
        <th>igx</th>
      </tr>
      <tr>


        <?php

        foreach ($detailsListArray as $value) {
          echo "<td>".$value->getX()."</td>
          <td>".$value->getTX()."</td>
          <td>".$value->getFX()."</td>
          <td>".$value->getYintra()."</td>
          <td>".$value->getYextra()."</td>
          <td>".$value->getIgx()."</td>
          </tr>";
        }

        echo "</table>";

        ?>
