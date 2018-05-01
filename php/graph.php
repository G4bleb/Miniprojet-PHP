<?php

/* Ce code permet de créer un graphique */

require_once 'dbconnect.php';
require_once '../jpgraph/jpgraph.php';
require_once '../jpgraph/jpgraph_line.php';


//Vérifications sur les identifiants
if (isset($this->id)) {
     $id_param=$this->id;
}
if (isset($_GET['id_param'])) {
     $id_param=$_GET['id_param'];
}


try {

     //Récupération des données sur la cambrure selectionnée et passage en objet Cambrure
     $statement=$dbCnx->query("SELECT * FROM cambrure WHERE id_param=".$id_param."");
     $tabCambrures=$statement->fetchAll(PDO::FETCH_CLASS,'Cambrure');
     $i=0;

     //Création de 3 tableaux qui serviront de données pour le graph
     foreach ($tabCambrures as $key => $value) {
          //Tableau axe abscisses
          $tabX[$i]=$value->getX();

          //Tableaux des points des courbes exterieures de la cambrure
          $tabYintra[$i]=$value->getYintra();
          $tabYextra[$i]=$value->getYextra();

          //Tableau des points de la courbe F(X) qui donne la 'forme' de la Cambrure
          $tabFX[$i]=$value->getFX();
          $i++;
     }
     $nb_label=$i;
     $i=0;

     //Setup the graph
     $graph = new Graph(600,250);
     $graph->SetScale("textlin");

     $theme_class=new UniversalTheme;

     $graph->SetTheme($theme_class);
     $graph->img->SetAntiAliasing(true);
     $graph->title->Set('Profil NACA');
     $graph->SetBox(false);

     $graph->img->SetAntiAliasing();

     $graph->yaxis->HideZeroLabel();
     $graph->yaxis->HideLine(false);
     $graph->yaxis->HideTicks(false,false);


     //Creation de l'axe des abscisses avec un interval en chaques points de $nb_label/20
     $graph->xgrid->Show();
     $graph->xgrid->SetLineStyle("solid");
     $graph->xaxis->SetTickLabels($tabX);
     $graph->xaxis->SetTextLabelInterval($nb_label/20);
     $graph->xgrid->SetColor('#E3E3E3');


     /*Creation des 3 courbes*/

     $p1 = new LinePlot($tabYintra);
     $graph->Add($p1);
     $p1->SetColor("#6495ED");
     $p1->SetLegend('Extrados');

     $p2 = new LinePlot($tabYextra);
     $graph->Add($p2);
     $p2->SetColor("#B22222");
     $p2->SetLegend('Intrados');

     $p3 = new LinePlot($tabFX);
     $graph->Add($p3);
     $p3->SetColor("#F7F313");
     $p3->SetLegend('Cambrure');

     $graph->xaxis->scale->SetGrace(10);

     $graph->legend->SetFrameWeight(1);

     // Output line
     $graph->Stroke("../graphs/".$id_param.".png");
} catch (Exception $e) {
     error_log("erreur dans graph.php : id_param est il bien passé ? Exception : ".$e->getMessage());
}
?>
