<?php

class Cambrure{
  private $id;
  private $x;
  private $t;
  private $f;
  private $yintra;
  private $yextra;
  private $id_param;
  private $igx;

  public function setX($valueX){
    $this->x=$valueX;
  }

  public function getX(){
    return $this->x;
  }

  public function setTX($valueTX){
    $this->t=$valueTX;
  }

  public function getTX(){
    return $this->t;
  }

  public function setFX($valueFX)
  {
    $this->f=$valueFX;
  }

  public function getFX(){
    return $this->f;
  }

  public function setYintra($valueYin){
    $this->yintra=$valueYin;
  }

  public function getYintra(){
    return $this->yintra;
  }

  public function setYextra($valueYex){
    $this->yextra=$valueYex;
  }

  public function getYextra(){
    return $this->yextra;
  }

  public function setIgx($valueIgx){
    $this->igx=$valueIgx;
  }

  public function getIgx(){
    return $this->igx;
  }
}

class Parametre{
  private $id;
  private $libelle; //Form
  private $corde; //Form
  private $tmax_prc;
  private $tmax_mm;
  private $fmax_prc;
  private $fmax_mm;
  private $nb_points; //Form
  private $date; //Form
  private $fic_img;
  private $fic_csv;

  public function getId(){
       return $this->id;
 }

 public function getLibelle(){
      return $this->libelle;
 }

  public function getCorde(){
    return $this->corde;
  }

  public function getTmax_prc(){
    return $this->tmax_prc;
  }

  public function getTmax_mm(){
    return $this->tmax_mm;
  }

  public function getFmax_prc(){
    return $this->fmax_prc;
  }

  public function getFmax_mm(){
    return $this->fmax_mm;
  }

  public function getNb_points(){
    return $this->nb_points;
  }

  public function getDate(){
      return $this->date;
  }

  public function tmax_prc_to_mm(){
    $this->tmax_mm = ($this->tmax_prc/100) * $this->corde;
  }

  public function fmax_prc_to_mm(){
    $this->fmax_mm = ($this->fmax_prc/100) * $this->corde;
  }

  public function setCorde($_corde){
    $this->corde = $_corde;
  }

  public function setNb_points($_nb_points){
    $this->nb_points = $_nb_points;
  }

  public function setTmax_mm($_tmax_mm){
    $this->tmax_mm = $_tmax_mm;
  }

  public function setFmax_mm($_fmax_mm){
    $this->fmax_mm = $_fmax_mm;
  }

  public function setTmax_prc($_tmax_prc){
    $this->tmax_prc = $_tmax_prc;
  }

  public function setFmax_prc($_fmax_prc){
    $this->fmax_prc = $_fmax_prc;
  }

  public function generateCambrures(){
    if (!isset($this->tmax_mm)) {
      $this->tmax_prc_to_mm();
    }
    if (!isset($this->fmax_mm)) {
      $this->fmax_prc_to_mm();
    }

    for ($i=0 ; $i <= $this->nb_points ; $i++) {
      $tabCambrures[$i] = new Cambrure();
    }
    //Positionnement de chaque Point x
    $ecart=$this->corde/$this->nb_points;
    for ($i=0, $length = 0 ; $i <= $this->nb_points ; $i++) {
      $tabCambrures[$i]->setX($length);
      $length=$length+$ecart;
    }

    $C=$this->corde;
    foreach ($tabCambrures as $key => $value) {
      $X=$value->getX();
      $TX= -1*(1.015*pow($X/$C,4)-2.843*pow($X/$C,3)+3.516*pow($X/$C,2)+1.26*($X/$C)-2.969*sqrt($X/$C))*$this->tmax_mm;
      $value->setTx($TX);
    }

    //Calcul de la cambrure F(X)

    foreach ($tabCambrures as $key => $value) {
      $X=$value->getX();
      $C=$this->corde;
      $FX=-4*(pow($X/$C,2)-($X/$C))*$this->fmax_mm;
      $value->setFX($FX);
    }
    //Calcul de Yextra
    foreach ($tabCambrures as $key => $value) {
      $X=$value->getX();
      $T=$this->corde;
      $YEX=$value->getFX()+$value->getTX()/2;
      $value->setYextra($YEX);
    }
    //Calcul de Yintra
    foreach ($tabCambrures as $key => $value) {
      $X=$value->getX();
      $T=$this->corde;
      $YIX=$value->getFX()-$value->getTX()/2;
      $value->setYintra($YIX);
    }

    //Calcul de IgX défini par la somme des section rectangulaire bh³/12 de largeur b='$ecart' et h=Tmoy Tmoy entre X et X+dX

    $TXP=0;
    foreach ($tabCambrures as $key => $value) {
      $IGX=($TXP+$value->getTX())/2;
      $TXP=$value->getTX();

    }
    //Igx est une valeur unique pour tout les points
    foreach ($tabCambrures as $key => $value) {
      $value->setIgx($IGX);
    }
    return $tabCambrures;
  }
}

?>
