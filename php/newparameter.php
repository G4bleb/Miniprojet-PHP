<?php
require 'dbconnect.php';
?>
<!--
********************************************************************************
AJOUT PAR FORMULAIRE D'UNE NOUVELLE CAMBRURE
********************************************************************************
-->

<form action="newparameter.php" method="GET">
  Taille de la corde ?  <input type="text" name="corde" value=""><br>
  Nombre de points ? <input type="text" name="nb_points" value=""><br>
  Epaisseur max en % ?  <input type="text" name="tmax_prc" value=""><br>
  Cambrure max en % ? <input type="text" name="fmax_prc" value=""><br>
  <input type="submit">
</form>

<?php
if (isset($_GET["corde"]) && isset($_GET["nb_points"])) {
  $newParameter = new Parametre();
  $newParameter->setCorde($_GET["corde"]);
  $newParameter->setNb_points($_GET["nb_points"]);
  $newParameter->setTmax_prc($_GET["tmax_prc"]);
  $newParameter->setFmax_prc($_GET["fmax_prc"]);


  $testId = 1;
  // $statement=$dbCnx->query("SELECT * FROM parametre WHERE id=$testId");
  // $parameters=$statement->fetchAll(PDO::FETCH_CLASS,'Parametre');
  // $param1 = $parameters[0];
  $tabCambrures1=$newParameter->generateCambrures();
  foreach ($tabCambrures1 as $key => $value) {
    $addQuery = $dbCnx->prepare("INSERT INTO cambrure (x, t, f, yintra, yextra, id_param, igx) VALUES (:x, :t, :f, :yintra, :yextra, :id_param, :igx)");
    $addQuery->execute(array('x'=>$value->getX(), 't'=>$value->getTX(), 'f'=>$value->getFX(), 'yintra'=>$value->getYintra(), 'yextra'=>$value->getYextra(), 'id_param'=>$testId, 'igx'=>$value->getIgx()));
  }



}
?>
