<?php
require_once 'dbconnect.php';
?>
<style>body {font-family: Sans-Serif;}</style>
<h2>Ajout d'un paramètre</h2>
<a href="../">Retour à l'accueil</a>

<!--
********************************************************************************
AJOUT PAR FORMULAIRE D'UNE NOUVELLE CAMBRURE
********************************************************************************
-->

<form action="newparameter.php" method="GET">
  Nom ? <input type="text" name="libelle" value=""><br>
  Taille de la corde en mm ?  <input type="text" name="corde" value=""><br>
  Nombre de points ? <input type="text" name="nb_points" value=""><br>
  Epaisseur max en % ?  <input type="text" name="tmax_prc" value=""><br>
  Cambrure max en % ? <input type="text" name="fmax_prc" value=""><br>
  <input type="submit" value=Ajouter>
</form>

<?php

//Vérifie que toutes les données ont bien été fournies
if (isset($_GET["libelle"]) && isset($_GET["corde"]) && isset($_GET["nb_points"]) && isset($_GET["tmax_prc"]) && isset($_GET["fmax_prc"])) {

  //Nouvel objet Parametre
  $newParameter = new Parametre();

  //On lui associe les valeurs entrées
  $newParameter->setLibelle($_GET["libelle"]);
  $newParameter->setCorde($_GET["corde"]);
  $newParameter->createDate();
  $newParameter->setNb_points($_GET["nb_points"]);
  $newParameter->setTmax_prc($_GET["tmax_prc"]);
  $newParameter->setFmax_prc($_GET["fmax_prc"]);
  $newParameter->tmax_prc_to_mm();
  $newParameter->fmax_prc_to_mm();

  //On l'insere dans la BDD
  $addParameterQuery = $dbCnx->prepare("INSERT INTO parametre (libelle, corde, tmax_prc, tmax_mm, fmax_prc, fmax_mm, nb_points, date, fic_img, fic_csv) VALUES (:libelle, :corde, :tmax_prc, :tmax_mm, :fmax_prc, :fmax_mm, :nb_points, :date, NULL, NULL)");
  $addParameterQuery->execute(array(':libelle'=>$_GET["libelle"],':corde'=>$_GET["corde"],':tmax_prc'=>$_GET["tmax_prc"],':tmax_mm'=>$newParameter->getTmax_mm(),':fmax_prc'=>$_GET["fmax_prc"],':fmax_mm'=>$newParameter->getFmax_mm(),':nb_points'=>$_GET["nb_points"],':date'=>$newParameter->getDate()));

  //Récupération des données sur le Paramètre entré et passage en objet Parametre
  $statement=$dbCnx->query("SELECT * FROM parametre WHERE libelle='".$_GET['libelle']."'");
  $parameters=$statement->fetchAll(PDO::FETCH_CLASS,'Parametre');
  $newParameter = $parameters[0];

  //Génération des cambrures associées
  $tabCambrures=$newParameter->generateCambrures();

  //Insertion des cambrures associées
  $addCambrureQuery = $dbCnx->prepare("INSERT INTO cambrure (x, t, f, yintra, yextra, id_param, igx) VALUES (:x, :t, :f, :yintra, :yextra, :id_param, :igx)");
  foreach ($tabCambrures as $key => $value) {
    $addCambrureQuery->execute(array(':x'=>$value->getX(),':t'=>$value->getTX(),':f'=>$value->getFX(),':yintra'=>$value->getYintra(),':yextra'=>$value->getYextra(),':id_param'=>$newParameter->getId(),':igx'=>$value->getIgx()));
  }

  //Génération des fichiers image et csv
  $newParameter->generateFiles($tabCambrures);
  $fileNamesQuery = $dbCnx->prepare("UPDATE parametre SET fic_img=:fic_img, fic_csv=:fic_csv WHERE id=:id");
  $fileNamesQuery->execute(array(':fic_img'=>$newParameter->id().'.png', ':fic_csv'=>$newParameter->id().'.csv', ':id'=>$newParameter->id()));

}
?>
