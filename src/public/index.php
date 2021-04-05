<?php
require __DIR__ . '/vendor/autoload.php';

use Framework\Configuration as Configuration;
use Framework\Database as Database;
use Framework\Cache as Cache;
use Framework\Session as Session;
use Framework\Router as Router;

define("DEBUG", TRUE);
define("APP_PATH", dirname(__DIR__));

try
{
    // step #1 : load all Imagine classes

    spl_autoload_register(function($class){

      $BaseDIR =  APP_PATH . '/application/libraries';

      $listDir = scandir(realpath($BaseDIR));

      if ( isset($listDir) && !empty($listDir) )
      {
          foreach ( $listDir as $listDirkey => $subDir)
          {
            $file = $BaseDIR . DIRECTORY_SEPARATOR . $subDir . DIRECTORY_SEPARATOR . $class . '.php';

            if (file_exists($file))
            {
              require_once $file;
              return true;
            }
          }
    }});

    // step #2: Initialize all plugin files

    $path = APP_PATH . "/application/plugins";

    $iterator = new DirectoryIterator($path);

    foreach ($iterator as $item)
    {
        if (!$item->isDot() && $item->isDir())
        {
            include($path . "/" . $item->getFilename() . "/initialize.php");
        }
    }

    // step #3: instantiate Configuration class, initialize Configuration settings and save in Registry

    $configuration = new Configuration(array(
        "type" => "ini"
    ));
    Registry::set("configuration", $configuration->initialize());

    // step #4: instantiate Database class, initialize Database settings and save in Registry

    $database = new Database();
    Registry::set("database", $database->initialize());

    // step #5: instantiate Cache class, initialize Cache settings and save in Registry

    $cache = new Cache();
    Registry::set("cache", $cache->initialize());

    // step #6: instantiate Session class, initialize Session settings and save in Registry

    $session = new Session();
    Registry::set("session", $session->initialize());

    // step #7: instantiate Router class, get Request data and save in Registry

    $router = new Router(array(
        "url" => $_GET["url"]) ?? "index/index",
        "extension" => $_GET["url"]) ?? "html"
    ));
    Registry::set("router", $router);

    // step #8: include all routes

    include("routes.php");

    // step #9: dispatch and cleanup
    $router->dispatch();

    // step #10: unset variables

    unset($configuration);
    unset($database);
    unset($cache);
    unset($session);
    unset($router);
}
catch (Exception $e)
{
    // step #11: list all exceptions

    $exceptions = array(
        "500" => array(
            "Framework\Cache\Exception",
            "Framework\Cache\Exception\Argument",
            "Framework\Cache\Exception\Implementation",
            "Framework\Cache\Exception\Service",

            "Framework\Configuration\Exception",
            "Framework\Configuration\Exception\Argument",
            "Framework\Configuration\Exception\Implementation",
            "Framework\Configuration\Exception\Syntax",

            "Framework\Controller\Exception",
            "Framework\Controller\Exception\Argument",
            "Framework\Controller\Exception\Implementation",

            "Framework\Core\Exception",
            "Framework\Core\Exception\Argument",
            "Framework\Core\Exception\Implementation",
            "Framework\Core\Exception\Property",
            "Framework\Core\Exception\ReadOnly",
            "Framework\Core\Exception\WriteOnly",

            "Framework\Database\Exception",
            "Framework\Database\Exception\Argument",
            "Framework\Database\Exception\Implementation",
            "Framework\Database\Exception\Service",
            "Framework\Database\Exception\Sql",

            "Framework\Model\Exception",
            "Framework\Model\Exception\Argument",
            "Framework\Model\Exception\Connector",
            "Framework\Model\Exception\Implementation",
            "Framework\Model\Exception\Primary",
            "Framework\Model\Exception\Type",
            "Framework\Model\Exception\Validation",

            "Framework\Request\Exception",
            "Framework\Request\Exception\Argument",
            "Framework\Request\Exception\Implementation",
            "Framework\Request\Exception\Response",

            "Framework\Router\Exception",
            "Framework\Router\Exception\Argument",
            "Framework\Router\Exception\Implementation",

            "Framework\Session\Exception",
            "Framework\Session\Exception\Argument",
            "Framework\Session\Exception\Implementation",

            "Framework\Template\Exception",
            "Framework\Template\Exception\Argument",
            "Framework\Template\Exception\Implementation",
            "Framework\Template\Exception\Parser",

            "Framework\View\Exception",
            "Framework\View\Exception\Argument",
            "Framework\View\Exception\Data",
            "Framework\View\Exception\Implementation",
            "Framework\View\Exception\Renderer",
            "Framework\View\Exception\Syntax"
        ),
        "404" => array(
            "Framework\Router\Exception\Action",
            "Framework\Router\Exception\Controller"
        )
    );

    $exception = get_class($e);

    //print_r($e);

    // step #12: Determine error template to be displayed

    foreach ($exceptions as $template => $classes)
    {
        foreach ($classes as $class)
        {
            if ($class == $exception)
            {
                header("Content-type: text/html");
                include(APP_PATH."/application/views/errors/{$template}.php");
                exit;
            }
        }
    }

    // step #13: Error message to be displayed if step #12 fails

    header("Content-type: text/html");
    echo "An error occurred.";
    exit;
}
