<?php
declare(strict_types=1);

use Framework\Base as Base;
use Framework\View as View;
use Framework\Events as Events;
use Framework\Registry as Registry;
use Framework\Template as Template;
use Framework\Controller\Exception as Exception;

class Controller extends Base
{
  /**
  * @read
  */
  protected string $_name;

  /**
  * @readwrite
  */
  protected array $_parameters;

  /**
  * @readwrite
  */
  protected string $_layoutView;

  /**
  * @readwrite
  */
  protected string $_actionView;

  /**
  * @readwrite
  */
  protected bool $_willRenderLayoutView = true;

  /**
  * @readwrite
  */
  protected bool $_willRenderActionView = true;

  /**
  * @readwrite
  */
  protected string $_defaultPath = "application/views";

  /**
  * @readwrite
  */
  protected string $_defaultLayout = "layouts/standard";

  /**
  * @readwrite
  */
  protected string $_defaultExtension = "html";

  /**
  * @readwrite
  */
  protected string $_defaultContentType = "text/html";

  protected function getName(): string
  {
    $this->_name = empty($this->_name) ? get_class($this) : $this->_name;

    return $this->_name;
  }

  protected function _getExceptionForImplementation(string $method): object
  {
    return new Exception\Implementation("{$method} method not implemented");
  }

  public function __construct(array $options)
  {
    parent::__construct($options);

    Events::fire("framework.controller.construct.before", array($this->name));

    if ($this->willRenderLayoutView)
    {
      $defaultPath = $this->defaultPath;
      $defaultLayout = $this->defaultLayout;
      $defaultExtension = $this->defaultExtension;

        $view = new View(array(
          "file" => APP_PATH."/{$defaultPath}/{$defaultLayout}.{$defaultExtension}"
        ));

        $this->layoutView = $view;
    }

    if ($this->willRenderActionView)
    {
      $router = Registry::get("router");
      $controller = $router->controller;
      $action = $router->action;

      $view = new View(array(
        "file" => APP_PATH."/{$defaultPath}/{$controller}/{$action}.{$defaultExtension}"
      ));

      $this->actionView = $view;
    }

    Events::fire("framework.controller.construct.after", array($this->name));
  }

  public function render()
  {
      Events::fire("framework.controller.render.before", array($this->name));

      $defaultContentType = $this->defaultContentType;
      $results = null;

      $doAction = $this->willRenderActionView && $this->actionView;
      $doLayout = $this->willRenderLayoutView && $this->layoutView;

      try
      {
        if ($doAction)
        {
          $view = $this->actionView;
          $results = $view->render();

          $this
            ->actionView
            ->template
            ->implementation
            ->set("action", $results);
        }

        if ($doLayout)
        {
          $view = $this->layoutView;
          $results = $view->render();

          header("Content-type: {$defaultContentType}");
          echo $results;
        }
        else if ($doAction)
        {
          header("Content-type: {$defaultContentType}");
          echo $results;
        }

        $this->willRenderLayoutView = false;
        $this->willRenderActionView = false;
      }
      catch (\Exception $e)
      {
        throw new View\Exception\Renderer("Invalid layout/template syntax");
      }

      Events::fire("framework.controller.render.after", array($this->name));
  }

  public function __destruct()
  {
    Events::fire("framework.controller.destruct.before", array($this->name));

    $this->render();

    Events::fire("framework.controller.destruct.after", array($this->name));
  }
}
