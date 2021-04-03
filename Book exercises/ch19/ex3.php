<?php

/*

ch 19: ex 3

- Allow custom $width and $height in thumnails($id) method (listing 19-11, p.306)

Basically allow the user to pass the width and height as parameters in the method, here is a sketch of the changes in the method

*/

public function($id,$width,$height) {
	
// ... everything as in the book	

$w = $width;
$h = $height;

if (!is_int($w) || !is_int($h) || ($w < 10) || ($w > 150) || ($h < 10) || ($h > 150)) {

	return new Exception\ThumbnailSize("Your thumbnail pixel width and pixel height should be integers between 10 and 150");	

}

// ... replace $width with $w and $height with $h in what remains of the method --  see book

}

/*

To allow multiple font faces in a single request in the fonts($name) method (listing 19-8, p.303-305),

I will replace the string $name parameter sent to the method with the array $names_array parameter and read through each array value $name within  
the foreach loop in the method, unsetting set array values on the way.



*/

use Shared\Controller as Controller;
use Fonts\Proxy as Proxy;
use Fonts\Types as Types;

class Files extends Controller	{
	
	public function fonts($names_array)	{
	 
		$path="/fonts";
		
		
		foreach ($names_array as $name) {
						 
			if (!file_exists("{$path}/{$name}"))	{
				 
				 $proxy=new Proxy();
				 
				 $proxy- >addFontTypes("{$name}", array(
					Types::OTF = > "{$path}/{$name}.otf",
					Types::EOT = > "{$path}/{$name}.eot",
					Types::TTF = > "{$path}/{$name}.ttf"
				));
		 
				$weight = "";
				$style = "";
				
				is_array($font) ? unset($font) : null;
				
				$font = explode("-", $name);
		 
				if (sizeof($font) > 1)	{
					
					switch (strtolower($font[1]))	{
						 case "Bold":
						 $weight = "bold";
						 break;
						 case "Oblique":
						 $style ="oblique";
						 break;
						 case "BoldOblique":
						 $weight ="bold";
						 $style="oblique";
						 break;
					}
				}
			
				$declarations="";
				
				$font =join("-", $font);
				
				is_array($sniff)  ? unset($sniff)  : null;
				is_array($served) ? unset($served) : null;
				
				$sniff = $proxy-> sniff($_SERVER["HTTP_USER_AGENT"]);
				$served = $proxy->serve($font, $_SERVER["HTTP_USER_AGENT"]);
	 
				if (sizeof($served) >0)	{
					
					is_array($keys)  ? unset($keys) : null;
			
					$keys = array_keys($served);
					$declarations .="@font-face {";
					$declarations .="font-family: \"{$font}\";";
		 
					if ($weight) {
				
						$declarations .="font-weight: {$weight};";
					}
			
					if ($style)	{
				
						$declarations .="font-style: {$style};";
					}
								
					$type = $keys[0];
					$url = $served[$type];
		 
					if ($sniff && strtolower($sniff["browser"]) == "ie")	{
				
						$declarations .="src: url(\"{$url}\");";
			
					} else {
				
						$declarations .="src: url(\"{$url}\") format(\"{$type}\");";
					
					}
			
					$declarations .="}";
				
				}
		
				header("Content-type: text/css");
	 
				if ($declarations)	{
					echo $declarations;

				} else {
					
					echo "/* no fonts to show */";
				}
				
				$this->willRenderLayoutView = false;
				$this->willRenderActionView = false;
			
			} else {
				
				header("Location: {$path}/{$name}");
			}
			
		}	
	}
}

?>
