<?php
declare(strict_types=1);

class RequestMethods
{
  private function __construct()
  {
    // do nothing
  }

  private function __clone()
  {
    // do nothing
  }

  public static function get(?string $key, ?string $default = ""): string
  {
    return $_GET[$key] ?? $default;
  }

  public static function post(?string $key, ?string $default = ""): string
  {
    return $_POST[$key] ?? $default;
  }

  public static function server(?string $key, ?string $default = ""): string
  {
    return $_SERVER[$key] ?? $default;
  }

  public static function cookie(?string $key, ?string $default = ""): string 
  {
    return $_COOKIE[$key] ?? $default;
  }
}
