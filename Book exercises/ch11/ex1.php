<?php

/* 

ch 11 : ex 1

- All the tests below cover :

a) The Registry class
b) StringMethods class
c) ArrayMethods class

*/

Framework\Test::add(
	function() {
		
		$registry = new Framework\Registry();

		return  ($registry instanceof Framework\Registry);		
	},
	"Registry is properly instantiated",
	"Registry"
);

Framework\Test::add(
	function() {
		
		$registry = new Framework\Registry();
	
		$registry::set("name","Kâmi");
	
		return ($registry::get("name") === "Kâmi");
	},
	"Registry sets and retrieves key/values properly",
	"Registry"
);

Framework\Test::add(
	function() {
		
		$registry = new Framework\Registry();
	
		$registry::set("name","Kâmi");
		$registry::erase("name");
	
		return ($registry::get("name") === null);
	},
	"Registry erases keys/values properly",
	"Registry"
);

Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
	
		return  ($stringMethods instanceof Framework\StringMethods);
	},
	"StringMethods is properly instantiated",
	"StringMethods"
);

Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
			
		return ($stringMethods::_normalize("   [a-z]") === "#[a-z]#");
		
	},
	"StringMethods normalizes a regex pattern properly",
	"StringMethods"
);


Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
			
		return ($stringMethods::getDelimiter() === "#");
		
	},
	"StringMethods retrieves default delimiter",
	"StringMethods"
);

Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
			
		$stringMethods::setDelimiter(%);
		
		return ($stringMethods::getDelimiter() === "%");
		
	},
	"StringMethods sets custom delimiter",
	"StringMethods"
);

Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
		
		$string = "He was eating a cake in the cafe.";
		$pattern = "/ca[kf]e";
					
		$matches = $stringMethods::match($string,$pattern);
		
		return ($matches[0] == "cake" && $matches[1] == "cafe");
	
	},
	"StringMethods return the matches of a pattern in a string as an array, 2 matches maximum found",
	"StringMethods"
);

# https://www.w3schools.com/php/phptryit.asp?filename=tryphp_func_regex_preg_split3
Framework\Test::add(
	function() {
		
		$stringMethods = new Framework\StringMethods();
		
		$string = "1970-01-01";
		$pattern = "/-/";
			
		$matches = $stringMethods::split(self::_normalize($pattern), $string, $limit);
	
		return ($matches[0][0] == "1970" && $matches[1][0] == "01" && $matches[2][0] == "01");
	},
	"StringMethods breaks a string into an array using matches of a regular expression as separators.",
	"StringMethods"
);

Framework\Test::add(
	function() {
		
		$arrayMethods = new Framework\ArrayMethods();
		
		$cleaned_array = array("test1","test2");
		
		$uncleaned_array = array("test1","test2","");
				
		return ($arrayMethods::clean($uncleaned_array) === $cleaned_array);
	
	},
	"ArrayMethods clears array of empty key/value pair and returns cleaned array",
	"ArrayMethods"
);

Framework\Test::add(
	function() {
		
		$arrayMethods = new Framework\ArrayMethods();
		
		$trimmed_array = array("test1","test2");
		
		$untrimmed_array = array("test1               ","        test2          ");
		
		return ($arrayMethods::trim($untrimmed_array) === $trimmed_array);
		
	},
	"ArrayMethods trims array of all whitespaces and returns a trimmed array",
	"ArrayMethods"
);
?>
