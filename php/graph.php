<?php // content="text/plain; charset=utf-8"
require_once '../jpgraph/jpgraph.php';
require_once '../jpgraph/jpgraph_line.php';
require 'dbconnect.php';

$statement=$dbCnx->query("SELECT * FROM cambrure WHERE id_param=1");
$tabCambrures=$statement->fetchAll(PDO::FETCH_CLASS,'Cambrure');
$i=0;

foreach ($tabCambrures as $key => $value) {
  $tabX[$i]=$value->getX();
  $tabYintra[$i]=$value->getYintra();
  $tabYextra[$i]=$value->getYextra();
  $i++;
}
$i=0;
// foreach ($tabCambrures as $key => $value) {
//   $tabSegments[$i]=array($tabX[$i], $tabY[$i], $tabX[$i+1], $tabY[$i+1]);
//   $i++;
// }
$datay1 = array(20,15,23,15);
$datay2 = array(12,9,42,8);
$datay3 = array(5,17,32,24);

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
$graph->xaxis->SetTickLabels($tabX);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($tabYintra);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Extrados');

// Create the second line
$p2 = new LinePlot($tabYextra);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Intrados');

// Create the third line
// $p3 = new LinePlot($datay3);
// $graph->Add($p3);
// $p3->SetColor("#FF1493");
// $p3->SetLegend('Line 3');

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();

?>
