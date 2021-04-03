<?php

/*

ch 31 : ex 2  - We will use CakePHP 2.0 authentication library to improve our login system 
for the UsersController.

*/

// step # 1 : in src/Application.php

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{  

	// declare all properties properties and all class includes all other methods ...

	public function bootstrap() {
		
		parent::bootstrap();

		$this->addPlugin('Authentication');
	}
	
	  public function middleware() {

		$middlewareQueue->add(new AuthenticationMiddleware($this));

     }  

	public function getAuthenticationService($request){
    
		$service = new AuthenticationService();

		$service->setConfig([
			'unauthenticatedRedirect' => Router::url([
			 'prefix' => false,
			 'plugin' => null,
			 'controller' => 'Users',
			 'action' => 'login',
			]),
			'queryParam' => 'redirect',
		]);

		$fields = [
			IdentifierInterface::CREDENTIAL_USERNAME => 'email',
			IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
		];
		
		$service->loadAuthenticator('Authentication.Session');
		$service->loadAuthenticator('Authentication.Form', [
			'fields' => $fields,
			'loginUrl' => Router::url([
			 'prefix' => false,
			 'plugin' => null,
			 'controller' => 'Users',
			 'action' => 'login',
			]),
		]);

		$service->loadIdentifier('Authentication.Password', compact('fields'));

		return $service;
	}

}

// step #2 : in src/Controller/AppController

	public function initialize()	{
	
		parent::initialize();

		$this->loadComponent('Authentication.Authentication');
	}

/* Note : 

By default, the component will require an authenticated user for all actions. 

You can disable this behavior in specific controllers using allowUnauthenticated().

In any controller inside the methods beforeFilter() or initialize(), you can make the view() and index() methods  for example not require a logged in user.

code : $this->Authentication->allowUnauthenticated(['view', 'index']);

*/


// step #3 : in src/Controller/UsersController.php

	public function login() {
		
		$result = $this->Authentication->getResult();
    
		if ($result->isValid()) {
			$target = $this->Authentication->getLoginRedirect() ?? '/users/profile/';
			return $this->redirect($target);
		}
	
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error('Invalid username or password');
		}
	}


	public function beforeFilter($event) {
		
		parent::beforeFilter($event);

		$this->Authentication->allowUnauthenticated(['login']);
	}
	
	
	public function logout()	{
		
		$this->Authentication->logout();
		
		return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	}

// step #4: in view file : ~/app/View/Users/login.ctp

	echo $this->Form->create("User");
	echo $this->Form->input("email");
	echo $this->Form->input("password", array(
		"type" => "password"
	));
	echo $this->Form->end("login");


// (OPTIONAL : step #5: in src/Model/Entity/User.php - to add password hashing)

use Authentication\PasswordHasher\DefaultPasswordHasher;

class User extends Entity	{
	
    // declare all properties and write all other methods ...

    protected function _setPassword($password)	{
        
		$hasher = new DefaultPasswordHasher();

        return $hasher->hash($password);
    }
}

?>
