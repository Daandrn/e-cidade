<?php
include (__DIR__ . "/../jpgraph.php");
include (__DIR__ . "/../jpgraph_line.php");
include (__DIR__ . "/../jpgraph_error.php");
include (__DIR__ . "/../jpgraph_bar.php");

$l1datay = array(11,9,2,4,3,13,17);
$l2datay = array(23,12,5,19,17,10,15);
$datax=array("Jan","Feb","Mar","Apr","May");

// Create the graph. 
$graph = new Graph(400,200,"auto");	
$graph->SetScale("textlin");

$graph->img->SetMargin(40,130,20,40);
$graph->SetShadow();

// Create the linear error plot
$l1plot=new LinePlot($l1datay);
$l1plot->SetColor("red");
$l1plot->SetWeight(2);
$l1plot->SetLegend("Prediction");

// Create the bar plot
$l2plot = new LinePlot($l2datay);
$l2plot->SetFillColor("orange");
$l2plot->SetLegend("Result");

// Add the plots to the graph
$graph->Add($l2plot);
$graph->Add($l1plot);

$graph->title->Set("Mixing line and filled line");
$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Y-title");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

//$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetTextTickInterval(2);

// Display the graph
$graph->Stroke();
?>
