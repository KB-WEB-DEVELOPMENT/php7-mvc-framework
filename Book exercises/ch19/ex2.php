<?php

/*

ch 19: ex 2

We will modify the Core class by modifying the autoload($class) method (listing 2-3, p.11)

so that it loads the Imagine Classes.

(This is an alternative to using Composer which the book author does not use even though it is standard use and 

would significantly eases the process.)

*/

function autoload($class)	{
	
	$paths = explode(PATH_SEPARATOR, get_include_path());
	$flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
	$file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))).".php";
 
	foreach ($paths as $path)	{
		 
		$combined = $path.DIRECTORY_SEPARATOR.$file;
	 
		if (file_exists($combined))	{
			
			include($combined);
			return;
		}
	}
 
	throw new Exception("{$class} not found");

	// loading Imagine Classes

	$imagine_path = lcfirst(str_replace("\\", DIRECTORY_SEPARATOR, $class));
 
	$file=APP_PATH."/application/libraries/{$imagine_path}.php";
 
	if (file_exists($file))	{
		
		require_once $file;
		return true;
	}

}

?>
