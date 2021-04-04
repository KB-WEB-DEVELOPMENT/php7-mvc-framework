<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\Router\Exception as Exception;

class Route extends Base
{
  /**
  * @readwrite
  */
  protected string $_pattern;

  /**
  * @readwrite
  */
  protected string $_controller;

  /**
  * @readwrite
  */
  protected string $_action;

  /**
  * @readwrite
  */
  protected array $_parameters;

  public function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }
}
