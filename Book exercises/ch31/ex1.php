<?php

/*

ch 31 : ex 1  - We will make some changes to the functionalities of our CakePHP Users search form.
 
- We will add a pagination system in our search view file (~/app/View/Users/search.ctp) to simplify the way our search results are displayed.

- To allow the user to navigate between the search results pages, we need to store the queried search in a session

- To allow the user to start a brand new search, we add a reset link underneath the fields in our search view file (~/app/View/Users/search.ctp)

*/


// step #1:

class UsersController extends AppController	{
	
 // all defined  properties and existing methods ...
 
	public function search()	{
	
		$data = $this->request->data;
 
		if (isset($data["Search"]))	{
			
			$query = !empty($data["Search"]["query"]) ? $data["Search"]["query"] : "";
			$order = !empty($data["Search"]["order"]) ? $data["Search"]["order"] : "modified";
			$direction = !empty($data["Search"]["direction"]) ? $data["Search"]["direction"] : "desc";
		}
		
		$users = null;
 
		if ($this->request->is("post"))	{
							  
				$conditions = array(
				 "conditions" => array(
				   "first = ?" => $query
					),
				 "fields" => array(
				   "id", "first", "last"
					),
				"order" => array(
					  $order . " " . $direction				
					)
		);
							
				$users = $this->User->find("all", $conditions);
				$count = $this->User->find("count", $conditions);
							
				$this->set("query", $query);
				$this->set("order", $order);
				$this->set("direction", $direction);
				$this->set("users", $users);
				$this->set("count", $count);
								
				$this->Session->write('searchCond', $conditions);
		}
			
		if ($this->Session->check('searchCond')) {
		 $conditions = $this->Session->read('searchCond');
		} else {
		  $conditions = null;
		}

		$this->Paginator->settings = array('all','conditions' => $conditions,'limit' => 10);	
		$this->set('users', $this->Paginator->paginate());
	
	}
}

?>

<!-- step #2: ~/app/View/Users/search.ctp -->

<h1>Search</h1>

<?php

	echo $this->Form->create("Search");
	
	echo $this->Form->input("query");
	
	echo $this->Form->input("order", array(
	  "options" => array(
		 "id" => "id",
		 "first" => "first",
		 "last" => "last"
		 )
	));
									
	echo $this->Form->input("direction", array(
	  "options" => array(
		"asc" => "asc",
		"desc" => "desc"
			)
	));
										
	echo $this->Form->submit('Search');
	
	echo $this->Html->link('Reset', array('action'=>'reset'));
		
	echo $this->Form->end("search");
?>

<?php if ($count > 0): ?>
	<table>
		<tr>
			<th>id</th>
			<th>name</th>
		</tr>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user["User"]["id"]; ?></td>
				<td><?php echo $user["User"]["first"]; ?> <?php echo $user["User"]["last"]; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	Your users search did not yied any results.
<?php endif; ?>
