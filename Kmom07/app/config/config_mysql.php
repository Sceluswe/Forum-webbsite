<?php

return [
    // Set up details on how to connect to the database
    'dsn'     => "mysql:host=localhost;dbname=smite;",
    'username'        => "root",
    'password'        => "",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "test_",

    // Display details on what happens: debug mode
    'verbose' => false,

    // Throw a more verbose exception when failing to connect
    //'debug_connect' => 'true',
];