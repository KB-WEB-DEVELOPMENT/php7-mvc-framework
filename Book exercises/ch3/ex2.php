<?php

/*

listing 3-11, page 35-37

 get rid of all exception methods on page 37

 in all if statements, replace all "throw lines" with: 
 set $errorType = "writeOnlyError"; with next line: return $errorType;
 set $errorType = "readOnlyError"; with next line: return $errorType;
 set $errorType = "implementationOnlyError"; with next line: return  $errorType;
 set $errorType = "exceptionOnlyError"; with next line: return  $errorType;

 add in listing

*/

switch($errorType) {  
    case "writeOnlyError":
        return new Exception\WriteOnly("{$property} is write-only");
        break;
    case "readOnlyError":
        return new Exception\ReadOnly("{$property} is read-only");
        break;
    case "implementationOnlyError":
        return new Exception\Argument("{$name} method not implemented");
        break;		
    case "exceptionOnlyError":
        return new Exception\Property("Invalid property");
        break;
}


?>
