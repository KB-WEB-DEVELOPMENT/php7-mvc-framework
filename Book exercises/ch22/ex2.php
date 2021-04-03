<?php

/*

ch 22 : ex 2 - very basic CI controller with a few very basic actions


*/


?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

  public $user;	

  public function __construct() {
 
	parent::__construct();
         
	  $this->load->model('Users');
  }

   // p.355-356
   public function register()	{

    // 1. load validation library
	// 2. check register form post
    // 3. set rules for validation
    // 4. validate inputs
    // 5. load user model, create new user and save, set $success to true, load view		   

   }	   

    
   public function delete($id)	{

		if (!empty($id) {
						
			$this->user =  new User(array(
				"id" => $id
			));	
		
			$this->user->row_delete($id);
			
			return;
		
		}		
   }

}


?>
