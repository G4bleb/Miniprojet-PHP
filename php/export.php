<?php
require 'dbconnect.php';

if (isset($this->id)) {
  $id=$this->id;
}
if (isset($_GET['id'])) {
  $id=$_GET['id'];
}
try {



$detailsList = $dbCnx->prepare("SELECT * FROM cambrure WHERE id_param=:id");
$detailsList->execute(array(':id'=>$id));
$detailsListArray = $detailsList->fetchAll(PDO::FETCH_CLASS, 'Cambrure');

$fileName = $id.'.csv';
$filePath = "../exports/".$fileName;

$fp = fopen($filePath, "w");
foreach ($detailsListArray as $fields) {
  if (is_object($fields)) {
    $fields = (array) $fields;
    array_shift($fields); //Retire l'id des lignes du tableau
  }
  fputcsv($fp, $fields);
}
fclose($fp);


} catch (Exception $e) {
  error_log("erreur dans export.php : id est il bien passé ? Exception : ".$e->getMessage());
}
 ?>