<?php

/* 

Ch: 10, ex 1 

The method needed in the Model class is getNumberPages($limit = null)
It makes use of the existing two methods all(...) and _all(...) , see chapter 10 for their content. 
*/


public function getNumberPages($where = array(), $limit = null, $page = 1) {
	
	$query = $this->connector->query()->from($this->table);
	
	foreach ($where as $clause => $value) {
		
		$query->where($clause, $value);
	
	}	

	if ($limit != null)	{

		$query->limit($limit, $page);
	}
	   
	$rows = array();
 
	foreach ($query->all() as $row)	{
	
		$rows[] = $row;
	
	}

	$row_count = sizeof($rows);
	
	$pages_needed = is_null($limit) ? $page : ceil($row_count/$limit);
	
	return $pages_needed;
}

?>
