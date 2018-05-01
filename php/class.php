<?php


     /* CLASSE CAMBRURE */

class Cambrure{
     private $id;
     private $x;
     private $t;
     private $f;
     private $yintra;
     private $yextra;
     private $id_param;
     private $igx;


     public function getX(){
          return $this->x;
     }
     public function setX($_X){
          $this->x=$_X;
     }


     public function getTX(){
          return $this->t;
     }
     public function setTX($_TX){
          $this->t=$_TX;
     }


     public function getFX(){
          return $this->f;
     }
     public function setFX($_FX){
          $this->f=$_FX;
     }


     public function getYintra(){
          return $this->yintra;
     }
     public function setYintra($_Yin){
          $this->yintra=$_Yin;
     }


     public function getYextra(){
          return $this->yextra;
     }
     public function setYextra($_Yex){
          $this->yextra=$_Yex;
     }


     public function getIgx(){
          return $this->igx;
     }
     public function setIgx($_igx){
          $this->igx=$_igx;
     }
}



     /* CLASSE PARAMETRE */

class Parametre{
     private $id;
     private $libelle;
     private $corde;
     private $tmax_prc; //Tmax en pourcents
     private $tmax_mm; //Tmax en mm
     private $fmax_prc; //Fmax en pourcents
     private $fmax_mm; //Fmax en mm
     private $nb_points;
     private $date;
     private $fic_img;
     private $fic_csv;


     public function getId(){
          return $this->id;
     }


     public function getLibelle(){
          return $this->libelle;
     }
     public function setLibelle($_libelle){
          $this->libelle = $_libelle;
     }


     public function getCorde(){
          return $this->corde;
     }
     public function setCorde($_corde){
          $this->corde = $_corde;
     }


     public function getTmax_prc(){
          return $this->tmax_prc;
     }
     public function setTmax_prc($_tmax_prc){
          $this->tmax_prc = $_tmax_prc;
     }


     public function getTmax_mm(){
          return $this->tmax_mm;
     }
     public function setTmax_mm($_tmax_mm){
          $this->tmax_mm = $_tmax_mm;
     }


     public function getFmax_prc(){
          return $this->fmax_prc;
     }
     public function setFmax_prc($_fmax_prc){
          $this->fmax_prc = $_fmax_prc;
     }


     public function getFmax_mm(){
          return $this->fmax_mm;
     }
     public function setFmax_mm($_fmax_mm){
          $this->fmax_mm = $_fmax_mm;
     }


     //Convertit Tmax en pourcents en mm
     public function tmax_prc_to_mm(){
          $this->tmax_mm = ($this->tmax_prc/100) * $this->corde;
     }

     //Convertit Fmax en pourcents en mm
     public function fmax_prc_to_mm(){
          $this->fmax_mm = ($this->fmax_prc/100) * $this->corde;
     }


     public function getNb_points(){
          return $this->nb_points;
     }
     public function setNb_points($_nb_points){
          $this->nb_points = $_nb_points;
     }


     public function getDate(){
          return $this->date;
     }

     //Crée un date au format Année-Mois-Jour
     public function createDate(){
          $paramDate = new DateTime();
          $this->date = $paramDate->format('Y-m-d');
     }


     public function generateCambrures(){

          //Vérifie si Tmax est bien en mm, sinon effectue la conversion
          if (!isset($this->tmax_mm)){
               $this->tmax_prc_to_mm();
          }

          //De meme pour Fmax
          if (!isset($this->fmax_mm)){
               $this->fmax_prc_to_mm();
          }

          for ($i=0 ; $i <= $this->nb_points ; $i++){
               $tabCambrures[$i] = new Cambrure();
          }


          //Positionnement de chaque Point x
          $ecart=$this->corde/$this->nb_points;
          for ($i=0, $length = 0 ; $i <= $this->nb_points ; $i++){
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


          //Calcul de Yextrados
          foreach ($tabCambrures as $key => $value) {
               $X=$value->getX();
               $T=$this->corde;
               $YEX=$value->getFX()+$value->getTX()/2;
               $value->setYextra($YEX);
          }


          //Calcul de Yintrados
          foreach ($tabCambrures as $key => $value) {
               $X=$value->getX();
               $T=$this->corde;
               $YIX=$value->getFX()-$value->getTX()/2;
               $value->setYintra($YIX);
          }

          //Calcul de IgX défini par la somme des section rectangulaire bh³/12 de largeur b='$ecart' et h=Tmoy Tmoy entre X et X+dX
          $base = $this->corde/$this->nb_points;
          $height = 0;
          $igx = 0;
          $lastkey = 0;
          $lastTx = 0;


          foreach ($tabCambrures as $key => $value) {
               $currentTx=$value->getTX();
               $height=($lastTx+$currentTx)/2;
               $section=$base*pow($height,3)/12;
               $igx+=$section;
               $lastkey=$key;
               $lastTx=$currentTx;
          }


          foreach ($tabCambrures as $key => $value) {
               $value->setIgx($igx);
          }


          return $tabCambrures;
     }

     //Génération du fichier d'export en csv
     public function generateFiles($tabCambrures)  {
          require 'dbconnect.php';
          include_once "graph.php";
          include_once "export.php";
          $this->fic_img=$this->id.".png";
          $this->fic_csv=$this->id.".csv";
     }
}

?>
