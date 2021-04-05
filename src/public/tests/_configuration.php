<?php
declare(strict_types=1);

use Framework\Test as Test;
use Framework\Configuration as Configuration;
use Framework\Configuration\Driver\Ini as Ini;


Test::add(
    function()
    {
        $configuration = new Configuration();
        return ($configuration instanceof Configuration);
    },
    "Configuration instantiates in uninitialized state",
    "Configuration"
);

Test::add(
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

Test::add(
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
