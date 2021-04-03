<?php

/*  ch 14: ex 2

We will simply use Memcached with PHP to store and manage sessions.

We need to modify the class Session (listing 14-9, p.228) and write a Memcached specific Server class (listing 14-10, page 230)

About memached :  https://www.php.net/manual/en/book.memcached.php
 
*/

namespace Framework	{
	
 use Framework\Base as Base;
 use Framework\Registry as Registry;
 use Framework\Session as Session;
 use Framework\Session\Exception as Exception;
 
 class Session extends Base	{
	 
	 /**
	 * @readwrite
	 */
	protected $_type;
 
	 /**
	 * @readwrite
	 */
	protected $_options;
 
	protected function _getExceptionForImplementation($method)	{
		
		return new Exception\Implementation("{$method} method not implemented");
	}
	
	protected function _getExceptionForArgument()	{
		
		return new Exception\Argument("Invalid argument");
	}

	public function initialize()	{
		
		$type=$this->getType();
 
		if (empty($type))	{
			
			$configuration =Registry::get("configuration");
 
			if ($configuration)	{
				
				$configuration=$configuration->initialize();
				$parsed =$configuration->parse("configuration/session");
 
				if (!empty($parsed->session->default) && !empty($parsed-> session- >default->type))	{
					
					$type = $parsed->session->default->type;
					unset($parsed->session->default->type);
				 
					$this-> __construct(array(
					   "type" =>$type,
					   "options" =>(array) $parsed->session->default
					));
				 }
			}
		}
  
		if (empty($type))	{
			
			throw new Exception\Argument("Invalid type");
		}
 
		switch ($type)	{
			
			case "server":	{
				
				return new Session\Driver\Server($this->getOptions());
				break;
			}

			case "memcached_server":	{
				
				return new Session\Driver\MemcachedServer($this->getOptions());
				break;
			} 
			default:	{
				
				throw new Exception\Argument("Invalid type");
				break;
			}
		}
	}
 }
}

namespace Framework\Session\Driver		{
	
	use Framework\Session as Session;
 
	class MemcachedServer extends Session\Driver		{
		
		 /**
		 * @readwrite
		 */
		 protected $_prefix="app_";
 
		public function __construct($options=array())	{
			
				$host = $options["host"];
				$port = (int)$options["port"];
 			
				$memcache_obj = new Memcache;
				$memcache_obj->connect($host,$port);
		}
 
		public function get($key, $default=null)	{
			
			$prefix=$this->getPrefix();
 
			if (isset($memcache_obj($prefix.$key)))	{
				
				return $memcache_obj($prefix.$key);
			}
			
			return $default;
		}
		
		
		public function set($key, $value)	{
			
			$prefix =$this->getPrefix();
						
			$memcache_obj->($prefix.$key,$value);
						
			return $this;
		}
		
		
		public function erase($key)	{
			
			$prefix = $this->getPrefix();
						
			$memcache_obj->delete($prefix.$key);
			
			return $this;
		}
 
		public function __destruct()	{
			
			$memcache_obj->close();
		
		}
 }
}


?>
