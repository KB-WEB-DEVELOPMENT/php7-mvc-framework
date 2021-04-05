<?php
declare(strict_types=1);

use Framework\Test as Test
use Framework\Cache as Cache;
use Framework\Cache\Driver\Memcached as Memcached;
use Framework\Cache\Exception\Service as Service;

Test::add(
    function()
    {
        $cache = new Cache();
        return ($cache instanceof Cache);
    },
    "Cache instantiates in uninitialized state",
    "Cache"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        return ($cache instanceof Memcached);
    },
    "Cache\Driver\Memcached initializes",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        return ($cache->connect() instanceof Memcached);
    },
    "Cache\Driver\Memcached connects and returns self",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();
        $cache = $cache->disconnect();

        try
        {
            $cache->get("anything");
        }
        catch (Service $e)
        {
            return ($cache instanceof Memcached);
        }

        return false;
    },
    "Cache\Driver\Memcached disconnects and returns self",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();

        return ($cache->set("foo", "bar", 1) instanceof Memcached);
    },
    "Cache\Driver\Memcached sets values and returns self",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();

        return ($cache->get("foo") == "bar");
    },
    "Cache\Driver\Memcached retrieves values",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();

        return ($cache->get("404", "baz") == "baz");
    },
    "Cache\Driver\Memcached returns default values",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();

        sleep(1);

        return ($cache->get("foo") == null);
    },
    "Cache\Driver\Memcached expires values",
    "Cache\Driver\Memcached"
);

Test::add(
    function()
    {
        $cache = new Cache(array(
            "type" => "memcached"
        ));

        $cache = $cache->initialize();
        $cache = $cache->connect();

        $cache = $cache->set("hello", "world");
        $cache = $cache->erase("hello");

        return ($cache->get("hello") == null && $cache instanceof Memcached);
    },
    "Cache\Driver\Memcached erases values and returns self",
    "Cache\Driver\Memcached"
);
