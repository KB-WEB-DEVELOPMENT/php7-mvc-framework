<?php

/*

ch 33: ex 1 - The following tests are located in ~ app/Test/Controller/UsersControllerTest.php

*/

class UsersControllerTest extends ControllerTestCase {

	public function testIndexGet() {
	
		$result = $this->testAction("/index", array(
			"method" => "get",
			"return" => "contents"
		));
 
		$this->assertContains("data[User][first]", $result);

	}	
		
	public function testSettingsGet() {
	
		$result = $this->testAction("/settings", array(
			"method" => "get",
			"return" => "contents"
		));
 
		$this->assertContains("data[User]", $result);
	}
	
	public function testSettingsPost() {
	
		$result = $this->testAction("/settings", array(
			"method" => "post",
			"return" => "contents"
		));
 
		$this->assertContains("data[User][first]", $result);
		$this->assertContains("data[User][last]", $result);
		$this->assertContains("data[User][email]", $result);
		$this->assertContains("data[User][password]", $result);
		$this->assertContains("data[User][photo]", $result);
	}
		
	
	public function testSearchPost() {
	
		$result = $this->testAction("/search", array(
			"method" => "post",
			"return" => "contents"
		));
 
		$this->assertContains($users, $result);
		$this->assertContains($count, $result);
	}
		
	public function testProfileGet() {
	
		$result = $this->testAction("/profile", array(
			"method" => "get",
			"return" => "contents"
		));
 
		$this->assertContains("photo", $result);
		$this->assertContains("user", $result);
	}


}	

?>
