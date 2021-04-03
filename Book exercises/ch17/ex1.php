<?php


/*

ch 17: ex 1

We will determine which error page has to be displayed in the bootstrap file based on the exception class detected

with a switch statement.

(listing 17-1, p 273- 276 - public/index.php) 
 
*/

define("DEBUG", TRUE);
define("APP_PATH", dirname(dirname(__FILE__)));

try	 {
	
	require("../framework/core.php");
	Framework\Core::initialize();
 
	$configuration = new Framework\Configuration(array("type" => "ini"));
 
	Framework\Registry::set("configuration", $configuration->initialize());
 
	$database = new Framework\Database();
	Framework\Registry::set("database", $database->initialize());
 
     $cache = new Framework\Cache();
     Framework\Registry::set("cache", $cache->initialize());
 
     $session = new Framework\Session();
     Framework\Registry::set("session", $session->initialize());
 
     $router = new Framework\Router(array(
					"url" => isset($_GET["url"]) ? $_GET["url"] : "home/index",
					"extension" => isset($_GET["url"]) ? $_GET["url"] : "html"
	));
 
	Framework\Registry::set("router", $router);
	
	$router->dispatch();
 
	unset($configuration);
	unset($database);
	unset($cache);
	unset($session);
	unset($router);

}	catch (Exception $e) {
	
		$exception = get_class($e);
	
		switch ($exception) {
			
			case 'Core':
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();						
			case 'Configuration':
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();									
			case 'Database':
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();									
			case 'Cache':
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit;									
			case 'Session':			
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();										
			case 'Router':			
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();										
			case 'Registry':
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/500.php");
				exit();
			/* the exception here can only be caused by a controller or an action, not a predetermined class */		
			default:
				header("Content-type: text/html");
				include(APP_PATH."/application/views/errors/404.php");
				exit();				
		}
				 
	}



?>
