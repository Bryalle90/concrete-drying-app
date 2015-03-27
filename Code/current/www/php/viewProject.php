<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    
    session_start();
    $projectdb = new DbProject();
    
    if(isset($_SESSION['id']) && $projectdb->isUserInProject($_SESSION['activeProject'], $_SESSION['id'])){
        $unit = $projectdb->getUnit($_SESSION['activeProject']) == 'S' ? 'Standard' : 'Metric';
        $zip = str_pad($projectdb->getZipcode($_SESSION['activeProject']), 5, '0', STR_PAD_LEFT);
        echo '?unit='.$unit.'&zip='.$zip;
    }
?>