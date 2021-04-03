<?php

/*  

ch 13: ex 1

We will create a method initialize() within the View class to automate the configuration
of the View class with two of the  most commonly used php templating engines (Twig and Smarty)

The initialize() methods for the database class (listing 13-3, p.205) and for the cache class (listing 13-4, p.206)
are used as a blueprint.

*/

public function initialize()	{
	
	$type =$this->getType();
 
	if (empty($type))	{
		
		$configuration=Registry::get("configuration");
 
		if ($configuration)	{
			
			$configuration =$configuration-> initialize();
			
			$parsed=$configuration->parse("configuration/cache");
	 
			if (!empty($parsed->cache->default) && !empty($parsed->cache->default->type))	{
			
				$type =$parsed->cache->default->type;
				
				unset($parsed->cache->default->type);
	 
				$this- >__construct(array(
				   "type" = >$type,
				   "options" =>(array)$parsed->cache->default
				));
			}
		}
	}
 
	if (empty($type))	{
		
		throw new Exception\Argument("Invalid type");
	}
 
	switch ($type)	{
		
		case "twig":	{
		 
			//https://twig.symfony.com/doc/3.x/api.html
			$loader = new \Twig\Loader\FilesystemLoader($this->config["path/to/templates"]);
			$twig =   new \Twig\Environment($loader, [
			  'cache' => $this->config["path/to/compilation_cache"],
			]);					  
			return $twig;		  
			break;		
		
		}

		case "smarty":	{
		 
			return new View\Config\Smarty($this->getOptions());
			break;		
		
		}		
				
		default:	{
		
			throw new Exception\Argument("Invalid type");
			break;
		}
	}
}



?>
