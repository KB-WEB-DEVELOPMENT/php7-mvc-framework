<?php

/*  ch 13: ex 2

To determine if an applicable error page can be shown, i.e: custom error page, we need to check if 
the "filepath" exists on the web server. 
We modify the constructor of the Controller class as follows (listing 13-9, page 214)
(We could redirect the user to a 404 error page if the file is not found)  

*/
public function __construct($options= array())	{
	
	parent::__construct($options);
 
	if ($this->getWillRenderLayoutView())	{
				
		$defaultPath=$this->getDefaultPath();
		$defaultLayout =$this->getDefaultLayout();
		$defaultExtension =$this->getDefaultExtension();
 
		// addition
		$layout_file_path = APP_PATH . "/{$defaultPath}/{$defaultLayout}.{$defaultExtension}"; 
		
		if (file_exists($layout_file_path)) {
    
			$view= new View(array(
			"file" = > APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}"
			       ));
			   
			$this- >setLayoutView($view);
		
		} else {
					
			throw new View\Exception\Renderer("Layout filepath does not exist!");
		}
		

	}
	
	if ($this- >getWillRenderActionView())	{
		
		$router =Registry::get("router");
		$controller = $router->getController();
		$action= $router->getAction();
		
		// addition
		$action_file_path = APP_PATH . "/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}";
		
		if (file_exists($action_file_path)) {
    
			$view= new View(array(
			"file" = > APP_PATH . "/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}"
			       ));
			   
			$this- >setActionView($view);
		
		} else {
					
			throw new View\Exception\Renderer("Action filepath does not exist!");
		  }
			
	}
}


?>
