<?php

/*

 ch 6: ex 6-1

In the registry class (listing 6-4,p 64), add the following:

*/

private  static $_pairs = array();

public static function getAll($default = null)	{

	unset(self::$_pairs);

	if (isset(self::$_instances)) {
		
		foreach(self::$_instances as $key => $value) {
			
			self::$_pairs("key/value" => "{$key}/{$value}");
		}
		
		return self::$_pairs;
	
	}	

	self::$_pairs("key/value" => "nothing/nothing");
	
	return self::$_pairs;

}

/*

use :  $pairs = Framework\Registry::getAll();
       var_dump($pairs);
*/

?>
