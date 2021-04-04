<?php
declare(strict_types=1);

use Framework\Session\Driver as Driver;

class Server extends Driver
{
  /**
  * @readwrite
  */
  protected string $_prefix = "app_";

  public function __construct(array $options)
  {

    parent::__construct($options);
    session_start();

  }

  public function get(string $key, string $default = null): ?string
  {

    return $_SESSION[$this->prefix.$key] ?? $default;

  }

  public function set(string $key, ?string $value): Server
  {

    $_SESSION[$this->prefix.$key] = $value;

    return $this;

  }

  public function erase(string $key): Server
  {

    unset($_SESSION[$this->prefix.$key]);

    return $this;
  }
}
