<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
$projectID = $_POST['pID'];
$seriesdb= new DbSeries();
$numbOfSeries = $seriesdb->getNumbSeries($projectID);

if($numbOfSeries == 0)
{
    echo('None');
}

else
{
$seriesID = $seriesdb->getSeriesID($projectID);
$c = array();
$w = array();

for ($i =0; $i < $numbOfSeries; $i++)
{
    $c[$i] = ($seriesdb->getConcreteTemp($seriesID[$i]));
    $w[$i] = ($seriesdb->getWindSpeed($seriesID[$i]));
}

echo json_encode( array(
    'id' => $seriesID,
    'concrete' => $c,
    'wind' => $w
    ));            
}
?>