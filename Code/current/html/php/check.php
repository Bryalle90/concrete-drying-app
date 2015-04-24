<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
$seriesdb= new DbSeries();
$projectID = $_POST['pID'];
$pid = $_POST['pID'];
$concreteTemp = $_POST['concrete'];
$windSpeed = $_POST['wind'];
$checkDuplicate = $seriesdb->checkDuplicates($pid, $concreteTemp, $windSpeed);

if ($checkDuplicate == 0)
{
    echo 'success';
}

else
{
    echo 'Series already exists';
}
?>