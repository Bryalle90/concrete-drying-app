<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbUser.php');
    
    session_start();
    
    if(isset($_POST['projectID'])){
        $projectdb = new DbProject();
        $userdb = new DbUser();
        if($_POST['email'] != ''){ // check if email address is blank
            $newUserID = $userdb->isUser($_POST['email']);
            if($newUserID != Null){ // check if user email exists in database
                if(!$projectdb->isUserInProject($_POST['projectID'], $newUserID)){ // check if user is alraedy in project
                    echo 'user was added to project';
                    $projectdb->addUserToSharedProject($_POST['projectID'], $newUserID);
                } else {
                    echo 'user is already in project';
                }
            } else {
                echo 'user does not have an account';
            }
        } else {
            echo 'you must enter the users email address';
        }
    }
?>