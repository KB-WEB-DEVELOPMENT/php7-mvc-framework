<?php
declare(strict_types=1);

use Framework\Router\Route as Route;

class Regex extends Route
{
  /**
  * @readwrite
  */
  protected array $_keys;

  public function matches(string $url): bool
  {
    $pattern = $this->pattern;

    // check values
    preg_match_all("#^{$pattern}$#", $url, $values);

    if (sizeof($values) && sizeof($values[0]) && sizeof($values[1]))
    {
      // values found, modify parameters and return
      $derived = array_combine($this->keys, $values[1]);
      $this->parameters = array_merge($this->parameters, $derived);

      return true;
    }
    return false;
  }
}
