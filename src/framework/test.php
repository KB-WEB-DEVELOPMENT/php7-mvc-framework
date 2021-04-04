<?php
declare(strict_types=1);

class Test
{

  private static array $_tests;

  public static function add(string $callback, string $title = "Unnamed Test", string $set = "General"): void
  {
    self::$_tests[] = array(
                        "set" => $set,
                        "title" => $title,
                        "callback" => $callback
                      );
  }

  public static function run(string $before = null, string $after = null): array
  {
    if ($before)
    {
      $before(self::$_tests);
    }

    $passed = array();
    $failed = array();
    $exceptions = array();

    foreach (self::$_tests as $test)
    {
      try
      {
        $result = call_user_func($test["callback"]);

        if ($result)
        {
          $passed[] = array(
                        "set" => $test["set"],
                        "title" => $test["title"]
                      );
        }
        else
        {
          $failed[] = array(
                        "set" => $test["set"],
                        "title" => $test["title"]
                      );
        }
      }
      catch (\Exception $e)
      {
        $exceptions[] = array(
                        "set" => $test["set"],
                        "title" => $test["title"],
                        "type" => get_class($e)
                    );
      }
    }

    if ($after)
    {
      $after(self::$_tests);
    }

    return array(
            "passed" => $passed,
            "failed" => $failed,
            "exceptions" => $exceptions
    );
  }
}
