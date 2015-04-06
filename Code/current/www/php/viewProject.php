<?php
    require_once('/../../classes/DbProject.php');
    
    session_start();
    $projectdb = new DbProject();
    
    if(isset($_SESSION['id']) && $projectdb->isUserInProject($_POST['projectID'], $_SESSION['id'])){
        $unit = $projectdb->getUnit($_POST['projectID']) == 'S' ? 'Standard' : 'Metric';
        $zip = str_pad($projectdb->getZipcode($_POST['projectID']), 5, '0', STR_PAD_LEFT);
        echo '?unit='.$unit.'&zip='.$zip;
    }
?>