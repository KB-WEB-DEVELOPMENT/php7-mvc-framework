<?php



/* ch 32: ex 2 - In order to be able to use the saveAll() method, we will make the following changes :

1) in the method _upload($name,$user) (p.435-436), do not use the method save() but instead write :

$this->Photo = array(
 "user_id" => $user,
 "mime" => $info["mime"],			
 "size" => $info["filesize"],
 "width" => $size[0],
 "height" => $size[1]
));
  
2) in the method register() (page 436), do not use the save() method but instead write :

if ($this->request->is("post")) { 
 
	$this->User = array(
	 "first" => $this->request->data["User"]["first"],
	 "last" => $this->request->data["User"]["last"],
	 "email" => $this->request->data["User"]["email"],
	 "password => $this->request->data["User"]["password"]	
  ));
 
3) underneath this line, write :

$data = array();
$data["photo"] = $this->Photo;
$data["user"] = $this->User;

4) rewrite the if statement of the register method (p.436) :

public function register()	{
	
	if  ( ($this->request->is("post") && !empty($this->Photo) &&  !empty($this->User) )	{
	 			
          $this->_upload("photo", $this->User->id);
	
	 $this->Photo->saveAll($data['photo']);
		
	 $this->User->saveAll($data['user']);	
	
	 $this->Session->setFlash("Your account has been created.");

	} else {
		
		$this->Session->setFlash("An error occurred while creating your account.");
	 }
}

  
 */

?>
