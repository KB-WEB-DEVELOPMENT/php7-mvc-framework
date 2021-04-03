<?php

/*

ch 23 : ex 1 - This is just an example as to how a CI table can be created using the Forge class.
We are assuming the Forge DB driver is already running so $this->db and $this->dbforge have already been set.
We also already created a DB named "my_db_test".

The following can be placed in any controller method to create the ipn_log DB table


*/

if ($this->dbforge->create_database('my_db_test'))	{
	
    try {
			$current_database = "my_db_test";
			$this->db->database = $current_database;
			$this->db->close();
			$config['hostname'] = "localhost";
			$config['username'] = "root";
			$config['password'] = "";
			$config['database'] = $current_database;
			$config['dbdriver'] = "mysql";
			$config['dbprefix'] = "";
			$config['pconnect'] = FALSE;
			$config['db_debug'] = TRUE;
			$config['cache_on'] = FALSE;
			$config['cachedir'] = "";
			$config['char_set'] = "utf8";
			$config['dbcollat'] = "utf8_general_ci";
			$this->load->database($config);
			$fields = array(
				'blog_id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
											  ),
					'blog_title' => array(
					  'type' => 'VARCHAR',
					  'constraint' => '100',
					),
					'blog_author' => array(
					  'type' =>'VARCHAR',
						'constraint' => '100',
						'default' => 'King of Town',
					),
					'blog_description' => array(
					  'type' => 'TEXT',
						'null' => TRUE,
					),
				);

			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('blog_id', TRUE);
			$this->dbforge->create_table('ipn_log', TRUE);
		
		} catch(Exception $e){
			
			echo $e->getMessage();die;
		  }
}

?>
