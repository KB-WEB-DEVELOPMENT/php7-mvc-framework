<?php

/*

ch 17: ex 2

We add the method getFriends() in the User model  

All DB "friend" table fields with active status are queried and returned starting ordered from the newest created

one to the oldest created.

*/

public function getFriends() 	{
	
	$friends = Friend::all(array(
	   "user = ?" => $this->getId(),
	   "live = ?" => true,
	   ), array("*","created","desc")
	
	return $friends;

}	


?>
