<?php

/*

ch 19: ex 1

We'll rewrite the Files Controller (p.303-307) and try to cache as much information as possible 

in the two methods fonts($name) and thumbnails($id)

 
*/

use Shared\Controller as Controller;
use Fonts\Proxy as Proxy;
use Fonts\Types as Types;

class Files extends Controller	{
	
	public function fonts($name)	{
		
		$path="/fonts";
		
		$cache=new Framework\Cache(array(
			"type" => "memcached"
		));

		$cache->initialize();

		$filepath = unserialize($cache->get("{$path}.{$name}"));	
 
		if empty($filepath)	{
			
			$proxy=new Proxy();
			$proxy->addFontTypes("{$name}", array(
				Types::OTF = > "{$path}/{$name}.otf",
				Types::EOT = > "{$path}/{$name}.eot",
				Types::TTF = > "{$path}/{$name}.ttf"
				 ));
				 
			$filepath = $cache->set("{$path}.{$name}", serialize("{$path}/{$name}"));	 
				  
			$weight = "";
			$style = "";
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
			$sniff =  $proxy->sniff($_SERVER["HTTP_USER_AGENT"]);
			$served = $proxy->serve($font, $_SERVER["HTTP_USER_AGENT"]);
 
			if (sizeof($served) >0)	{
 
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
				
				if ($sniff && strtolower($sniff["browser"]) == "ie") {
					
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
			$this->willRenderActionView =false;
 
		} else {
			
			header("Location: {$filepath}");
		}
	}


	public function thumbnails($id)	{
		
		$path=APP_PATH."/public/uploads";
		
		$cache=new Framework\Cache(array(
			"type" => "memcached"
		));

		$cache->initialize();
				
		$file=File::first(array(
			"id=?" => $id
		));
		
		if ($file)	{
			
			$width=64;
			$height =64;
 
			$name = $file->name;
			$filename =pathinfo($name, PATHINFO_FILENAME);
			$extension = pathinfo($name, PATHINFO_EXTENSION);
 
			if ($filename && $extension)	{
				
				$thumbnail = unserialize($cache->get("{$filename}-{$width}x{$height}.{$extension}")); 
					 
				if  empty($thumbnail) {
					
					$imagine = new Imagine\Gd\Imagine();
					$size = new Imagine\Image\Box($width, $height);
					$mode =Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

					$cache->set("{$filename}-{$width}x{$height}.{$extension}", serialize("{$path}/{$name}"));
					
					$imagine->open("{$path}/{$name}")
							->thumbnail($size, $mode)
							->save("{$path}/{$thumbnail}");
				}
				
				header("Location: /uploads/{$thumbnail}");
				exit();
			}
		
			header("Location: /uploads/{$name}");
			exit();
		
		}
	}
}

?>
