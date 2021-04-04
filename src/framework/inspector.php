<?php
declare(strict_types=1);

use Framework\ArrayMethods as ArrayMethods;
use Framework\StringMethods as StringMethods;

class Inspector
{
  protected string $_class;

  protected array $_meta = array(
                      "class" => array(),
                      "properties" => array(),
                      "methods" => array()
                    );

  protected array $_properties;
  protected array $_methods;

  public function __construct(string $class): void
  {
    $this->_class = $class;
  }

  protected function _getClassComment(): ?string
  {
    $reflection = new \ReflectionClass($this->_class);
    return $reflection->getDocComment();
  }

  protected function _getClassProperties(): ?string
  {
    $reflection = new \ReflectionClass($this->_class);
    return $reflection->getProperties();
  }

  protected function _getClassMethods(): ?string
  {
    $reflection = new \ReflectionClass($this->_class);
    return $reflection->getMethods();
  }

  protected function _getPropertyComment(string $property): ?string
  {
    $reflection = new \ReflectionProperty($this->_class, $property);
    return $reflection->getDocComment();
  }

  protected function _getMethodComment(string $method): ?string
  {
    $reflection = new \ReflectionMethod($this->_class, $method);
    return $reflection->getDocComment();
  }

  protected function _parse(string $comment): array
  {
    $meta = array();
    $pattern = "(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_]*)";
    $matches = StringMethods::match($comment, $pattern);

    if ($matches != null)
    {
      foreach ($matches as $match)
      {
        $parts = ArrayMethods::clean(
                       ArrayMethods::trim(
                            StringMethods::split($match, "[\s]", 2)
                        )
                );

        $meta[$parts[0]] = true;

        if (sizeof($parts) > 1)
        {
          $meta[$parts[0]] = ArrayMethods::clean(
                              ArrayMethods::trim(
                                StringMethods::split($parts[1], ",")
                              )
                            );
        }
      }
     }

     return $meta;
  }

  public function getClassMeta(): ?string
  {
    if (!isset($_meta["class"]))
    {
      $comment = $this->_getClassComment();

      $_meta["class"] = !empty($comment) ? $this->_parse($comment) : null;
    }

    return $_meta["class"];
  }

  public function getClassProperties(): array
  {
    if (!isset($_properties))
    {
      $properties = $this->_getClassProperties();

      foreach ($properties as $property)
      {
        $_properties[] = $property->getName();
      }
    }

    return $_properties;
  }

  public function getClassMethods(): array
  {
    if (!isset($_methods))
    {
      $methods = $this->_getClassMethods();

      foreach ($methods as $method)
      {
        $_methods[] = $method->getName();
      }
    }
    return $_methods;
  }

  public function getPropertyMeta(string $property): ?string
  {
    if (!isset($_meta["properties"][$property]))
    {
      $comment = $this->_getPropertyComment($property);

      $_meta["properties"][$property] = !empty($comment) ? $this->_parse($comment) : null;

      return $_meta["properties"][$property];

    }

    return $_meta["properties"][$property];
  }

  public function getMethodMeta(string $method): ?string
  {
    if (!isset($_meta["methods"][$method]))
    {
      $comment = $this->_getMethodComment($method);

      $_meta["methods"][$method] = !empty($comment) ? $this->_parse($comment) : null;

      return $_meta["methods"][$method];
    }

    return $_meta["methods"][$method];
  }
}
