<?php


/*  

ch 9 (page 142) - Exercise 2

We will add the OR clause and LIKE clause to the class Query (listing 9-15, p 134 - 141)

The following snippets need to be added to the class Query

*/

// declaration of all other class properties

/**
 * @read
 */
 protected $_or = array();

/**
 * @read
 */
 protected $_like = array();


// add within _buildSelect() the following code - similar changes to be made inside _buildUpdate($data) and _buildDelete($data)
protected function _buildSelect() {
	
	$fields= array();
	$where= $order = $limit = $join = $or = $like = "";
	$template ="SELECT %s FROM %s %s %s %s %s %s %s";
	
	// blabla ...

	$_or = $this->or;

	if (!empty($_or))	{
	 
		$joined = join(" OR ", $_or);
		$or = "OR {$joined}";

	}

	$_like = $this->like;
 
	if (!empty($_like))	{
		
		$joined = join(" LIKE ", $_like);
		$like = "LIKE {$joined}";
	} 
	
	// blabla ...

	return sprintf($template, $fields, $this->from, $join, $where, $or, $like, $order, $limit);
	
}	

 public function or()	{
	 
	$arguments = func_get_args();
 
	if (sizeof($arguments)<1)	{
		
		throw new Exception\Argument("Invalid argument");
	}
	
	$arguments[0] = preg_replace("#\?#", "%s", $arguments[0]);
 
	foreach (array_slice($arguments, 1, null, true) as $i =>$parameter)	{
		
		$arguments[$i] = $this->_quote($arguments[$i]);
	}
	
	$this->_or[] = call_user_func_array("sprintf", $arguments);
 
	return $this;
 }

 public function like()	{
	 
	$arguments = func_get_args();
 
	if (sizeof($arguments)<1)	{
		
		throw new Exception\Argument("Invalid argument");
	}
	
	// 	https://stackoverflow.com/questions/5020130/how-to-escape-literal-percent-sign-when-no-backslash-escapes-option-is-enabled
	$arguments[0] = preg_replace("#\?#", "\%" . "%s" . "\%", $arguments[0]);
	
	foreach (array_slice($arguments, 1, null, true) as $i =>$parameter)	{
		
		$arguments[$i] = $this->_quote($arguments[$i]);
	}
	
	$this->_like[] = call_user_func_array("sprintf", $arguments);
 
	return $this;
 }

?>
