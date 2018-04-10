<!DOCTYPE html>
<style>body {font-family: Sans-Serif;}</style>
<html>
<body>
  <h1>Mini-projet PHP CIR2</h1>


<?php
require 'dbconnect.php';
require '../jpgraph/jpgraph.php'
require '../jpgraph/config.inc.php'
$testId = 1;
$statement=$dbCnx->query("SELECT * FROM parametre WHERE id=$testId");
$parameters=$statement->fetchAll(PDO::FETCH_CLASS,'Parametre');
$param1 = $parameters[0];
$tabCambrures1=$param1->generateCambrures();
foreach ($tabCambrures1 as $key => $value) {
  $addQuery = $dbCnx->prepare("INSERT INTO cambrure (x, t, f, yintra, yextra, id_param, igx)
   VALUES ($value->getX(), $value->getTX(), $value->getFX(), $value->getYintra, $value->getYextra, $testId, $value->getIgx())");
  $addQuery->execute();
}
// $statement=$dbCnx->query("SELECT * FROM cambrure");
// $cambrures=$statement->fetchAll(PDO::FETCH_CLASS,'Cambrure');


//Calcul de T(X) : Formule T(X) = -[1.015(X/C)⁴-2.843(X/C)³+3.516(X/C)²+1.26(X/C)-2.269(X/C)-²]*Tmax



//C la longueur de la corde (donnée), Tmax la Cambrure max (donnée) en mm
//number pow ( number $base , number $exp )
//Retourne base élevé à la puissance exp.


?>


</body>
</html>
