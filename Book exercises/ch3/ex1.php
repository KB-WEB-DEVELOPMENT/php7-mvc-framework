<?php

// listing 3-11, page 37, add after if statement

if (empty($meta["@nonnull"]))
{
	throw $this->_getExceptionForNonNullonly($normalized);
	
}

protected function _getExceptionForNonNullonly($property)
{
    return new Exception\Property("{$property} cannot be set to null");
}

?>

