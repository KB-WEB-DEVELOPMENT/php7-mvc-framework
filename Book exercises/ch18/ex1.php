<?php

/*

ch 18: ex 1

- We will check for file size errors, images mime type errors and filename datatype errors

We rewrite the upload method (listing 18-4, p. 292)

*/

use Shared\Controller as Controller;

class Users extends Controller	{
	
	protected function _upload($name, $user)	{
	 
		if (isset($_FILES[$name]))	{
						 
			$file = $_FILES[$name];
			
			// Only checking for most common images mime content type : https://www.php.net/manual/de/function.mime-content-type.php
			
			if ( $file["size"] > 2000000 || !in_array($file["type"],['image/png', 'image/jpeg', 'image/jpg', 'image/gif']) || !is_string($file["name"]) ) {
				
				return new Exception\Upload("There was a problem with your image file upload");	
				
			}	
			
			$path = APP_PATH."/public/uploads/";
			$time = time();
			$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
			$filename = "{$user}-{$time}.{$extension}";
 
			if (move_uploaded_file($file["tmp_name"], $path.$filename))	{
	 
				$meta = getimagesize($path.$filename);
 
				if ($meta)	{
		
					$width = $meta[0];
					 $height = $meta[1];
					 $file = new File(array(
						 "name" => $filename,
						 "mime" => $file["type"],
						 "size" => $file["size"],
						 "width" => $width,
						 "height" => $height,
						 "user" => $user
					));
		 
					$file->save();
 
				}
			}
		}
	}
}







?>
