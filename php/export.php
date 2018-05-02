<?php
require 'dbconnect.php';

//Vérifications des identifiants
if (isset($this->id)) {
  $id=$this->id;
}
if (isset($_GET['id'])) {
  $id=$_GET['id'];
}


try {
  //Récupération des données sur la cambrure et passage en objet Cambrure
  $detailsList = $dbCnx->prepare("SELECT * FROM cambrure WHERE id_param=:id");
  $detailsList->execute(array(':id'=>$id));
  $detailsListArray = $detailsList->fetchAll(PDO::FETCH_CLASS, 'Cambrure');

  //Nom et chemin du fichier
  $fileName = $id.'.csv';
  $filePath = "../exports/".$fileName;

  //Ecriture du fichier
  $fp = fopen($filePath, "w") or die();
  foreach ($detailsListArray as $fields) {
    if (is_object($fields)) {
      $fields = (array) $fields;
      array_shift($fields); //Retire l'id des lignes du tableau
    }
    fputcsv($fp, $fields);
  }
  fclose($fp);

  //Gestion des erreurs
} catch (Exception $e) {
  error_log("erreur dans export.php : Exception : ".$e->getMessage());
}

?>
