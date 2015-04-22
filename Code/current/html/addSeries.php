<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');

$seriesdb= new DbSeries();

$projectID = $_POST['pID'];
$isOriginal = $_POST['isOriginal'];

$concTemp = $_POST['concrete'];
$windSpeed = $_POST['wind'];

if ($windSpeed == 'Outside')
{
    $windSpeed = 0;
}
else
{
    $windSpeed = 1;
}

if ($concTemp == 'null')
{
    $concTemp = '';
}

$seriesdb->addSeries($projectID, $isOriginal, $concTemp, $windSpeed);
$seriesdb->get
//return seriesID
echo 'success';
?>