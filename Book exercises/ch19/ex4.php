<?php

/*

ch 19: ex 4

The plugin to be written has for goal to display statistics about the amount of time the application takes
to update DB tables rows (similarly to the Logger Plugin, p.315-317), when a user tries to update
a DB table row when he clicks on the button "save" in the app.

Three things need to be done :

1) Fire the "before event" in the proper place in the correct method ---> the save() method in the Query class (p.138)
2) Fire the "after  event" in the proper place in the correct method ---> the save() method in the Query class (p.138)
3) Write the Updatequerylogger class

*/

// step #1 :

public function save($data)	{
	
	$start_time = microtime();
	
	// Before event
	Events::fire("framework.updatequery.before", array($start_time));
	
	$isInsert =sizeof($this->_where) == 0;
 
	if ($isInsert)	{
	 
		$sql=$this->_buildInsert($data);
 
	} else {
	 
		$sql = $this->_buildUpdate($data);
	
		$end_time = microtime();
		
		$sql_query = $sql;
		
	}
 
	$result =$this->_connector->execute($sql);
 
  
	if ($result === false) {
	 
		throw new Exception\Sql();
 
	}
	
	$end_time = microtime();

	// after event
	Events::fire("framework.updatequery.after", array($end_time), array($sql_query));
 
	if ($isInsert) {
 
		return $this->_connector->lastInsertId;
 
	}
 
	return 0;
}

// step #2 : create an initialize.php file in the application/plugins directory, content here (similar to p.315).
// It will be run when the application bootstraps through /public/index.php (listing 19-20, p.315)

 
// initialize updateQueryLogger in initialize.php
include("updatequerylogger.php");

$updateQueryLogger = new Updatequerylogger(array(
			"file" => APP_PATH . "/logs/" . date("d/m/Y") . ".txt"
			));

Framework\Events::add("framework.updatequery.before", function($start) use
($updateQueryLogger)
{
	$updateQueryLogger->logStart("framework.updatequery.before", $start);
});	

Framework\Events::add("framework.updatequery.after", function($end,$query) use
($updateQueryLogger)
{
	$updateQueryLogger->logEnd("framework.updatequery.after", $end, $query);
});

// step #3 : create the Updatequerylogger class (similar to p.316)

class Updatequerylogger	
{
	
	protected $_file;
	protected $_start;
	protected $_end;
	protected $_query;
	
	public function __construct($options) {
		
		if (!isset($options["file"])) {
			
			throw new Exception("Log file invalid");
		}
			
		$this->_file = $options["file"]; 
		
	}

	protected function logStart($event, $start) {
		
		$this->_start = $start;
		
	}
	
	protected function logEnd($event, $end, $query) {
				
		$this->_end = $end;
		
		$this->_query = $query;
				
	}
	  
    public function __destruct()	{
	  	
		$messages = "";
	
		$saved_times = array();
	
		$duration = $this->_end - $this->_start;
			
		$saved_times[] = $duration;
	
		$query = $this->_query;
				
		$messages . = "Query Code: " . $query;
		$messages . = ", Time needed for query in milliseconds: " . $duration;
		$messages . = ", fastest query in milliseconds: " . min($saved_times);
		$messages . = ", slowest query in milliseconds: " . max($saved_times);
		$messages . = ", Average time for a query in milliseconds: " . (array_sum($saved_times)/count($saved_times));
		$messages . = "\n";
	 
		file_put_contents($this->_file, $messages, FILE_APPEND);
	}	
	
}

?>
