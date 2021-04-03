<?php

/* 

Ch: 10, ex 2 - to validate the data before it is inserted/updated in the DB, we will
create a method called validate($data = array(), $value = null) which we will call 
from within the save() method, page 157/158 .
 
validate($data = array(), $value = null) returns a boolean value.

*/


//  method save() on page 157-158
public function save() {

	// blabla ...
	
	if (!$column["read"])	{
		
		if (!$this->validate($data,$this->$prop)) {
						
			throw new Exception\Datatype("The field data type entered does not match the database field type.");	
			
		}	
	
		// blabla ...
	
	}
	
	// blabla ..
}

public function validate($data = array(), $value = null) {

	$type = $data["type"];
	
	$datatype = "";
	
	switch ($type) {
		case "autonumber":
			$datatype = "int";
			break;
		case "text":
			$datatype = "string";
			break;
		case "integer":
			$datatype = "int";
			break;
		case "decimal":
			$datatype = "float";
			break;
		case "boolean":
			$datatype = "boolean";
			break;
		case "datetime":
			$datatype = "string";
			break;		
		default:
			$datatype = "incorrect!";
			break;
	}
	
	return  ($datatype === gettype($value))  

}

?>
