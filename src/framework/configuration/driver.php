<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\Configuration\Exception as Exception;

class Driver extends Base
{
  protected array $_parsed;

  public function initialize(): Driver
  {
    return $this;
  }

  protected function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }
}
