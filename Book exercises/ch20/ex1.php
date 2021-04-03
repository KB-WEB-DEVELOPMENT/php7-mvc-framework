<?php

/*

ch 20: ex  1 

I will give here a general idea as to how a queried user data can be stored/retrieved in/from the cache

with the two hypothetical methods login() and getUser($id)

*/

// see p.322 for full method
public function login() {
	
	if (RequestMethods::post("login")	{
		
		$email = RequestMethods::post("email");
		$password = RequestMethods::post("password");
	}	
	
	$user = User::first(array(
				"email=?" => $email,
				"password=?" => $password,		
				"live=?" => true,
				"deleted=?" => false
			));


	$cache = new Framework\Cache(array(
		"type" => "mecached"
	));

	$cache->initialize();

	$cache->set("users.{$user->id}", serialize($user));
		
	// rest of code ...

}

public function getUser($id) {
	
	// some code ...	
	
	$user = unserialize($cache->get("users.{$id}"));
	
	// rest of code ...
	
}


?>
