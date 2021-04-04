<?php
declare(strict_types=1);

use Framework\ArrayMethods as ArrayMethods;
use Framework\Configuration as Configuration;
use Framework\Configuration\Exception as Exception;

class Ini extends Configuration\Driver
{
  protected function _pair(array $config, string $key, string $value): array
  {
      if (strstr($key, "."))
        {
          $parts = explode(".", $key, 2);

          $config[$parts[0]] = empty($config[$parts[0]] ? array() : $this->_pair($config[$parts[0]], $parts[1], $value);

        }
      else
      {
         $config[$key] = $value;
      }
      return $config;
  }

  public function parse(string $path): array
  {
    if (empty($path))
    {
      throw new Exception\Argument("\$path argument is not valid");
    }

      if (!isset($this->_parsed[$path]))
      {
        $config = array();

        ob_start();
        include("{$path}.ini");
        $string = ob_get_contents();
        ob_end_clean();

        $pairs = parse_ini_string($string);

        if ($pairs == false)
        {
          throw new Exception\Syntax("Could not parse configuration file");
        }

        foreach ($pairs as $key => $value)
        {
          $config = $this->_pair($config, $key, $value);
        }

        $this->_parsed[$path] = ArrayMethods::toObject($config);
      }

      return $this->_parsed[$path];
  }
}
