<?php
declare(strict_types=1);

class Events
{
  private static array $_callbacks;

  private function __construct()
  {
  // do nothing
  }

  private function __clone()
  {
    // do nothing
  }

  public static function add(string $type, array $callback): void
  {
    if (empty(self::$_callbacks[$type]))
    {
      self::$_callbacks[$type] = array();
    }

    self::$_callbacks[$type][] = $callback;
  }

  public static function fire(string $type, array $parameters = null): void
  {
    if (!empty(self::$_callbacks[$type]))
    {
      foreach (self::$_callbacks[$type] as $callback)
      {
        call_user_func_array($callback, $parameters);
      }
    }
  }

  public static function remove(string $type, array $callback): void
  {
    if (!empty(self::$_callbacks[$type]))
    {
      foreach (self::$_callbacks[$type] as $i => $found)
      {
        if ($callback == $found)
        {
          unset(self::$_callbacks[$type][$i]);
        }
      }
    }
  }
}
