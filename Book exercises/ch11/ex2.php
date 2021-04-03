<?php

/* 

ch 11 - ex 2:

To display a fancy output of the tests results, we'll create a table obtained through
the method getTestResults()

This method is added in the Test class (listing 11-1, page 174-175)

*/

public static function getTestResults() {
	
	$results = [];
	$passed = [];
	$failed = [];
	$exceptions = [];
	
	$results = $this->run();
	
	$passed = $results["passed"]; 
	$failed = $results["failed"];
	$exceptions = $results["exceptions"];
	
	$html_output = '<!DOCTYPE html><html><body> .
						<table style="width:100%">' .
							 '<tr>'  .
								'<th>Test title </th>' .
								'<th>Test group type</th>'
								'<th>Status</th>' .
							 '</tr>';		 
							 if (!empty($passed)) {
								 
								 foreach ($passed as $p) {
									'<tr><td>' . $p["title"] . '</td><td>' . $p["set"] . '</td><td>PASSED</td></tr>';										
								 }
							 } 
							 if (!empty($failed)) {
								 
								 foreach ($failed as $f) {
									'<tr><td>' . $f["title"] . '</td><td>' . $f["set"] . '</td><td>FAILED</td></tr>';										
								 }
							 }
							 if (!empty($exceptions)) {
								 
								 foreach ($exceptions as $e) {
									'<tr><td>' . $e["title"] . '</td><td>' . $e["set"] . '</td><td>' .  $e["type"] . '</td></tr>';										
								 }
							 }
							 if (empty($passed) && empty($failed) && empty($exceptions)) {
								 '<tr><td>No tests conducted until now.</td><td></td><td></td></tr>';
							 }	 
						'</table></body></html>';
						
	return $html_output;					
		
}

?>
