<?php
// Gantt example
include (__DIR__ . "/../jpgraph.php");
include (__DIR__ . "/../jpgraph_gantt.php");

// 
// The data for the graphs
//
$data = array(
  array(0,ACTYPE_GROUP,    "Phase 1",        "2001-10-26","2001-11-23",''),
  array(1,ACTYPE_NORMAL,   "  Label 2",      "2001-10-26","2001-11-16",''),
  array(2,ACTYPE_NORMAL,   "  Label 3",      "2001-11-20","2001-11-22",''),
  array(3,ACTYPE_MILESTONE,"  Phase 1 Done", "2001-11-23",'M2') );

// The constrains between the activities
//$constrains = array(array(1,2,CONSTRAIN_ENDSTART),
//		    array(2,3,CONSTRAIN_STARTSTART));
$constrains = array();

$progress = array(array(1,0.4));

// Create the basic graph
$graph = new GanttGraph();
$graph->title->Set("Example with grouping and constrains");

// Setup scale
$graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAYWNBR);

// Add the specified activities
$graph->CreateSimple($data,$constrains,$progress);

// .. and stroke the graph
$graph->Stroke();

?>


