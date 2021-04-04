<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\Database\Exception as Exception;

class Connector extends Base
{
  public function initialize(): Connector
  {
    return $this;
  }

  protected function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }
}
