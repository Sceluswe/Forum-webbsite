<?php
/*return [
    // Set up details on how to connect to the database
    'dsn'     => "mysql:host=blu-ray.student.bth.se;dbname=emmd12",
    'username'        => "emmd12",
    'password'        => "o3YMN:5n",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "test_",

    // Display details on what happens: debug mode
    'verbose' => false,

    // Throw a more verbose exception when failing to connect
    //'debug_connect' => 'true',
];*/

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

// -------- For the local environment --------
// Connect to a MySQL database using PHP PDO
// define('DB_USERN', 'root');
// define('DB_PASSWORD', '');
// $scelus['database']['dsn']            = 'mysql:host=localhost;dbname=movie;';
// $scelus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

// -------- For BTH environment --------
// Connect to a MySQL database using PHP PDO
// define('DB_USER', 'emmd12');
// define('DB_PASSWORD', 'o3YMN:5n');
// $scelus['database']['dsn']            = 'mysql:host=blu-ray.student.bth.se;dbname=emmd12';
// $scelus['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

