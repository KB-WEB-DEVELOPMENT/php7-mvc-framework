<?php
declare(strict_types=1);

use Framework\Configuration as Configuration;
use Framework\Configuration\Driver\Ini as Ini;


Framework\Test::add(
    function()
    {
        $configuration = new Configuration();
        return ($configuration instanceof Configuration);
    },
    "Configuration instantiates in uninitialized state",
    "Configuration"
);

Framework\Test::add(
    function()
    {
        $configuration = new Configuration(array(
            "type" => "ini"
        ));

        $configuration = $configuration->initialize();
        return ($configuration instanceof Ini);
    },
    "Configuration\Driver\Ini initializes",
    "Configuration\Driver\Ini"
);

Framework\Test::add(
    function()
    {
        $configuration = new Configuration(array(
            "type" => "ini"
        ));

        $configuration = $configuration->initialize();
        $parsed = $configuration->parse("_configuration");

        return ($parsed->config->first == "hello" && $parsed->config->second->second == "bar");
    },
    "Configuration\Driver\Ini parses configuration files",
    "Configuration\Driver\Ini"
);
