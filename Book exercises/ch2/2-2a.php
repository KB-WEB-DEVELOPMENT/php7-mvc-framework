<?php

namespace Framework;

/**
*
* PHPDocs ClassMethods Testing Class
*
* Class tests only class methods comments
*
* @package Exercise
* @author  Kâmi Barut-Wanayo
* 
*/

class Classmethodtest 
{

/**	
*	
*/	
public function showMessage() {
 echo "No PHPDocs methods comments available";	
}
/**
*	
*	This function returns a string.
*
*	@param string $name
*	@return string	
*
*	@throws Exception if element $name is not a string
*
*/	
public function greetPerson($name) {
   if (!is_string($name)) {
	Throw new Exception("Enter a string !");   
   }
   return "Hello " . $name;
}
}
?>
