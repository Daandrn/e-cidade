<?php
include (__DIR__ . "/../jpgraph.php");
include (__DIR__ . "/../jpgraph_line.php");

$ydata = array(11,3,8,12,5,1,9,15,5,7);

// Create the graph. These two calls are always required
$graph = new Graph(300,200,"auto");	
$graph->SetScale("textlin");
$graph->yaxis->scale->SetGrace(10,10);

// Create the linear plot
$lineplot=new LinePlot($ydata);
$lineplot->mark->SetType(MARK_CIRCLE);

// Add the plot to the graph
$graph->Add($lineplot);

$graph->img->SetMargin(40,20,20,40);
$graph->title->Set("Grace value version 2");
$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Y-title");

$graph->xaxis->SetPos('min');

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$lineplot->SetColor("blue");
$lineplot->SetWeight(2);
$graph->yaxis->SetWeight(2);
$graph->SetShadow();

// Display the graph
$graph->Stroke();
?>
