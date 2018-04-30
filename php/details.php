<?php
require_once 'dbconnect.php';
require_once 'graph.php';

$selectedParam = $dbCnx->prepare("SELECT * FROM parametre WHERE id=:id");
$selectedParam->execute(array(':id'=>$_GET['id']));
$selectedParamArray = $selectedParam->fetchAll(PDO::FETCH_CLASS, 'Parametre');
echo "<h2>Parametre \"".$selectedParamArray[0]->getLibelle()."\" :</h2>";
?>
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
    <tr>
      <?php
      echo "<td>".$selectedParamArray[0]->getCorde()."</td>
      <td>".$selectedParamArray[0]->getTmax_prc()."</td>
      <td>".$selectedParamArray[0]->getTmax_mm()."</td>
      <td>".$selectedParamArray[0]->getFmax_prc()."</td>
      <td>".$selectedParamArray[0]->getFmax_mm()."</td>
      <td>".$selectedParamArray[0]->getNb_points()."</td>
      <td>".$selectedParamArray[0]->getDate()."</td>
      </tr>
      </table>";

      $detailsList = $dbCnx->prepare("SELECT * FROM cambrure WHERE id_param=:id");
      $detailsList->execute(array(':id'=>$_GET['id']));
      $detailsListArray = $detailsList->fetchAll(PDO::FETCH_CLASS, 'Cambrure');

      ?>

      <?php
      echo "<img src='graph.php?id_param=".$_GET['id']."' />";

      echo "<form action='export.php' method='get'>
      <input type='hidden' name='id' value='".$_GET['id']."'>
      <input type='submit' value='Exporter en CSV' target='_blank'>
      </form>";

      ?>

      <h2>Détails :</h2>

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
