<?php

/*  ch 14: ex 1

An appropriate place to close the connection to the database would be when the user logs out.

We will add the method logout() in the Users Class (listing 14-16, page 234)

We will assume a submit form with a logout button named "logout" has been created in a some view 
 
*/

public function logout()  {
	
	if (RequestMethods::post("logout"))	{

		$session=Registry::get("session");

		$session->erase("user");

		$database = \Framework\Registry::get("database");
		
		$database->disconnect();

		header("Location: /users/login.html");
		
		exit();

	} 
	
}


?>
