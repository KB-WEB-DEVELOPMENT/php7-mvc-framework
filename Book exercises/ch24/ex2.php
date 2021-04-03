<?php

/*

ch 24 : ex 2 - We will extend CI_Model core class to move just the method _populate($options)   
from the File class (listing 24-1, p.367) as a simple example.

*/

class MY_File extends CI_Model {
	
	protected function _populate($options)	{
		
		foreach ($options as $key => $value) {
			
			if (property_exists($this, $key)) {	
				$this->$key = $value;
			}
		}
	}
 	
}	

?>
