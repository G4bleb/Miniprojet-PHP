<!DOCTYPE html>
<style>body {font-family: Sans-Serif;}</style>
<html>
<body>
  <h1>Mini-projet PHP CIR2</h1>

<form action="prj-cir2-1718-GL-HR.php" method="GET">

</form>
<?php

//TODO open database

$mysqlServerIp = "172.31.4.25";
$mysqlServerPort = "3306";
$mysqlDbName = "user6";
$mysqlDbCharset = "UTF8";
$mysqlDsn = "mysql:host=".$mysqlServerIp.";port=".$mysqlServerPort.";dbname=".$mysqlDbName.";charset=".$mysqlDbCharset.";";
$myUserDb = 'user6' ;
$myPwdDb = 'user6' ;
$dbCnx = new PDO($mysqlDsn, $myUserDb, $myPwdDb);


class parametre{
  private id;
  private libelle; //Form
  private corde; //Form
  private tmax_prc;
  private tmax_mm;
  private fmax_prc;
  private fmax_mm;
  private nb_points; //Form
  private date; //Form
  private fic_img;
  private fic_csv;

  public getCorde(){
    return $this->corde;
  }

  public getNb_points(){
    return $this->nb_points;
  }
}

class cambrure{
  private id;
  private x;
  private t;
  private f;
  private yintra;
  private yextra;
  private id_param;
  private lgx;

  public setterX($valueX){
    $this->x=$valueX;
  }
}
$statement=$dbCnx->query("SELECT * FROM parametre");
$parameters=$statement->fetchAll(PDO::FETCH_CLASS,'parametre');
$statement=$dbCnx->query("SELECT * FROM cambrure");
$parameters=$statement->fetchAll(PDO::FETCH_CLASS,'cambrure');
//Positionement de chaque Point x
$ecart=$parameters->getCorde()/$parameters->getNb_points();
while ($i=0,$length=0;$i<$parameters->getNb_points();$i++) {
  $tabcambrure[$i]->setterX($length);
  $length=$length+$ecart;
}





?>

</body>
</html>
