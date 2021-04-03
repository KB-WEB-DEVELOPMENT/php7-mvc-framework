<?php

/*

ch 20: ex  2

A complex pagination sytem cannot be created out of the box because that would imply implementing 
completely different controllers/models and how they related to views/templaes

A second difficulty is that all templates are html files.    

I will opt for html tabs using javascript: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_tabs 

We will need to use the template system we built (ch 8, p.83-113), the following  snippet being what we need :

{foreach $value in $stack}
	{foreach $item in $value}
		item ({echo $item_i}): {echo $item}<br />
	{/foreach}
{/foreach}

Assumptions :

- We will limit each tab to a list of 10 users.

Say our users query returns 46 users for example:

-> Total number of tabs = ceil(46/10)  = 5 = Total number of numeric keys in array usergroups 

-> usersgroups[0] : all users with ids between 1 and 10 
-> usersgroups[1] : all users with ids between 11 and 21
-> usersgroups[2] : all users with ids between 22 and 32
-> usersgroups[3] : all users with ids between 33 and 44
-> usersgroups[4] : all users with ids either  45 or 46

*/

// step #1 :change the method view() in Users class, p 329 

public function view()	{
	
	$limit = 10;
	
	$offset = 10;

	$rows = User:all();

	$tot_num_rows = sizeof($rows);

	$tot_num_tabs = ceil($tot_num_rows/$limit);

	isset($usersgroups) ? unset($usersgroups) : null;
	
	$usersgroups = array();
	
	$database = new Database(array(
	 "type" = >"mysql",
	 "options" = >array(
	 "host" = > "localhost",
	 "username" = > "your_username",
	 "password" = > "your_password",
	 "schema" = > "your_schema",
	 "port" = > "3306"
	)	
	));
	
	$database = $database->initialize();

	for ($i = 0; $i < $tot_num_tabs ; $i++) {
  
		$usersgroups[$i] = $database
		 ->query()
		 ->from("users")
		 ->limit($limit)
		 ->offset($offset*{$i}) 	
		 ->order("id","asc")
		 ->all();
	} 
	
	$this->actionView->set("tot_num_tabs", $tot_num_tabs);
	
	$this->actionView->set("usersgroups", $usersgroups);

	
}

?>

<!-- step # 2 : modify the template application/views/users/view.html (listing 20-10, p. 331) -->

	<div style>
	
		.tab {
		  overflow: hidden;
		  border: 1px solid #ccc;
		  background-color: #f1f1f1;
		}


		.tab button {
		  background-color: inherit;
		  float: left;
		  border: none;
		  outline: none;
		  cursor: pointer;
		  padding: 14px 16px;
		  transition: 0.3s;
		  font-size: 17px;
		}


		.tab button:hover {
		  background-color: #ddd;
		}


		.tab button.active {
		  background-color: #ccc;
		}

		.tabcontent {
		  display: none;
		  padding: 6px 12px;
		  border: 1px solid #ccc;
		  border-top: none;
		}
		
	</div>

<body>

{ if $tot_num_tabs != 0 }


	<div class="tab">

		<?php	for ($i = 0; $i < $tot_num_tabs; $i++) {  ?>
		
			<button class="tablinks" onclick="openUsersGroup(group, '" . <?php echo $i  ?> . "')">Group <?php echo ($i + 1) ?></button>
	  
	</div>

			<?php 
				echo '<div id="' . $i . '" class="tabcontent">';
			?>			
						<table>
							<tr>
								<th>Name</th>
								<th>Change</th>
							</tr>	
							{ foreach $user in $usersgroups[<?php echo $i ?>] }
							<tr>
								<td >{echo $_user->first} {echo $_user->last}</td>
								<td>
									<a href = "/users/edit/{echo $user-> id}.html" >edit</a>
								{if $user->deleted}
									<a href = "/users/undelete/{echo $user-> id}.html">undelete</a>
								{/if}
								{else}
									<a href ="/users/delete/{echo $user-> id}.html">delete</a>
								{/else}
								</td>
							</tr>
							{/foreach}
						</table>	
				</div>
	
		<?php } ?>
	
	</div>	


{/if}

{else}
	Sorry, there are no users in the database. 
{/else}


<script>

	function openUsersGroup(group, groupIndexKeyAsString) {

	  var i, tabcontent, tablinks;
	  tabcontent = document.getElementsByClassName("tabcontent");
	  for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	  }
	  tablinks = document.getElementsByClassName("tablinks");
	  for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	  }
	  document.getElementById(cityName).style.display = "block";
	  group.currentTarget.className += " active";
	}

</script>
   
</body>


