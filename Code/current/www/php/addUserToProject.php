<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbProject.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/../classes/DbUser.php');
    
    session_start();
    
    $projectdb = new DbProject();
    if(isset($_SESSION['id']) && $projectdb->getOwner($_SESSION['activeProject']) == $_SESSION['id']){
        $userdb = new DbUser();
        if($_POST['email'] != ''){ // check if email address is blank
            $newUserID = $userdb->isUser($_POST['email']);
            if($newUserID != Null){ // check if user email exists in database
                if(!$projectdb->isUserInProject($_SESSION['activeProject'], $newUserID)){ // check if user is alraedy in project
                    echo 'user was added to project';
                    $projectdb->addUserToSharedProject($_SESSION['activeProject'], $newUserID);
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