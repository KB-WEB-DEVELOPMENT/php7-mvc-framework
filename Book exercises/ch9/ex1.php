<?php 

/* We will be using PHP PDO ("PHP Data Objects") to interact with the database along MySQL used in the book.

I will point out here all the additions/changes which need to be made following chapter 9 chronology.

A good intro here : https://phpdelusions.net/pdo

/* p1. 115 , Listing 9-3. The Database Factory Class

add the following in switch statement

*/

case "mypdo":

{
	return new Database\Connector\myPdo($this->options);
	break;

}
 
/* p. 116-119, Listing 9-5. Rewrite  the MySql class but this time it is the MyPdo class

*/

namespace Framework\Database\Connector	{
	
 use Framework\Database as Database;
 use Framework\Database\Exception as Exception;
 
 class MyPdo extends Database\Connector		{
 
	protected $_service;

	/**
	 * @readwrite
	 */	
	protected $_pdo;

	/**
	 * @readwrite
	 */	
	protected $_dsn;
	
	/**
	 * @readwrite
	 */
	 protected $_host;
	 
	/**
	 * @readwrite
	 */
	 protected $_dbname;	 
	 

	 /**
	 * @readwrite
	 */ 
     protected $_username;

	/**
    * @readwrite
    */
	protected $_password;
	 
	/**
	* @readwrite
	*/
	protected $_charset = "utf8mb4";
 
	/**
	* @readwrite
	*/
	protected $_isConnected =false;
	

	protected function _isValidService()	{
 
		$isEmpty = empty($this->_service);
		
		$isInstance = $this->_service instanceof \PDO;

		if ($this->isConnected && $isInstance && !$isEmpty)	{
			
			return true;
		}
		
	 return false;
	}

	public function connect()	{
 
		if (!$this->_isValidService())	{
			
			$this->_dsn = 'mysql:host={$this->_host};dbname={$this->_dbname};charset={$this->_charset}';
		
			$options = array();
			
			$options = [
						PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::ATTR_EMULATE_PREPARES   => false,
			];
			
			try {
			
				// assume here all parameters have been set previously
				$this->_service = new PDO($this->_dsn, $this->_username, $this->_password, $options);
				
				$this->_isConnected = true;	
			
				
			} catch (\PDOException $e) {
			
				$this->_isConnected = false;
				
				throw new \PDOException($e->getMessage(), (int)$e->getCode());
			
			 }	
			
		}
	
		return $this;
	
	}

	public function disconnect()	{
		
		if ($this->_isValidService())	{
			
			$this->_isConnected=false;
			
			$this->_service = null;
		}
		
      return $this;
    }
	
	
	public function query()	{
 
		return new Database\Query\MyPdo(array(
			"connector" = >$this
		));
		
	}
	
	// https://phpdelusions.net/pdo#query
	public function execute($sql)	{

		if (!$this->_isValidService())	{
			
			throw new Exception\Service("Not connected to a valid service");
		}
		
		return $this->_service->prepare($sql)->execute()->fetch();
 
	}
	
	// no mysql_real_escape_string for PDO, here:
	// https://stackoverflow.com/questions/14012642/what-is-the-pdo-equivalent-of-function-mysql-real-escape-string/14012675
	public function escape($value)	{
		
		if (!$this->_isValidService())	{
			
			throw new Exception\Service("Not connected to a valid service");
		}
		
		return $value;
	}
	
	// https://www.php.net/manual/en/pdo.lastinsertid.php
	public function getLastInsertId($sql)	{
		
		if (!$this->_isValidService())	{
			
			throw new Exception\Service("Not connected to a valid service");
		}
 
		$this->_service->prepare($sql)->execute();
		
		return $this->_service->lastInsertId(); 
		
		
	}

	// https://www.php.net/manual/en/pdostatement.rowcount
	public function getAffectedRows($sql)	{
		
		if (!$this->_isValidService())	{
			
			throw new Exception\Service("Not connected to a valid service");
		}
 
		$this->_service->prepare($sql)->execute();
		
		return $this->_service->rowCount();
	}

	/* method below not needed as PDO displays its own errors, see $options variable in connect() method above
	
	public function getLastError()	{
		
		if (!$this->_isValidService()) {

			throw new Exception\Service("Not connected to a valid service");
		}
 
		return $this->_service->error;
	}
	
	*/
	
	
 }
}

/* The last change is how the 3 methods _buildSelect(), _buildInsert($data) and _buildUpdate($data) are rewritten for PDO,

see page 125-128, listing 9-9.

I will rewrite here just _buildSelect(), using https://www.php.net/manual/en/pdo.query.php

The other two methods are similar.

ex: https://www.php.net/manual/en/pdo.query.php

<?php

$sql = 'SELECT name, color, calories FROM fruit ORDER BY name';
foreach ($conn->query($sql) as $row) {
    print $row['name'] . "\t";
    print $row['color'] . "\t";
    print $row['calories'] . "\n";
}
?

*/

namespace Framework\Database	{
	
 use Framework\Base as Base;
 use Framework\ArrayMethods as ArrayMethods;
 use Framework\Database\Exception as Exception;
 
 class Query extends Base	{
	 
	 protected function _buildSelect()	{
		 
		 $fields =array();
		 $where =$order=$limit=$join="";
		 $template="SELECT %s FROM %s %s %s %s %s";
		 
		 foreach ($this- >fields as $table = >$_fields)	{
			 
			 foreach ($_fields as $field =>$alias)	{
				 
				 if (is_string($field))	{
					 
					$fields[]="{$field} AS {$alias}";
				 
				 }	else	{
					 
					$fields[]=$alias;
				 }
				 
			 }
		 
		 }
	 
		$fields=join(", ", $fields);
		
		$_join =$this->join;
	 
		if (!empty($_join))	{
			 
			$join =join(" ", $_join);
		 }
	 
		$_where = $this->where;
	 
		if (!empty($_where))	{
			 
			$joined = join(" AND ", $_where);
			$where = "WHERE {$joined}";
		 }
	 
		$_order= $this->order;
	 
		if (!empty($_order))	{
		 
			$_direction = $this- >direction;
			
			$order="ORDER BY {$_order} {$_direction}";
		}
	 
	 
		$_limit =$this->limit;
	 
		if (!empty($_limit))	{
		 
			$_offset =$this->offset;
	 
			if ($_offset)	{
				$limit ="LIMIT {$_limit}, {$_offset}";
		
			} else	{
	 
				$limit="LIMIT {$_limit}";
			  }
		}
	 
		return sprintf($template, $fields, $this->from, $join, $where, $order, $limit);
	 
	}
  }
}


?>
