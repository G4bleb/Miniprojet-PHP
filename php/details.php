<?php
require_once 'dbconnect.php';
require_once 'graph.php';

function arrayToCsv(array &$array) {
  if (count($array) == 0) {
    return null;
  }
  ob_start();
  $df = fopen("php://output", 'w');
  fputcsv($df, array_keys(reset($array)));
  foreach ($array as $row) {
    fputcsv($df, $row);
  }
  fclose($df);
  return ob_get_clean();
}

function download_send_headers($filename) {
  // disable caching
  $now = gmdate("D, d M Y H:i:s");
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
  header("Last-Modified: {$now} GMT");

  // force download
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");

  // disposition / encoding on response body
  header("Content-Disposition: attachment;filename={$filename}");
  header("Content-Transfer-Encoding: binary");
}

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
       ?>

      <form method='get'><input type='submit' name='export' value='Exporter'></form>
      <h2>Détails :</h2>

      <table border=1 style = 'border-collapse: collapse'>
        <tr>
          <th>x</th>
          <th>t</th>
          <th>f</th>
          <th>yintrados</th>
          <th>yextrados</th>
          <th>igx</th>
          <tr/>
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

            if(isset($_GET['export'])){
              download_send_headers("data_export_" . date("Y-m-d") . ".csv");
              echo array2csv($detailsListArray);
              die();
            }

            ?>
