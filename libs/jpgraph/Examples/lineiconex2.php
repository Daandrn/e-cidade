<?php
include (__DIR__ . "/../jpgraph.php");
include (__DIR__ . "/../jpgraph_line.php");
include (__DIR__ . "/../jpgraph_flags.php");

$datay = array(30,25,33,25,27,45,32);

// Setup the graph
$graph = new Graph(400,250);
$graph->SetMargin(40,40,20,30);	
$graph->SetScale("textlin");

$graph->title->Set('Adding a country flag as a an icon');

$p1 = new LinePlot($datay);
$p1->SetColor("blue");
$p1->SetFillGradient('yellow@0.4','red@0.4');

$graph->Add($p1);

$icon = new IconPlot();
$icon->SetCountryFlag('iceland',50,30,1.5,40,3);
$icon->SetAnchor('left','top');
$graph->Add($icon);

// Output line
$graph->Stroke();

?>


