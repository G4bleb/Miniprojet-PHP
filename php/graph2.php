<?php
  require_once 'dbconnect.php';
  require_once '../jpgraph/jpgraph.php';
  require_once '../jpgraph/jpgraph_line.php';
    $i=1;
    $reponse=$dbCnx->query("SELECT * FROM cambrure");
    //Les requêtes ne fonctionnent pas
    //On souhaite créer l'équivalent d'un foreach(cambrure) qui irait récupérer le IGX ainsi que Tmax et fmax_mm
    //L'objectif étant de créer un Graph dynamique où Igx en fonction de Fmax Représente la rigidité et Igx/tmax_mm la solidité
    //Seul le while change comparé à graph.php. L'ajout des tableaux en jpgraph reste le même
    while($donnees=$reponse->fetch()){
      $statement=$dbCnx->query("SELECT * FROM parametre WHERE id=".$i);
      $tabParametres=$statement->fetchAll(PDO::FETCH_CLASS,'Parametre');
      $tabFmax[$i]=$tabParametres->getFmax_prc();
      $statement=$dbCnx->query("SELECT * FROM cambrure WHERE id_param=".$i);
      $tabCambrures=$statement->fetchAll(PDO::FETCH_CLASS,'Cambrure');
      $tabIgx[$i]=$tabCambrures->getIgx();
      $tabIgxVmax[$i]=$tabIgx[$i]/($tabParametres->getTmax_mm()/2);
      $i++;
    }
    $nb_label=$i;
    $i=0;

    // Setup the graph
    $graph = new Graph(1200,600);
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

    $graph->xgrid->Show();
    $graph->xgrid->SetLineStyle("solid");
    $graph->xaxis->SetTickLabels($tabFmax);
    $graph->xgrid->SetColor('#E3E3E3');

    // Create the first line
    $p1 = new LinePlot($tabIgx);
    $graph->Add($p1);
    $p1->SetColor("#6495ED");
    $p1->SetLegend('Augmentation de la rigidité');

    // Create the second line
    $p2 = new LinePlot($tabIgxVmax);
    $graph->Add($p2);
    $p2->SetColor("#B22222");
    $p2->SetLegend('Augmentation de la solidité');

    $graph->legend->SetFrameWeight(1);

    // Output line
    $graph->Stroke();

?>
