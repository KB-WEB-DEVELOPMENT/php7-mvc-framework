<?php


/*

ch 16: ex 1

task #1: to add validation for email addresses, the $_validators array [listing 16-2 (p.263)] is modified 

and the _validateEmail($value) method [listing 16-3 (p.264)] is added, see below.

task #2:  to add validation for dates, the $_validators array [listing 16-2 (p.263)] is modified 

and _validateDate($value) method [listing 16-3 (p.264)] is added, see below.


*/

protected $_validators = array(
 "required" = >array(
 "handler" = > "_validateRequired",
 "message" = > "The {0} field is required"
 ),
 "alpha" =>array(
 "handler" = > "_validateAlpha",
 "message" = > "The {0} field can only contain letters"
 ),
 "numeric" =>array(
 "handler" = > "_validateNumeric",
 "message" = > "The {0} field can only contain numbers"
 ),
 "alphanumeric" = > array(
 "handler" = > "_validateAlphaNumeric",
 "message" = > "The {0} field can only contain letters and numbers"
 ),
 "max" =>array(
 "handler" = > "_validateMax",
 "message" = > "The {0} field must contain less than {2} characters"
 ),
 "min" =>array(
 "handler" = > "_validateMin",
 "message" = > "The {0} field must contain more than {2} characters"
 ),
 "email" =>array(
 "handler" = > "_validateEmail",
 "message" = > "The email address is incorrect"
 ),
 "date" =>array(
 "handler" = > "_validateDate",
 "message" = > "The date entered does not follow a standard date format"
 )
);


protected function _validateEmail($value)	{
	
	$email = filter_var($value, FILTER_SANITIZE_EMAIL);
	
	return (filter_var($email, FILTER_VALIDATE_EMAIL)); 
}

#https://www.php.net/manual/en/function.checkdate.php#113205
protected function _validateDate($value,$format = 'Y-m-d H:i:s')	{
	
	$d = DateTime::createFromFormat($format, $value);
	
    return $d && $d->format($format) == $date;
}




?>
