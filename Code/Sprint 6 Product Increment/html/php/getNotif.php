<?php
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbSeries.php');
include($_SERVER['DOCUMENT_ROOT'].'/classes/DbChangeInStateNotification.php');
$pid = $_POST['pID'];
$seriesdb= new DbSeries();
$risk = new DbChangeInStateNotification();
$numbOfNotif = $risk->getNumbOfNotificationID($pid);




if ($numbOfNotif > 0)
{
    $idArr = $risk->getchangeInStateNotificationID($pid);
    $seriesID = array();
    $timeArr = array();
    $currentZoneArr = array();
    $notifyZoneArr = array();
    
    for($i = 0; $i < $numbOfNotif; $i++)
    {
        
        $timeArr[$i] = $risk->getTime($idArr[$i]);
        $seriesID[$i] = $risk->getSeries($idArr[$i]);
        $currentZoneArr[$i] = $risk->getCurrentZone($idArr[$i]);
        $notifyZoneArr[$i] = $risk->getNotifyZone($idArr[$i]);
        
        
        if ($seriesdb->getIsOriginal($seriesID[$i]) == 'y')
        {
            $seriesID[$i] = 'original';
        }
    }
    
   // $arr = $risk->getNotif($pid);
   echo json_encode( array(
    'changeInStateID' => $idArr,
    'sid' => $seriesID,
    'time' => $timeArr,
    'currentZone' => $currentZoneArr,
    'notifyZone' => $notifyZoneArr
    ));
}

else
{
    echo 'None';
}


/*
echo json_encode( array(
    'seriesID' => $idArr,
    'time' => $timeArr,
    'currentZone' => $currentZoneArr,
    'notifyZone' => $notifyZoneArr
    ));
    */

?>