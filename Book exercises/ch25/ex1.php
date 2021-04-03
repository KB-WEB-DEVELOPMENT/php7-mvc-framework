<?php

/*

ch 25 : ex 1 - We will write two CodeIgniter action tests. The first one will test if the user input is a valid email address format,
the second one will test if the user file upload is an image.

*/

class Testing extends CI_Controller 	{
	
	public function index() {
		
		$this->load->view('welcome_message');
	}
	
	public function emailTest($input) {
		
		$this->load->library("unit_test");
		
		$result = $this->getEmailTestResult($input);
		
		$expected_result = 1;
		
		$test_name = "tests valid email address format";
		
		$this->unit->run($result,$expected_result,$test_name);
	
		echo $this->unit->report();
	
	}	
	
	public function getEmailTestResult($input) {
		
		return (filter_var($input, FILTER_VALIDATE_EMAIL)) ? 1 : 0;
	
	}	

	public function ImagesTest($input) {
		
		$this->load->library("unit_test");	
	
		$result = $this->getImagesTestResult($input);
	
		$expected_result = 1;
		
		$test_name = "tests uploaded file is an image";
		
		$this->unit->run($result,$expected_result,$test_name);
		
		echo $this->unit->report();
	}
		
	public function getImagesTestResult($filename) {
		
		$extensions = array('.gif', '.jpg', '.JPG', '.png' ,'.PNG' ,'.jpeg' ,'.JPEG');
				
		if ( !empty($filename) && is_file($filename) ) {
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
			return in_array($ext,$extensions) ? 1 : 0;
					
		} else {
			
			return 0;
		}	
	
	}

}	

?>

