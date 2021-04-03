<?php

/*

ch 18: ex 2

- I will create the method getThumbnail($name, $user) in the Users controller which transforms an uploaded image file into a thumbnail.
  The method returns a thumbnail using imagecopysized(). 

- The method is used in the templates search.html and profile.html below .

*/

use Shared\Controller as Controller;

class Users extends Controller	{
	
	protected function getThumbnail($filename)	{
	  
		$file = File::first(array(
				"name = ?" = > $filename,
		));
		
		$shrinking_factor = 0.2;

		$newwidth  =  $file["width"]   * $shrinking_factor;
		$newheight =  $file["height"]  * $shrinking_factor;

		$new_image = imagecreatetruecolor($newwidth, $newheight);

		$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
		
		switch ($extension) {			
			case 'gif':
				$source = imagecreatefromgif($file["name"]);
				break;						
			case 'png':
				$source = imagecreatefrompng($file["name"]);
				break;									
			case 'jpeg':
				$source = imagecreatefromjpeg($file["name"]);
				break;
			case 'jpg':
				$source = imagecreatefromjpeg($file["name"]);
				break;				
		}
		
		imagecopyresized($new_image, $source, 0, 0, 0, 0, $newwidth, $newheight, $file["width"], $file["height"]);

		switch ($extension) {			
			case 'gif':
				$thumbail = imagegif($new_image);
				break;						
			case 'png':
				$thumbail = imagepng($new_image);
				break;									
			case 'jpeg':
				$thumbail = imagejpeg($new_image);
				break;
			case 'jpg':
				$thumbail = imagejpeg($new_image);
				break;				
		}		

		return $thumbail;

	}
	
	
}
	
?>

<!-- search.html template --> 


{foreach $_user in $users}

	{script $file = $_user->file}
		<tr>
			<td > {if $file} <img src = "/uploads/{ echo $user->getThumbnail($file->name)}" />{/if}</td>
			<td> {echo $_user->first} {echo $_user->last}</td>
			<td>
				{if $_user->isFriend($user->id)}
					<a href = "/unfriend/{echo $_user->id}.html">unfriend</a>
				{/if}
				{else}
					<a href = "/friend/{echo $_user->id}.html">friend</a>
				{/else}
			</td>
		</tr>

{/foreach}

<!-- profile.html template -->

{script $file = $user->file}

	{if $file}<img src = "/uploads/{ echo $user->getThumbnail($file->name)}" />{/if}

	<h1>{echo $user->first} {echo $user->last}</h1>

This is a profile page!



	
		
	







