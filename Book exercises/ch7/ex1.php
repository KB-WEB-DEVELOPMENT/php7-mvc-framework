<?php

/*

ex 7-5:

step #1: add in Route class (p.68, listing 6-2) the following property :

@readwrite
protected $route_name;

step #2: Write a Route subclass similar to class Simple (page 70, listing 7-5), call it class Simple2 , with two additional
         methods : setRouteName($route_name, $route) and redirect($route_name)

*/

namespace Framework\Router\Route	{
	
	use Framework\Router as Router;
	use Framework\ArrayMethods as ArrayMethods;
 
	class Simple2 extends Router\Route	{
		
		public function matches($url)	{
			
			$pattern = $this- >pattern;
 
			// get keys
			preg_match_all("#:([a-zA-Z0-9]+)#", $pattern, $keys);
 
			if (sizeof($keys) && sizeof($keys[0]) && sizeof($keys[1]))	{
				
				$keys = $keys[1];
			
			} else	{
				
				// no keys in the pattern, return a simple match
				return preg_match("#^{$pattern}$#", $url);
			  }
			
			// normalize route pattern
		    $pattern = preg_replace("#(:[a-zA-Z0-9]+)#", "([a-zA-Z0-9-_]+)", $pattern);
 
			// check values

			preg_match_all("#^{$pattern}$#", $url, $values);
 
		    if (sizeof($values) && sizeof($values[0]) && sizeof($values[1]))	{
	 
				// unset the matched url
				unset($values[0]);
 
				// values found, modify parameters and return
				$derived = array_combine($keys, ArrayMethods::flatten($values));
 
				$this->parameters = array_merge($this->parameters, $derived);
 
				return true;
			}
			
			return false;
		}
		
		public function setRouteName($route_name, $route) {
					
			if (!isset($route_name) || !is_string($route_name)) { throw new Exception("Your route name data type is incorrect."); } 
		
			if (!isset($route) || !is_array($route)) { throw new Exception("Your route data type is incorrect."); }
		
			foreach ($this->_routes as $r)	{
								
				if ($r == $route) {

					$this->route_name = $route_name	
		
					$r["name"] = $this->route_name;

					return;
					
				} 
			}
			
			throw new Exception("The route you selected does not exist.");										
		}

		public function redirect($route_name) {
					
			if (!isset($route_name) || !is_string($route_name)) { throw new Exception("Your route name data type is incorrect."); }	
		
			foreach ($this->_routes as $r)	{
								
				if ($r["name"] == $route_name) {
	
					$controller = $r->controller;
					$action =  $r->action;
					$parameters = $r->parameters;
			
					$this->_pass($controller,$action, $parameters);
					return;
				} 					
			}
			
			throw new Exception("The route name you selected does not exist.");
				
		}


}




?>
