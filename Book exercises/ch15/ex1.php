<?php

/*  ch 15: ex 1

to create additional (extended) template tags that allow for subitem access (e.g : { yield scripts.shared}) to 
objects in the shared storage (prepend, set, append and yield), I will assume that whenever a single string variable {key} is used in the Extended class,
I will instead use the key combined with the subkey, i.e : {key}.{subkey} where {subkey} is optional 

This change implies either the addition of the $subkey parameter and/or minor changes in here:

- The Extended class
- the 3 extended implementation utility methods [listing 15-9, p.251]in the Response class : 

_getKey($tree) 
_setValue($key, $subkey, $value)
_getValue($key, $subkey)

- set($key,$subkey, $value) handler method [listing 15-10, p.251-252] in the Response class
- append($key,$subkey,$value) handler method [listing 15-11. p.252] in the Response class 
- prepend($key,$subkey,$value) handler method [listing 15-11. p.252] in the Response class
- yield($tree,$content) handler method [listing 15-12.p.253] in the Response class

*/

namespace Framework\Template\Implementation		{
	
 use Framework\Request as Request;
 use Framework\Registry as Registry;
 use Framework\Template as Template;
 use Framework\StringMethods as StringMethods;
 use Framework\RequestMethods as RequestMethods;
 
 class Extended extends Standard	{
	 
	 /**
	 * @readwrite
	 */
	 
	 protected $_defaultPath = "application/views";
	 /**
	 * @readwrite
	 */
	 
	 protected $_defaultKey = "_data";
	 
	 /**
	 * @readwrite
	 */
	 
	 protected $_index = 0;
	 
	 public function __construct($options = array())	{
		 
		parent::__construct($options);
	 
		$this->_map = array(
		  "partial" = >array(
			"opener" = >"{partial",
			"closer" = >"}",
			"handler" = >"_partial"
		),
		"include" = >array(
		"opener" = >"{include",
		"closer" = > "}",
		"handler" = > "_include"
									),
			"yield" = > array(
			"opener" = > "{yield",
			"closer" = >"}",
			"handler" = >"yield"
									
									)
		)+ $this->_map;
						
		$this- >_map["statement"]["tags"] = array(
		    "set" =>array(
			  "isolated" =>false,
				"arguments" =>"{key},{subkey}",
				"handler" = >"set"
				),
				"append" = >array(
				"isolated" =>false,
				"arguments" =>"{key},{subkey}",
				"handler" = >"append"
				),
				"prepend" = >array(
				"isolated" =>false,
				"arguments" =>"{key},{subkey}",
				"handler" = >"prepend"
				)
		)+$this->_map["statement"]["tags"];
	 }
 }
}

// all methods below belong the Response class (Listing 15-7, p.249)

protected function _getKey($tree)	{
	
	if ( empty($tree["arguments"]["key"]) || empty($tree["arguments"]["key"]["subkey"])	) {
		
		return null;
	}
 
	return !empty($tree["arguments"]["key"]["subkey"]) ? $tree["arguments"]["key"]["subkey"] : $tree["arguments"]["key"];  
	
}
	
	
protected function _setValue($key, $subkey = null, $value)	{
	
	if (!empty($key))	{
 
		$default = $this->getDefaultKey();
		
		$data = Registry::get($default, array());
		
		$data[$key.$subkey] = $value;
 
		Registry::set($default, $data);
 }


}

protected function _getValue($key, $subkey = null)	{
	
 $data = Registry::get($this- >getDefaultKey());
  
 return isset($data[$key]) ?  $data[$key.$subkey] : null; 

}

public function set($key,$subkey = null, $value) {
	
	if (StringMethods::indexOf($value, "\$_text")> −1)	{
		
		$first = StringMethods::indexOf($value, "\"");
		$last = StringMethods::lastIndexOf($value, "\"");
		$value = stripslashes(substr($value, $first+1, ($last - $first) - 1));
	}
	
	if (is_array($key))	{
		
		$key = $this->_getKey($key.$subkey);
	}
	
	$this->_setValue($key,$subkey, $value);

}

public function append($key, $subkey = null, $value)	{
	
	if (is_array($key))	{
		
		$key = $this->_getKey($key.$subkey);
	}
	
	$previous = $this->_getValue($key,$subkey);
	
	$this->set($key, $previous.$value)

}
public function prepend($key, $subkey = null, $value)	{
	
	if (is_array($key))	{
		
		$key = $this- >_getKey($key.$subkey);
	}
	
	$previous = $this->_getValue($key,$subkey);
 
	$this->set($key, $value.$previous);

}

public function yield($tree, $content)	{
	 
 $key = trim($tree["raw"]);
 
 $value = addslashes($this->_getValue($key, $subkey));
 
 return "\$_text[] = \"{$value}\";";

}


?>
