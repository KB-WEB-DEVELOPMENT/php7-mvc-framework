<?php 


/*

ch 8 - ex 8-1:

2 steps :

1) add to the Standard class in the array "statement" in listing 8-4, p87

*/

 "switch" => array(
	"isolated" => false,
	"arguments" => "{element} in {object}",
	"handler" => "_switch"
)

/*

2) add the following method to the same class

*/

protected function _switch($tree, $content) {

	//$object = $tree["arguments"]["object"];
	$element = $tree["arguments"]["element"];
 

	return $this->_loop(
		$tree,
		"switch ({$element})  {
			{$content}
		}"
	);
} 	
 



?>
