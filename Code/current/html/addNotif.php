<?php 
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');
$seriesID = $_POST['sID'];
$pid = $_POST['pID'];
$time = $_POST['time'];

if ($seriesID == 'orginal')
{
    $seriesdb= new DbSeries();
    $seriesID = $seriesdb->getOriginalSeriesID($pid);
}

$risk = new DbChangeInStateNotification();
$risk->addNotification($pid, $seriesID, $time, $_POST['zCurrent'], $_POST["zWanted"]);
echo 'done';

?>