<?php

/*
 
 Ex 4-1:
 
 step 1 : create an associative array php configuration file say config.php and not an *.ini configuration file.
          Content of config.php here below:
*/


return [

	"database_provider" => "mysql", 

	"database_host" => "yourhost",

	"database_username" => "yourusername",
	 
	"database_password" => "yourpassword",

	"database_port" => "yourport",

	"database_schema" => "kamiphpmvc",
	
	// add additional keys if needed
];
 
/* 
step 2:  The idea is to have both the $my_config array below compared to the config.php file content above,
         as follows :
*/		 
		$my_config = [ "my_database_provider" => "mysql", "..." => "..." ];	

        $configuration = new Framework\Configuration(array(
				  "type" => "array",
				  "options" => $my_config
				));
		$configuration->initialize();


/*

   step 3: page 43-44, listing 4-5, modify the initialize() method in the Configuration class so that it checks (1) the type (2) compare
   config.php with $my_config, throw an Exception if they are different
   
   in the Configuration class,
     
   add: include 'config.php';
   
   add: protected $loaded_config;

*/
	public function initialize()
	{
		if (!is_array($this->options)) {
			
			throw new Exception\Argument("Invalid type")
		}	
		
		$this->loaded_config = new config();
		
		$diff = array_diff_assoc($this->loaded_config, $this->options);
		
		if (!empty($diff)) {
			
			throw new Exception\Argument("Wrong configuration !");
		}	
				
    }


/*
 
 Ex 4-2:
 
 more complex configuration methods combining ini files and associative arrays can be thought of.
 
*/

<?>
