<?php

// ch 33: ex 2 - The following are two session-based tests in ~ app/Test/Controller/UsersControllerTest.php

class UsersControllerTest extends ControllerTestCase {

	public function testLoginSessionPost() {

		$result = $this->testAction("/login", array(
			"method" => "post",
			"return" => "contents"
		));
		
		// this line inside the "login" action
		$session = $this->getRequest()->getSession();
		
		// this line inside the "login" action
		$session->write('Auth.User.id',1);

		$this->assertSession(1, 'Auth.User.id');
	}

	public function testLogoutSessionPost() {
	
		$result = $this->testAction("/logout", array(
			"method" => "post",
			"return" => "contents"
		));
 
		// this line inside the "logout" action
		$session = $this->getRequest()->getSession();
		
		// this line inside the "logout" action
		$session->write('Auth.User.id',1);
		
		$session->delete('Auth.User.id');
		
		$this->assertSession(null, 'Auth.User.id');
	}
	
?>
