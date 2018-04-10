<?php
require 'dbconnect.php';
?>
<!--
********************************************************************************
AJOUT PAR FORMULAIRE D'UNE NOUVELLE CAMBRURE
********************************************************************************
-->

<form action="php/newparameter.php" method="GET">
  Taille de la corde ?  <input type="text" name="corde" value=""><br>
  Nombre de points ? <input type="text" name="nb_points" value=""><br>
  Epaisseur max en mm ?  <input type="text" name="tmax_mm" value=""><br>
  Cambrure max en mm ? <input type="text" name="fmax_mm" value=""><br>
  <input type="submit">
</form>

<?php
if (isset($_GET["corde"]) && isset($_GET["nb_points"])) {
  $newParameter = new Parametre();
  $newParameter->setCorde($_GET["corde"]);
  $newParameter->setNb_points($_GET["nb_points"]);
  $newParameter->setTmax_mm($_GET["tmax_mm"]);
  $newParameter->setFmax_mm($_GET["fmax_mm"]);
}
?>
