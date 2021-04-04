<?php
declare(strict_types=1);

  class Markup
  {
    public function __construct()
    {
      // do nothing
    }

    public function __clone()
    {
      // do nothing
    }

    public static function errors(array $array, string $key, string $separator = "<br />", string $before = "<br />", string $after = ""): ?string
    {
      if (isset($array[$key]))
        {
          return $before.join($separator, $array[$key]).$after;
        }
          return "";
        }
    }
