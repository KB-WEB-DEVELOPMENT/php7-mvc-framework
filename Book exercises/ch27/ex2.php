<?php

/*

ch 27 : ex 2  - There are 3 ways to disable or prevent automatic view rendering in Zend Framework.

Examples of each are shown below.

*/

https://olegkrivtsov.github.io/using-zend-framework-3-book/html/en/Model_View_Controller/Disabling_the_View_Rendering.html

// method #1 (disable view and layout):

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SomeController extends AbstractActionController {
	
	public function someAction() {
		
		// some code ...
	
		return $this->response; 
		
		/* alternative 1 :
		
		$response = $this->getResponse();
		$response->setStatusCode(200);
		$response->setContent('foo');
		
		return $response;
		
		*/
		
		/* alternative 2 :
		
		$data = array(
			'result' => true,
			'data' => array()
		);
		
		return $this->getResponse()->setContent(Json::encode($data));
		
		*/	
		
		
	}	
		
}	

// method #2 (disable view):

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SomeController extends AbstractActionController {
	
	public function someAction() {
		
		// some code ...
	
		return false; 
		
	}	
		
}

// method #3 (disable layout):

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SomeController extends AbstractActionController {
	
	public function someAction() {
		
		// some code ...
	
		$view = new ViewModel();
		
		$view->setTerminal(true);
    
		return $view; 
		
	}	
		
}


?>
