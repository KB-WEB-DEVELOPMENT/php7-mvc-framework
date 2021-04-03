<?php

/*

ch 28 : ex 1  - we will create a public function inside the UsersController to 

create a thumbnail of selected height and width for a chosen file (throwing an error if the method parameters are incorrect)

*/

protected function _makeThumbnail($file, $width, $height) {
		
	$path = dirname(APPLICATION_PATH)."/public/uploads";
	
	$w = (int)$width;
	
	$h = (int)$height;
	
	if (!is_int($w) || !is_int($h) || $w <= 0 || $h <= 0) {
		
		throw new ParameterNotFoundException('The width and/or height you entered are not positive integers.');	
		
	}
	 
	 $name = $file->name;
	 $filename = pathinfo($name, PATHINFO_FILENAME);
	 $extension = pathinfo($name, PATHINFO_EXTENSION);
 
	if ($filename && $extension) {
	 
		$thumbnail = "{$filename}-{$width}x{$height}.{$extension}";
 
		if (!file_exists("{$path}/{$thumbnail}")) {
			
			$imagine = new Imagine\Gd\Imagine();
			$size = new Imagine\Image\Box($w, $h);
			$mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
	 
			$imagine->open("{$path}/{$name}")
					->thumbnail($size, $mode)
					->save("{$path}/{$thumbnail}");
		}
	
		return $thumbnail;
 
	}
}

?>
