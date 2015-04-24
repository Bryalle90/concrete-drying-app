<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
$seriesdb= new DbSeries();
$seriesID = $_POST['sID'];
//TODO Delete notifcations on that series

$seriesdb->deleteSeries($seriesID);
echo('Done');

?>