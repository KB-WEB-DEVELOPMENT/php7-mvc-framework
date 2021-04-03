<?php

/*

Ch 15 - ex 3 : managing cookies

the following methods are added to the Request class (listing 15-4, p.244)

*/

public function createCookie($name, $value, $expires = time() + (86400 * 30), $path ="/", $domain = "", $secure="", $httponly=0)	{
	
	if (is_string($name) && is_string($value))  {
		
		setcookie($name, $value);
		
		exit();
	
	} else {

		throw new Exception\Argument("Cookie not properly set.");
	}
}

public function getCookie($name)	{

	return isset($_COOKIE[$name]) ?  $_COOKIE[$name] : null;	
	
}


public function deleteCookie($name)	{

	if (isset($_COOKIE[$name]))  {

		setcookie($name, "", time() - 3600);		

		exit();
	
	} else {

		throw new Exception\Argument("Cookie name incorrect.");
	}
}

public function isCookieSet($name) {

	return isset($_COOKIE[$name]) ?  true : false;
}


?>
