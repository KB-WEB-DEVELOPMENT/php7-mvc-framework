<?php


/*

ch 16: ex 2

One of the most common ways to hash a  password is shown below for the register() method.

It works the same way for the login() and settings() methods.
 
*/


public function register()	{
	
	$view = $this->getActionView();
 
	if (RequestMethods::post("register"))	{
		 
		 $user = new User(array(
		   "first" = >RequestMethods::post("first"),
		   "last" = > RequestMethods::post("last"),
		   "email" = > RequestMethods::post("email"),
		   "password" = > password_hash(RequestMethods::post("password"), PASSWORD_BCRYPT)
		));
						
		if ($user->validate())  {
			
		  $user->save();
		  $view->set("success", true);
		}
		
		$view->set("errors", $user- >getErrors());
	}
}

?>
