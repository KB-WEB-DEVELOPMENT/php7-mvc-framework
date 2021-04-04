<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\Events as Events;
use Framework\Template as Template;
use Framework\View\Exception as Exception;

class View extends Base
{
  /**
  * @readwrite
  */
  protected string $_file;

  /**
  * @readwrite
  */
  protected array $_data;

  /**
  * @read
  */
  protected string $_template;

  public function __construct(array $options)
  {
    parent::__construct($options);

    Events::fire("framework.view.construct.before", array($this->file));

    $this->_template = new Template(array(
                "implementation" => new Template\Implementation\Extended()
            ));

    Events::fire("framework.view.construct.after", array($this->file, $this->template));
  }

  public function _getExceptionForImplementation(string $method): ?object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }

  public function render(): ?object
  {
    Events::fire("framework.view.render.before", array($this->file));

    return !file_exists($this->file) ?  ""  :  $this->template->parse(file_get_contents($this->file))->process($this->data);

  }

  public function get(?string $key, ?string $default = ""): string
  {
    return $this->data[$key] ?? $default;

  }

  protected function _set(?string $key, ?string $value): void
  {
    if (!is_string($key) && !is_numeric($key))
    {
      throw new Exception\Data("Key must be a string or a number");
    }

    $data = $this->data;

    if (!$data)
    {
      $data = array();
    }

    $data[$key] = $value;
    $this->data = $data;
  }

  public function set(?array $key, ?string $value = null): View
  {
    if (is_array($key))
    {
      foreach ($key as $_key => $value)
      {
        $this->_set($_key, $value);
      }
      return $this;
    }

    $this->_set($key, $value);

    return $this;
  }

  public function erase(?string $key): View
  {
    unset($this->data[$key]);
    return $this;
  }
}
