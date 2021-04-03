<?php

/*

 ch 32: ex 1 - We will create a CakePHP 4.0 Plugin component called ThumbnailPluginComponent to import the Imagine library
               used in the _thumbnail($photo) method (listing 32-8,p .437-438)
			   
ThumbnailPlugin Files and Directory Configuration :

/src
/plugins
    /ThumbnailPlugin
        /config
        /src
            /Plugin.php
            /Controller
                /Component
                  ThumbnailPluginComponent
            /Model (not needed for us, empty)
                /Table (not needed for us, empty)
                /Entity (not needed for us, empty)
                /Behavior (not needed for us, empty)
            /View (not needed for us, empty)
                /Helper (not needed for us, empty)
            /Template (not needed for us, empty)
                /Layout (not needed for us, empty)
        /tests (not needed for us, empty)
            /TestCase (not needed for us, empty)
            /Fixture (not needed for us, empty)
        /webroot (not needed for us, empty)


To install the ThumbnailPlugin manually in composer.json file, add :

{
    "autoload": {
        "psr-4": {
            "YourPluginNameSpace\\": "plugins/ThumbnailPlugin/src/"
        }
    },
}


*/

// step 1: loading the plugin in src/Application

use Cake\Http\BaseApplication;

use ThumbnailPlugin\Plugin as ThumbnailPlugin;

class Application extends BaseApplication {

    public function bootstrap() {
        
     parent::bootstrap();
        
     $this->addPlugin(ThumbnailPlugin::class);

    }
}

// step 2: ThumbnailPlugin Hook Configuration in Application::bootstrap()

use ThumbnailPlugin\Plugin as ThumbnailPlugin;

$thumbnailPlugin = new ThumbnailPlugin();

$thumbnailPlugin->enable('routes');

$this->addPlugin($thumbnailPlugin);


// step 3: write the ThumbnailPluginComponent class in:  plugins/ThumbnailPlugin/src/Controller/Component 

namespace YourPluginNameSpace;

use Cake\Controller\Component;

class ThumbnailPluginComponent {
	    
	public function importImagineLibrary() {
		
		 App::uses("File", "Utility");
		
		 App::import("Vendor", "Imagine/Image/ManipulatorInterface");
		 App::import("Vendor", "Imagine/Image/ImageInterface");
		 App::import("Vendor", "Imagine/Image/ImagineInterface");
		 App::import("Vendor", "Imagine/Image/BoxInterface");
		 App::import("Vendor", "Imagine/Image/PointInterface");
		 App::import("Vendor", "Imagine/Image/Point");
		 App::import("Vendor", "Imagine/Gd/Image");
		 App::import("Vendor", "Imagine/Gd/Imagine");
		 App::import("Vendor", "Imagine/Image/Box");
		        
    }
 
}

// step 4: load the ThumbnailPluginComponent in the _thumbnail($photo) method (listing 32-8,p .437-438) inside the UsersController class

protected function _thumbnail($photo) {
	
		$obj = $this->loadComponent('ThumbnailPluginComponent');
	
		$obj->importImagineLibrary();
		
		// rest of method ...

}	
	

?>
