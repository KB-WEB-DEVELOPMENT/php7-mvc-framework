<?php

/* 

ex: 5-1, page 60

The idea is to pass the logged-in user_id to the methods which require it so that the specific user
Memcache values are called.

page 58, modify the following three methods:
*/

 public function get($key, $userid, $default=null)	{
 
	 if (!$this- > _isValidService())	{
		throw new Exception\Service("Not connected to a valid service");
	 }
	 
	 $value = $this- >_service-> get($key . $userid, MEMCACHE_COMPRESSED);
	 
	 if ($value)	{
		return $value;
	 }
	 
	 return $default;
}
 
 public function set($key, $userid, $value, $duration = 120)	{
 
	 if (!$this- >_isValidService())	{
		throw new Exception\Service("Not connected to a valid service");
	 }
	 
	 $this->_service- >set($key . $userid, $value, MEMCACHE_COMPRESSED, $duration);
	 
	 return $this;
 }
 
 public function erase($key,$userid)	{
	 
	 if (!$this->_isValidService())	{
		 
		throw new Exception\Service("Not connected to a valid service");
	 }
	 
	  $this->_service->delete($key . $userid);
	 
	 return $this;
 }
 

?>
