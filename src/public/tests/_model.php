<?php
declare(strict_types=1);

use Framework\Database as Database;
use Framework\Database\Connector\Mysql as Mysql;
use Framework\Registry as Registry;
use Framework\Model as Model;

$database = new Database(array(
    "type" => "mysql",
    "options" => array(
        "host" => "localhost",
        "username" => "root",
        "password" => "",
        "schema" => "php7-mvc"
    )
));
$database = $database->initialize();
$database = $database->connect();

Registry::set("database", $database);

class Example extends Model
{
    /**
    * @readwrite
    * @column
    * @type autonumber
    * @primary
    */
    protected int $_id;

    /**
    * @readwrite
    * @column
    * @type text
    * @length 32
    */
    protected string $_name;

    /**
    * @readwrite
    * @column
    * @type datetime
    */
    protected DateTimeImmutable $_created;
}

Framework\Test::add(
    function() use ($database)
    {
        $example = new Example();
        return ($database->sync($example) instanceof Mysql);
    },
    "Model syncs",
    "Model"
);

Framework\Test::add(
    function() use ($database)
    {
        $example = new Example(array(
            "name" => "foo",
            "created" => date("Y-m-d H:i:s")
        ));

        return ($example->save() > 0);
    },
    "Model inserts rows",
    "Model"
);

Framework\Test::add(
    function() use ($database)
    {
        return (Example::count() == 1);
    },
    "Model fetches number of rows",
    "Model"
);

Framework\Test::add(
    function() use ($database)
    {
        $example = new Example(array(
            "name" => "foo",
            "created" => date("Y-m-d H:i:s")
        ));

        $example->save();
        $example->save();
        $example->save();

        return (Example::count() == 2);
    },
    "Model saves single row multiple times",
    "Model"
);

Framework\Test::add(
    function() use ($database)
    {
        $example = new Example(array(
            "id" => 1,
            "name" => "hello",
            "created" => date("Y-m-d H:i:s")
        ));
        $example->save();

        return (Example::first()->name == "hello");
    },
    "Model updates rows",
    "Model"
);

Framework\Test::add(
    function() use ($database)
    {
        $example = new Example(array(
            "id" => 2
        ));
        $example->delete();

        return (Example::count() == 1);
    },
    "Model deletes rows",
    "Model"
);

unset($database);
