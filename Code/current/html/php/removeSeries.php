<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
$seriesdb= new DbSeries();
$seriesID = $_POST['sID'];
$seriesdb->deleteSeries($seriesID);
echo('Done');
?>