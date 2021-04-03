<?php

/*

ch 30 : ex 1  - add in app/Config/routes.php

*/

Router::connect('users/profile/*', array('controller' => 'users' , 'action' => 'profile', 'profile_view'));

?>
