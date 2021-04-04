<?php
declare(strict_types=1);


class ArrayMethods
{
  private function __construct()
  {
    // do nothing
  }

  private function __clone()
  {
  // do nothing
  }

  public static function clean(array $array): bool
  {
    return array_filter($array, function($item) {
      return !empty($item);
    });
  }

  public static function trim(array $array): array
  {
    return array_map(function($item) {
      return trim($item);
    }, $array);
  }

  public static function first(array $array): ?string
  {
    if (sizeof($array) == 0)
    {
      return null;
    }

    $keys = array_keys($array);
    return $array[$keys[0]];
  }

  public static function last(array $array): ?string
  {
    if (sizeof($array) == 0)
    {
      return null;
    }

    $keys = array_keys($array);
    return $array[$keys[sizeof($keys) - 1]];
  }

  public static function toObject(array $array): object
  {
    $result = new \stdClass();

    foreach ($array as $key => $value)
    {
      if (is_array($value))
      {
        $result->{$key} = self::toObject($value);
      }
      else
      {
        $result->{$key} = $value;
      }
    }

    return $result;
  }

  public function flatten(array $array, array $return): array
  {
    foreach ($array as $key => $value)
    {
      if (is_array($value) || is_object($value))
      {
        $return = self::flatten($value, $return);
      }
      else
      {
        $return[] = $value;
      }
    }
    return $return;
  }

  public function toQueryString(array $array): string
  {
    return http_build_query(
            self::clean(
                        $array
                       )
          );
  }
}
