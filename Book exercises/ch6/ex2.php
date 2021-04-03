<?php

/*

ch 6: ex 6-2


In the registry class (listing 6-4,p 64), add the following:

*/

public static function isClassInRegistry($classname = null)	{

	if (isset(self::$_instances)) {
		
		$key_exists = array_search($classname,self::$_instances);

		if (!empty($key_exists)) {
			return true;
		}
	}	
	
	return false;

}


?>
