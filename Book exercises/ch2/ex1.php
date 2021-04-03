<?php

function autoload($class)	{
	
 $paths = explode(PATH_SEPARATOR, get_include_path());
 $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
 $file = strtolower(str_replace("\\", DIRECTORY_SEPARATOR, trim($class, "\\"))).".php";

 $loaded_dependencies = array();
 $unloaded_depencies = array();
 
 // use microtime() for php version < 7, use getrusage() for php version >= 7 
 $time_start = microtime(true);
 
 foreach ($paths as $path)	{
	 
	$combined = $path.DIRECTORY_SEPARATOR.$file;
 
	 if (file_exists($combined))	{
		 
		$loaded_dependencies[]= $combined;  
		include($combined);
		return;
	 } else {
	   $unloaded_dependencies[]= $combined;	 
	 }	 
 }
 
 $time_end = microtime(true);
 
 $execution_time  = $time_end - $time_start  // execution time given in seconds
 // var_dump($execution_time);
 // var_dump($loaded_dependencies);
 // var_dump($unloaded_dependencies);
 unset($loaded_dependencies);
 unset($unloaded_dependencies);
 
 throw new Exception("{$class} not found");
}

?>
