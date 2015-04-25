<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');
$risk = new DbChangeInStateNotification();
$seriesdb= new DbSeries();
$seriesID = $_POST['sID'];
$pid = $_POST['pID'];
$time = $_POST['time'];
$currentZone = $_POST['zCurrent'];
$wantedZone = $_POST['zWanted'];


if ($seriesID == 'original')
{
    
    $seriesID = $seriesdb->getOriginalSeriesID($pid);
}

$id = $risk->getID($pid, $seriesID, $time);
$risk->deleteNotification($id);
echo 'success';
?>