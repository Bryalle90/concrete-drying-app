<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
    session_start();
    
    if(isset($_POST['projectID'])){
        $projectdb = new DbProject();
        $unit = $projectdb->getUnit($_POST['projectID']) == 'S' ? 'Standard' : 'Metric';
        $zip = str_pad($projectdb->getZipcode($_POST['projectID']), 5, '0', STR_PAD_LEFT);
        echo '?unit='.$unit.'&zip='.$zip;
    }
?>