<?php

/*

ch 21 : ex 1 

We will write 6 tests, 5 of which test if a form field value submitted through the posting of a form 
is of the correct type (and length if it is a string) 

The 5 fields are : "firstname", "lastname", "email", "password" and "photo"

The 6th and last test checks if the form has been submitted at all

*/

// step 1: keep the POST request with provided data 

$post = function($url, $data) {
	
	$request = new Framework\Request();
	return $request->post("http://" . $_SERVER["HTTP_HOST"] . /{$url}, $data)
}	

// step 2: write a new callback/function to check the data type and length for $firstname

$testFirstName = function($post) {
	
	return (!empty($data) && is_string($data) && strlen($data) < 25 && strlen($data) > 2) ? true : false;
	
}

// step 3: write the test

Framework\Test::add(
   function() use ($post, $testFirstName) {

		$pathAndData = $post("register.html", "John Doe");
		
		return $testFirstName($pathAndData);
		
   }	   

// Same process for the other methods/callbacks, I will repeat step 2 here for all the other fields :

$testLastName = function($post) {
	
	return (!empty($data) && is_string($data) && strlen($data) < 25 && strlen($data) > 2) ? true : false;
	
}

$testEmail = function($post) {
	
	return ( !empty($data) && filter_var($data, FILTER_VALIDATE_EMAIL) ) ? true : false;
	
}

// tests for existence and datatype, not if the password has been hashed
$testPassword = function($post) {
	
	return ( !empty($data) && is_string($data) ) ? true : false;
	
}

// we assume here that $data includes the path followed by the filename of the image
$testPhoto = function($post) {
	
	return ( !empty($data) &&  getimagesize($data) ) ? true : false;
	
}

$testSubmission = function($post) {
	
	return !empty($post) ? true : false;
	
}

?>
