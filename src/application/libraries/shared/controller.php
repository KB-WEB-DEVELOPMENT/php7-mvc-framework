<?php
declare(strict_types=1);

use Framework\Controller as ControllerB
use Framework\Events as Events;
use Framework\Registry as Registry;

class Controller extends ControllerB
{
    /**
    * @readwrite
    */
    protected $_user;

    /**
    * @protected
    */
    public function _admin(): ?object
    {

      if (!$this->user->admin)
      {
         throw new Router\Exception\Controller("Not a valid admin user account");
      }
    }

    /**
    * @protected
    */
    public function _secure(): void
    {
      $user = $this->getUser();

      if (!$user)
      {
        header("Location: /login.html");
        exit();
      }
    }

    public static function redirect(string $url): void
    {
      header("Location: {$url}");
      exit();
    }

    public function setUser(object $user): Controller
    {

      $session = Registry::get("session");

      isset($user) ? $session->set("user", $user->id) : $session->erase("user");

      $this->_user = $user;

      return $this;
    }


    public function __construct($options = array()): void
    {
        parent::__construct($options);

        $database = Registry::get("database");
        $database->connect();

        // schedule: load user from session
        Events::add("framework.router.beforehooks.before", function($name, $parameters) {
          $session = Registry::get("session");
          $controller = Registry::get("controller");
          $user = $session->get("user");

          if ($user)
          {
             $controller->user = \User::first(array(
                "id = ?" => $user
              ));
            }
        });

        // schedule: save user to session
        Events::add("framework.router.afterhooks.after", function($name, $parameters) {
          $session = Registry::get("session");
          $controller = Registry::get("controller");

          if ($controller->user)
          {
            $session->set("user", $controller->user->id);
         }
        });

        // schedule disconnect from database
        Events::add("framework.controller.destruct.after", function($name) {
          $database = Registry::get("database");
          $database->disconnect();
        });
    }

    public function render(): void
    {
      if ($this->getUser())
      {
        if ($this->getActionView())
        {
          $this->getActionView()
               ->set("user", $this->getUser());
        }

        if ($this->getLayoutView())
        {
          $this->getLayoutView()
               ->set("user", $this->getUser());
        }
      }

      parent::render();
    }
}
