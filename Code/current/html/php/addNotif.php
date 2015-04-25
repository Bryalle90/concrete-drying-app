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

if ($risk->checkNotification($pid, $seriesID, $time, $currentZone , $wantedZone) > 0)
{
    echo 'Notification has already been created';
}
else
{
	$risk->addNotification($pid, $seriesID, $time, $currentZone , $wantedZone);
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbProject.php');
	$logger = new DbLog();
	$projectdb = new DbProject();
	
	$zip = $projectdb->getZipcode($pid);
	$logger->addRiskCreated($zip, $time);
	
    echo 'success';
}


?>