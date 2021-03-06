<?php

require "/../libraries/password-compat/lib/password.php";

class DbUser {
    
    private $dbhandle;

    private $HOST = 'localhost';
    private $ACCOUNT = 'root';
    private $PASSWORD = '';
    private $DATABASE = 'plasticcracks';
    
    private function hashPass($pass){
        return(password_hash($pass, PASSWORD_BCRYPT, array("cost" => 10)));
    }
    
    private function verifyPass($pass, $hash){
        return(password_verify($pass, $hash));
    }

    public function __construct(){
        $this->connectdb();
    }

    //connect to the database
    public function connectdb(){            
        $this->dbhandle = mysql_connect($this->HOST, $this->ACCOUNT, $this->PASSWORD);
                    
        $selected = mysql_select_db($this->DATABASE, $this->dbhandle);
    }

    //inserts a new user to the table
    public function addUser($name, $email, $userPass, $isAdmin){
        $hashedPass = $this->hashPass($userPass);
        $sql = "INSERT INTO user (name, email, userPass, isAdmin)
        VALUES ('$name', '$email', '$hashedPass', '$isAdmin')";
        mysql_query($sql);      
    }

    //delete user from table
    public function deleteUser($userID){        
        $sql = "DELETE FROM user WHERE userID = '$userID'";
        mysql_query($sql);
    }

    //changes the users name in the table
    public function changeName($userID, $name){
        $sql = "UPDATE user SET name = '$name' WHERE userID = '$userID'";
        mysql_query($sql);
    }

    //changes the users email in the table
    public function changeEmail($userID, $email){
        $sql = "UPDATE user SET email = '$email' WHERE userID = '$userID'";
        mysql_query($sql);
    }

    //changes the users password in the table
    public function changePassword($userID, $password){
        $hashedPass = $this->hashPass($password);
        $sql = "UPDATE user SET userPass = '$hashedPass' WHERE userID = '$userID'";
        mysql_query($sql);
    }   
    

    //checks to see if user is admin and return true of false
    public function isUserAdmin($userID){
        $sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);

        if($result == 'y' || $result == 'Y'){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getName($userID){
        $sql = "SELECT name FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);
        return $result;
    }

    public function getCurrentNumberOfNotifications($userID){
        $sql = "SELECT currentNumberOfNotifications FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);
        return $result;
    }

    public function getEmail($userID){
        $sql = "SELECT email FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);
        return $result;
    }

    public function getUserPass($userID){
        $sql = "SELECT userPass FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);
        return $result;
    }

    public function getIsAdmin($userID){
        $sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $result = mysql_result($result, 0);
        return $result;
    }
    
    public function verifyLogin($email, $userPass){
        $id = $this->isUser($email);
        if($id != Null){
            $hash = $this->getUserPass($id);
            if($this->verifyPass($userPass, $hash))
                return ($id);
        }
        return(Null);
    }

    //checks to see if email exists and return userID if they do, returns NULL if not
    public function isUser($email){
        $sql = "SELECT userID FROM user WHERE email = '$email'";
        $result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $id = mysql_result($result, 0);
        
        return($id);
    }

    //close the connection
    public function disconnectdb(){
        mysql_close($dbhandle);
    }

    public function _destruct(){
        $this->disconnectdb();
    }

}

?>
