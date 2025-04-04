<?php
include (__DIR__ . "/../jpgraph.php");
include (__DIR__ . "/../jpgraph_log.php");
include (__DIR__ . "/../jpgraph_line.php");

$ydata = array(11,3,8,12,5,1,9,13,5,7);
$y2data = array(354,200,265,99,111,91,198,225,293,251);

// Create the graph. These two calls are always required
$graph = new Graph(350,200,"auto");	
$graph->SetScale("textlog");
$graph->SetY2Scale("log");

$graph->SetShadow();
$graph->SetMargin(40,110,20,40);

$graph->ygrid->Show(true,true);
$graph->xgrid->Show(true,false);

// Create the linear plot
$lineplot=new LinePlot($ydata);

$lineplot2=new LinePlot($y2data);
$lineplot2->SetColor("orange");
$lineplot2->SetWeight(2);

$graph->yaxis->scale->ticks->SupressFirst();

// Add the plot to the graph
$graph->Add($lineplot);
$graph->AddY2($lineplot2);

$graph->title->Set("Example 8");
$graph->xaxis->title->Set("X-title");
$graph->yaxis->title->Set("Y-title");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

$lineplot->SetColor("blue");
$lineplot->SetWeight(2);

$lineplot2->SetColor("orange");
$lineplot2->SetWeight(2);

$graph->yaxis->SetColor("blue");
$graph->y2axis->SetColor("orange");

$lineplot->SetLegend("Plot 1");
$lineplot2->SetLegend("Plot 2");

$graph->legend->Pos(0.05,0.5,"right","center");

// Display the graph
$graph->Stroke();
?>
