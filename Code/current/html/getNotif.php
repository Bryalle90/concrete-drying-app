<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');
$pid = $_POST['pID'];
$seriesdb= new DbSeries();
$risk = new DbChangeInStateNotification();

//print_r($risk->getNotif($pid));
print_r($risk->getchangeInStateNotificationID($pid));

?>