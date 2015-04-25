<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');

$seriesdb= new DbSeries();
$risk = new DbChangeInStateNotification();

$seriesID = $_POST['sID'];
$pid = $_POST['pID'];
$time = $_POST['time'];
$wantedZone = $_POST['zWanted'];

if ($seriesID == 'original')
{   
    $seriesID = $seriesdb->getOriginalSeriesID($pid);    
}

$id = $risk->getID($pid, $seriesID, $time);
$risk->editNotif($id, $wantedZone);
echo 'success';

?>