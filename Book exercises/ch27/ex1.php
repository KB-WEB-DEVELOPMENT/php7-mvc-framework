<?php

/*

ch 27 : ex 1 - We will recreate our Application_Model_DbTable_User  model class by subclassing the  Zend_Db_Table_Abstract class

link : https://akrabat.com/on-models-in-a-zend-framework-application/

*/

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract {
		
	protected $_table;
	public $id;
	public $first;
	public $last;
	public $email;
	public $password;
	public $live = true;
	public $deleted = false;
	public $created;
	public $modified;

	public function setTable($table) {

		// content p.389-390

	}	
	
	public function getTable() {

		// content p.390

	}	
	
	public function _populate($options) {

		// content p.390

	}	
	
	public function __construct($options = array()) {

		// content p.391

	}	
	
	public function load() {
		
		// content p.391
			
	}	
	
	public function save() {
		
		// content p.392
	
	}	
	
	public static function first($where = null) {
		
		// content p.392
	
	}

	public static function count($where = null) {
		
		// content p.393
	
	}		
	
	public static function all($where = null, $fields = null, $order = null, $direction = "asc", $limit = null, $page = null) {
		
		// content p.393-394
	
	}	
	
	
}	
	

?>
