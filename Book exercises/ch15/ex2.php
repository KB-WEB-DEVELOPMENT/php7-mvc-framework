<?php

/*

ch 15 : ex 2

To add the lastname to the query, just modify listing 15-16 (page 255) , i.e : the $where variable as follows :

*/

$where = array(
 "SOUNDEX(first) = SOUNDEX(?)"  => $query,
 "SOUNDEX(last)  = SOUNDEX(?)"  => $query,
 "live = ?" =>true,
 "deleted = ?" =>false
 );


?>
