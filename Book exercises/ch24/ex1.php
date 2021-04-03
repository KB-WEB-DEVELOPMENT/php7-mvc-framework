<?php

/*

ch 24 : ex 1 - We will make the height and width of the thumbnail dynamic in the CodeIgniter Thumbnail class 

*/

// step # 1: modify the Thumbnail class

require("imagine/image/imagineinterface.php");
require("imagine/image/manipulatorinterface.php");
require("imagine/image/imageinterface.php");
require("imagine/image/boxinterface.php");
require("imagine/image/box.php");
require("imagine/gd/imagine.php");
require("imagine/gd/image.php");
require("imagine/image/pointinterface.php");
require("imagine/image/point.php");
 
class Thumbnail	{
	
	protected $_filename;
	protected $_width;
	protected $_height;
 
	public function __construct($options = array())	{
 
		if (isset($options["file"])) {
			
			$file = $options["file"];
			$path = dirname(BASEPATH)."/uploads";
			$this->_height = $options["height"];
			$this->_width = $options["width"];
			$name = $file->name;
			$filename = pathinfo($name, PATHINFO_FILENAME);
			$extension = pathinfo($name, PATHINFO_EXTENSION);
 
			if ($filename && $extension)	{
				
				$thumbnail = "{$filename}-{$this->_width}x{$this->_height}.{$extension}";
 
				if (!file_exists("{$path}/{$thumbnail}")) {
					
					 $imagine = new Imagine\Gd\Imagine();
					 $size = new Imagine\Image\Box($this->_width, $this->_height);
					 $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
 
					 $imagine->open("{$path}/{$name}")
							 ->thumbnail($size, $mode)
							 ->save("{$path}/{$thumbnail}");
				}

				$this->_filename = $thumbnail;
			}
		}
	}
	
	public function getFilename()	{
		
		return $this->_filename;
	}
}

// step # 2: modify the Users class

class Users extends CI_Controller	{

	// all other properties & methods in the class ...
	
	 public function profile()	{
		 
		// code appearing before this line ...
		
		$this->load->library("thumbnail", array(
				"file" => $file,
				"width" => 90,
				"height" => 90
		));

	  // rest of code in the method ... 
		 
	 }
 
?> 

<!-- step # 3: modify the profile.html view -->

<?php if ($filename): ?>
	<img src="/uploads/<?php echo $filename; ?>" />
<?php endif; ?>
<h1><?php echo $user->first; ?> <?php echo $user->last; ?></h1>
This is a profile page!

