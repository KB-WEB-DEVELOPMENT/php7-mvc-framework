<?php
declare(strict_types=1);

class Registry
{
  private static array $_instances;

  private function __construct()
  {
    // do nothing
  }

  private function __clone()
  {
    // do nothing
  }

  public static function get(string $key, object $default = null): ?object
  {

    return self::$_instances[$key] ?? $default;

  }

  public static function set(string $key, object $instance = null): void
  {
    self::$_instances[$key] = $instance;
  }

  public static function erase(string $key): void
  {
    unset(self::$_instances[$key]);
  }
}
