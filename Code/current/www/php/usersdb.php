<?php
// temporary testing php file until zach finishes user database class
require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class UsersDB
{
	public function hashPass($pass){
		return password_hash($pass, PASSWORD_BCRYPT, array("cost" => 10));
	}
	
	public function verifyPass($pass, $hash){
		return password_verify($pass, $hash);
	}
}